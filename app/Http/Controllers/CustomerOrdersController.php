<?php

namespace App\Http\Controllers;

use App\Http\Services\CustomerOrderServices;
use App\Http\Services\ProductServices;
use App\Models\CustomerOrderProduct;
use App\Models\CustomerOrders;
use App\Models\Lookup;
use App\Models\Parties;
use App\Models\ProductCustomerOrder;
use App\Models\ProductJournal;
use App\Models\Products;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CustomerOrdersController extends Controller
{
    protected $productServices;
    protected $customerOrderServices;

    public function __construct(ProductServices $productServices, CustomerOrderServices $customerOrderServices)
    {
        $this->productServices = $productServices;
        $this->customerOrderServices = $customerOrderServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer_orders = Transactions::with('transactionType', 'transactionDetails', 'transactionItems')
            ->whereHas('transactionType', function($query) {
                $query->where('name', 'Sales Order');
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('Sales/Sale/ListCO', compact('customer_orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createDnp(): Response
    {
        $coNumber = 'CO-'.rand(100000,999999);
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $customers = Parties::where('type_parties', 'CUSTOMER')->get();
        $payment_terms = Lookup::where('category', 'PAYMENT_TERM')->get();
        $products = $this->productServices->getStockProducts("DNP");

        return Inertia::render('Sales/Sale/CreateCoDnp', [
            'coNumber' => $coNumber, 
            'dateNow' => $dateNow, 
            'customers' => $customers, 
            'payment_terms' => $payment_terms,
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createDku(): Response
    {
        $coNumber = 'CO-'.rand(100000,999999);
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $customers = Parties::where('type_parties', 'CUSTOMER')->get();
        $payment_terms = Lookup::where('category', 'PAYMENT_TERM')->get();
        $products = $this->productServices->getStockProducts("DKU");

        return Inertia::render('Sales/Sale/CreateCoDku', [
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
    public function storeDnp(Request $request)
    {
        // dd($request->all()); // Digunakan untuk debugging

        // Validasi input request
        $request->validate([
            'document_code' => 'required|string',
            'term_of_payment' => 'required|numeric',
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
            $tx_type = TransactionType::where('name', 'Sales Order')->first();

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
                $product = Products::where('id', $txItem['product_id'])->first();
                $warehouse = Warehouse::where('name', 'DNP')->first();

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

                //decrease quantity at warehouse
                ProductJournal::create([
                    'quantity' => $txItem['quantity'],
                    'amount' => $txItem['amount'],
                    'action' => "OUT",
                    'expiry_date' => null,
                    'warehouse_id' => $warehouse->id,
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id,
                ]);
            }

        });

        return redirect()->route('sales.create-co')->with('success', 'Customer Order berhasil disubmit!');
    }

        /**
     * Store a newly created resource in storage.
     */
    public function storeDku(Request $request)
    {
        // dd($request->all()); // Digunakan untuk debugging

        // Validasi input request
        $request->validate([
            'document_code' => 'required|string',
            'term_of_payment' => 'required|numeric',
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
            $tx_type = TransactionType::where('name', 'Sales Order')->first();

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
                'transaction_type_id' => $tx_type->id, // Atur transaction_type_id untuk CO
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
                $product = Products::where('id', $txItem['product_id'])->first();
                $warehouse = Warehouse::where('name', 'DKU')->first();

                // Simpan transaction item
                TransactionItem::create([
                    'unit' => $txItem['unit'],
                    'quantity' => $txItem['quantity'],
                    'tax_amount' => $txItem['tax_amount'],
                    'amount' => $txItem['amount'],
                    'tax_id' => 3,
                    'total_price' => $txItem['total_price'],
                    'discount_1' => $txItem['discount_1'],
                    'discount_2' => $txItem['discount_2'],
                    'discount_3' => $txItem['discount_3'],
                    // 'total_price_discount' => $txItem['total_price_discount'],
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, // Menyimpan product_id dari produk yang ditemukan atau baru
                ]);

                //decrease quantity at warehouse
                ProductJournal::create([
                    'quantity' => $txItem['quantity'],
                    'amount' => $txItem['amount'],
                    'action' => "OUT",
                    'expiry_date' => null,
                    'warehouse_id' => $warehouse->id,
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id,
                ]);
            }
        });

        return redirect()->route('sales.create-co-dku')->with('success', 'Customer Order berhasil disubmit!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transactions $transactions)
    {
        $transactions->load('transactionType', 'transactionDetails', 'transactionItems.product');

        return Inertia::render('Sales/Sale/DetailCO', ['customer_order' => $transactions]);
    }

    /**
     * Display the listing view travel document
     */
    public function createTravelDocument()
    {
        $customer_orders_dnp = $this->customerOrderServices->getTransactions("Sales Order","false",15,'Warehouse','DNP');
        $customer_orders_dku = $this->customerOrderServices->getTransactions("Sales Order","false",15,'Warehouse','DKU');
        // dd($customer_orders_dku);
        
        return Inertia::render('Warehouse/ListTravelDocument', compact('customer_orders_dnp','customer_orders_dku'));
    }

    /**
     * Index list of travel documents
     */
    public function indexTravelDocuments()
    {
        $travel_documents_dnp = $this->customerOrderServices->getTransactions("Surat Jalan",null,15,"Warehouse","DNP");
        $travel_documents_dku = $this->customerOrderServices->getTransactions("Surat Jalan",null,15,"Warehouse","DKU");

        return Inertia::render('Warehouse/IndexTravelDocument', compact('travel_documents_dnp', 'travel_documents_dku'));
    }

    /**
     * Show the detail of travel document
     */
    public function detailTravelDocument(Transactions $transactions)
    {
        // Load the necessary relationships
        $transactions->load('transactionType', 'transactionDetails', 'transactionItems.product');

        // Find the transactionDetails with category 'Warehouse' and get its value
        $prefixDocument = $transactions->transactionDetails
            ->where('category', 'Warehouse')
            ->pluck('value') // Get the value field
            ->first(); // Get the first result, in case there are multiple results

        // Set default value if no value found
        if (!$prefixDocument) {
            $prefixDocument = 'DNP'; // Default value if prefix not found
        }

        // Generate the document code with the prefixDocument
        $document_code = $prefixDocument.'/BKS/SJ/'.rand(10000,99999);


        return Inertia::render('Warehouse/CreateTravelDocument', compact('transactions', 'document_code'));
    }

    /**
     * Store a newly created Travel document resource in storage.
     */
    public function storeTravelDocument(Request $request) 
    {
        // dd($request->all());

        $request->validate([
            'document_code' => 'required|string',
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
            'transaction_items.*.product_id' => 'required|numeric',
            'transaction_items.*.product' => 'required_if:transaction_items.*.product_id,null|array',
            'transaction_items.*.product.code' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.unit' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.name' => 'required_with:transaction_items.*.product|string',
        ]);

        // Gunakan transaksi database
        DB::transaction(function () use ($request) {
            $tx_type = TransactionType::where('name', 'Surat Jalan')->first();

            // Simpan customer order
            $transaction = Transactions::create([
                'document_code' => $request->input('document_code'),
                'correlation_id' => rand(10000,99999),
                'sub_total' => $request->input('sub_total'),
                'total' => $request->input('total'),
                'tax_amount' => $request->input('tax_amount'),
                'created_by' => Auth::user()->id,
                'description' => $request->input('description'),
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

                // Jika category adalah 'CO Number', lakukan pencarian pada CustomerOrder
                if ($detail['category'] === 'CO Number') {
                    // Cari CustomerOrder berdasarkan nomor CO dari value
                    $customerOrder = Transactions::where('document_code', $detail['value'])->first();

                    // Jika CustomerOrder ditemukan, update transaction_detail dengan category 'Generating'
                    if ($customerOrder) {
                        TransactionDetail::where('transactions_id', $customerOrder->transaction_id)
                            ->where('category', 'Generating')
                            ->update(['value' => 'true']);
                    }
                }
            }

            // Simpan transaction items
            foreach ($request->input('transaction_items') as $txItem) {
                $product = Products::find($txItem['product_id']);

                // Simpan transaction item
                TransactionItem::create([
                    'unit' => $txItem['unit'],
                    'quantity' => $txItem['quantity'],
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, 
                ]);
            }
        });

        return redirect()->route('warehouse.list-travel-document')->with('success', 'Surat jalan berhasil dibuat!');
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
