<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Restaurant::create([
            'home_description' => 'Al jaren is De Gouden Draak een begrip als het gaat om de beste afhaalgerechten in \'s-Hertogenbosch. Graag trakteren we u op authentieke gerechten uit de Cantonese keuken.',
            'menu_description' =>  'Mogelijkheid tot telefonisch bestellen<br>Ruime parkeergelegenheid<br>Catering orientaalse stijl<br>Airconditioned<br>Bezorgen €2.00 extra',
            'phone_number' => '06-12345678',
            'email_address' => 'contact@degoudendraak.nl'
        ]);
    }
}
