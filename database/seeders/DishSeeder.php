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
        $extraOptions = Option::whereIn('name', ['Bami Goreng', 'Nasi Goreng', 'Mihoen goreng', 'Chinese bami'])->get();

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
                $dish->option_required = true;
                $dish->option_amount = 1;
                $dish->options()->attach($bamiNasiOptions);
            }

            if (str_contains($record['naam'], 'Kippen- of Tomatensoep')) {
                $dish->option_required = true;
                $dish->option_amount = 1;
                $dish->options()->attach($soupOptions);
            }

            if (str_contains($record['soortgerecht'], '(met witte rijst)')) {
                $dish->option_required = false;
                $dish->option_amount = 1;
                $dish->options()->attach($extraOptions);
            }

            $dish->save();
        }

        // Student discount Dish
        $dish = new Dish();
        $dish->name = "Chinese Rijsttafel (2 personen)";
        $dish->description = "Speciale Studentenaanbieding";
        $dish->price = 21;
        $dish->is_discount = true;
        $dish->category_id = Category::where('name', 'AANBIEDINGEN')->first()->id;
        $dish->save();

        $dish->option_required = true;
        $dish->option_amount = 3;
        $dish->options()->attach(Option::whereIn('name',
            ['Koe Loe Yuk', 'Tjap Tjoy', 'Babi Pangang', 'Foe Yong Hai', 'Garnalen met Gebakken Knoflook', 'Kipfilet in Zwarte Bonen saus', 'Bami', 'Nasi']
        )->get());
    }
}
