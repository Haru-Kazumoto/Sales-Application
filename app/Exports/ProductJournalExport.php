<?php

namespace App\Exports;

use App\Exports\Products\AllProducts;
use App\Exports\Products\ProductBatches;
use App\Exports\Products\StagnationProducts;
use App\Models\ProductJournal;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductJournalExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct()
    {}

    public function sheets(): array
    {
        return [
            new AllProducts(),
            new ProductBatches(),
            new StagnationProducts(),
        ];
    }

}
