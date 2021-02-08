<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Services\TransactionService;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(TransactionService $transactionService)
    {
        // TODO: look for a better way to form a relative path name
        $movementPath = dirname(__FILE__) . '/sources/Fertiliser inventory movements - Sheet1.csv';
        $rows = SimpleExcelReader::create($movementPath)->getRows();

        $transactions = $rows
            ->map(function (array $props) {
                // TODO: maybe use Carbon::createFromFormat() in future
                // to make sure DateTime understands the format is d/m/y format
                $date = new Carbon(str_replace('/', '-', $props['Date']));
                $price = $props['Unit Price'];

                return [
                    'date' => $date,
                    'type' => $props['Type'],
                    'quantity' => $props['Quantity'],
                    'unit_price' => is_numeric($price) ? $price : 0,
                ];
            })
            // TODO: improve way to process transactions
            ->map(function ($data) {
                $transaction = Transaction::create($data);
                $transaction->save();

                return $transaction;
            })

            print_r($transactions->all());
    }
}
