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
        $role = $user->role->role_name; // Pastikan 'role_name' sesuai dengan field di DB

        // Define the allowed paths for each role
        $rolePaths = [
            'WAREHOUSE' => '/warehouse',
            'FINANCE' => '/finance',
            'PROCUREMENT' => '/procurement',
        ];

        // Get the current path being accessed
        $currentPath = $request->path();

        // Check if user is accessing a path outside their allowed role paths
        foreach ($rolePaths as $roleName => $rolePath) {
            // Jika role user berbeda dan path yang diakses dimulai dengan prefix role lain, redirect ke dashboard role user
            if ($role !== $roleName && str_starts_with($currentPath, trim($rolePath, '/'))) {
                return redirect($rolePaths[$role] . '/dashboard');
            }
        }

        return $next($request);
    }
}
