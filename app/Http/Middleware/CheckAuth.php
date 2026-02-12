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

        \Log::info('CheckAuth middleware', [
            'is_logged_in' => session('is_logged_in'),
            'session' => session()->all(),
            'url' => $request->url(),
        ]);

        if (!session('is_logged_in')) {
            \Log::warning('User not logged in, redirecting to login', [
                'url' => $request->url(),
                'session' => session()->all(),
            ]);
            return redirect()->route('login')->withErrors([
                'error' => 'Silakan login terlebih dahulu',
            ]);
        }

        return $next($request);
    }
}
