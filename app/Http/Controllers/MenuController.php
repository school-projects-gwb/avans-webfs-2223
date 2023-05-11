<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Option;
use App\Models\Restaurant;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    public function get_data(): array
    {
        return [
            'dish_data' => Dish::with('category', 'options')
                ->select('dishes.*', 'categories.special_description')
                ->join('categories', 'dishes.category_id', '=', 'categories.id')
                ->get()
                ->groupBy('category.name')
                ->map(function ($dishes, $categoryName) {
                    $category = $dishes->first()->category;
                    return [
                        'special_description' => $category->special_description,
                        'dishes' => $dishes,
                    ];
                }),
            'option_data' => Option::whereNotNull('price')->get(),
            'restaurant_data' => Restaurant::first()
        ];
    }
}
