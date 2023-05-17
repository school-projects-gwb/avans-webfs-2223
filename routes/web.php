<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TakeawayController;
use App\Models\Dish;
use App\Models\News;
use App\Models\Option;
use App\Models\Restaurant;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


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
        'news_articles' => News::all()
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

// Order
Route::controller(OrderController::class)->group(function () {
    // GET
    Route::get('/cart/is-order-placed', 'isOrderPlaced')->name('cart.is-order-placed');
    Route::get('/cart/data', 'getData')->name('cart.data');
    // POST
    Route::post('/cart/handle-dish-cookie/{dishId}/{amount}', 'handleDishCookie')->name('cart.handle-dish-cookie');
    Route::post('/cart/handle-dish-option-cookie/{dishId}/{optionId}', 'handleDishOptionCookie')->name('cart.handle-dish-option-cookie');
    Route::post('/cart/clear-order-cookie-data', 'clearOrderCookieData')->name('cart.clear-order-cookie-data');
    Route::post('/cart/place-order', 'store')->name('cart.place-order');
    Route::post('/cart/clear-order-cookie', 'clearOrderCookie')->name('cart.clear-order-cookie');
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

Route::middleware('role:Administrator|Cashier')->name('pos.')->prefix('pos')->group(function (){
   Route::get('/', function (){
        dd("hoi");
   })->name('index');
});

require __DIR__.'/auth.php';
