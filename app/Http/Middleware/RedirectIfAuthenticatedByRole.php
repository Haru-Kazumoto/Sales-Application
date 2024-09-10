<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
    
            switch ($user->role) {
                case 'FINANCE':
                    return redirect()->route('dashboard.finance');
                case 'WAREHOUSE':
                    return redirect()->route('dashboard.warehouse');
                case 'ADMIN':
                    return redirect()->route('dashboard.admin');
                case 'SUPERADMIN':
                    return redirect()->route('dashboard.superadmin');
                default:
                    return back()->with('failed', 'User is not assigned!');
            }
        }
    
        return $next($request); 
    }
}
