<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only redirect authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            $currentRoute = $request->route()->getName();
            
            Log::info('Role-based redirect middleware', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'current_route' => $currentRoute,
                'current_url' => $request->url()
            ]);
            
            // If employee is trying to access main dashboard, redirect to ESS
            if ($user->role === 'employee' && $currentRoute === 'dashboard') {
                Log::info('Redirecting employee from dashboard to ESS');
                return redirect()->route('ess.dashboard');
            }
            
            // If admin/hr is trying to access ESS, redirect to main dashboard
            if (in_array($user->role, ['admin', 'hr']) && $currentRoute === 'ess.dashboard') {
                Log::info('Redirecting admin/hr from ESS to main dashboard');
                return redirect()->route('dashboard');
            }
        }
        
        return $next($request);
    }
}
