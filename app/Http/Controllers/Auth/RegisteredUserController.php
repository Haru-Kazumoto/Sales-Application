<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function showRegisterView(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * BUATKAN 1 PAGE ATAU ROLE UNTUK MEMBUAT USER USER LAYAKNYA SUPERADMIN MEMBUAT SEBUAH ADMIN ADMIN NYA.
     * BUATKAN 1 MODULE DAN IMPLEMENTASIKAN LOGIC REGISTERING NYA DISANA JANGAN DISINI
     * ALSO, EVERY EACH FUNCTION GIVE THE DOCUMENTATION ABOVE IT
     */

    /**
     * Creating a new user from superadmin
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'fullname' => 'required|string',
            'username' => 'required|string|max:255|unique'.User::class,
            'email' => 'required|string|email|unique',
            'password' => ['required', 'confirmed'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));
        });

        return redirect(route('login', absolute: false))->with('success', 'New user has registered');
    }
}
