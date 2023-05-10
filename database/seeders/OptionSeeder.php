<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Option::create(['price' => null, 'name' => 'Bami', 'condition_text' => '']);
        Option::create(['price' => null, 'name' => 'Nasi', 'condition_text' => '']);
        Option::create(['price' => null, 'name' => 'Kippensoep', 'condition_text' => '']);
        Option::create(['price' => null, 'name' => 'Tomatensoep', 'condition_text' => '']);
    }
}
