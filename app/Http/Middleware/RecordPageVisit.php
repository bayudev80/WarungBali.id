<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordPageVisit
{
    /**
     * Catat satu kunjungan per session per hari. Dipasang global (bukan
     * per-route) supaya menghitung kunjungan situs secara keseluruhan,
     * tapi dibungkus try-catch supaya kegagalan pencatatan statistik
     * tidak sampai mematikan request pengguna.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            PageVisit::firstOrCreate([
                'session_id'   => $request->session()->getId(),
                'visited_date' => now()->toDateString(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return $next($request);
    }
}
