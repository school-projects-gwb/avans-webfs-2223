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
    public function getData(Request $request, $sorting): array
    {
        $sort_favourites = $sorting == 'fav' || $sorting == 'all';
        $sort_menu = $sorting == 'menu' || $sorting == 'all';

        $favourite_dish_ids = json_decode($request->cookie('dish_ids'), true);

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
            'sort_options' => ['none' => 'Ongesorteerd', 'all' => 'Alles', 'fav' => 'Favorieten', 'menu' => 'Menu'],
            'favourite_dishes' => $favourite_dish_ids
        ];
    }

    public function handleDishCookie($dishId) {
        // Get the existing array of dish IDs from the cookie, or create a new array if the cookie doesn't exist
        $existingDishIds = json_decode(request()->cookie('dish_ids', '[]'));

        // Check if the dish ID is already in the array
        $index = array_search($dishId, $existingDishIds);

        if ($index !== false) {
            // Dish ID is already in the array, so remove it
            array_splice($existingDishIds, $index, 1);
            $message = 'Dish removed from cookie';
        } else {
            // Dish ID is not in the array, so add it
            $existingDishIds[] = $dishId;
            $message = 'Dish added to cookie';
        }

        // Create a new cookie instance with the updated array of dish IDs
        $cookie = cookie('dish_ids', json_encode($existingDishIds), 60 * 24 * 7); // Set the cookie to expire in 1 week

        // Return a response with the cookie attached
        return response($message)->withCookie($cookie);
    }
}
