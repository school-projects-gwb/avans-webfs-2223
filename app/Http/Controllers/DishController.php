<?php

namespace App\Http\Controllers;

use App\Http\Requests\DishCreateRequest;
use App\Http\Requests\DishUpdateRequest;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Option;
use Database\Seeders\CategorySeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
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
        return Inertia::render('Dish/Create', [
            'options' => Option::all(),
            'categories' => Category::all()
        ]);
    }

    public function store(DishCreateRequest $request) {
        $data = $request->only(['name', 'description', 'price', 'is_discount', 'option_required', 'option_amount', 'category_id']);

        $existingMenuNumbers = Dish::pluck('menu_number')->toArray();

        $nextMenuNumber = 1;
        while (in_array($nextMenuNumber, $existingMenuNumbers)) {
            $nextMenuNumber++;
        }

        $data['menu_number'] = $nextMenuNumber;

        // Create the dish
        $dish = Dish::create($data);

        // Attach the options
        $optionIds = $request->input('option_ids', []);
        $options = Option::whereIn('id', $optionIds)->get();
        $dish->options()->attach($options);

        return redirect::Route('admin.dishes.index');
    }

    public function edit(Request $request, $dishId) {
        $dish = Dish::with('options')->find($dishId);

        return Inertia::render('Dish/Edit', [
            'dish' => $dish,
            'options' => Option::all(),
            'categories' => Category::all()
        ]);
    }

    public function update(DishUpdateRequest $request, $dishId) {
        $dish = Dish::find($dishId);
        $dish->update($request->only(['name', 'description', 'price', 'is_discount', 'option_required', 'option_amount', 'category_id']));
        $dish->options()->sync($request->option_ids);

        return redirect::Route('admin.dishes.index');
    }

    public function destroy(Request $request, Dish $dish) {
        $dish->delete();
        return redirect::Route('admin.dishes.index');
    }
}
