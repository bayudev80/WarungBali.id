<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLocalhostDomain
{
    /**
     * Pastikan semua akses web lokal dialihkan dari 127.0.0.1 ke 'localhost'
     * agar alamat web selalu seragam menggunakan localhost.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        if ($host === '127.0.0.1') {
            $scheme = $request->getScheme();
            $port = $request->getPort();
            $portSuffix = ($port && !in_array($port, [80, 443])) ? ':' . $port : '';
            $newUrl = $scheme . '://localhost' . $portSuffix . $request->getRequestUri();

            return redirect()->to($newUrl, 301);
        }

        return $next($request);
    }
}
