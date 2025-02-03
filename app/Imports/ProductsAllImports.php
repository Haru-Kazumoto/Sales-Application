<?php

namespace App\Imports;

use App\Models\Parties;
use App\Models\Products;
use App\Models\ProductSubType;
use App\Models\ProductType;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        DB::transaction(function() use ($collection) {
            foreach ($collection as $index => $row) {
                try {
                    $supplier = Parties::where('name', $row[2])->first();
                    $product_type = ProductType::where('name', $row[7])->first();
                    $product_sub_type = ProductSubType::where('name', $row[6])->first();
                    $existingProduct = Products::where('code', $row[3])->first(); // Cek kode produk

                    if (!$supplier) {
                        throw new Exception("Supplier with name {$row[2]} not found at row {$index}");
                    }

                    if (!$product_type) {
                        throw new Exception("Product type with name {$row[7]} not found at row {$index}");
                    }

                    if (!$product_sub_type) {
                        throw new Exception("Product sub type with name {$row[6]} not found at row {$index}");
                    }

                    if ($existingProduct) {
                        Log::info("Skipping row {$index}: Product with code {$row[3]} already exists.");
                        continue; // Skip jika kode produk sudah ada
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
                } catch (Exception $e) {
                    Log::error("Error processing row {$index}: " . json_encode($row) . " | Error: " . $e->getMessage());
                    throw $e; // Lempar error agar transaksi dibatalkan
                }
            }
        });

    }
}
