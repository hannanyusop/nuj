<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->isCustomer()) {
            // Redirect based on user type
            return match($user->type) {
                'admin' => redirect()->route('admin.dashboard'),
                'manager', 'staff' => redirect()->route('staff.dashboard'),
                'runner' => redirect()->route('runner.dashboard'),
                default => redirect()->route('login'),
            };
        }

        return $next($request);
    }
} 