<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Option;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private string $dish_cookie_key = 'cart_dish_ids';

    public function getData(Request $request): array
    {
        $cookieData = $this->getCookieData();

        if ($cookieData == null) {
            $cookieData = [];
        }

        $dishData = Dish::with('category', 'options')
            ->select('dishes.*', 'categories.special_description')
            ->join('categories', 'dishes.category_id', '=', 'categories.id')
            ->whereIn('dishes.id', array_keys($cookieData))
            ->get()
            ->map(function ($dish) use ($cookieData) {
                $optionLimits = $this->getOptionLimits($dish, $cookieData[$dish->id]['options']);
                $dish->is_option_required_limit = $optionLimits['required_limit_reached'];
                $dish->is_option_optional_limit = $optionLimits['optional_limit_reached'];
                return $dish;
            });

        return [
            'dish_data' => $dishData,
            'option_data' => $cookieData,
            'total_amount' => $this->getOrderTotals($dishData, $cookieData)
        ];
    }

    public function store() {
        $valid = true;

        $cookieData = $this->getCookieData();

        foreach ($cookieData as $dish_id => $dish_data) {
            foreach ($dish_data['options'] as $option_id) {
                $statusCode = $this->handleDishOptionCookie($dish_id, $option_id)->getStatusCode();
                if ($statusCode != 200) {
                    $valid = false;
                    break;
                }
            }
        }

        dd($valid);
    }

    private function getCookieData() {
        return json_decode(request()->cookie($this->dish_cookie_key), true);
    }

    public function handleDishCookie($dishId) {
        $cookieData = $this->getCookieData();

        $index = $cookieData == null ? false : in_array($dishId, array_keys($cookieData));
        $cookieData = $cookieData == null ? [] : $cookieData;

        if ($index === false) {
            $cookieData[$dishId] = ['amount' => 1, 'options' => []];
            $message = 'Dish added to cookie';
        } else {
            $message = 'Dish already in cart';
        }

        $cookie = cookie($this->dish_cookie_key, json_encode($cookieData), 60 * 24 * 7); // 1 week

        return response($message)->withCookie($cookie);
    }

    private function getOrderTotals($dishData, $cookieData) {
        $dishTotals = $dishData->sum(function ($dish) {
            return $dish->price;
        });

        $optionTotals = 0;

        foreach ($cookieData as $dish_id => $dish_data) {
            $optionData = Option::whereIn('id', $dish_data['options'])->whereNotNull('price')->get();
            $totals = $optionData->sum(function ($option) {
               return $option->price;
            });
            $optionTotals += $totals;
        }

        return number_format($dishTotals + $optionTotals, 2, ',', '.');
    }

    private function getOptionLimits($dish, $option_ids) {
        $result = [];

        // Optional options
        $optional_options = Option::whereIn('id',  $option_ids)->whereNotNull('price')->get();
        $result['optional_limit_reached'] = count($optional_options) == 1;

        // Required options
        $required_options = Option::whereIn('id',  $option_ids)->whereNull('price')->get();
        $result['required_limit_reached'] = count($required_options) == $dish->option_amount;

        return $result;
    }

    public function handleDishOptionCookie($dishId, $optionId) {
        $cookieData = $this->getCookieData();
        $indexCheck = in_array($dishId, array_keys($cookieData));
        $message = 'Optie succesvol toegevoegd.';
        $status = 200;

        if ($indexCheck === false) {
            $message = 'Gerecht niet gevonden.';
            $status = 304;
        } else if (!in_array($optionId,$cookieData[$dishId]['options'])) { // Make sure we're ADDING and not REMOVING option
            // Check whether dish has option
            $dish = Dish::with(['options' => function ($query) use ($cookieData, $dishId) {
                $query->whereIn('id', $cookieData[$dishId]['options']);
            }])->find($dishId);
            // Make sure dish exists and has as the cookie options
            if ($dish && count($dish->options) == count($cookieData[$dishId]['options'])) {
                $option = Option::where('id', $optionId)->first();
                // Check optional options
                if ($option->price) {
                    // Make sure this is 0, you cannot have more than 1 optional free option
                    $current_options = Option::whereIn('id', $cookieData[$dishId]['options'])->whereNotNull('price')->get();
                    if (count($current_options) > 0) {
                        $message = 'Je mag maximaal 1 van dit type gerecht hebben.';
                        $status = 304;
                    } else {
                        $cookieData[$dishId]['options'][] = $optionId;
                    }
                } else {
                    $current_options = Option::whereIn('id', $cookieData[$dishId]['options'])->whereNull('price')->get();
                    if (count($current_options) >= $dish->option_amount) {
                        $message = 'Je mag maximaal '.$dish->option_amount .' van dit type gerecht(en) hebben.';
                        $status = 304;
                    } else {
                        $cookieData[$dishId]['options'][] = $optionId;
                    }
                }
            }
        } else {
            $optionIndex = array_search($optionId, $cookieData[$dishId]['options']);
            unset($cookieData[$dishId]['options'][$optionIndex]);
            $cookieData[$dishId]['options'] = array_values($cookieData[$dishId]['options']);
            $message = 'Optie succesvol verwijderd.';
        }

        $cookie = cookie($this->dish_cookie_key, json_encode($cookieData), 60 * 24 * 7); // 1 week

        return response($message, $status)->withCookie($cookie);
    }
}
