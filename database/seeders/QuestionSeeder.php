<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Question::create(['question' => 'Hoe zou u de algehele eetervaring beoordelen?']);
        Question::create(['question' => 'Hoe tevreden was u over de kwaliteit van het eten?']);
        Question::create(['question' => 'Hoe zou u de service van het personeel beoordelen?']);
        Question::create(['question' => 'Hoe schoon en goed onderhouden was het restaurant?']);
        Question::create(['question' => 'Hoe waarschijnlijk is het dat u dit restaurant aan anderen zou aanbevelen?']);
    }
}
