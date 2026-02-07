<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGuest
{
    /**
     * Cek apakah user belum login (guest)
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('is_logged_in')) {
            return redirect('/');
        }

        return $next($request);
    }
}
