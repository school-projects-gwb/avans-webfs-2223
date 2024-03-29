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
        // Not sold separately
        Option::create(['price' => null, 'name' => 'Bami', 'condition_text' => null]);
        Option::create(['price' => null, 'name' => 'Nasi', 'condition_text' => null]);
        Option::create(['price' => null, 'name' => 'Kippensoep', 'condition_text' => null]);
        Option::create(['price' => null, 'name' => 'Tomatensoep', 'condition_text' => null]);
        Option::create(['price' => null, 'name' => 'Koe Loe Yuk', 'condition_text' => null]);
        Option::create(['price' => null, 'name' => 'Tjap Tjoy', 'condition_text' => null]);
        Option::create(['price' => null, 'name' => 'Babi Pangang', 'condition_text' => null]);
        Option::create(['price' => null, 'name' => 'Foe Yong Hai', 'condition_text' => null]);
        Option::create(['price' => null, 'name' => 'Garnalen met Gebakken Knoflook', 'condition_text' => null]);
        Option::create(['price' => null, 'name' => 'Kipfilet in Zwarte Bonen saus', 'condition_text' => null]);

        // Sold separately
        Option::create(['price' => 0.9, 'name' => 'Bami Goreng', 'condition_text' => 'I.p.v. rijst']);
        Option::create(['price' => 0.9, 'name' => 'Nasi Goreng', 'condition_text' => 'I.p.v. rijst']);
        Option::create(['price' => 2.5, 'name' => 'Mihoen goreng', 'condition_text' => 'I.p.v. rijst']);
        Option::create(['price' => 2.5, 'name' => 'Chinese bami', 'condition_text' => 'I.p.v. rijst']);
    }
}
