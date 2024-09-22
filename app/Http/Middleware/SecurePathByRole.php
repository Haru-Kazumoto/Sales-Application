<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SecurePathByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the authenticated user
        $user = Auth::user();
        $division = $user->division->division_name; // Pastikan 'division_name' sesuai dengan field di DB

        // Define the allowed paths for each division
        $divisionPaths = [
            'WAREHOUSE' => '/warehouse',
            'FINANCE' => '/finance',
            'PROCUREMENT' => '/procurement',
            'SALES' => '/sales',
            'ADMIN' => '/admin',
            'AGING_FINANCE' => '/aging-finance',
        ];

        // Get the current path being accessed
        $currentPath = $request->path();

        // Check if user is accessing a path outside their allowed division paths
        foreach ($divisionPaths as $divisionName => $divisionPath) {
            // Jika division user berbeda dan path yang diakses dimulai dengan prefix division lain, redirect ke dashboard division user
            if ($division !== $divisionName && str_starts_with($currentPath, trim($divisionPath, '/'))) {
                return redirect($divisionPaths[$division] . '/dashboard');
            }
        }

        return $next($request);
    }
}
