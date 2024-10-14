<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'DNP', 'value' => 'DNP'],
            ['name' => 'DKU', 'value' => 'DKU'],
            ['name' => 'DD', 'value' => 'DD'],
        ];

        foreach($data as $d) {
            Warehouse::create($d);
        }
    }
}
