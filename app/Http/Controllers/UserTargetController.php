<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserTargetController extends Controller
{
    public function create(User $user)
    {
        $salesman = $user->load('division', 'userTarget');

        return Inertia::render('Marketing/SalesmanTargetCreate', compact('salesman'));
    }

    public function index()
    {
        $salesman = User::whereHas('division', function($query) {
            $query->where('division_name', 'SALES');
        })
            ->with('division', 'userTarget')
            ->get();

        return Inertia::render('Marketing/SalesmanTarget', compact('salesman'));
    }
}
