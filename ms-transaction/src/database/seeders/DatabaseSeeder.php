<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if (User::exists()) {
            return;
        }

        User::factory()->count(10)->create();
        Transaction::factory()->count(50)->create();
    }
}
