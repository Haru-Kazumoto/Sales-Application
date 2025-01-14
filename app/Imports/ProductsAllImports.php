<?php

namespace App\Imports;

use App\Models\Parties;
use App\Models\Products;
use App\Models\ProductSubType;
use App\Models\ProductType;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsAllImports implements ToCollection, WithStartRow
{
    /**
     * Tentukan baris awal data yang akan diproses.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 3; // Data mulai dari baris ke-3
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // dd($collection);
        DB::transaction(function() use ($collection)
            {
                foreach($collection as $row) 
                {
                    $supplier = Parties::where('name', $row[2])->first();
                    $product_type = ProductType::where('name', $row[7])->first();
                    $product_sub_type = ProductSubType::where('name', $row[6])->first();

                    if (!$supplier) {
                        throw new Exception("Supplier with name {$row[2]} not found.");
                    }

                    if (!$product_type) {
                        throw new Exception("Product type with name {$row[7]} not found.");
                    }

                    Products::create([
                        'code' => $row[3],
                        'name' => $row[4],
                        'unit' => $row[5],
                        'product_company' => $row[1],
                        'product_type' => $row[6],
                        'category' => $row[8],
                        'product_type_id' => $product_type->id,
                        'product_sub_type_id' => $product_sub_type->id,
                        'supplier_id' => $supplier->id,
                    ]);
                }

            }
        );
    }
}
