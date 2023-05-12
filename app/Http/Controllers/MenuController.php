<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Option;
use App\Models\Restaurant;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    public function get_data($sorting): array
    {
        $sort_favourites = $sorting == 'fav' || $sorting == 'all';
        $sort_menu = $sorting == 'menu' || $sorting == 'all';

        $favourite_dish_ids = [2];

        if (count($favourite_dish_ids) > 0 && $sorting != 'disabled') {
            $favourite_dishes = Dish::whereIn('id', $favourite_dish_ids)
                ->with('category', 'options')
                ->select('dishes.*', DB::raw("'FAVORIETEN' as category_name"));

            if ($sort_favourites) {
                $favourite_dishes = $favourite_dishes->sortBy('menu_number');
            }

            $favourite_dishes = $favourite_dishes->get();
        }

        $dish_data = Dish::with('category', 'options')
            ->select('dishes.*', 'categories.special_description')
            ->join('categories', 'dishes.category_id', '=', 'categories.id')
            ->get()
            ->groupBy('category.name');

        $category_data = $dish_data->map(function ($dishes, $categoryName) use ($sort_menu) {
            $category = $dishes->first()->category;
            return [
                'name' => $categoryName,
                'special_description' => $category->special_description,
                'dishes' => $sort_menu ? $dishes->sortBy('menu_number') : $dishes,
            ];
        });

        $favourite_data = null;
        if (count($favourite_dish_ids) > 0 && $sorting != 'disabled') {
            $favourite_data = $favourite_dishes->groupBy('category_name')
                ->map(function ($dishes, $categoryName) use ($sort_favourites) {
                    return [
                        'name' => $categoryName,
                        'special_description' => '',
                        'dishes' => $sort_favourites ? $dishes->sortBy('menu_number') : $dishes,
                    ];
                });
        }

        $merged_data = $favourite_data == null ? $category_data : $favourite_data->merge($category_data);

        return [
            'dish_data' => $merged_data,
            'option_data' => Option::whereNotNull('price')->get(),
            'restaurant_data' => Restaurant::first(),
            'sort_options' => ['none' => 'Ongesorteerd', 'all' => 'Alles', 'fav' => 'Favorieten', 'menu' => 'Menu']
        ];
    }
}
