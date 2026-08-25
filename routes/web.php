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
use App\Http\Controllers\Admin\PemilikAkunController;
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboardController;
use App\Http\Controllers\Pemilik\WarungController as PemilikWarungController;
use App\Http\Controllers\Pemilik\MenuController as PemilikMenuController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;

// =========================
// HOME
// =========================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search-ajax', [HomeController::class, 'searchAjax'])->name('search.ajax');
Route::get('/kategori/{slug}', [HomeController::class, 'kategori'])->name('kategori.show');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/warung/random', [HomeController::class, 'randomWarung'])->name('warung.random');

// Dynamic Dashboard: Arahkan sesuai peran pengguna
Route::middleware('auth')->get('/dashboard', function () {
    $role = auth()->user()->role ?? 'user';
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($role === 'pemilik') {
        return redirect()->route('pemilik.dashboard');
    }
    return redirect()->route('user.dashboard');
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

        // Cabang: admin bisa menambahkan cabang baru untuk warung yang
        // sudah ada. Warung induknya otomatis warung yang dipilih di
        // daftar (tidak dipilih manual dari dropdown).
        Route::get('warung/{warung}/cabang/create', [WarungController::class, 'createCabang'])
            ->name('warung.cabang.create');
        Route::post('warung/{warung}/cabang', [WarungController::class, 'storeCabang'])
            ->name('warung.cabang.store');

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
        Route::post('user/{id}/send-password', [UserController::class, 'sendPassword'])
            ->name('user.send-password');

        // Verifikasi akun pemilik warung yang daftar lewat "Daftar sebagai
        // Pemilik Warung". Sengaja terpisah dari approve/reject warung di
        // atas -- lihat catatan di PemilikAkunController.
        Route::get('pemilik-akun', [PemilikAkunController::class, 'index'])
            ->name('pemilik-akun.index');
        Route::patch('pemilik-akun/{id}/verifikasi', [PemilikAkunController::class, 'verifikasi'])
            ->name('pemilik-akun.verifikasi');

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

        // Cabang: pemilik yang sudah punya warung utama bisa menambahkan
        // cabang baru langsung dari sini. Warung induk-nya otomatis warung
        // milik pemilik yang sedang login (tidak dipilih manual dari daftar).
        Route::get('warung/cabang/create', [PemilikWarungController::class, 'createCabang'])
            ->name('warung.cabang.create');
        Route::post('warung/cabang', [PemilikWarungController::class, 'storeCabang'])
            ->name('warung.cabang.store');
        Route::get('warung/cabang/{cabang}/edit', [PemilikWarungController::class, 'editCabang'])
            ->name('warung.cabang.edit');
        Route::put('warung/cabang/{cabang}', [PemilikWarungController::class, 'updateCabang'])
            ->name('warung.cabang.update');
        Route::delete('warung/cabang/{cabang}', [PemilikWarungController::class, 'destroyCabang'])
            ->name('warung.cabang.destroy');

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
// DASHBOARD PENGGUNA & AKUN
// =========================
Route::middleware('auth')
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');

        Route::post('/profile', [UserDashboardController::class, 'updateProfile'])
            ->name('profile.update');

        Route::delete('/profile/foto', [UserDashboardController::class, 'removeFoto'])
            ->name('profile.remove-foto');

        Route::post('/password', [UserDashboardController::class, 'updatePassword'])
            ->name('password.update');

        Route::post('/password/request-email', [UserDashboardController::class, 'requestPasswordEmail'])
            ->name('password.request-email');

        Route::delete('/review/{id}', [UserDashboardController::class, 'deleteReview'])
            ->name('review.delete');
    });

// =========================
// PROFILE & PUBLIC ACTIONS
// =========================
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/favorit', [FavoritController::class, 'index'])
        ->name('favorit.index');

    Route::post('/favorit/{id}', [FavoritController::class, 'toggle'])
        ->name('favorit.toggle');

    Route::post('/warung/{id}/review', [PublicReviewController::class, 'store'])
        ->name('review.store');

    Route::get('/kategori-random', [HomeController::class, 'kategoriRandom']);
});

require __DIR__.'/auth.php';