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

        if ($favourite_dish_ids == null) {
            $favourite_dish_ids = [];
        }

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
        $key = 'dish_ids';
        $existingDishIds = json_decode(request()->cookie($key, '[]'));

        $index = array_search($dishId, $existingDishIds);

        if ($index !== false) {
            array_splice($existingDishIds, $index, 1);
            $message = 'Dish removed from cookie';
        } else {
            $existingDishIds[] = $dishId;
            $message = 'Dish added to cookie';
        }

        $cookie = cookie($key, json_encode($existingDishIds), 60 * 24 * 7); // Set the cookie to expire in 1 week

        return response($message)->withCookie($cookie);
    }

    public function printPdf()
    {
        $dish_data = Dish::with('category', 'options')
            ->select('dishes.*', 'categories.special_description')
            ->join('categories', 'dishes.category_id', '=', 'categories.id')
            ->where('categories.name', '<>', 'AANBIEDINGEN')
            ->get()
            ->groupBy('category.name');

        $category_data = $dish_data->map(function ($dishes, $categoryName) {
            $category = $dishes->first()->category;
            return [
                'name' => $categoryName,
                'special_description' => $category->special_description,
                'dishes' => $dishes->sortBy('menu_number')
            ];
        });

        $dish_discount_data = Dish::with('options')
            ->select('dishes.*')
            ->join('categories', 'dishes.category_id', '=', 'categories.id')
            ->where('categories.name', '=', 'AANBIEDINGEN')
            ->orderBy('menu_number')
            ->get();

        $menu_data = [
            'dish_data' => $category_data,
            'option_data' => Option::whereNotNull('price')->get(),
            'restaurant_data' => Restaurant::first(),
            'dish_discount_data' => $dish_discount_data
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('menu', compact('menu_data'));
        return $pdf->download('menu.pdf');
    }
}
