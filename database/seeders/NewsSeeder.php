<?php

namespace Database\Seeders;

use App\Enums\ActivityTypeEnum;
use App\Models\ActivityType;
use App\Models\News;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::create(['restaurant_id' => 1, 'title' => 'Nieuwsartikel 1', 'content' =>  'Nieuwsartikel inhoud 1']);
        News::create(['restaurant_id' => 1, 'title' => 'Nieuwsartikel 2', 'content' =>  'Nieuwsartikel inhoud 2']);
        News::create(['restaurant_id' => 1, 'title' => 'Nieuwsartikel 3', 'content' =>  'Nieuwsartikel inhoud 3']);
    }
}
