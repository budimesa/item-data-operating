<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ColorTolController;
use App\Http\Controllers\DensityController;
use App\Http\Controllers\ItemDataController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\SeriesTypeController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SizeTolController;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rute untuk halaman "General Data"
Route::get('/master/general-data', function () {
    return Inertia::render('GeneralData');
})->name('general-data');

// Rute untuk halaman "Item Data"
Route::get('/master/item-data', function () {
    return Inertia::render('ItemData');
})->name('item-data');

Route::resource('brands', BrandController::class);
Route::resource('colors', ColorController::class);
Route::resource('color-tols', ColorTolController::class);
Route::resource('densities', DensityController::class);
Route::resource('item-types', ItemTypeController::class);
Route::resource('series-types', SeriesTypeController::class);
Route::resource('sizes', SizeController::class);
Route::resource('size-tols', SizeTolController::class);
Route::resource('item-datas', ItemDataController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
