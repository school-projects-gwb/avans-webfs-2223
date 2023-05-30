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

    public function create(Request $request) {

    }

    public function store(Request $request) {

    }

    public function edit(Request $request, $dishId) {

    }

    public function update(Request $request, Dish $dish) {

    }

    public function destroy(Request $request, Dish $dish) {

    }
}
