<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix path cacert.pem pada Laragon jika mengarah ke drive D: yang tidak ada
        $cacertLaragon = 'C:/laragon/etc/ssl/cacert.pem';
        if (file_exists($cacertLaragon)) {
            $curCainfo = ini_get('curl.cainfo');
            if (empty($curCainfo) || !file_exists($curCainfo)) {
                ini_set('curl.cainfo', $cacertLaragon);
            }
            $curOpenssl = ini_get('openssl.cafile');
            if (empty($curOpenssl) || !file_exists($curOpenssl)) {
                ini_set('openssl.cafile', $cacertLaragon);
            }
        }

        Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\View::composer('partials.navbar', function ($view) {
            $view->with('navbarKategori', \App\Models\Kategori::orderBy('nama_kategori', 'asc')->get());
        });

        \Illuminate\Support\Facades\View::composer(['admin.layouts.app', 'admin.pemilik-akun.index', 'admin.warung.verifikasi'], function ($view) {
            $pendingAkunCount = \App\Models\User::where('role', 'pemilik')->where('status_akun', 'pending')->count();
            $pendingWarungCount = \App\Models\Warung::where('status', 'pending')->count();
            $view->with('pendingAkunCount', $pendingAkunCount)
                 ->with('pendingWarungCount', $pendingWarungCount)
                 ->with('totalPendingVerifikasi', $pendingAkunCount + $pendingWarungCount);
        });
    }
}
