<?php

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
    Route::middleware('table-registration-valid')->group(function () {
        Route::get('/table-registration/data', 'getData')->name('table-registration.data');
        Route::get('/table-registration/can-order', 'getCanOrder')->name('table-registration.can-order');
        Route::get('/table-registration/show', 'show')->name('table-registration.show');
    });

    // POST
    Route::post('/table-registration/start-order', 'store')->name('table-registration.start-order');
    Route::middleware('table-registration-valid')->group(function () {
        Route::post('/table-registration/add-order/{orderId}', 'addOrder')->name('table-registration.add-order');
        Route::post('/table-registration/clear-cookie', 'clearRegistrationCookie')->name('table-registration.clear-cookie');
    });
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

// Help Requests
Route::controller(HelpRequestController::class)->group(function () {

});

Route::resource('/admin/news', NewsController::class)->middleware('role:Administrator');

Route::middleware('role:Administrator|Cashier')->name('pos.')->prefix('pos')->group(function (){
   Route::get('/', [PointOfSaleController::class, 'index'])->name('index');
});

require __DIR__.'/auth.php';
