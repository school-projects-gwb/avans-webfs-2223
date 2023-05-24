<?php

namespace App\Http\Controllers;

use App\Helpers\CookieHandler;
use App\Helpers\CookieKey;
use App\Models\Dish;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class OrderController extends Controller
{
    /**
     * Get all order data (for displaying in cart)
     * @param Request $request
     * @return array Order data
     */
    public function getData(Request $request): array
    {
        $cookieData = CookieHandler::getData(CookieKey::DISH);

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

    /**
     * Create (and validate) new order based on cookie data
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate([
            'first_name' => 'nullable|string|max:45',
            'last_name' => 'nullable|string|max:75'
        ]);

        $valid = true;
        $status = 201;
        $message = "Order created";

        $cookieData = CookieHandler::getData(CookieKey::DISH);
        // Make sure all dishes and options are valid
        // Strategy: Go through every dish and check whether the options are valid
        foreach ($cookieData as $dish_id => $dish_data) {
            foreach ($dish_data['options'] as $option_id) {
                $statusCode = $this->handleDishOptionCookie($dish_id, $option_id)->getStatusCode();
                if ($statusCode != 200) {
                    $valid = false;
                    $message = "Gerecht data niet valide";
                    break;
                }
            }

            $optionLimits = $this->getOptionLimits(Dish::with('category', 'options')->find($dish_id), $dish_data['options']);

            if (!$optionLimits['required_limit_reached']) {
                $valid = false;
                $message = "Kies alle verplichte opties";
                break;
            }
        }

        if (!$valid) {
            $status = 422;
        } else {// Create new order and order lines
            $order = new Order();
            $order->first_name = $request->input('first_name');
            $order->last_name = $request->input('last_name');
            $order->is_takeaway = true;
            $order->save();

            foreach ($cookieData as $dish_id => $dish_data) {
                $orderLine = new OrderLine();
                $orderLine->dish_id = $dish_id;
                $orderLine->amount = $dish_data['amount'];
                $order->orderLines()->save($orderLine);

                foreach ($dish_data['options'] as $option_id) {
                    $orderLine = new OrderLine();
                    $orderLine->dish_id = $dish_id;
                    $orderLine->option_id = $option_id;
                    $orderLine->amount = 1;
                    $order->orderLines()->save($orderLine);
                }
            }
            // Return cookie containing order information
            $message = Order::with('orderLines')->find($order->id);
            $cookie = cookie(CookieKey::ORDER_PLACED->key(), json_encode(['order' => $message]), 60 * 24 * 7);
            return response($message, $status)->withCookie($cookie);
        }

        return response($message, $status);
    }

    public function clearOrderCookie() {
        $dishCookie = Cookie::forget(CookieKey::DISH->key());
        $orderPlacedCookie = Cookie::forget(CookieKey::ORDER_PLACED->key());

        return response('Cookies removed')->withCookie($dishCookie)->withCookie($orderPlacedCookie);
    }

    public function isOrderPlaced() {
        return CookieHandler::getData(CookieKey::ORDER_PLACED);
    }

    public function handleDishCookie($dishId, $amount) {
        $cookieData = CookieHandler::getData(CookieKey::DISH);

        $index = $cookieData == null ? false : in_array($dishId, array_keys($cookieData));
        $cookieData = $cookieData == null ? [] : $cookieData;

        if ($index === false) {
            $cookieData[$dishId] = ['amount' => 1, 'options' => []];
        } else {
            // Already exists
            if ($amount > 0) { // Modify amount
                $cookieData[$dishId]['amount'] = $amount;
            } else { // Remove dish
                unset($cookieData[$dishId]);
            }
        }

        $cookie = cookie(CookieKey::DISH->key(), json_encode($cookieData), 60 * 24 * 7); // 1 week

        return response("")->withCookie($cookie);
    }

    private function getOrderTotals($dishData, $cookieData) {
        $dishTotals = $dishData->sum(function ($dish) use ($cookieData) {
            return $dish->price * $cookieData[$dish->id]['amount'];
        });

        $optionTotals = 0;

        foreach ($cookieData as $dish_id => $dish_data) {
            $optionData = Option::whereIn('id', $dish_data['options'])->whereNotNull('price')->get();
            $totals = $optionData->sum(function ($option) use ($dish_data) {
               return $option->price * $dish_data['amount'];
            });
            $optionTotals += $totals;
        }

        return number_format($dishTotals + $optionTotals, 2, ',', '.');
    }

    /**
     * Get limit of options for both optional and required options
     * Rule: Only 1 optional option per order
     * Rule: Maximum of option_amount options for required options
     * Required option = Option that has no price set
     * @param $dish
     * @param $option_ids
     * @return array
     */
    private function getOptionLimits($dish, $option_ids) {
        $result = [];

        // Optional options
        $optional_options = Option::whereIn('id',  $option_ids)->whereNotNull('price')->get();
        $result['optional_limit_reached'] = count($optional_options) == 1;

        // Required options
        $required_options = Option::whereIn('id',  $option_ids)->whereNull('price')->get();
        $result['required_limit_reached'] = !$dish->option_required || count($required_options) == $dish->option_amount;

        return $result;
    }

    public function handleDishOptionCookie($dishId, $optionId) {
        $cookieData = CookieHandler::getData(CookieKey::DISH);
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

        $cookie = cookie(CookieKey::DISH->key(), json_encode($cookieData), 60 * 24 * 7); // 1 week

        return response($message, $status)->withCookie($cookie);
    }
}
