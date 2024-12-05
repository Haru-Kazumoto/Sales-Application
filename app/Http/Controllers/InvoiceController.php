<?php

namespace App\Http\Controllers;

use App\Exports\InvoiceExport;
use App\Http\Services\CustomerOrderServices;
use App\Http\Services\TransactionServices;
use App\Models\InvoicePayment;
use App\Models\Lookup;
use App\Models\Products;
use App\Models\Tax;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use App\Utils\DocumentNumberGenerator;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{

    private $customerOrderServices;
    private $transactionServices;

    public function __construct(CustomerOrderServices $customerOrderServices, TransactionServices $transactionsServices)
    {
        $this->customerOrderServices = $customerOrderServices;
        $this->transactionServices = $transactionsServices;
    }

    /**
     * Index all invoices
     */
    public function indexInvoices()
    {
        $tx_type = TransactionType::where('name', 'Penjualan')->first();
        $invoices = $this->transactionServices->getTransactions($tx_type->id, null,null,10, true);

        return Inertia::render('AgingFinance/Sales/ListInvoice', compact('invoices'));
    }

    public function listDnpInvoice(): Response
    {
        $travel_documents_dnp = $this->customerOrderServices->getTransactions("Surat Jalan","false",15,"Warehouse","DNP");

        return Inertia::render('AgingFinance/Sales/InvoiceDNP', compact('travel_documents_dnp'));
    }

    public function listDkuInvoice(): Response
    {
        $travel_documents_dku = $this->customerOrderServices->getTransactions("Surat Jalan","false",15,"Warehouse","DKU");

        return Inertia::render('AgingFinance/Sales/InvoiceDKU', compact('travel_documents_dku'));
    }

    /**
     * Show create invoice form
     * 
     * @param \App\Models\Transactions $transaction
     * @return void
     */
    public function createInvoice(Transactions $transactions): Response
    {
        // Load relasi yang diperlukan
        $transactions->load('transactionType', 'transactionDetails', 'transactionItems.product');

        $invoice_number = DocumentNumberGenerator::generate(
            '',
            'transactions',
            'document_code',
            6
        );

        // Data tambahan
        $payment_terms = Lookup::where('category', 'PAYMENT_TERM')->get();
        $taxes = Tax::all();

        // Render Inertia dengan data
        return Inertia::render('AgingFinance/Sales/CreateInvoiceDNP', compact('transactions', 'invoice_number', 'payment_terms', 'taxes'));
    }


    /**
     * Store  a newly created resource in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @return never
     */
    public function storeInvoice(Request $request): RedirectResponse
    {
        // dd($request->all());

        $request->validate([
            'document_code' => 'required|string',
            'term_of_payment' => 'required|numeric',
            'due_date' => 'required',
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
            'transaction_items.*.amount' => 'nullable|numeric',
            'transaction_items.*.tax_id' => 'required|numeric',
            'transaction_items.*.product_id' => 'required|numeric',
            'transaction_items.*.product' => 'required_if:transaction_items.*.product_id,null|array',
            'transaction_items.*.product.code' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.unit' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.name' => 'required_with:transaction_items.*.product|string',
        ]);

        DB::transaction(function() use ($request) {
            $tx_type = TransactionType::where('name', 'Penjualan')->first();

            $transaction = Transactions::create([
                'document_code' => $request->input('document_code'),
                'correlation_id' => rand(10000,88888),
                'created_by' => Auth::user()->id,
                'term_of_payment' => $request->input('term_of_payment'),
                'due_date' => $request->input('due_date'),
                'sub_total' => $request->input('sub_total'), //required
                'total' => $request->input('total'), //required
                'tax_amount' => $request->input('tax_amount'), //required
                'transaction_type_id' => $tx_type->id,
            ]);

            $tx_details = $request->transaction_details;
            $tx = Transactions::where('document_code', $tx_details[1]['value'])->first();
            if($tx){
                $status = TransactionDetail::where('transactions_id', $tx->id)
                    ->where('category', 'Generating')
                    ->first();
                $status->update(['value' => 'true']);
            }

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
                    'unit' => $txItem['unit'],
                    'quantity' => $txItem['quantity'],
                    'amount' => $txItem['amount'],
                    'tax_id' => $txItem['tax_id'],
                    'total_price' => $txItem['total_price'],
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, 
                ]);
            }
        });
        
        return redirect()->route('aging-finance.list-invoice')->with('success', 'Faktur berhasil dibuat!');
    }

    public function showDetailInvoice(Transactions $transactions)
    {
        $data = $transactions->load([
            'transactionDetails',
            'transactionItems.product',
            'transactionItems.tax',
            'invoicePayments' => function ($query) use ($transactions) {
                $query->select(
                    'id',
                    'transaction_id',
                    'total_paid',
                    'invoice_date',
                    'payment_method',
                    DB::raw("({$transactions->total} - SUM(total_paid)) AS sisa_pembayaran"),
                    DB::raw("
                        (CASE 
                            WHEN (SELECT COUNT(*) FROM invoice_payment WHERE transaction_id = {$transactions->id}) = 1 
                                AND ({$transactions->total} - SUM(total_paid)) = 0 THEN 'FULLY PAID'
                            WHEN ({$transactions->total} - SUM(total_paid)) > 0 THEN 'INSTALMENT'
                            ELSE 'PAID' 
                        END) AS status_payment
                    ")
                )->groupBy('id', 'transaction_id', 'total_paid', 'invoice_date');
            }
        ]); 

        $totalTagihan = $transactions->total;
        $totalPaid = $transactions->invoicePayments->sum('total_paid');
        $totalLeft = $totalTagihan - $totalPaid;
        
        // Cek jumlah pembayaran yang dilakukan
        $paymentCount = $transactions->invoicePayments->count();

        if ($paymentCount === 1 && $totalLeft === 0) {
            $statusPayment = 'FULLY PAID';
        } elseif ($totalLeft > 0) {
            $statusPayment = 'INSTALMENT';
        } else {
            $statusPayment = 'PAID';
        }

        return Inertia::render('AgingFinance/Sales/DetailInvoice', compact(
            'data',
            'totalTagihan',
            'totalPaid',
            'totalLeft',
            'statusPayment',
        ));
    }

    public function invoicePayment(Transactions $transactions, Request $request)
    {
        DB::transaction(function() use ($request, $transactions){

            InvoicePayment::create([
                'invoice_number' => $transactions->document_code,
                'invoice_date' => $request->invoice_date,
                'total_paid' => $request->total_paid,
                'total_bill' => $transactions->total,
                'action' => "IN",
                'transaction_id' => $transactions->id,
                'payment_method' => $request->payment_method,
            ]);
        });

        return back()->with('success', 'Pembayaran berhasil!');
    }

    public function indexAging()
    {
        $tx_type = TransactionType::where('name', 'Penjualan')->first();

        // Ambil parameter filter `status` dari request
        $statusFilter = request('status');

        $invoices = Transactions::with([
            'transactionDetails',
            'transactionItems.product',
        ])
        ->where('transaction_type_id', $tx_type->id)
        ->selectRaw('transactions.*, 
                (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) as total_left, 
                CASE 
                    WHEN (SELECT COUNT(*) FROM invoice_payment WHERE transaction_id = transactions.id) = 1 
                        AND (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) = 0 THEN "FULLY PAID"
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) = total THEN "UNPAID"
                    WHEN due_date <= CURDATE() THEN "OVERDUE"
                    WHEN (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) > 0 THEN "INSTALMENT" 
                    ELSE "PAID" 
                END as status_payment'
            )
        // Tambahkan filter by status jika parameter `status` ada
        ->when($statusFilter, function ($query) use ($statusFilter) {
            $query->having('status_payment', $statusFilter); // Filter berdasarkan status
        })
        ->orderByRaw("
                CASE 
                    WHEN EXISTS (
                        SELECT 1 
                        FROM transaction_details 
                        WHERE transaction_details.transactions_id = transactions.id 
                        AND transaction_details.category = 'Transportation' 
                        AND transaction_details.value = '-'
                    ) THEN 0 
                    ELSE 1 
                END
        ")
        ->orderByDesc('created_at')
        ->paginate(10);

        return Inertia::render('AgingFinance/Transaction/Aging', compact('invoices'));
    }


    public function exportInvoice()
    {
        return Excel::download(new InvoiceExport(), 'invoice_data_'.date('d_F_Y').'.xlsx');
    }
}
