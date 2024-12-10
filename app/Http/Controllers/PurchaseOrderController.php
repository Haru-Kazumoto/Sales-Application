<?php

namespace App\Http\Controllers;

use App\Http\Services\LookupServices;
use App\Http\Services\PartiesServices;
use App\Http\Services\PurchaseOrderServices;
use App\Http\Services\TransactionServices;
use App\Models\Lookup;
use App\Models\Parties;
use App\Models\Products;
use App\Models\StoreHouse;
use App\Models\Tax;
use App\Models\TradePromo;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use App\Utils\DocumentNumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseOrderController extends Controller
{

    protected $transactionService;
    protected $purchaseOrderService;
    protected $partiesService;
    protected $lookupService;

    public function __construct(
        TransactionServices $transactionService, 
        PurchaseOrderServices $purchaseOrderServices, 
        PartiesServices $partiesServices,
        LookupServices $lookupServices
    ) 
    {
        $this->transactionService = $transactionService;
        $this->purchaseOrderService = $purchaseOrderServices;
        $this->partiesService = $partiesServices;
        $this->lookupService = $lookupServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $tx_type = TransactionType::where('name', 'Purchase Order')->first();

        $transactions = $this->transactionService->getTransactions(
            $tx_type->id, 
            $request->filter_field,
            $request->filter_query,
            10,
            $request->dateRange
        );

        return Inertia::render('Procurement/Purchase/ListPurchaseOrders', compact('transactions'));
    }

    function generatePONumber()
    {
        // Ambil tahun dan bulan sekarang
        $yearMonth = date('ym'); // '2411' untuk November 2024
        
        // Cari nomor PO terakhir yang dimulai dengan "PO-2411-"
        $lastPONumber = DB::table('transactions')
                        ->where('document_code', 'like', 'PO-'.$yearMonth.'-%')
                        ->orderByDesc('document_code')
                        ->first();

        // Jika tidak ada nomor PO sebelumnya, mulai dari 1
        $nextIncrement = 1;
        if ($lastPONumber) {
            // Ambil bagian angka increment dari nomor PO terakhir
            $lastIncrement = substr($lastPONumber->document_code, -6);
            $nextIncrement = (int) $lastIncrement + 1;
        }

        // Format nomor urut dengan padding 6 digit
        $formattedIncrement = str_pad($nextIncrement, 6, '0', STR_PAD_LEFT);
        
        // Gabungkan format PO yang lengkap
        $poNumber = 'PO-'.$yearMonth.'-'.$formattedIncrement;
        
        return $poNumber;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $payment_terms = $this->lookupService->getAllLookupBy('category', 'PAYMENT_TERM');
        $store_locations = $this->lookupService->getAllLookupBy('category', 'STORE_LOCATION');
        $suppliers = Parties::where('type_parties', 'VENDOR')->get();
        $products = Products::all();
        $units = $this->lookupService->getAllLookupBy('category', 'UNIT');
        $transports = $this->partiesService->getPartiesByGroupAndType('VENDOR', 'Angkutan');
        $tax = Tax::all();
        $po_number = DocumentNumberGenerator::generate(
            'PO-',
            'transactions',
            'document_code',
            4
        );
        $trade_promos = TradePromo::where('is_active', true)->get();

        return Inertia::render('Procurement/Purchase/CreatePurchaseOrder', [
            'po_number' => $po_number,
            'storehouses' => StoreHouse::all(),
            'payment_terms' => $payment_terms,
            'store_locations' => $store_locations,
            'tax' => $tax,
            'products' => $products,
            'units' => $units,
            'suppliers' => $suppliers,
            'transports' => $transports,
            'trade_promos' => $trade_promos,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'document_code' => 'required|string',
            'term_of_payment' => 'required|numeric',
            'due_date' => 'required',
            'description' => 'nullable|string',
            'sub_total' => 'required|numeric', 
            'total' => 'required|numeric', 
            'tax_amount' => 'nullable|numeric', 
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
            'transaction_items.*.tax_id' => 'nullable|numeric', // not used
            'transaction_items.*.product_id' => 'required|numeric',
            'transaction_items.*.total_price' => 'required|numeric',
            'transaction_items.*.product' => 'required_if:transaction_items.*.product_id,null|array',
            'transaction_items.*.product.code' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.unit' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.name' => 'required_with:transaction_items.*.product|string',
        ]);

        // Gunakan transaksi database
        DB::transaction(function () use ($request) {
            $tx_type = TransactionType::where('name', 'Purchase Order')->first();

            // Simpan transaksi
            $transaction = Transactions::create([
                'document_code' => $request->input('document_code'),
                'correlation_id' => rand(10000,88888),
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
                // // Pastikan data memiliki field yang diperlukan
                // if (isset($txItem['trade_promo_id']) && !is_null($txItem['trade_promo_id'])) {
                //     $tradePromoId = $txItem['trade_promo_id'];
                //     $tradePromo = TradePromo::find($tradePromoId);
            
                //     // Pastikan promo ditemukan
                //     if (!$tradePromo) {
                //         return back()->with('failed', 'Promo tidak ditemukan');
                //     }
            
                //     // Cek kuota promo
                //     // disini kondisinya masuk dan sudah di dd kan hasilnya true, harusnya berhenti dan mengembalikan flash namun masih jalan
                //     if ($tradePromo->quota < $txItem['quantity']) {
                //         return back()->with('failed', 'Kuota promo tidak cukup');
                //     }
            
                //     // Kurangi kuota promo
                //     $tradePromo->quota -= $txItem['quantity'];
                //     $tradePromo->save();
                // }
            
                // Validasi produk
                if (!isset($txItem['product_id'])) {
                    return back()->with('failed', 'Produk tidak valid');
                }
            
                $product = Products::find($txItem['product_id']);
                if (!$product) {
                    return back()->with('failed', 'Produk tidak ditemukan');
                }
            
                // Simpan transaction item
                TransactionItem::create([
                    'total_price' => $txItem['total_price'] ,
                    'unit' => $txItem['unit'] ,
                    'quantity' => $txItem['quantity'] ,
                    'tax_amount' => $txItem['tax_amount'] ?? 0,
                    'amount' => $txItem['amount'] ,
                    'tax_id' => $txItem['tax_id'] ?? null,
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id,
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
        $transaction->load(['transactionDetails', 'transactionItems.product','transactionItems.tax']);

        // Mengembalikan view dengan data transaksi
        return Inertia::render('Procurement/Purchase/PurchaseOrderDetail', compact('transaction'));
    }

    public function getDataByPoNumber(string $po_number)
    {
        // Mengambil transaksi berdasarkan transaction_id dari transaction detail yang ditemukan
        $transaction = Transactions::with(['transactionDetails', 'transactionItems.product','transactionItems.tax'])
            ->where('document_code', $po_number) // Pastikan menggunakan field yang benar
            ->first();

        // Memeriksa apakah transaksi ada
        if (!$transaction) 
        {
            return redirect()->back()->with('failed', 'Nomor transaksi salah atau tidak ditemukan');
        }

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
     * Index all Purchase Order with number plate is not set yet
     * 
     * @return void
     */
    public function indexNewPO(Request $request): Response
    {
        // Dapatkan semua filter dari request
        $filterRangeDate = $request->get('range_date', null);  // Default null jika tidak ada
        $filterField = $request->get('filter_field', null);
        $filterQuery = $request->get('filter_query', null);
        $orderBy = $request->get('order_by', 'asc');
        $paginate = $request->get('paginate', 10);  // Default paginate 10 item

        // Panggil service method dengan parameter dari request
        $purchase_orders = $this->purchaseOrderService->getAllPurchaseOrderWithNumberPlateIsNull(
            $filterRangeDate, 
            $filterField, 
            $filterQuery, 
            $orderBy, 
            10
        );

        // dd($purchase_orders);

        return Inertia::render('Procurement/Purchase/SetNumberPlate', compact('purchase_orders'));
    }

    public function updateNumberPlate(Request $request) 
    {
        $request->validate([
            'transactions_id' => "required|exists:transactions,id",
            'number_plate' => 'required|string',
        ]); 


        $transactionDetail = TransactionDetail::where('transactions_id', $request->transactions_id)
            ->where('category', 'Transportation')
            ->first();

        DB::transaction(function() use ($transactionDetail, $request) {
            $transactionDetail->update([
                'value' => $request->number_plate,
            ]);
        });

        return back()->with('success', 'Nomor polisi berhasil di tambahkan!');
    }
}

