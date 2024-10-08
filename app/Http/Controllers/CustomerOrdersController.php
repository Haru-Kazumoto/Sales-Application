<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrderProduct;
use App\Models\CustomerOrders;
use App\Models\Lookup;
use App\Models\Parties;
use App\Models\ProductCustomerOrder;
use App\Models\Products;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CustomerOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customerOrders = CustomerOrders::with('productCustomerOrders')->get();

        return Inertia::render('Sales/Sale/ListCO', compact('customerOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $coNumber = 'CO-'.rand(100000,999999);
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $customers = Parties::where('type_parties', 'CUSTOMER')->get();
        $payment_terms = Lookup::where('category', 'PAYMENT_TERM')->get();
        $products = Products::all();

        return Inertia::render('Sales/Sale/CreateCO', [
            'coNumber' => $coNumber, 
            'dateNow' => $dateNow, 
            'customers' => $customers, 
            'payment_terms' => $payment_terms,
            'products' => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all()); // Digunakan untuk debugging

        // Validasi input request
        $request->validate([
            'document_code' => 'required|string|unique:transactions,document_code',
            'term_of_payment' => 'required|string',
            'due_date' => 'required|date',
            'sub_total' => 'required|numeric',
            'total' => 'required|numeric',
            'tax_amount' => 'required|numeric',
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
            'transaction_items.*.product_id' => 'required|numeric',
            // 'transaction_items.*.total_price_discount' => 'required|numeric',
            'transaction_items.*.total_price' => 'required|numeric',
            'transaction_items.*.discount_1' => 'nullable|numeric',
            'transaction_items.*.discount_2' => 'nullable|numeric',
            'transaction_items.*.discount_3' => 'nullable|numeric',
            'transaction_items.*.product' => 'required_if:transaction_items.*.product_id,null|array',
            'transaction_items.*.product.code' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.unit' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.name' => 'required_with:transaction_items.*.product|string',
        ]);

        // Gunakan transaksi database
        DB::transaction(function () use ($request) {
            // Simpan customer order
            $transaction = Transactions::create([
                'document_code' => $request->input('document_code'),
                'correlation_id' => rand(10000, 88888),
                'created_by' => Auth::user()->id,
                'term_of_payment' => $request->input('term_of_payment'),
                'due_date' => $request->input('due_date'),
                'description' => $request->input('description'),
                'sub_total' => $request->input('sub_total'),
                'total' => $request->input('total'),
                'tax_amount' => $request->input('tax_amount'),
                'transaction_type_id' => 7, // Atur transaction_type_id untuk CO
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

                // Jika produk belum ada, buat produk baru
                if (!isset($txItem['product_id'])) {
                    $product = Products::create([
                        'code' => $txItem['product']['code'],
                        'unit' => $txItem['product']['unit'],
                        'name' => $txItem['product']['name'],
                    ]);
                }

                // Simpan transaction item
                TransactionItem::create([
                    'unit' => $txItem['unit'],
                    'quantity' => $txItem['quantity'],
                    'tax_amount' => $txItem['tax_amount'],
                    'amount' => $txItem['amount'],
                    'tax_id' => $txItem['tax_id'],
                    'total_price' => $txItem['total_price'],
                    'discount_1' => $txItem['discount_1'],
                    'discount_2' => $txItem['discount_2'],
                    'discount_3' => $txItem['discount_3'],
                    // 'total_price_discount' => $txItem['total_price_discount'],
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, // Menyimpan product_id dari produk yang ditemukan atau baru
                ]);
            }
        });

        return redirect()->route('sales.create-co')->with('success', 'Customer Order berhasil disimpan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(CustomerOrders $customerOrders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerOrders $customerOrders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerOrders $customerOrders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerOrders $customerOrders)
    {
        //
    }
}
