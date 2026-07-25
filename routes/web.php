<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoritController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\WarungController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\FavoritController as AdminFavoritController;
use App\Http\Controllers\Admin\MenuController;

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

        Route::prefix('warung/{warung}/menu')->name('warung.menu.')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('index');
            Route::get('/create', [MenuController::class, 'create'])->name('create');
            Route::post('/', [MenuController::class, 'store'])->name('store');
            Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('edit');
            Route::put('/{menu}', [MenuController::class, 'update'])->name('update');
            Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('destroy');
        });

        Route::resource('user', UserController::class);

        Route::get('review', [ReviewController::class, 'index'])
            ->name('review.index');
        Route::delete('review/{id}', [ReviewController::class, 'destroy'])
            ->name('review.destroy');

        Route::get('favorit', [AdminFavoritController::class, 'index'])
            ->name('favorit.index');
        Route::delete('favorit/{id}', [AdminFavoritController::class, 'destroy'])
            ->name('favorit.destroy');
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