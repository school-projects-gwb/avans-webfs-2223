<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\OpeningTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpeningTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OpeningTime::create(['restaurant_id' => 1, 'weekday_number' => 0, 'start_time' => '15:30', 'end_time' =>  '21:00']);
        OpeningTime::create(['restaurant_id' => 1, 'weekday_number' => 1, 'start_time' => '15:30', 'end_time' =>  '21:00']);
        OpeningTime::create(['restaurant_id' => 1, 'weekday_number' => 2, 'start_time' => '15:30', 'end_time' =>  '21:00']);
        OpeningTime::create(['restaurant_id' => 1, 'weekday_number' => 3, 'start_time' => '15:30', 'end_time' =>  '21:00']);
        OpeningTime::create(['restaurant_id' => 1, 'weekday_number' => 4, 'start_time' => '12:00', 'end_time' =>  '21:30']);
        OpeningTime::create(['restaurant_id' => 1, 'weekday_number' => 5, 'start_time' => '12:00', 'end_time' =>  '21:30']);
        OpeningTime::create(['restaurant_id' => 1, 'weekday_number' => 6, 'start_time' => '12:00', 'end_time' =>  '21:30']);
    }
}
