<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index() {
        return Inertia::render('Review/Index', [
            'reviews' => Review::with('reviewQuestions', 'reviewQuestions.question')->get()
        ]);
    }

    public function create() {

    }

    public function store() {

    }
}
