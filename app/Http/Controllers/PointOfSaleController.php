<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Dish;
use App\Models\Restaurant;

class PointOfSaleController extends Controller
{
    public function index(){
        return Inertia::render('PointOfSale/Index');
    }
}

