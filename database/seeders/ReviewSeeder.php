<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Review;
use App\Models\ReviewQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $review = new Review();
        $review->order_id = 1;
        $review->comment = 'Geweldige ervaring gehad bij jullie!';
        $review->save();

        $questionIds = [1, 2, 3, 4, 5];

        foreach ($questionIds as $questionId) {
            $reviewQuestion = new ReviewQuestion();
            $reviewQuestion->answer = random_int(3, 5);
            $reviewQuestion->question_id = $questionId;
            $review->reviewQuestions()->save($reviewQuestion);
        }
    }
}
