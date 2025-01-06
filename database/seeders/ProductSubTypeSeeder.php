<?php

namespace Database\Seeders;

use App\Models\ProductSubType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSubTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'BAKING POWDER'],
            ['name' => 'BREAD IMPROVER'],
            ['name' => 'BREAD PREMIX'],
            ['name' => 'BROWN SUGAR PASTE'],
            ['name' => 'BUTTER BLAND'],
            ['name' => 'BUTTER CREAM'],
            ['name' => 'BUTTER OIL SUBTITUTE'],
            ['name' => 'CAKE EMULSIFIER'],
            ['name' => 'CAKE PREMIX'],
            ['name' => 'CHEESE'],
            ['name' => 'CHEESE PASTE'],
            ['name' => 'CHOC PASTE'],
            ['name' => 'CHOCO CRUNCH PASTA'],
            ['name' => 'CHOCO STICK'],
            ['name' => 'CHOCOLATE BLOCK'],
            ['name' => 'CHOCOLATE CHIP'],
            ['name' => 'CHOCOLATE FILLING'],
            ['name' => 'CHOCOLATE MESIS'],
            ['name' => 'COATING'],
            ['name' => 'COCO POWDER'],
            ['name' => 'DRINK POWDER'],
            ['name' => 'EGG WASH'],
            ['name' => 'FILLING CUSTARD'],
            ['name' => 'FILLING JAM'],
            ['name' => 'FILLING POWDER'],
            ['name' => 'FLAVOUR POWDER'],
            ['name' => 'FRUIT JAM'],
            ['name' => 'FUTION'],
            ['name' => 'GEL'],
            ['name' => 'GULA CAIR'],
            ['name' => 'GULA DEKORASI'],
            ['name' => 'GULA HALUS'],
            ['name' => 'GULA PALM'],
            ['name' => 'GULA PASIR'],
            ['name' => 'GULA TABUR'],
            ['name' => 'MARGARINE'],
            ['name' => 'MARGARINE CREAM'],
            ['name' => 'MARINADE'],
            ['name' => 'MILK POWDER'],
            ['name' => 'MINYAK CAIR'],
            ['name' => 'MINYAK PADAT'],
            ['name' => 'PAN GREACE'],
            ['name' => 'PREMIUM'],
            ['name' => 'SPECIAL'],
            ['name' => 'SUPER PREMIUM'],
            ['name' => 'TEPUNG PREMIX'],
            ['name' => 'TOPPING'],
            ['name' => 'TOPPING CREAM'],
            ['name' => 'VANILLA PASTE'],
        ];

        foreach ($datas as $data) {
            ProductSubType::create($data);
        }
    }
}
