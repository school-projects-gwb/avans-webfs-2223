<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Dish;
use App\Models\Restaurant;

class PointOfSaleController extends Controller
{
    public function index(){
        return Inertia::render('PointOfSale/Index', [
                'menu_data' => Dish::with('category', 'options')
            ->select('dishes.*', 'categories.special_description')
            ->join('categories', 'dishes.category_id', '=', 'categories.id')
            ->get()
            ->groupBy('category.name')
            ]);
    }
}
