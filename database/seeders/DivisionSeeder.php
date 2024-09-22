<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataToInsert = [
            ['division_name' => 'SALES', 'division_uid' => '001'],
            ['division_name' => 'PROCUREMENT', 'division_uid' => '002'],
            ['division_name' => 'WAREHOUSE', 'division_uid' => '003'],
            ['division_name' => 'FINANCE', 'division_uid' => '004'],
            ['division_name' => 'ADMIN', 'division_uid' => '005'],
            ['division_name' => 'AGING_FINANCE', 'division_uid' => '006'],
        ];

        foreach($dataToInsert as $data) {
            Division::create($data);
        }
    }
}
