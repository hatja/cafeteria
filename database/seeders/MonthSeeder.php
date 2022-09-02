<?php

namespace Database\Seeders;

use App\Models\Month;
use Illuminate\Database\Seeder;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Month::truncate();
        foreach (Month::$monthNames as $name) {
            Month::create(['name' => $name]);
        }
    }
}
