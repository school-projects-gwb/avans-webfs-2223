<?php

namespace App\Http\Controllers;

use App\Helpers\CookieHandler;
use App\Helpers\CookieKey;
use App\Models\Order;
use App\Models\Table;
use App\Models\TableRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TableRegistrationController extends Controller
{
    public function cashierIndex() {
        $tableRegistrations = TableRegistration::with('orders', 'table')->get();

        return Inertia::render('TableRegistration/CashierIndex', [
            'table_registrations' => $tableRegistrations
        ]);
    }

    public function getReceiptPdf() {

    }

    public function index() {
        return Inertia::render('TableRegistration/Index');
    }

    public function show() {
        return Inertia::render('TableRegistration/Orders');
    }

    public function addOrder(Request $request, $orderId) {
        $tableRegistration = $this->getTableRegistration();

        $order = Order::find($orderId);
        $tableRegistration->orders()->attach($order);

        return true;
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

    public function setOrderCookie(Request $request, $orderId) {
        $order = Order::with('orderLines')->find($orderId);
        $cookieData = [];

        foreach ($order->orderlines as $orderLine) {
            $dishId = $orderLine->dish_id;
            $optionId = $orderLine->option_id;

            if (!key_exists($dishId, $cookieData)) {
                $cookieData[$dishId] = [
                    'amount' => $optionId ? 1 : $orderLine->amount,
                    'options' => []
                ];
            }

            if ($optionId != null) {
                $cookieData[$dishId]['amount'] = 1;
                $cookieData[$dishId]['options'][] = str($optionId);
            }
        }

        $cookie = cookie(CookieKey::DISH->key(), json_encode($cookieData), 60 * 24 * 7); // 1 week
        return response("")->withCookie($cookie);
    }

    private function getTableRegistration() {
        $cookieData = CookieHandler::getData(CookieKey::TABLE_REGISTRATION);
        return TableRegistration::with('orders', 'table')->find($cookieData['registration_id']);
    }

    public function getData() {
        $cookieData = CookieHandler::getData(CookieKey::TABLE_REGISTRATION);
        $tableRegistration = TableRegistration::with('orders', 'table')->find($cookieData['registration_id']);

        return [
            'registration_data' => $tableRegistration
        ];
    }

    public function getCanOrder()
    {
        $tableRegistration = $this->getTableRegistration();

        if ($tableRegistration) {
            $lastOrder = $tableRegistration->orders()->latest()->first();

            if ($lastOrder) {
                $currentTime = now();
                $orderTime = $lastOrder->created_at;

                $timeDifference = $currentTime->diffInMinutes($orderTime);

                if ($timeDifference >= 5 && $tableRegistration->orders->count() < 5) {
                    return true;
                }
            } else {
                return true;
            }
        }

        return false;
    }

    public function clearRegistrationCookie() {
        $registrationCookie = Cookie::forget(CookieKey::TABLE_REGISTRATION->key());
        return response('Cookies removed')->withCookie($registrationCookie);
    }
}
