<?php

namespace App\Http\Controllers;

use App\Http\Services\PurchaseOrderService;
use App\Models\Lookup;
use App\Models\Parties;
use App\Models\Products;
use App\Models\PurchaseOrderProduct;
use App\Models\PurchaseOrder;
use App\Models\StoreHouse;
use App\Models\SubSalesOrder;
use App\Models\Tax;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseOrderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $transactions = Transactions::with([
            'transactionDetails', // Memuat relasi transactionDetails
            'transactionItems.product' // Memuat relasi transactionItems beserta product di dalamnya
        ])
            ->where('transaction_type_id', 4)
            ->get();

        return Inertia::render('Procurement/Purchase/ListPurchaseOrders', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $payment_terms = Lookup::where('category', 'PAYMENT_TERM')->get();
        $store_locations = Lookup::where('category', 'STORE_LOCATION')->get();
        $suppliers = Parties::where('type_parties', 'VENDOR')->get();
        $products = Products::all();
        $units = Lookup::where('category', 'UNIT')->get();
        $tax = Tax::all();

        return Inertia::render('Procurement/Purchase/CreatePurchaseOrder', [
            'po_number' => 'PO-'.rand(100000, 6666666),
            'storehouses' => StoreHouse::all(),
            'payment_terms' => $payment_terms,
            'store_locations' => $store_locations,
            'tax' => $tax,
            'products' => $products,
            'units' => $units,
            'suppliers' => $suppliers
        ]);
    }

    public function store(Request $request): RedirectResponse
    {

        // dd($request->all());

        $request->validate([
            'document_code' => 'required|string|unique:transactions,document_code',
            'term_of_payment' => 'required|string',
            'due_date' => 'required',
            'description' => 'nullable|string',
            'sub_total' => 'required|numeric', //required
            'total' => 'required|numeric', //required
            'tax_amount' => 'required|numeric', //required
            'transaction_details' => 'required|array',
            'transaction_details.*.name' => 'required|string',
            'transaction_details.*.category' => 'required|string',
            'transaction_details.*.value' => 'required|string',
            'transaction_details.*.data_type' => 'required|string',
            'transaction_items' => 'required|array',
            'transaction_items.*.unit' => 'required|string',
            'transaction_items.*.quantity' => 'required|numeric',
            'transaction_items.*.tax_amount' => 'nullable|numeric',
            'transaction_items.*.amount' => 'required|numeric',
            'transaction_items.*.tax_id' => 'required|numeric',
            'transaction_items.*.product_id' => 'required|numeric',
            'transaction_items.*.product' => 'required_if:transaction_items.*.product_id,null|array',
            'transaction_items.*.product.code' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.unit' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.name' => 'required_with:transaction_items.*.product|string',
        ]);

        // Gunakan transaksi database
        DB::transaction(function () use ($request) {
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
                'transaction_type_id' => 4,
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

                // Jika produk sudah ada (product_id), ambil id-nya. Jika tidak, buat produk baru.
                // if(isset($txItem['product_id'])) 
                // {
                //     $product = Products::find($txItem['product_id']);
                // } 
                // else 
                // {
                //     $product = Products::create([
                //         'code' => $txItem['product']['code'],
                //         'unit' => $txItem['product']['unit'],
                //         'name' => $txItem['product']['name'],
                //     ]);
                // }

                // Simpan transaction item
                TransactionItem::create([
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

        return redirect()->route('procurement.purchase-order')->with('success', 'Purchase Order Berhasil Tersubmit!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transactions $transaction)
    {
        // Memuat relasi yang diperlukan
        $transaction->load(['transactionDetails', 'transactionItems.product']);

        // Mengembalikan view dengan data transaksi
        return Inertia::render('Procurement/Purchase/PurchaseOrderDetail', compact('transaction'));
    }

    public function getDataByPoNumber(string $po_number)
    {
        // Mengambil transaksi berdasarkan transaction_id dari transaction detail yang ditemukan
        $transaction = Transactions::with(['transactionDetails', 'transactionItems.product'])
            ->where('document_code', $po_number) // Pastikan menggunakan field yang benar
            ->first();

        // Memeriksa apakah transaksi ada
        if (!$transaction) 
        {
            return redirect()->back()->with('failed', 'Nomor transaksi salah atau tidak ditemukan');
        }

        // dd($transaction);

        // Memeriksa apakah transaksi telah digunakan
        // // Cek apakah ada transaksi dengan transaction_type_id 6
        // $isTransactionHasUsed = Transactions::where('transaction_type_id', 6)
        //     ->whereHas('transactionDetails', function ($query) use ($poNumber) {
        //         $query->where('category', 'PO Number') // Pastikan category adalah PO Number
        //             ->where('value', $poNumber); // Bandingkan dengan nomor PO yang diberikan
        //     })
        //     ->exists(); // Menggunakan exists() untuk memeriksa ada tidaknya data

        // // Jika transaksi sudah ada
        // if ($isTransactionHasUsed) 
        // {
        //     return redirect()->back()->with('failed', 'Nomor transaksi telah digunakan!');
        // }

        // Mengembalikan view dengan data transaksi
        return Inertia::render('Procurement/ItemsReceipt/CreateSalesOrder', compact('transaction'));
    }

    public function generatePurchaseOrderDocument(Transactions $transactions) 
    {
        // Memuat relasi yang diperlukan
        $transactions->load('transactionDetails', 'transactionItems.product');

        // Mengambil detail berdasarkan kategori
        $supplier = $transactions->transactionDetails->firstWhere('category', 'Supplier')->value ?? '';
        $storehouse = $transactions->transactionDetails->firstWhere('category', 'Storehouse')->value ?? '';
        $located = $transactions->transactionDetails->firstWhere('category', 'Allocation')->value ?? '';
        $purchase_order_date = $transactions->transactionDetails->firstWhere('category', 'PO Date')->value ?? null;
        $send_date = $transactions->transactionDetails->firstWhere('category', 'Delivery Date')->value ?? null;
        $transportation = $transactions->transactionDetails->firstWhere('category', 'Transportation')->value ?? '';
        $sender = $transactions->transactionDetails->firstWhere('category', 'Sender')->value ?? '';
        $delivery_type = $transactions->transactionDetails->firstWhere('category', 'Delivery Type')->value ?? '';

        // Data yang akan dikirimkan ke view PDF
        $data = [
            'purchase_order' => $transactions,
            'supplier' => $supplier,
            'storehouse' => $storehouse,
            'located' => $located,
            'purchase_order_date' => $purchase_order_date,
            'send_date' => $send_date,
            'transportation' => $transportation,
            'sender' => $sender,
            'delivery_type' => $delivery_type,
        ];

        // Mengenerate PDF
        $pdf = Pdf::loadView('documents.purchase-order-document', $data);

        return $pdf->stream('purchase_order_'.rand(100000,900000).'_.pdf');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }
}

