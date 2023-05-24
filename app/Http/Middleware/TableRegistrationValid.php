<?php

namespace App\Http\Middleware;

use App\Helpers\CookieHandler;
use App\Helpers\CookieKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TableRegistrationValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cookie_value = $request->cookie(CookieKey::TABLE_REGISTRATION->key());

        return $cookie_value ? $next($request) : redirect()->route('table-registration.index');
    }
}
