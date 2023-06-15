<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesGetRequest;
use App\Models\News;
use App\Models\OrderLine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesController extends Controller
{
    public function index(Request $request) {
        return Inertia::render('Sales/Index');
    }

    public function getData(SalesGetRequest $request) {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $orderLines = OrderLine::with('option', 'dish')->whereBetween('created_at', [
            Carbon::parse($start_date)->startOfDay(),
            Carbon::parse($end_date)->endOfDay()
        ])->get();

        $orderLinesByDish = [];
        $salesData = [
            'total_gross' => 0
        ];

        foreach ($orderLines as $orderLine) {
            $dishId = $orderLine->dish_id;
            $optionId = $orderLine->option_id;

            if (!isset($orderLinesByDish[$dishId])) {
                $orderLinesByDish[$dishId] = [
                    'created_at' => Carbon::parse($orderLine->created_at)->format('d-m-Y'),
                    'dish_name' => $orderLine->dish->name,
                    'amount' => $orderLine->amount,
                    'combined_price' => $orderLine->dish->price * $orderLine->amount,
                    'option_names' => "",
                ];

                $salesData['total_gross'] += $orderLine->dish->price * $orderLine->amount;
            }

            if ($optionId != null) {
                $orderLinesByDish[$dishId]['option_names'] .= ($orderLinesByDish[$dishId]['option_names'] == "" ? '' : ', ') . $orderLine->option->name;
                $orderLinesByDish[$dishId]['combined_price'] += $orderLine->option->price * $orderLine->amount;
                $salesData['total_gross'] += $orderLine->option->price;
            }
        }

        $totalGrossFormatted = number_format(floatval($salesData['total_gross']), 2, ',', '.');
        $salesData['total_net'] = round(($salesData['total_gross'] / 106) * 100, 2);
        $salesData['total_vat'] = round($salesData['total_gross'] - $salesData['total_net'], 2);
        $salesData['total_gross'] = $totalGrossFormatted;
        $salesData['orderLines'] = $orderLinesByDish;

        return $salesData;
    }
}
