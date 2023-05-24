<?php

namespace App\Helpers;
use Illuminate\Http\Request;

class CookieHandler
{
    public static function getData(CookieKey $cookieKey) {
        return json_decode(request()->cookie($cookieKey->key()), true);
    }
}
