<?php

namespace Database\Seeders;

use App\Models\PartiesGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartiesGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PartiesGroup::create(['name' => 'ANGKUTAN', 'description' => 'Tipe untuk data angkutan/transportasi pengiriman']);
    }
}
