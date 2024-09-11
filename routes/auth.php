<?php

use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function() {
    Route::get('login', [AuthenticatedController::class, 'showLoginView'])->name('login');
    Route::post('login', [AuthenticatedController::class, 'login']);

    Route::get('register', [RegisteredUserController::class, 'showRegisterView'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'register'])->name('register.post');
});

Route::post('logout', [AuthenticatedController::class, 'logout'])->name('logout')->middleware('auth');