<?php

namespace App\Http\Services;

use App\Models\ProductJournal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductServices {

//    /**
//      * Get all data stock products from warehouse
//      * 
//      * @param string|null $warehouse Optional warehouse name for filtering, null means no filter.
//      * @param bool $paginate Whether to paginate the results or not.
//      * @param int $perPage Number of results per page when using pagination.
//      */
//     public function getStockProducts(?string $warehouse = null,?string $action = null, ?int $paginate = null)
//     {
//         $query = DB::table('products')
//             ->select(
//                 'products.id',
//                 'products.code',
//                 'products.name',
//                 'products.unit',
//                 'products.retail_price',
//                 'warehouse.name AS warehouse',
//                 // 'promo_products.id',
//                 'promo_products.description',
//                 'promo_products.min',
//                 'promo_products.max', // contoh kolom dari tabel promo_products
//                 'promo_products.promo_value',
//                 'promo_products.start_date',
//                 'promo_products.end_date',
//                 DB::raw('SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
//                         SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END) AS last_stock'),
//                 DB::raw('CASE 
//                             WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
//                                 SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) > 20 
//                             THEN "TERSEDIA"
//                             WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
//                                 SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) >= 10 
//                             THEN "PERLU TAMBAH"
//                             WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
//                                 SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) = 0 
//                             THEN "STOK HABIS"
//                         END AS status')
//             )
//             ->join('product_journal', 'products.id', '=', 'product_journal.product_id')
//             ->join('warehouse', 'product_journal.warehouse_id', '=', 'warehouse.id')
//             ->join('promo_products', 'products.promo_product_id', '=', 'promo_products.id') // Join ke tabel promo_products
//             ->groupBy(
//                 'products.id', 
//                 'products.code', 
//                 'products.name', 
//                 'products.unit', 
//                 'warehouse.name', 
//                 'products.retail_price', 
//                 // 'promo_products.id',
//                 'promo_products.description',
//                 'promo_products.min',
//                 'promo_products.max', // contoh kolom dari tabel promo_products
//                 'promo_products.promo_value',
//                 'promo_products.start_date',
//                 'promo_products.end_date',
//             )
//             ->orderByRaw("CASE 
//                     WHEN (SUM(CASE WHEN product_journal.action = 'IN' THEN product_journal.quantity ELSE 0 END) - 
//                         SUM(CASE WHEN product_journal.action = 'OUT' THEN product_journal.quantity ELSE 0 END)) = 0 
//                     THEN 1 
//                     WHEN (SUM(CASE WHEN product_journal.action = 'IN' THEN product_journal.quantity ELSE 0 END) - 
//                         SUM(CASE WHEN product_journal.action = 'OUT' THEN product_journal.quantity ELSE 0 END)) >= 10 
//                     THEN 2 
//                     ELSE 3 
//                 END")
//             ->orderBy('last_stock', 'asc'); // Urutkan last_stock dari terkecil hingga terbesar


//         // Filter by warehouse if provided
//         if (!is_null($warehouse)) {
//             $query->where('warehouse.name', $warehouse);
//         }

//         if(!is_null($action)){
//             $query->where('product_journal.action', $action);
//         }

//         // Return paginated results if $paginate is provided, otherwise just get()
//         if (!is_null($paginate)) {
//             return $query->paginate($paginate);
//         }

//         // Jika tidak ada paginasi, gunakan get untuk mendapatkan semua hasil
//         return $query->get();
//     }

    /**
     * Get all data stock products from warehouse
     * 
     * @param string|null $warehouse Optional warehouse name for filtering, null means no filter.
     * @param string|null $action Optional action for filtering.
     * @param int|null $paginate Number of results per page when using pagination.
     */
    public function getStockProducts(?string $warehouse = null, ?string $action = null, ?int $paginate = null)
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
                'promo_products.max', // contoh kolom dari tabel promo_products
                'promo_products.promo_value',
                'promo_products.start_date',
                'promo_products.end_date',
                DB::raw('SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                        SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END) AS last_stock'),
                DB::raw('CASE 
                            WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                                SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) > 20 
                            THEN "TERSEDIA"
                            WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                                SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) >= 10 
                            THEN "PERLU TAMBAH"
                            WHEN (SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                                SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) <= 0 
                            THEN "HABIS"
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
                'promo_products.promo_value',
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

        if (!is_null($warehouse)) {
            $query->where('warehouse.name', $warehouse);
        }

        if (!is_null($action)) {
            $query->where('product_journal.action', $action);
        }

        if (!is_null($paginate)) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }


    /**
     * Get expired products with warehouse filter and transformed data.
     *
     * @param string $warehouse
     * @return \Illuminate\Support\Collection
     */
    public function getExpiredProducts(string $warehouse)
    {
        // Ambil produk expired dengan detail seperti sebelumnya
        $expiredProducts = ProductJournal::with(['transaction.transactionDetails', 'transaction.transactionItems.product' ,'warehouse'])
            ->where('expiry_date', '<=', Carbon::today())
            ->whereHas('warehouse', function($query) use ($warehouse) {
                $query->where('name', $warehouse);
            })
            ->get();

        // Transformasi data untuk menyesuaikan format yang diinginkan
        return $expiredProducts->map(function ($product) {
            $unit = $product->transaction->transactionItems->pluck('unit')->first();
            $productName = $product->transaction->transactionItems->pluck('product.name')->first();
            $warehouseEntryDate = $product->transaction->transactionDetails
                ->where('category', 'Warehouse Entry Date')
                ->pluck('value')
                ->first();

            return [
                'name' => $productName,
                'quantity' => $product->quantity,
                'unit' => $unit,
                'sso_number' => $product->transaction->document_code,
                'warehouse_entry_date' => $warehouseEntryDate,
                'expiry_date' => $product->expiry_date,
            ];
        });
    }
}