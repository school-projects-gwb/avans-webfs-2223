<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Question;
use App\Models\Review;
use App\Models\ReviewQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ReviewController extends Controller
{
    private int $maxRating = 5;

    public function index() {
        return Inertia::render('Review/Index', [
            'reviews' => Review::with('reviewQuestions', 'reviewQuestions.question')->get()
        ]);
    }

    public function create($orderId) {
        $order = Order::with('review')->find($orderId);
        if (!$order || $order->review != null) {
            return redirect('/');
        }

        return Inertia::render('Review/Create', [
            'order_id' => $orderId,
            'max_rating' => $this->maxRating,
            'questions' => Question::all()
        ]);
    }

    public function success() {
        return Inertia::render('Review/Success');
    }

    public function store(Request $request, $orderId) {
        $order = Order::with('review')->find($orderId);
        if (!$order || $order->review != null) return response('Geen geldige bestelling, of bestelling is al gereviewed.');

        $valid = true;
        foreach ($request->input('questions') as $question) {
            $questionCheck = Question::find($question['question_id']);
            if (!$questionCheck) $valid = false;
        }

        if (!$valid || count(Question::all()) != count($request->input('questions'))) return response('Niet alle vragen zijn geldig of ingevuld.');

        $review = new Review();
        $review->order_id = $orderId;
        $review->comment = $request->input('comment');
        $review->save();

        foreach ($request->input('questions') as $question) {
            $reviewQuestion = new ReviewQuestion();
            $reviewQuestion->answer = $question['answer'];
            $reviewQuestion->question_id = $question['question_id'];
            $review->reviewQuestions()->save($reviewQuestion);
        }

        return response('');
    }
}
