<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Option;
use Illuminate\Http\Request;

class TakeawayOrderController extends Controller
{
    private string $dish_cookie_key = 'cart_dish_ids';

    public function getData(Request $request): array
    {
        $cookieData = $this->getCookieData();

        if ($cookieData == null) {
            $cookieData = [];
        }

        $dish_data = Dish::with('category', 'options')
            ->select('dishes.*', 'categories.special_description')
            ->join('categories', 'dishes.category_id', '=', 'categories.id')
            ->whereIn('dishes.id', array_keys($cookieData))
            ->get();

        return [
            'dish_data' => $dish_data,
            'option_data' => $cookieData
        ];
    }

    private function getCookieData() {
        return json_decode(request()->cookie($this->dish_cookie_key), true);
    }

    public function handleDishCookie($dishId) {
        $cookieData = $this->getCookieData();

        $index = $cookieData == null ? false : in_array($dishId, array_keys($cookieData));
        $cookieData = $cookieData == null ? [] : $cookieData;

        if ($index === false) {
            $cookieData[$dishId] = [];
            $message = 'Dish added to cookie';
        } else {
            $message = 'Dish already in cart';
        }

        $cookie = cookie($this->dish_cookie_key, json_encode($cookieData), 60 * 24 * 7); // 1 week

        return response($message)->withCookie($cookie);
    }

    public function handleDishOptionCookie($dishId, $optionId) {
        $cookieData = $this->getCookieData();
        $indexCheck = in_array($dishId, array_keys($cookieData));
        $message = 'Optie succesvol toegevoegd.';

        if ($indexCheck === false) {
            $message = 'Gerecht niet gevonden.';
        } else if (!in_array($optionId,$cookieData[$dishId])) { // Make sure we're ADDING and not REMOVING option
            // Check whether dish has option
            $dish = Dish::with(['options' => function ($query) use ($cookieData, $dishId) {
                $query->whereIn('id', $cookieData[$dishId]);
            }])->find($dishId);
            // Make sure dish exists and has as the cookie options
            if ($dish && count($dish->options) == count($cookieData[$dishId])) {
                $option = Option::where('id', $optionId)->first();
                // Check optional options
                if ($option->price) {
                    // Make sure this is 0, you cannot have more than 1 optional free option
                    $current_options = Option::whereIn('id', $cookieData[$dishId])->whereNotNull('price')->get();
                    if (count($current_options) > 0) {
                        $message = 'Je mag maximaal 1 van dit type gerecht hebben.';
                    } else {
                        $cookieData[$dishId][] = $optionId;
                    }
                } else {
                    $current_options = Option::whereIn('id', $cookieData[$dishId])->whereNull('price')->get();
                    if (count($current_options) >= $dish->option_amount) {
                        $message = 'Je mag maximaal '.$dish->option_amount .' van dit type gerecht(en) hebben.';
                    } else {
                        $cookieData[$dishId][] = $optionId;
                    }
                }
            }
        } else {
            $optionIndex = array_search($optionId, $cookieData[$dishId]);
            unset($cookieData[$dishId][$optionIndex]);
            $message = 'Optie succesvol verwijderd.';
        }

        $cookie = cookie($this->dish_cookie_key, json_encode($cookieData), 60 * 24 * 7); // 1 week

        return response($message)->withCookie($cookie);
    }
}
