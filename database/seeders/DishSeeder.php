<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::createFromPath(database_path('menu_data.csv'), 'r');
        $csv->setHeaderOffset(0);

        $bamiNasiOptions = Option::whereIn('name', ['Bami', 'Nasi'])->get();
        $soupOptions = Option::whereIn('name', ['Kippensoep', 'Tomatensoep'])->get();

        foreach ($csv->getRecords() as $record) {
            $dish = new Dish();
            $dish->menu_number = $record['menunummer'] == "NULL" ? null : $record['menunummer'];
            $dish->menu_addition = $record['menu_toevoeging'] == "NULL" ? null : $record['menu_toevoeging'];
            $dish->name = $record['naam'];
            $dish->price = $record['price'];
            $dish->description = $record['beschrijving'] == "NULL" ? null : $record['beschrijving'];
            $dish->category_id = Category::where('name', $record['soortgerecht'])->first()->id;
            $dish->save();

            // Check whether Dish has options
            if (str_contains($record['naam'], 'Bami of Nasi') && !str_contains($record['naam'], 'ipv')) {
                $dish->options()->attach($bamiNasiOptions);
            }

            if (str_contains($record['naam'], 'Kippen- of Tomatensoep')) {
                $dish->options()->attach($soupOptions);
            }
        }
    }
}
