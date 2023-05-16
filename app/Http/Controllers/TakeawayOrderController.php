<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class TakeawayOrderController extends Controller
{
    public function getData(Request $request): array
    {
        $cart_dish_ids = json_decode($request->cookie('cart_dish_ids'), true);

        if ($cart_dish_ids == null) {
            $cart_dish_ids = [];
        }

        $dish_data = Dish::with('category', 'options')
            ->select('dishes.*', 'categories.special_description')
            ->join('categories', 'dishes.category_id', '=', 'categories.id')
            ->whereIn('dishes.id', $cart_dish_ids)
            ->get();

        return [
            'dish_data' => $dish_data
        ];
    }

    public function handleDishCookie($dishId) {
        $key = 'cart_dish_ids';
        $existingDishIds = json_decode(request()->cookie($key, '[]'));

        $index = array_search($dishId, $existingDishIds);

        if ($index === false) {
            $existingDishIds[] = $dishId;
            $message = 'Dish added to cookie';
        } else {
            $message = 'Dish already in cart';
        }

        $cookie = cookie($key, json_encode($existingDishIds), 60 * 24 * 7); // Set the cookie to expire in 1 week

        return response($message)->withCookie($cookie);
    }
}
