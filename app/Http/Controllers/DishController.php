<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DishController extends Controller
{
    public function menu(Request $request) {
        return Inertia::render('Dish/Menu');
    }

    public function index(Request $request) {
        $dishes = Dish::with('options')->orderBy('menu_number')->orderBy('category_id')->get();

        return Inertia::render('Dish/Index', [
            'dishes' => $dishes
        ]);
    }
}
