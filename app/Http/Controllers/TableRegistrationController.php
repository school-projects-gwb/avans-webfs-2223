<?php

namespace App\Http\Controllers;

use App\Helpers\CookieHandler;
use App\Helpers\CookieKey;
use App\Models\TableRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TableRegistrationController extends Controller
{
    public function index() {
        $cookie_value = CookieHandler::getData(CookieKey::TABLE_REGISTRATION);
        return Inertia::render('TableRegistration/Index');
    }

    public function show() {
        return Inertia::render('TableRegistration/Orders');
    }
    
    public function store(Request $request) {
        $tableRegistration = new TableRegistration();
        return Redirect::route('table-registration.show');
    }
}
