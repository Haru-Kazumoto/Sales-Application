<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductMasterImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'ALL' => new ProductsAllImports(),
        ];
    }
}
