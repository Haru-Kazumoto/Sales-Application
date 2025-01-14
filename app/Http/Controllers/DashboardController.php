<?php

namespace App\Http\Controllers;

use App\Http\Services\TransactionServices;
use App\Models\ProductJournal;
use App\Models\Transactions;
use App\Models\TransactionType;
use App\Models\UserTarget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{

    private $transactionServices;

    public function __construct(TransactionServices $transactionServices)
    {
        $this->transactionServices = $transactionServices;
    }

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

        $counts = DB::table('transactions')
            ->selectRaw("
                SUM(CASE 
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) = total THEN 1 
                    ELSE 0 
                END) AS count_unpaid,
                SUM(CASE 
                    WHEN due_date <= CURDATE() THEN 1 
                    ELSE 0 
                END) AS count_due_date,
                SUM(CASE 
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) > 0 
                        AND (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) < total THEN 1 
                    ELSE 0 
                END) AS count_instalment,
                SUM(CASE 
                    WHEN due_date < CURDATE() 
                        AND (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) > 0 THEN 1 
                    ELSE 0 
                END) AS count_overdue
            ")
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('transaction_type')
                    ->whereColumn('transactions.transaction_type_id', 'transaction_type.id')
                    ->where('transaction_type.name', 'Penjualan');
            })
            ->first();

        $tx_type = TransactionType::where('name', 'Penjualan')->first();
        $invoices = $this->transactionServices->getTransactions($tx_type->id, null,null,30, true);

        return Inertia::render('AgingFinance/Dashboard',[
            'count_invoice' => $count_invoice,
            'count_unpaid' => $counts->count_unpaid,
            'count_overdue' => $counts->count_overdue,
            'count_instalment' => $counts->count_instalment,
            'count_due_date' => $counts->count_due_date,
            'invoices' => $invoices,
        ]);
    }

    public function indexSalesDashboard(): Response
    {
        $user_id = Auth::user()->id;
        $target = UserTarget::where('user_id', $user_id)->first();
        $sales = DB::table('transactions as tx')
        ->select(
            'tx.id',
            DB::raw("(
                SELECT value FROM transaction_details td WHERE td.category = 'Customer' LIMIT 1
            ) AS customer_name"),
            'tx.total',
            DB::raw("DATE_FORMAT(tx.due_date, '%d %M %Y') AS due_date")
        )
        ->where('tx.created_by', $user_id)
        ->get();
        $target_margin = DB::table('report_marketing')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_nett_margin) as amount_sales')
            ->whereYear('created_at',Carbon::now()->year)
            ->where('created_by', $user_id)
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();
        $total_margin = DB::table('report_marketing')
            ->whereYear('created_at', Carbon::now()->year) // Filter tahun berjalan
            ->sum('total_nett_margin'); // Hitung total kolom
        $shortfall = max(0, $target->annual_target - $total_margin);

        return Inertia::render('Sales/Dashboard', compact(
            'target',
            'sales',
            'target_margin',
            'total_margin',
            'shortfall'
        ));
    }

    public function indexMarketingDashboard(): Response
    {
        return Inertia::render('Marketing/Dashboard');
    }

    public function indexCashierDashboard(): Response
    {
        $count_invoice = Transactions::whereHas('transactionType', function($query){
            $query->where('name', 'Penjualan');
        })->count();

        $counts = DB::table('transactions')
            ->selectRaw("
                SUM(CASE 
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) = total THEN 1 
                    ELSE 0 
                END) AS count_unpaid,
                SUM(CASE 
                    WHEN due_date <= CURDATE() THEN 1 
                    ELSE 0 
                END) AS count_due_date,
                SUM(CASE 
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) > 0 
                        AND (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) < total THEN 1 
                    ELSE 0 
                END) AS count_instalment,
                SUM(CASE 
                    WHEN due_date < CURDATE() 
                        AND (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) > 0 THEN 1 
                    ELSE 0 
                END) AS count_overdue
            ")
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('transaction_type')
                    ->whereColumn('transactions.transaction_type_id', 'transaction_type.id')
                    ->where('transaction_type.name', 'Penjualan');
            })
            ->first();

        $tx_type = TransactionType::where('name', 'Penjualan')->first();
        $invoices = $this->transactionServices->getTransactions($tx_type->id, null,null,30, true);

        return Inertia::render('Cashier/Dashboard',[
            'count_invoice' => $count_invoice,
            'count_unpaid' => $counts->count_unpaid,
            'count_overdue' => $counts->count_overdue,
            'count_instalment' => $counts->count_instalment,
            'count_due_date' => $counts->count_due_date,
            'invoices' => $invoices,
        ]);
    }

    public function indexInvoiceistDashboard(): Response
    {
        $count_invoice = Transactions::whereHas('transactionType', function($query){
            $query->where('name', 'Penjualan');
        })->count();

        $counts = DB::table('transactions')
            ->selectRaw("
                SUM(CASE 
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) = total THEN 1 
                    ELSE 0 
                END) AS count_unpaid,
                SUM(CASE 
                    WHEN due_date <= CURDATE() THEN 1 
                    ELSE 0 
                END) AS count_due_date,
                SUM(CASE 
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) > 0 
                        AND (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) < total THEN 1 
                    ELSE 0 
                END) AS count_instalment,
                SUM(CASE 
                    WHEN due_date < CURDATE() 
                        AND (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) > 0 THEN 1 
                    ELSE 0 
                END) AS count_overdue
            ")
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('transaction_type')
                    ->whereColumn('transactions.transaction_type_id', 'transaction_type.id')
                    ->where('transaction_type.name', 'Penjualan');
            })
            ->first();

        $tx_type = TransactionType::where('name', 'Penjualan')->first();
        $invoices = $this->transactionServices->getTransactions($tx_type->id, null,null,30, true);

        return Inertia::render('Invoiceist/Dashboard',[
            'count_invoice' => $count_invoice,
            'count_unpaid' => $counts->count_unpaid,
            'count_due_date' => $counts->count_due_date,
            'count_instalment' => $counts->count_instalment,
            'count_overdue' => $counts->count_overdue,
            'invoices' => $invoices,
        ]);
    }
}
