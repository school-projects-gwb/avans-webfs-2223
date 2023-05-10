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
        Category::create(['name' => 'VOORGERECHT']);
        Category::create(['name' => 'BAMI EN NASI GERECHTEN']);
        Category::create(['name' => 'COMBINATIE GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'OSSENHAAS GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'VISSEN GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'PEKING EEND GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'TIEPAN SPECIALITEITEN (met witte rijst)']);
        Category::create(['name' => 'VEGETARISCHE GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'KINDERMENUS']);
        Category::create(['name' => 'MIHOEN GERECHTEN']);
        Category::create(['name' => 'CHINESE BAMI GERECHTEN']);
        Category::create(['name' => 'INDISCHE GERECHTEN']);
        Category::create(['name' => 'EIERGERECHTEN (met witte rijst)']);
        Category::create(['name' => 'GROENTEN GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'VLEES GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'RIJSTTAFELS']);
        Category::create(['name' => 'KIP GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'GARNALEN GERECHTEN (met witte rijst)']);
        Category::create(['name' => 'BUFFET', 'special_description' => 'Wij verzorgen warme buffet vanaf 15 personen. Deze kunnen aangepast worden naar eigen smaak. Informeer vrijblijvend naar de mogelijkheden.']);
        Category::create(['name' => 'DIVERSEN']);
        Category::create(['name' => 'AANBIEDINGEN']);
    }
}
