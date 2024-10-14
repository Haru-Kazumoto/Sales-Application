<?php

namespace App\Http\Controllers;

use App\Http\Services\CustomerOrderServices;
use App\Models\InvoicePayment;
use App\Models\Lookup;
use App\Models\Products;
use App\Models\Tax;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{

    private $customerOrderServices;

    public function __construct(CustomerOrderServices $customerOrderServices)
    {
        $this->customerOrderServices = $customerOrderServices;
    }

    /**
     * Index all invoices
     */
    public function indexInvoices()
    {
        return Inertia::render('AgingFinance/Sales/ListInvoice');
    }

    public function listDnpInvoice(): Response
    {
        $travel_documents_dnp = $this->customerOrderServices->getTransactions("Surat Jalan",15,"Warehouse","DNP");

        return Inertia::render('AgingFinance/Sales/InvoiceDNP', compact('travel_documents_dnp'));
    }

    public function listDkuInvoice(): Response
    {
        $travel_documents_dku = $this->customerOrderServices->getTransactions("Surat Jalan",15,"Warehouse","DKU");

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
        $transactions->load('transactionType', 'transactionDetails', 'transactionItems.product');
        $invoice_number = rand(1000,9999).'-'.rand(1000,9999);
        $payment_terms = Lookup::where('category', 'PAYMENT_TERM')->get();
        $taxes = Tax::all();

        return Inertia::render('AgingFinance/Sales/CreateInvoiceDNP', compact('transactions', 'invoice_number','payment_terms', 'taxes'));
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
            'term_of_payment' => 'required|string',
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
            $tx_type = TransactionType::where('name', 'Pembelian')->first();

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
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, 
                ]);

                $invoice_date = TransactionDetail::where('category', 'Invoice Date')->first();
        
            }

            //create invoice journal
            InvoicePayment::create([
                'invoice_number' => $request->input('document_code'),
                'invoice_date' => $invoice_date->value,
                'total_bill' => $request->input('total'),
                'total_paid' => 0,
                'action' => 'IN',
                'transaction_id' => $transaction->id,
            ]);

        });
        
        return redirect()->route('aging-finance.list-invoice')->with('success', 'Faktur berhasil dibuat!');
    }

}
