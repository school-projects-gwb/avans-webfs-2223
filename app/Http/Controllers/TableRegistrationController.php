<?php

namespace App\Http\Controllers;

use App\Helpers\CookieHandler;
use App\Helpers\CookieKey;
use App\Models\Table;
use App\Models\TableRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TableRegistrationController extends Controller
{
    public function index() {
        return Inertia::render('TableRegistration/Index');
    }

    public function show() {
        return Inertia::render('TableRegistration/Orders');
    }

    public function store(Request $request) {
        $request->validate([
            'table_number' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $table = Table::where('table_number', $value)->first();

                    if (!$table) {
                        $fail('Tafel niet gevonden of niet beschikbaar.');
                    }
                }
            ]
        ]);

        $table = Table::where('table_number', $request->table_number)->first();
        $tableRegistration = new TableRegistration();
        $tableRegistration->table_id = $table->id;
        $tableRegistration->save();

        $cookie = cookie(CookieKey::TABLE_REGISTRATION->key(), json_encode(['registration_id' => $tableRegistration->id]), 60 * 24 * 7);
        return redirect('/table-registration/show')->withCookie($cookie);
    }
}
