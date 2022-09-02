<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Wallet::truncate();
        Wallet::create(['name' => 'Wallet #1']);
        Wallet::create(['name' => 'Wallet #2']);
        Wallet::create(['name' => 'Wallet #3']);
    }
}
