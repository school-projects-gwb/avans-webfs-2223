<?php

use App\Http\Controllers\DishController;
use App\Http\Controllers\HelpRequestController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableRegistrationController;
use App\Http\Controllers\TakeawayController;
use App\Models\Dish;
use App\Models\News;
use App\Models\Option;
use App\Models\Restaurant;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PointOfSaleController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Home', [
        'highlighted_offer' => Dish::where('is_discount', true)->with('options')->first(),
        'restaurant' => Restaurant::first()
    ]);
});

Route::get('/news', function () {
    return Inertia::render('News', [
        'news_articles' => News::orderBy('created_at', 'desc')->get()
    ]);
});

Route::get('/menu', function () {
    return Inertia::render('Menu');
});

Route::get('/contact', function () {
    return Inertia::render('Contact', [
        'restaurant' => Restaurant::first()
    ]);
});

// Orders
Route::controller(OrderController::class)->group(function () {
    // GET
    Route::get('/cart/is-order-placed', 'isOrderPlaced')->name('cart.is-order-placed');
    Route::get('/cart/data', 'getData')->name('cart.data');
    // POST
    Route::post('/cart/handle-dish-cookie/{dishId}/{amount}', 'handleDishCookie')->name('cart.handle-dish-cookie');
    Route::post('/cart/handle-dish-option-cookie/{dishId}/{optionId}', 'handleDishOptionCookie')->name('cart.handle-dish-option-cookie');
    Route::post('/cart/clear-order-cookie-data', 'clearOrderCookieData')->name('cart.clear-order-cookie-data');
    Route::post('/cart/place-order/{isTakeaway}', 'store')->name('cart.place-order');
    Route::post('/cart/clear-order-cookie', 'clearOrderCookie')->name('cart.clear-order-cookie');
});

// Table orders
Route::controller(TableRegistrationController::class)->group(function () {
    // GET
    Route::get('/table-registration', 'index')->name('table-registration.index');
    Route::get('/table-registration/cashier-index', 'cashierIndex')->name('table-registration.cashier-index')->middleware('role:Cashier');
    Route::get('/table-registration/get-receipt-pdf', 'getReceiptPdf')->name('table-registration.get-receipt-pdf')->middleware('role:Cashier');

    Route::middleware('table-registration-valid')->group(function () {
        Route::get('/table-registration/data', 'getData')->name('table-registration.data');
        Route::get('/table-registration/can-order', 'getCanOrder')->name('table-registration.can-order');
        Route::get('/table-registration/show', 'show')->name('table-registration.show');

        Route::post('/table-registration/add-order/{orderId}', 'addOrder')->name('table-registration.add-order');
        Route::post('/table-registration/clear-cookie', 'clearRegistrationCookie')->name('table-registration.clear-cookie');
        Route::post('/table-registration/repeat-order/{orderId}', 'setOrderCookie')->name('table-registration.repeat-order');
    });

    // POST
    Route::post('/table-registration/start-order', 'store')->name('table-registration.start-order');
});

// Menu
Route::controller(MenuController::class)->group(function () {
    Route::get('/menu/data/{sorting}', 'getData')->name('menu.data');
    Route::get('/menu/print-pdf', 'printPdf')->name('menu.print-pdf');
    Route::post('/menu/handle-dish-cookie/{dishId}', 'handleDishCookie')->name('menu.handle-dish-cookie');
});

// Takeaway
Route::get('/cart/get-order-qr-data', [TakeawayController::class, 'getOrderQRData'])->name('cart.get-order-qr-data');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if($user->hasRole('Cashier')){
        return redirect()->route('pos.index');
    }

    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Tables & Planning
Route::middleware('role:Administrator')->controller(PlanningController::class)->name('admin.')->prefix('admin')->group(function () {
    Route::get('/planning', 'index')->name('planning.index');
    Route::get('/planning/data', 'getData')->name('planning.data');
    Route::post('/planning/create-table', 'createTable')->name('planning.create-table');
    Route::post('/planning/unassign/{tableId}/{userId}/{weekday}', 'unassign')->name('planning.unassign');
    Route::post('/planning/assign/{tableId}/{userId}/{weekday}', 'assign')->name('planning.assign');
    Route::delete('/planning/destroy-table/{tableId}', 'destroyTable')->name('planning.destroy-table');
});

// Dishes
Route::get('/admin/dishes/menu', [DishController::class, 'menu'])->name('admin.dishes.menu')->middleware('role:Cashier');

Route::middleware('role:Administrator')->controller(DishController::class)->name('admin.')->prefix('admin')->group(function () {
    Route::get('/dishes', 'index')->name('dishes.index');
    Route::get('/dishes/create', 'create')->name('dishes.create');
    Route::post('/dishes/store', 'store')->name('dishes.store');
    Route::get('/dishes/edit/{dishId}', 'edit')->name('dishes.edit');
    Route::put('/dishes/update/{dishId}', 'update')->name('dishes.update');
    Route::delete('/dishes/destroy/{dish}', 'destroy')->name('dishes.destroy');
});

// Help Requests
Route::controller(HelpRequestController::class)->group(function () {
    Route::middleware('role:Cashier')->group(function () {
        Route::get('/help-requests', 'index')->name('help-requests.index');
        Route::get('/help-requests/data', 'getData')->name('help-requests.data');
        Route::delete('/help-requests/destroy/{helpRequestId}', 'destroy')->name('help-requests.destroy');
    });

    Route::get('/help-requests/get/{tableId}', 'show')->name('help-requests.get');
    Route::post('/help-requests/create/{tableId}', 'create')->name('help-requests.create');
});

Route::resource('/admin/news', NewsController::class)->middleware('role:Administrator');

Route::middleware('role:Administrator|Cashier')->name('pos.')->prefix('pos')->group(function (){
   Route::get('/', [PointOfSaleController::class, 'index'])->name('index');
});

require __DIR__.'/auth.php';
