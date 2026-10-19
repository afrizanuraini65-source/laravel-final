<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            if (in_array($user->status_verifikasi, ['PENDING', 'REJECTED'])) {
                if (!$request->routeIs('verification.pending') && !$request->routeIs('logout')) {
                    return redirect()->route('verification.pending');
                }
            } elseif ($request->routeIs('verification.pending')) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
