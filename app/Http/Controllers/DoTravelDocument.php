<?php

namespace App\Http\Controllers;

use App\Http\Services\PartiesServices;
use App\Models\Products;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DoTravelDocument extends Controller
{
    protected $partiesService;

    public function __construct(PartiesServices $partiesServices)
    {
        $this->partiesService = $partiesServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tx_type = TransactionType::where('name', 'Surat Jalan')->first();

        //DNP
        $travel_documents_dnp = Transactions::query()
            ->whereHas('transactionDetails', function ($query) {
                $query->where('category', 'Shipping Warehouse')
                    ->whereIn('value', ['DIRECT', 'DIRECT_DEPO', 'DO']);
            })
            ->whereHas('transactionDetails', function ($query) {
                $query->where('category', 'Warehouse')
                    ->where('value', 'DNP');
            })
            ->with('transactionDetails', 'transactionItems.product')
            ->where('transaction_type_id', $tx_type->id)
            ->orderByDesc('created_at')
            ->get();

        $travel_documents_dku = Transactions::query()
            ->whereHas('transactionDetails', function ($query) {
                $query->where('category', 'Shipping Warehouse')
                    ->whereIn('value', ['DIRECT', 'DIRECT_DEPO', 'DO']);
            })
            ->whereHas('transactionDetails', function ($query) {
                $query->where('category', 'Warehouse')
                    ->where('value', 'DKU');
            })
            ->with('transactionDetails', 'transactionItems.product')
            ->where('transaction_type_id', $tx_type->id)
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Procurement/DoTravelDocument/IndexDoTravelDocument', compact('travel_documents_dnp','travel_documents_dku'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Query untuk transaksi dengan Delivery kategori DNP
        $dnp_data = Transactions::query()
            ->whereHas('transactionDetails', function ($query) {
                $query->where('category', 'Delivery')
                    ->whereIn('value', ['DIRECT', 'DIRECT_DEPO', 'DO']);
            })
            ->whereHas('transactionDetails', function ($query) {
                $query->where('category', 'Warehouse')
                    ->where('value', 'DNP');
            })
            ->whereHas('transactionDetails', function($query) {
                $query->where('category','Generating')->where('value', 'false');
            })
            ->with([
                'transactionDetails',
                'transactionItems.product',
            ])
            ->get();
        // dd($dnp_data->toArray());

        // Query untuk transaksi dengan Delivery kategori DKU
        $dku_data = Transactions::query()
            ->whereHas('transactionDetails', function ($query) {
                $query->where('category', 'Delivery')
                    ->whereIn('value', ['DIRECT', 'DIRECT_DEPO', 'DO']);
            })
            ->whereHas('transactionDetails', function ($query) {
                $query->where('category', 'Warehouse')
                    ->where('value', 'DKU');
            })
            ->whereHas('transactionDetails', function($query) {
                $query->where('category','Generating')->where('value', 'false');
            })
            ->with([
                'transactionDetails',
                'transactionItems.product',
            ])
            ->get();

        return Inertia::render('Procurement/DoTravelDocument/ListDoTravelDocument', compact('dnp_data', 'dku_data'));
    }

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


        return Inertia::render('Procurement/DoTravelDocument/CreateDoTravelDocument', compact('transactions','transports'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

            // Tangani file upload
            $filePath = null;
            if ($request->hasFile('file_attachment')) {
                $file = $request->file('file_attachment');
                $filePath = $file->store('attachments_sj', 'public'); // Simpan ke folder 'attachments' di disk 'public'
            }

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
                'file_attachment' => $filePath,
            ]);

            $tx_details = $request->transaction_details;
            $tx = Transactions::where('document_code', $tx_details[12]['value'])->first();
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

        return redirect()->route('procurement.do.create')->with('success', 'Surat jalan berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transactions $transactions)
    {
        $transactions->load([
            'transactionDetails',
            'transactionItems.product'
        ]);
        $transactions->file_attachment = $transactions->file_attachment 
            ? asset('storage/' . $transactions->file_attachment) 
            : null;

        return Inertia::render('Procurement/DoTravelDocument/DetailTravelDocument', [
            'travel_document' => $transactions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
