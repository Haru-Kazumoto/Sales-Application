<?php

namespace App\Http\Controllers;

use App\Http\Services\PartiesServices;
use App\Http\Services\TransactionServices;
use App\Models\Products;
use App\Models\SubSalesOrder;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SubSalesOrderController extends Controller
{
    protected $transactionServices;
    protected $partiesServices;

    public function __construct(TransactionServices $transactionsServices, PartiesServices $partiesServices)
    {
        $this->transactionServices = $transactionsServices;
        $this->partiesServices = $partiesServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tx_type = TransactionType::where('name', 'Penerimaan Barang Pembelian')->first();
        $transactions = $this->transactionServices->getTransactions(
            $tx_type->id, 
            $request->filter_field,
            $request->filter_query,
            10,
            $request->dateRange
        );

        return Inertia::render('Procurement/ItemsReceipt/ListSalesOrders', compact('transactions'));
    }

   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parties = $this->partiesServices->getPartiesByGroupAndType('VENDOR', 'Angkutan');

        return Inertia::render('Procurement/ItemsReceipt/CreateSalesOrder', compact('parties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

        // dd($request->all());

        $request->validate([
            'document_code' => 'required|string',
            'term_of_payment' => 'required|numeric',
            'due_date' => 'required',
            'description' => 'nullable|string',
            'sub_total' => 'required|numeric', //required
            'total' => 'required|numeric', //required
            'tax_amount' => 'nullable|numeric', //required
            'transaction_details' => 'required|array',
            'transaction_details.*.name' => 'required|string',
            'transaction_details.*.category' => 'required|string',
            'transaction_details.*.value' => 'required|string',
            'transaction_details.*.data_type' => 'required|string',
            'transaction_items' => 'required|array',
            'transaction_items.*.unit' => 'required|string',
            'transaction_items.*.quantity' => 'required|numeric',
            'transaction_items.*.tax_amount' => 'nullable|numeric',
            'transaction_items.*.tax_id' => 'nullable|numeric',
            'transaction_items.*.amount' => 'required|numeric',
            'transaction_items.*.product_id' => 'required|numeric',
            'transaction_items.*.total_price' => 'required|numeric',
            'transaction_items.*.product' => 'required_if:transaction_items.*.product_id,null|array',
            'transaction_items.*.product.code' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.unit' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.name' => 'required_with:transaction_items.*.product|string',
        ]);

        // Gunakan transaksi database
        DB::transaction(function () use ($request) {
            $tx_type = TransactionType::where('name', 'Penerimaan Barang Pembelian')->first();

            // Simpan transaksi
            $transaction = Transactions::create([
                'document_code' => $request->input('document_code'),
                'correlation_id' => rand(10000,88888),
                'created_by' => Auth::user()->id,
                'term_of_payment' => $request->input('term_of_payment'),
                'due_date' => $request->input('due_date'),
                'description' => $request->input('description'),
                'sub_total' => $request->input('sub_total'), //required
                'total' => $request->input('total'), //required
                'tax_amount' => $request->input('tax_amount'), //required
                'transaction_type_id' => $tx_type->id,
            ]);

            // Simpan transaction details
            foreach ($request->input('transaction_details') as $detail) {
                TransactionDetail::create([
                    'name' => $detail['name'],
                    'category' => $detail['category'],
                    'value' => $detail['value'],
                    'data_type' => $detail['data_type'],
                    'transactions_id' => $transaction->id,
                ]);
            }

            // Simpan transaction items
            foreach ($request->input('transaction_items') as $txItem) {
                $product = Products::find($txItem['product_id']);

                // Simpan transaction item
                TransactionItem::create([
                    'total_price' => $txItem['total_price'],
                    'unit' => $txItem['unit'],
                    'quantity' => $txItem['quantity'],
                    'tax_amount' => $txItem['tax_amount'],
                    'amount' => $txItem['amount'],
                    'tax_id' => $txItem['tax_id'],
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, // Menyimpan product_id hasil dari produk yang diambil atau baru
                ]);
            }
        });

        return redirect()->route('procurement.sales-order')->with('success', 'Sub Sales Order Berhasil Tersubmit!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transactions $transaction)
    {
        // Memuat relasi yang diperlukan
        $transaction->load(['transactionDetails', 'transactionItems.product']);

        // Mengembalikan view dengan data transaksi
        return Inertia::render('Procurement/ItemsReceipt/SubSalesOrderDetail', compact('transaction'));
    }

    /**
     * Generate document SO to PDF
     */
    public function generateSubSalesOrderDocument(Transactions $transactions)
    {
        // Memuat relasi yang diperlukan
        $transactions->load('transactionDetails', 'transactionItems.product');

        // Mengambil detail berdasarkan kategori
        $proof_number = $transactions->transactionDetails->firstWhere('category', "Proof Number")->value ?? null;
        $purchase_order_date = $transactions->transactionDetails->firstWhere('category', 'PO Date')->value ?? null;
        $supplier = $transactions->transactionDetails->firstWhere('category', 'Supplier')->value ?? '';
        $alocation = $transactions->transactionDetails->firstWhere('category', 'Allocation')->value ?? '';
        $sender = $transactions->transactionDetails->firstWhere('category', 'Sender')->value ?? '';

        // Data yang akan dikirimkan ke view PDF
        $data = [
            'sales_order' => $transactions,
            'proof_number' => $proof_number,
            'supplier' => $supplier,
            'purchase_order_date' => $purchase_order_date,
            'alocation' => $alocation,
            'sender' => $sender
        ];

        $pdf = Pdf::loadView('documents.sub-sales-order-document', $data);

        return $pdf->stream('sub_sales_order_'.rand(100000,900000).'_.pdf');
    }

    /**
     * Retrieve data from SSO NUMBER
     * @param string $sso_number
     * @return RedirectResponse|\Inertia\Response
     */
    public function getDataBySsoNumber(string $sso_number)
    {
        $tx_type = TransactionType::where('name', 'Penerimaan Barang Pembelian')->first();

        // Mengambil transaksi berdasarkan transaction_id dari transaction detail yang ditemukan
        $transaction = Transactions::with(['transactionDetails', 'transactionItems.product'])
            ->where('document_code', $sso_number) // Pastikan menggunakan field yang benar
            ->where('transaction_type_id', $tx_type->id)
            ->first();

        // Memeriksa apakah transaksi ada
        if (!$transaction) 
        {
            return back()->with('failed', 'Nomor dokumen salah atau tidak ditemukan');
        }

        //SINCE THE UPDATE, WAREHOUSE IS ABLE TO GENERATE SSO multiple times for gradual data entry needs
        // // Cek status 'Generate Status' di transactionDetails
        // $status_generate = $transaction->transactionDetails
        //     ->where('category', 'Generate Status')
        //     ->where('value', 'true')
        //     ->first();

        // if ($status_generate) {
        //     return back()->with('failed', 'Data SSO telah dimasukan ke gudang!');
        // }
        
        // Mengembalikan view dengan data transaksi
        return Inertia::render('Warehouse/IncomingItem', compact('transaction'));
    }
}
