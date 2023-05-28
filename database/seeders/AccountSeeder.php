<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ping',
            'email' => 'admin@wfs.nl',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ])->assignRole('Administrator');

        User::create([
            'name' => 'Hans',
            'email' => 'klant@wfs.nl',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ])->assignRole('User');

        // Cashiers
        User::create([
            'name' => 'Piet',
            'email' => 'cashier@wfs.nl',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ])->assignRole('Cashier');

        User::create([
            'name' => 'Jaap',
            'email' => 'cashier2@wfs.nl',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ])->assignRole('Cashier');

        User::create([
            'name' => 'Tom',
            'email' => 'cashier3@wfs.nl',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ])->assignRole('Cashier');
    }
}
