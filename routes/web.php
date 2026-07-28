<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoritController;
use App\Http\Controllers\ReviewController as PublicReviewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\WarungController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\FavoritController as AdminFavoritController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboardController;
use App\Http\Controllers\Pemilik\WarungController as PemilikWarungController;
use App\Http\Controllers\Pemilik\MenuController as PemilikMenuController;

// =========================
// HOME
// =========================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/warung/random', [HomeController::class, 'randomWarung'])->name('warung.random');

// Dashboard (redirect ke home)
Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

// =========================
// ADMIN
// =========================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('kategori', KategoriController::class);

        Route::resource('warung', WarungController::class);

        Route::get('warung-verifikasi', [WarungController::class, 'verifikasi'])
            ->name('warung.verifikasi');

        Route::patch('warung/{id}/approve', [WarungController::class, 'approve'])
            ->name('warung.approve');
        Route::patch('warung/{id}/reject', [WarungController::class, 'reject'])
            ->name('warung.reject');

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
// PEMILIK WARUNG
// =========================

// Semua route pemilik cukup butuh login (auth). Tidak lagi digerbang oleh
// middleware role:pemilik, karena setiap controller di bawah ini SUDAH
// menangani sendiri kondisi "belum punya warung" / "role belum pemilik"
// dengan redirect yang ramah -- bukan error 403 mentah. Ini supaya akun
// yang baru login sebagai "user" (belum resmi jadi pemilik) tetap bisa
// diarahkan dengan mulus ke form pendaftaran warung, tanpa terjebak 403.
Route::middleware(['auth'])
    ->prefix('pemilik')
    ->name('pemilik.')
    ->group(function () {

        Route::get('/', [PemilikDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('warung/create', [PemilikWarungController::class, 'create'])
            ->name('warung.create');
        Route::post('warung', [PemilikWarungController::class, 'store'])
            ->name('warung.store');
        Route::get('warung/edit', [PemilikWarungController::class, 'edit'])
            ->name('warung.edit');
        Route::put('warung', [PemilikWarungController::class, 'update'])
            ->name('warung.update');

        Route::get('menu', [PemilikMenuController::class, 'index'])
            ->name('menu.index');
        Route::get('menu/create', [PemilikMenuController::class, 'create'])
            ->name('menu.create');
        Route::post('menu', [PemilikMenuController::class, 'store'])
            ->name('menu.store');
        Route::get('menu/{id}/edit', [PemilikMenuController::class, 'edit'])
            ->name('menu.edit');
        Route::put('menu/{id}', [PemilikMenuController::class, 'update'])
            ->name('menu.update');
        Route::delete('menu/{id}', [PemilikMenuController::class, 'destroy'])
            ->name('menu.destroy');
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

    Route::post('/warung/{id}/review', [PublicReviewController::class, 'store'])
        ->name('review.store');

    Route::get('/kategori-random', [HomeController::class, 'kategoriRandom']);
});

require __DIR__.'/auth.php';