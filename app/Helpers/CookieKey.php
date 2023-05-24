<?php

namespace App\Helpers;

enum CookieKey
{
    case TABLE_REGISTRATION;
    case DISH;
    case ORDER_PLACED;

    public function key(): string
    {
        return match ($this) {
            CookieKey::TABLE_REGISTRATION => 'table_order_id',
            CookieKey::DISH => 'cart_dish_ids',
            CookieKey::ORDER_PLACED => 'order_placed'
        };
    }
}
