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
            ['division_name' => 'SALES', 'division_uid' => strval(rand(10000,66666))],
            ['division_name' => 'PROCUREMENT', 'division_uid' => strval(rand(10000,66666))],
            ['division_name' => 'WAREHOUSE', 'division_uid' => strval(rand(10000,66666))],
            ['division_name' => 'FINANCE', 'division_uid' => strval(rand(10000,66666))],
            ['division_name' => 'ADMIN', 'division_uid' => strval(rand(10000,66666))],
            ['division_name' => 'AGING_FINANCE', 'division_uid' => strval(rand(10000,66666))],
        ];

        foreach($dataToInsert as $data) {
            Division::create($data);
        }
    }
}
