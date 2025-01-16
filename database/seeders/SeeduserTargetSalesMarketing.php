<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use App\Models\UserTarget;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeeduserTargetSalesMarketing extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan semua user dengan divisi 'SALES' dan 'MARKETING'
        $salesMarketingDivisions = Division::whereIn('division_name', ['SALES', 'MARKETING'])->get();

        // Tahun sekarang
        $currentYear = Carbon::now()->year;

        // Loop untuk setiap divisi SALES dan MARKETING
        foreach ($salesMarketingDivisions as $division) {
            // Dapatkan semua user yang memiliki divisi ini
            $users = User::where('division_id', $division->id)->get();

            foreach ($users as $user) {
                // Loop untuk setiap bulan 1 sampai 12
                for ($month = 1; $month <= 12; $month++) {
                    // Buat UserTarget untuk setiap bulan
                    UserTarget::create([
                        'user_id' => $user->id,
                        'monthly_target' => 0, // Nilai default
                        'period' => $currentYear, // Tahun sekarang
                        'at_month' => $month, // Bulan 1 sampai 12
                    ]);
                }
            }
        }
    }
}
