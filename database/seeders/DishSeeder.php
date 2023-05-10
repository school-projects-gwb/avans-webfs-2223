<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
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

        foreach ($csv->getRecords() as $record) {
            $dish = new Dish();
            $dish->menu_number = $record['menunummer'] == "NULL" ? null : $record['menunummer'];
            $dish->menu_addition = $record['menu_toevoeging'] == "NULL" ? null : $record['menu_toevoeging'];
            $dish->name = $record['naam'];
            $dish->price = $record['price'];
            $dish->description = $record['beschrijving'] == "NULL" ? null : $record['beschrijving'];
            if (Category::where('name', $record['soortgerecht'])->first() == null) {
                var_dump($record['soortgerecht']);
            }
            $dish->category_id = Category::where('name', $record['soortgerecht'])->first()->id;
            $dish->save();
        }
    }
}
