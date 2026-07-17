<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoritController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/warung/random', [HomeController::class, 'randomWarung'])->name('warung.random');

// Dashboard
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/favorit/{id}', [FavoritController::class, 'toggle'])->name('favorit.toggle');
    Route::get('/kategori-random', [HomeController::class, 'kategoriRandom']);
    });

require __DIR__.'/auth.php';