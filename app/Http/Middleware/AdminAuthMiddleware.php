<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated as admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access admin panel.');
        }

        // Optional: Check if admin account is active
        $admin = Auth::guard('admin')->user();
        if ($admin && $admin->status != 'active') {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Your account is inactive. Please contact super admin.');
        }

        return $next($request);
    }
}
