<?php

namespace Database\Seeders;

use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::role('Cashier')->get();

        for ($i = 1; $i <= 9; $i++) {
            $table = Table::create(['table_number' => $i]);

            foreach ($users as $user) {
                for ($x = 1; $x <= 7; $x++) {
                    $table->users()->attach($user->id, ['weekday' => $x]);
                }
            }
        }
    }
}
