<?php

use App\Http\Controllers\ProfileController;
use App\Models\Dish;
use App\Models\News;
use App\Models\Option;
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
        'highlighted_offer' => Dish::where('is_discount', true)->with('options')->first()
    ]);
});

Route::get('/menu', function () {
    return Inertia::render('Menu', [
        'dish_data' => Dish::with('category', 'options')
            ->select('dishes.*', 'categories.special_description')
            ->join('categories', 'dishes.category_id', '=', 'categories.id')
            ->get()
            ->groupBy('category.name')
            ->map(function ($dishes, $categoryName) {
                $category = $dishes->first()->category;
                return [
                    'special_description' => $category->special_description,
                    'dishes' => $dishes,
                ];
            }),
        'option_data' => Option::whereNotNull('price')->get()
    ]);
});

Route::get('/news', function () {
    return Inertia::render('News', [
        'news_articles' => News::all()
    ]);
});

Route::get('/contact', function () {
    return Inertia::render('Contact');
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
