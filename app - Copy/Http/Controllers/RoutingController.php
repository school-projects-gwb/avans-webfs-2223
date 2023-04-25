<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class RoutingController extends Controller
{
    public function index()
    {
        return Inertia::render('Index', [
            'title' => 'Svelte',
        ]);
    }
}
