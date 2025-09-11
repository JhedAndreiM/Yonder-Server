<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user hasn't changed their password and is not already on the profile settings page
            if (!$user->password_changed && !$request->routeIs('account.page') && !$request->routeIs('profile.update-password')) {
                // Redirect to profile settings page with a flag to force modal open
                return redirect()->route('account.page')->with('force_password_change', true);
            }
        }

        return $next($request);
    }
}
