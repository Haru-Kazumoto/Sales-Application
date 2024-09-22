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
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $loginRequest)
    {
        $loginRequest->authenticate();

        $loginRequest->session()->regenerate();

        if (Auth::check()) {
            $divisionName = Auth::user()->division->division_name;
    
            return match ($divisionName) {
                'FINANCE' => redirect()->intended(route('dashboard.finance')),
                'WAREHOUSE' => redirect()->intended(route('dashboard.warehouse')),
                'ADMIN' => redirect()->intended(route('dashboard.admin')),
                'SUPERADMIN' => redirect()->intended(route('dashboard.superadmin')),
                'PROCUREMENT' => redirect()->intended(route('dashboard.procurement')),
                'AGING_FINANCE' => redirect()->intended(route('dashboard.aging-finance')),
                'SALES' => redirect()->intended(route('dashboard.sales')),
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
