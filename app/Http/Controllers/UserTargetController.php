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
        $targets = UserTarget::where('user_id', $user->id)->get();

        return Inertia::render('Marketing/SalesmanTargetCreate', compact('salesman','targets'));
    }

    public function index()
    {
        $salesman = User::whereHas('division', function($query) {
            $query->whereIn('division_name', ['SALES','MARKETING']);
        })
            ->with('division', 'userTarget')
            ->get();
        // dd($salesman->toArray());

        return Inertia::render('Marketing/SalesmanTarget', compact('salesman'));
    }

    public function update(Request $request, User $user)
    {
        // dd($request->all(), $user->id);
        $request->validate([
            'annual_target' => 'nullable|numeric',
            'monthly_targets' => 'nullable|array',
            'monthly_targets.*.month' => 'required|numeric|min:1|max:12', // Validasi bulan
            'monthly_targets.*.target' => 'nullable|numeric', // Validasi target per bulan
        ]);

        DB::transaction(function () use ($request, $user) {
            // Update annual target di tabel User
            $user->update([
                'annual_target' => $request->annual_target,
            ]);
    
            // Hapus monthly targets lama
            $user->userTarget()->delete();
    
            // Jika ada monthly_targets, simpan data baru
            if ($request->has('monthly_targets') && is_array($request->monthly_targets)) {
                foreach ($request->monthly_targets as $monthly_target) {
                    // Simpan setiap target bulanan
                    UserTarget::create([
                        'user_id' => $user->id,
                        'at_month' => $monthly_target['month'],
                        'monthly_target' => $monthly_target['target'],
                    ]);
                }
            }
        });

        return redirect()->route('marketing.index-target')->with("success", "Target salesman berhasil diperbarui!");
    }

}
