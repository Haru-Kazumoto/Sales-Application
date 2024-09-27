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

        // Define the allowed dashboard paths for each division
        $divisionDashboardPaths = [
            'WAREHOUSE' => '/dashboard-warehouse',
            'FINANCE' => '/dashboard-finance',
            'PROCUREMENT' => '/dashboard-procurement',
            'SALES' => '/dashboard-sales',
            'ADMIN' => '/dashboard-admin',
            'AGING_FINANCE' => '/dashboard-aging-finance',
        ];

        // Get the current path being accessed
        $currentPath = $request->path();

        // Redirect to the correct dashboard if accessing a different division's dashboard
        foreach ($divisionDashboardPaths as $divisionName => $dashboardPath) {
            // Jika division user berbeda dan path yang diakses adalah dashboard division lain, redirect ke dashboard division user
            if ($division !== $divisionName && str_starts_with($currentPath, trim($dashboardPath, '/'))) {
                return redirect($divisionDashboardPaths[$division]);
            }
        }

        return $next($request);
    }
}

