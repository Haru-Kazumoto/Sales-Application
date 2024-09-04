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

        $user = Auth::user();

        $roleRedirects = [
            'ADMIN' => 'dashboard.admin',
            'SUPERADMIN' => 'dashbaord.superadmin'
        ];

        if(isset($roleRedirects[$user->role])) {
            return redirect()->route($roleRedirects[$user->role]);
        }

        return back()->with('failed', 'Unknown role, or role is not assigned yet.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
