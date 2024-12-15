<?php

namespace App\Exports\Products;

use App\Models\ProductJournal;
use App\Models\Products;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductBatches implements FromCollection, WithHeadings, WithMapping, WithTitle, WithColumnWidths
{

    protected $index = 1;

    public function title(): string
    {
        return "Produk Batch";
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Products::query()
            ->select(
                'products.id',
                'products.code',
                'product_journal.batch_code',
                'products.name',
                'products.unit',
                'products.retail_price',
                'products.restaurant_price',
                'products.redemp_price',
                'warehouse.name as warehouse',
                'promo_products.description',
                'promo_products.min',
                'promo_products.max',
                'promo_products.promo_value_1',
                'promo_products.promo_value_2',
                'promo_products.promo_value_3',
                'promo_products.start_date',
                'promo_products.end_date',
                'promo_products.name as promo_name',
                DB::raw('SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END) AS last_stock'),
                DB::raw('
                    CASE 
                        WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                              SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) > 20 
                        THEN "TERSEDIA"
                        WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                              SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) >= 10 
                        THEN "PERLU TAMBAH"
                        WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                              SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) <= 0 
                        THEN "HABIS"
                    END AS status
                ')
            )
            ->join('product_journal', 'products.id', '=', 'product_journal.product_id')
            ->join('warehouse', 'product_journal.warehouse_id', '=', 'warehouse.id')
            ->leftJoin('promo_products', 'products.promo_product_id', '=', 'promo_products.id')
            ->whereIn('product_journal.action', ['IN', 'OUT']) //only get the IN and OUT data action
            ->groupBy(
                'products.id',
                'products.code',
                'products.name',
                'products.unit',
                'products.retail_price',
                'warehouse.name',
                'product_journal.batch_code',
                'promo_products.description',
                'promo_products.min',
                'promo_products.max', 
                'promo_products.promo_value_1',
                'promo_products.promo_value_2',
                'promo_products.promo_value_3',
                'promo_products.start_date',
                'promo_products.end_date',
                'promo_products.name',
                'products.restaurant_price',
                'products.redemp_price',
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
            ->orderBy('last_stock', 'asc')
            ->orderByDesc('products.created_at');
            // dd($query->get()->toArray());
        return $query->get();
    }

    public function headings(): array
    {
        return [
            "#",
            "Nama Barang",
            "Kode Barang",
            "Kode Produksi Barang",
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
            $row->batch_code,
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
            'D' => 25, // Kode Batch
            'E' => 15, // Kemasan
            'F' => 15, // StoK barang
            'G' => 15, // PT
            'H' => 20, // Status barang
        ];
    }
}
