<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Cek apakah user sudah login
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->withErrors([
                'error' => 'Silakan login terlebih dahulu',
            ]);
        }

        return $next($request);
    }
}
