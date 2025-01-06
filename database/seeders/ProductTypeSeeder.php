<?php

namespace Database\Seeders;

use App\Models\Products;
use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'TEPUNG'],
            ['name' => 'GULA'],
            ['name' => 'FAT'],
            ['name' => 'IMPROVER & STABILIZER'],
            ['name' => 'TEPUNG PREMIX'],
            ['name' => 'DAIRY & NON DAIRY'],
            ['name' => 'FILLING & TOPPING'],
            ['name' => 'COTTING & GLAZE'],
            ['name' => 'FILLING POWDER'],
            ['name' => 'FLAVOUR'],
            ['name' => 'PENGEMBANG'],
            ['name' => 'CHOCOLATE'],
            ['name' => 'DRINK POWDER'],
            ['name' => 'PASTA FILLING'],
        ];

        DB::transaction(function () use ($data) {
            foreach ($data as $item) {
                ProductType::create($item);
            }
        });
    }
}
