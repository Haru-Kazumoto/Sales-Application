<?php

namespace App\Http\Controllers;

use App\Http\Services\CustomerOrderServices;
use App\Http\Services\PartiesServices;
use App\Http\Services\ProductServices;
use App\Models\Lookup;
use App\Models\Parties;
use App\Models\ProductJournal;
use App\Models\Products;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use App\Models\Warehouse;
use Barryvdh\DomPDF\Facade\Pdf;
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
    protected $partiesService;

    public function __construct(ProductServices $productServices, CustomerOrderServices $customerOrderServices, PartiesServices $partiesServices)
    {
        $this->productServices = $productServices;
        $this->customerOrderServices = $customerOrderServices;
        $this->partiesService = $partiesServices;
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
        $customers = Parties::where('type_parties', 'CUSTOMER')
            ->where('users_id', Auth::user()->id)
            ->with('partiesGroup')
            ->get();
        $payment_terms = Lookup::where('category', 'PAYMENT_TERM')->get();
        $products = $this->productServices->getStockProductsWithBatchCode("DNP");
        $all_products = Products::query()->get();

        return Inertia::render('Sales/Sale/CreateCoDnp', [
            'coNumber' => $coNumber, 
            'dateNow' => $dateNow, 
            'customers' => $customers, 
            'payment_terms' => $payment_terms,
            'products' => $products,
            'all_products' => $all_products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createDku(): Response
    {
        $coNumber = 'CO-'.rand(100000,999999);
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $customers = Parties::where('type_parties', 'CUSTOMER')
            ->where('users_id', Auth::user()->id)
            ->get();
        $payment_terms = Lookup::where('category', 'PAYMENT_TERM')->get();
        $products = $this->productServices->getStockProductsWithBatchCode("DKU");
        $all_products = Products::query()->get();

        return Inertia::render('Sales/Sale/CreateCoDku', [
            'coNumber' => $coNumber, 
            'dateNow' => $dateNow, 
            'customers' => $customers, 
            'payment_terms' => $payment_terms,
            'products' => $products,
            'all_products' => $all_products,
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
            $delivery = $request->transaction_details[4]['value'];

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

                // decrease quantity at warehouse if the delivery is not 'DIRECT', 'DIRECT_DEPO', 'DO'
                if($delivery && !in_array($delivery, ['DIRECT', 'DIRECT_DEPO', 'DO'])){
                    foreach ($txItem['product_journals'] as $journal) {
                        ProductJournal::create([
                            'quantity' => $journal['quantity'],
                            'amount' => $txItem['amount'],
                            'action' => $journal['action'],
                            'batch_code' => $journal['batch_code'], 
                            // 'expiry_date' => $journal['expiry_date'],
                            'transactions_id' => $transaction->id,
                            'warehouse_id' => $warehouse->id,
                            'product_id' => $product->id,
                        ]);
                    }
                }
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
        $transports = $this->partiesService->getPartiesByGroupAndType('VENDOR', 'Angkutan');

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


        return Inertia::render('Warehouse/CreateTravelDocument', compact('transactions', 'document_code', 'transports'));
    }

    /**
     * Store a newly created Travel document resource in storage.
     */
    public function storeTravelDocument(Request $request) 
    {
    
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

            $transaction = Transactions::create([
                'document_code' => $request->input('document_code'),
                'correlation_id' => rand(10000,99999),
                'sub_total' => $request->input('sub_total'),
                'term_of_payment' => $request->input('term_of_payment'),
                'due_date' => $request->input('due_date'),
                'total' => $request->input('total'),
                'tax_amount' => $request->input('tax_amount'),
                'created_by' => Auth::user()->id,
                'description' => $request->input('description'),
                'transaction_type_id' => $tx_type->id,
            ]);

            $tx_details = $request->transaction_details;
            $tx = Transactions::where('document_code', $tx_details[0]['value'])->first();
            if($tx){
                $status = TransactionDetail::where('transactions_id', $tx->id)
                    ->where('category', 'Generating')
                    ->first();
                $status->update(['value' => 'true']);
            }

            // Simpan transaction details
            foreach ($request->transaction_details as $detail) {
                TransactionDetail::create([
                    'name' => $detail['name'],
                    'category' => $detail['category'],
                    'value' => $detail['value'],
                    'data_type' => $detail['data_type'],
                    'transactions_id' => $transaction->id,
                ]);
            }

            // Simpan transaction items
            foreach ($request->transaction_items as $txItem) {
                $product = Products::find($txItem['product_id']);

                // Simpan transaction item
                TransactionItem::create([
                    'unit' => $txItem['unit'],
                    'quantity' => $txItem['quantity'],
                    'tax_amount' => $txItem['tax_amount'],
                    'amount' => $txItem['amount'],
                    'total_price' => $txItem['total_price'],
                    'discount_1' => $txItem['discount_1'],
                    'discount_2' => $txItem['discount_2'],
                    'discount_3' => $txItem['discount_3'],
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, // Menyimpan product_id dari produk yang ditemukan atau baru
                ]);
            }
        });

        return redirect()->route('warehouse.travel-document')->with('success', 'Surat jalan berhasil dibuat!');
    }

    public function generateTravelDocument(Transactions $transactions)
    {
        // Memuat relasi yang diperlukan dan memfilter product journals dengan action 'IN'
        $transactions
            ->load([
                'transactionDetails',
                'transactionItems.product.productJournals' => function ($query) {
                    $query->where('action', 'IN');
                },
            ]);

        // Mengambil detail berdasarkan kategori
        $customer = $transactions->transactionDetails->firstWhere('category', "Customer")->value ?? null;
        $customer_address = $transactions->transactionDetails->firstWhere('category', "Customer Address")->value ?? null;
        $number_plate = $transactions->transactionDetails->firstWhere('category', "Number Plate")->value ?? null;
        $travel_document_date = Carbon::parse($transactions->transactionDetails->firstWhere('category', 'Travel Document Date')->value)->format('d F Y');
        $warehouse = $transactions->transactionDetails->firstWhere('category', 'Warehouse')->value ?? '';

        // Data yang akan dikirimkan ke view PDF
        $data = [
            'travel_document' => $transactions, // document_code, produk
            'customer' => $customer, // nama customer
            'customer_address' => $customer_address,
            'number_plate' => $number_plate,
            'travel_document_date' => $travel_document_date,
            'warehouse' => $warehouse,
        ];

        $pdf = Pdf::loadView('documents.travel-document', $data);

        return $pdf->stream('travel_document_'.rand(10000, 90000).'.pdf');
    }

    
}
