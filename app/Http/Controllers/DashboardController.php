<?php

namespace App\Http\Controllers;

use App\Models\ProductJournal;
use App\Models\PurchaseOrder;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function indexProcurementDashboard(): Response
    {
        // Query dasar untuk transaksi yang memiliki tipe 'Purchase Order'
        $queryBase = Transactions::whereHas('transactionType', function($query) {
            $query->where('name', 'Purchase Order');
        });

        // Total PO
        $total_po = $queryBase->count();
        $purchase_orders = $queryBase->with('transactionDetails', 'transactionItems')
            ->orderByDesc('created_at')
            ->paginate(10);

        // Total DNP PO
        $total_dnp_po = (clone $queryBase)->whereHas('transactionDetails', function ($query) {
            $query->where('category', 'Allocation')
                ->where('value', 'DNP');
        })->count();

        // Total DKU PO
        $total_dku_po = (clone $queryBase)->whereHas('transactionDetails', function ($query) {
            $query->where('category', 'Allocation')
                ->where('value', 'DKU');
        })->count();

        return Inertia::render('Procurement/Dashboard', compact(
            'total_po',
            'total_dnp_po',
            'total_dku_po',
            'purchase_orders'
        ));
    }


    public function indexFinanceDashboard(): Response
    {
        return Inertia::render('Finance/Dashboard');
    }

    public function indexWarehouseDashboard(): Response
    {
        // Query untuk mendapatkan produk dengan last_stock dan status
        $products = DB::table('products')
            ->select(
                'products.code',
                'products.name',
                'products.unit',
                'warehouse.name AS warehouse',
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
                                SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END)) = 0 
                            THEN "STOK HABIS"
                        END AS status')
            )
            ->join('product_journal', 'products.id', '=', 'product_journal.product_id') // Perbaiki field join 'product_id'
            ->join('warehouse', 'product_journal.warehouse_id', '=', 'warehouse.id')
            ->groupBy('products.code', 'products.name', 'products.unit', 'warehouse.name')
            ->orderByRaw("CASE 
                    WHEN (SUM(CASE WHEN product_journal.action = 'IN' THEN product_journal.quantity ELSE 0 END) - 
                          SUM(CASE WHEN product_journal.action = 'OUT' THEN product_journal.quantity ELSE 0 END)) = 0 
                    THEN 1 
                    WHEN (SUM(CASE WHEN product_journal.action = 'IN' THEN product_journal.quantity ELSE 0 END) - 
                          SUM(CASE WHEN product_journal.action = 'OUT' THEN product_journal.quantity ELSE 0 END)) >= 10 
                    THEN 2 
                    ELSE 3 
                 END")
            ->orderBy('last_stock', 'asc') // Urutkan last_stock dari terkecil hingga terbesar
            ->paginate(10); // Menggunakan paginate untuk paginasi
        
        // Query untuk menghitung jumlah stok berdasarkan status
        $stockSummary = DB::table(DB::raw('(SELECT products.id, products.code, products.name, products.unit, warehouse.name AS warehouse,
                SUM(CASE WHEN product_journal.action = "IN" THEN product_journal.quantity ELSE 0 END) - 
                SUM(CASE WHEN product_journal.action = "OUT" THEN product_journal.quantity ELSE 0 END) AS last_stock
                FROM products
                INNER JOIN product_journal ON products.id = product_journal.product_id
                INNER JOIN warehouse ON product_journal.warehouse_id = warehouse.id
                GROUP BY products.id, products.code, products.name, products.unit, warehouse.name
            ) AS product_stock'))
        ->select(
            DB::raw('COUNT(CASE WHEN last_stock > 20 THEN 1 ELSE NULL END) AS available'),
            DB::raw('COUNT(CASE WHEN last_stock >= 10 AND last_stock <= 20 THEN 1 ELSE NULL END) AS need_to_add'),
            DB::raw('COUNT(CASE WHEN last_stock = 0 THEN 1 ELSE NULL END) AS unavailable')
        )
        ->first();

        // Ambil produk yang expired hari ini atau sebelumnya
        $expiredProductsCount = ProductJournal::where('expiry_date', '<=', Carbon::today())->count();

        // Ambil produk expired dengan detail seperti sebelumnya
        $expiredProducts = ProductJournal::with(['transaction.transactionDetails', 'transaction.transactionItems.product'])
            ->where('expiry_date', '<=', Carbon::today())
            ->get();

        // Transformasi data untuk menyesuaikan format yang diinginkan
        $product_expireds = $expiredProducts->map(function ($product) {
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


        // Mengembalikan data ke view dengan Inertia
        return Inertia::render('Warehouse/Dashboard', [
            'products' => $products, // Produk dengan stok terakhir dan status
            'product_expireds' => $product_expireds, // Produk expired
            'stock_summary' => $stockSummary, // Jumlah stok tersedia, harus ditambah, habis
            'expired_count' => $expiredProductsCount // Jumlah produk expired
        ]);
    }

    public function indexAdminDashboard(): Response
    {
        return Inertia::render('Admin/Dashboard');
    }

    public function indexAgingFinanceDashboard(): Response
    {
        $count_invoice = Transactions::whereHas('transactionType', function($query){
            $query->where('name', 'Penjualan');
        })->count();

        return Inertia::render('AgingFinance/Dashboard',compact(
            'count_invoice'
        ));
    }

    public function indexSalesDashboard(): Response
    {
        return Inertia::render('Sales/Dashboard');
    }

    public function indexMarketingDashboard(): Response
    {
        return Inertia::render('Marketing/Dashboard');
    }
}
