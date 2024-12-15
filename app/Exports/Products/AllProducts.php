<?php

namespace App\Exports\Products;

use App\Models\ProductJournal;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class AllProducts implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithTitle
{

    protected $index = 1;

    public function title(): string
    {
        return "Semua Produk";
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = DB::table('products')
            ->select(
                'products.id',
                'products.code',
                'products.name',
                'products.unit',
                'products.retail_price',
                'warehouse.name AS warehouse',
                'promo_products.description',
                'promo_products.min',
                'promo_products.max',
                'promo_products.promo_value_1',
                'promo_products.promo_value_2',
                'promo_products.promo_value_3',
                'promo_products.start_date',
                'promo_products.end_date',
                DB::raw('SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                        SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END) AS last_stock'),
                        DB::raw('CASE 
                        WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                              SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) > 20 
                        THEN "TERSEDIA"
                        WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                              SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) >= 9 
                        THEN "PERLU TAMBAH" -- Sekarang mencakup 9 hingga 20
                        WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                              SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) > 0 
                        THEN "SEDIKIT" -- Mencakup 1 hingga 8
                        ELSE "HABIS" -- Menangani nilai 0 atau negatif
                    END AS status')            
            )
            ->join('product_journal', 'products.id', '=', 'product_journal.product_id')
            ->join('warehouse', 'product_journal.warehouse_id', '=', 'warehouse.id')
            ->leftJoin('promo_products', 'products.promo_product_id', '=', 'promo_products.id')
            ->groupBy(
                'products.id',
                'products.code',
                'products.name',
                'products.unit',
                'products.retail_price',
                'warehouse.name',
                'promo_products.description',
                'promo_products.min',
                'promo_products.max', 
                'promo_products.promo_value_1',
                'promo_products.promo_value_2',
                'promo_products.promo_value_3',
                'promo_products.start_date',
                'promo_products.end_date',
            )
            ->orderByRaw("CASE 
                    WHEN (SUM(CASE WHEN product_journal.action = 'IN' THEN product_journal.quantity ELSE 0 END) - 
                        SUM(CASE WHEN product_journal.action = 'OUT' THEN product_journal.quantity ELSE 0 END)) <= 0 
                    THEN 1
                    WHEN (SUM(CASE WHEN product_journal.action = 'IN' THEN product_journal.quantity ELSE 0 END) - 
                        SUM(CASE WHEN product_journal.action = 'OUT' THEN product_journal.quantity ELSE 0 END)) >= 10 
                    THEN 2
                    ELSE 3
                END")
            ->orderBy('last_stock', 'asc');
        
        // dd($query->get()->toArray());
        return $query->get();
    }
    
    public function headings(): array
    {
        return [
            "#",
            "Nama Barang",
            "Kode Barang",
            "Kemasan",
            "Stok Barang",
            "PT",
            "Status Barang",
        ];
    }

    public function map($row): array
    {
        return [
            $this->index++,
            $row->name,
            $row->code,
            $row->unit,
            $row->last_stock,
            $row->warehouse,
            $row->status,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // Index
            'B' => 40, // Nama Barang
            'C' => 20, // Kode Barang
            'D' => 15, // Kemasan
            'E' => 15, // StoK barang
            'F' => 15, // PT
            'G' => 20, // Status barang
        ];
    }
}
