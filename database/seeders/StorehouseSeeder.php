<?php

namespace Database\Seeders;

use App\Models\StoreHouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'PABRIK SRIBOGA'],
            ['name' => 'PABRIK SARIROTI'],
            ['name' => 'PABRIK SRIWIDYA'],
        ];

        foreach($data as $d) {
            StoreHouse::create($d);
        }
    }
}
