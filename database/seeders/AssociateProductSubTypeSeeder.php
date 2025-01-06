<?php

namespace Database\Seeders;

use App\Models\Products;
use App\Models\ProductSubType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssociateProductSubTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua produk
        $products = Products::all();

        // Gunakan transaksi untuk menghindari inkonsistensi data
        DB::transaction(function () use ($products) {
            foreach ($products as $product) {
                // Cari ProductSubType berdasarkan nama di field product_type
                $productSubType = ProductSubType::where('name', $product->product_type)->first();
                echo $productSubType;

                if ($productSubType) {
                    // Update field product_type_id di produk
                    $product->update([
                        'product_type_id' => $productSubType->id,
                    ]);
                } else {
                    // Log jika tidak ditemukan (opsional)
                    Log::warning("ProductSubType not found for product_type: {$product->product_type}");
                }
            }
        });
    }

}
