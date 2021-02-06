<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\SimpleExcel\SimpleExcelReader;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // TODO: look for a better to form a relative path name
        $movementPath = dirname(__FILE__) . '/sources/Fertiliser inventory movements - Sheet1.csv';
        $rows = SimpleExcelReader::create($movementPath)->getRows();

        $rows->each(function(array $rowProperties) {
            print_r($rowProperties);
        // \App\Models\User::factory(10)->create();
        });
    }
}
