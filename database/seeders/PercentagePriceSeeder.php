<?php

namespace Database\Seeders;

use App\Models\Lookup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PercentagePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $percentage_1 = 7.5 / 100;
        $percentage_2 = 7 / 100;
        $percentage_3 = 5 / 100;
        $percentage_4 = 6 / 100;
        $percentage_5 = 14 / 100;
        $percentage_6 = 11 / 100;

        $data = [
            ['label' => '7.5%','value' => (string)$percentage_1,'category' => 'PERCENTAGE'],
            ['label' => '7%', 'value' => (string)$percentage_2, 'category' => 'PERCENTAGE'],
            ['label' => '5%', 'value' => (string)$percentage_3, 'category' => 'PERCENTAGE'],
            ['label' => '6%', 'value' => (string)$percentage_4, 'category' => 'PERCENTAGE'],
            ['label' => '14%', 'value' => (string)$percentage_5, 'category' => 'PERCENTAGE'],
            ['label' => '11%', 'value' => (string)$percentage_6, 'category' => 'PERCENTAGE'],
            ['label' => 'OTHER', 'value' => '0', 'category' => 'PERCENTAGE'],
        ];

        foreach($data as $item) {
            Lookup::create($item);
        }
    }
}
