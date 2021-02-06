<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // TODO: look for a better way to form a relative path name
        $movementPath = dirname(__FILE__) . '/sources/Fertiliser inventory movements - Sheet1.csv';
        $rows = SimpleExcelReader::create($movementPath)->getRows();

        $data = $rows->map(function(array $props) {
            // TODO: maybe use Carbon::createFromFormat() in future
            // to make sure DateTime understands the format is d/m/y format
            $date = new Carbon(str_replace('/', '-', $props['Date']));

            return [
                'date' => $date,
                'type' => $props['Type'],
                'quantity' => $props['Quantity'],
                'unit_price' => $props['Unit Price'],
            ];
        });

        print_r($data->all());
        // \App\Models\User::factory(10)->create();
    }
}
