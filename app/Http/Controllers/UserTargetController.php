<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function update(Request $request, User $user)
    {
        $request->validate([
            'annual_target' => 'required|numeric',
            'monthly_target' => 'required|numeric',
            'period' => 'required',
        ]);

        DB::transaction(function () use ($request, $user) {
            // Cari apakah user sudah memiliki UserTarget
            $sales_target = $user->userTarget; // Pastikan ada relasi 'userTarget' di model User

            if ($sales_target) {
                // Jika ada, lakukan update
                $sales_target->update([
                    'annual_target' => $request->annual_target,
                    'monthly_target' => $request->monthly_target,
                    'period' => $request->period,
                ]);
            } else {
                // Jika tidak ada, buat entri baru
                $sales_target = new UserTarget([
                    'annual_target' => $request->annual_target,
                    'monthly_target' => $request->monthly_target,
                    'period' => $request->period,
                ]);

                // Associate dengan user dan simpan
                $sales_target->user()->associate($user);
                $sales_target->save();
            }
        });

        return redirect()->route('marketing.index-target')->with("success", "Target salesman berhasil diperbarui!");
    }

}
