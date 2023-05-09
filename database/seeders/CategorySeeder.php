<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'SOEP']);
        Category::create(['name' => 'VOORGERECHTEN']);
        Category::create(['name' => 'BAMI EN NASI GERECHTEN']);
        Category::create(['name' => 'COMBINATIE GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'OSSENHAAS GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'VISSEN GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'PEKING EEND GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'TIEPAN SPECIALITEITEN (met witte rijst)']);
        Category::create(['name' => 'VEGETARISCHE GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'KINDERMENU\'S']);
        Category::create(['name' => 'MIHOEN GERECHTEN']);
        Category::create(['name' => 'CHINESE BAMI GERECHTEN']);
        Category::create(['name' => 'INDISCHE GERECHTEN']);
        Category::create(['name' => 'EIERGERECHTEN (met witte rijst)']);
        Category::create(['name' => 'GROENTEN GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'VLEES GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'RIJSTTAFELS']);
        Category::create(['name' => 'KIPGERECHTEN (met witte rijst)']);
        Category::create(['name' => 'GARNALEN GERECHTEN (met witte rijst)']);
    }
}
