<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TakeawayController extends Controller
{
    private string $order_placed_cookie_key = 'order_placed';

    public function getOrderQRData() {
        $orderCookie = json_decode(request()->cookie($this->order_placed_cookie_key), true);

        if ($orderCookie['order']) {

        }
    }
}
