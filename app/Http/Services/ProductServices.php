<?php

namespace App\Http\Services;

use App\Models\ProductJournal;
use App\Models\Products;
use App\Traits\Filterable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductServices {
    use Filterable;

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
                'promo_products.max',
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
     * Get products with stock and batch code, filtered by name and status.
     *
     * @param string|null $name
     * @param string|null $status
     * @param int|null $pagination
     * @return Collection|LengthAwarePaginator
     */
    public function getStockProductsWithBatchCode(?string $warehouse = null,?string $name = null, ?string $status = null, ?int $pagination = null): Collection|LengthAwarePaginator
    {
        $query = Products::query()
            ->select(
                'products.id',
                'products.code',
                'product_journal.batch_code',
                'products.name',
                'products.unit',
                'products.retail_price',
                'warehouse.name as warehouse',
                'promo_products.description',
                'promo_products.min',
                'promo_products.max',
                'promo_products.promo_value',
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
                'promo_products.promo_value',
                'promo_products.start_date',
                'promo_products.end_date',
                'promo_products.name',
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

        if($warehouse)
        {
            $query->where('warehouse.name', $warehouse);
        }

        // Apply search filter by product name
        if($name)
        {
            $query = $this->applySearchFilter($query, 'products.name', $name);
        }

        // Apply status filter if status is provided
        if ($status) {
            $query->having('status', '=', $status);
        }

        // Order by products.created_at to avoid ambiguity
        $query->orderByDesc('products.created_at');

        // Apply pagination directly here
        if ($pagination) {
            $paginator = $query->paginate($pagination);
            
            // Appending the filters for pagination links
            $paginator->appends(['name' => $name, 'status' => $status]);
            
            return $paginator;
        }

        // Return all results if pagination is not set
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