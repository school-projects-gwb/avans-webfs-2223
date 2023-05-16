<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
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

Route::get('/menu/data/{sorting}', [MenuController::class, 'getData'])->name('menu.data');
Route::get('/cart/data', [OrderController::class, 'getData'])->name('cart.data');

Route::post('/cart/handle-dish-cookie/{dishId}/{amount}', [OrderController::class, 'handleDishCookie'])->name('cart.handle-dish-cookie');
Route::post('/cart/handle-dish-option-cookie/{dishId}/{optionId}', [OrderController::class, 'handleDishOptionCookie'])->name('cart.handle-dish-option-cookie');
Route::post('/cart/place-order', [OrderController::class, 'store'])->name('cart.place-order');

Route::get('/menu/print-pdf', [MenuController::class, 'printPdf'])->name('menu.print-pdf');

Route::get('/contact', function () {
    return Inertia::render('Contact', [
        'restaurant' => Restaurant::first()
    ]);
});

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
