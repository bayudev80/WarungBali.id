<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoritController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\WarungController;

// =========================
// HOME
// =========================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/warung/random', [HomeController::class, 'randomWarung'])->name('warung.random');

// Dashboard (redirect ke home)
Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

// =========================
// ADMIN
// =========================
Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('kategori', KategoriController::class);

        Route::resource('warung', WarungController::class);
    });

// =========================
// PROFILE
// =========================
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::post('/favorit/{id}', [FavoritController::class, 'toggle'])
        ->name('favorit.toggle');

    Route::get('/kategori-random', [HomeController::class, 'kategoriRandom']);
});

require __DIR__.'/auth.php';