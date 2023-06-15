<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesController extends Controller
{
    public function index(Request $request) {
        return Inertia::render('Sales/Index');
    }
    
    public function getData(Request $request) {

    }
}
