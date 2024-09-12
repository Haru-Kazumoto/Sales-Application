<?php

use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    if(Auth::check()){
        $user_role = Auth::user()->role->role_name;

        return match ($user_role) {
            'FINANCE' => redirect()->intended(route('dashboard.finance')),
            'WAREHOUSE' => redirect()->intended(route('dashboard.warehouse')),
            'ADMIN' => redirect()->intended(route('dashboard.admin')),
            'SUPERADMIN' => redirect()->intended(route('dashboard.superadmin')),
            'PROCUREMENT' => redirect()->intended(route('dashboard.procurement')),
            default => back()->with('failed', 'Unknown Dashboard'), // Halaman default jika role tidak dikenali
        };
    }

    return redirect()->route('login');
});

Route::middleware(['guest'])->group(function() {
    Route::get('login', [AuthenticatedController::class, 'showLoginView'])->name('login');
    Route::post('login', [AuthenticatedController::class, 'login']);

    Route::get('register', [RegisteredUserController::class, 'showRegisterView'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'register'])->name('register.post');
});

Route::post('logout', [AuthenticatedController::class, 'logout'])->name('logout')->middleware('auth');