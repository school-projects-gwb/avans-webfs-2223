<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Dish;
use App\Models\Restaurant;

class PointOfSaleController extends Controller
{
    public function index(){
        $dishCounts = OrderLine::select('dish_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('dish_id')
            ->with('dish')
            ->get();

        $optionCounts = OrderLine::select('option_id')
            ->whereNotNull('option_id')
            ->whereHas('option', function ($query) {
                $query->whereNotNull('price');
            })
            ->selectRaw('COUNT(*) as count')
            ->groupBy('option_id')
            ->with('option')
            ->get();

        dd($optionCounts);
        return Inertia::render('PointOfSale/Index');
    }
}

