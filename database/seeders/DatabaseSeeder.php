<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Dishes
        $this->call(CategorySeeder::class);
        $this->call(OptionSeeder::class);
        $this->call(DishSeeder::class);
        // Restaurant
        $this->call(RestaurantSeeder::class);
        $this->call(OpeningTimeSeeder::class);
        $this->call(NewsSeeder::class);
        //General
        $this->call(RolePermissionSeeder::class);
        $this->call(AccountSeeder::class);
    }
}
