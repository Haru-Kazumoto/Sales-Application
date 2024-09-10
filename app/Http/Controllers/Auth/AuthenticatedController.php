<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedController extends Controller
{
    public function showLoginView(): Response
    {
        return Inertia::render('Auth/Login', [
            'status' => session('status'),
        ]);
    }

    public function login(LoginRequest $loginRequest): RedirectResponse
    {
        $loginRequest->authenticate();

        $loginRequest->session()->regenerate();

        if (Auth::check()) {
            $roleName = Auth::user()->role->role_name;
    
            return match ($roleName) {
                'FINANCE' => redirect()->intended(route('dashboard.finance')),
                'WAREHOUSE' => redirect()->intended(route('dashboard.warehouse')),
                'ADMIN' => redirect()->intended(route('dashboard.admin')),
                'SUPERADMIN' => redirect()->intended(route('dashboard.superadmin')),
                'PROCUREMENT' => redirect()->intended(route('dashboard.procurement')),
                default => back()->with('failed', 'Unknown Dashboard'), // Halaman default jika role tidak dikenali
            };
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
