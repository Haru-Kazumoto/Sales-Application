<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserTarget;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang memiliki divisi "SALES"
        $sales_users = User::whereHas('division', function ($query) {
            $query->where('division_name', 'SALES');
        })->get();

        // Iterasi setiap user dengan divisi SALES
        foreach ($sales_users as $user) {
            // Buat instance UserTarget tanpa langsung disimpan ke database
            $userTarget = new UserTarget([
                'annual_target' => 0,
                'monthly_target' => 0,
                'period' => Carbon::now()->year, // Tahun ini
            ]);

            // Hubungkan UserTarget dengan User menggunakan associate
            $userTarget->user()->associate($user);

            // Simpan UserTarget ke database setelah relasi di-set
            $userTarget->save();
        }
    }
}
