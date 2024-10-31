<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductServices;
use App\Http\Services\TransactionServices;
use App\Models\BookingRequest;
use App\Models\ProductJournal;
use App\Models\Products;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use App\Models\Warehouse;
use App\Traits\Filterable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BookingOrderController extends Controller
{
    use Filterable;

    protected $productServices;
    protected $transactionServices;

    public function __construct(ProductServices $productServices, TransactionServices $transactionServices)
    {
        $this->productServices = $productServices;
        $this->transactionServices = $transactionServices;
    }

    /**
     * Get all order for warehouse
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function indexOrder(Request $request)
    {
        $txType = TransactionType::where('name', 'Booking Order')->first();
        $booking_request_order = Transactions::where('transaction_type_id', $txType->id)
            ->with('transactionDetails','transactionItems.product')
            ->orderByDesc('updated_at')
            ->paginate(10);

        return Inertia::render('Warehouse/BookingItem/BookingRequest', compact('booking_request_order'));
    }

    /**
     * Display a listing of the resource.
     */
    public function indexOrderDnp(Request $request)
    {
        $filter_field = 'document_code';
        $filter_query = $request->filter_query;
        $txType = TransactionType::where('name', 'Booking Order')->first();

        $booking_request_products = TransactionItem::whereHas('transaction', function($query) use ($txType, $filter_query) {
            $query->where('transaction_type_id', $txType->id);
            
            // Menambahkan filter berdasarkan document_code jika filter_query ada
            if ($filter_query) {
                $query->where('document_code', 'LIKE', '%' . $filter_query . '%');
            }

            $query->whereHas('transactionDetails', function($query) {
                $query->where('category', 'Warehouse')
                      ->where('value', 'DNP');
            });
        })
            ->with('transaction.transactionDetails', 'product')
            ->orderByRaw("CASE WHEN status_booking = 'APPROVED' THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN status_booking = 'REJECTED' THEN 0 ELSE 2 END")
            ->orderByDesc('updated_at');

        // Menerapkan pagination setelah filtering
        $booking_request_products = $this->applyPagination($booking_request_products, 10, [
            'filter_field' => $filter_field,
            'filter_query' => $filter_query,
        ]);

        return Inertia::render('Sales/BookingItem/DNP/ListBookingOrder', compact('booking_request_products'));
    }

    public function showOrder(Transactions $transactions) 
    {
        $transactions->load('transactionDetails', 'transactionItems.product');
        $txType = TransactionType::where('name', 'Booking Order')->first();

        $booking_request_products = TransactionItem::whereHas('transaction', function($query) use ($txType, $transactions) {
                $query
                    ->where('transaction_type_id', $txType->id)
                    ->where('id', $transactions->id);
            })
            ->with('transaction.transactionDetails', 'product')
            ->get();

        return Inertia::render('Warehouse/BookingItem/DetailBookingRequest', compact('transactions','booking_request_products'));
    }

    /**
     * Approve the booking request status after checking all the products
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Transactions $transaction
     * @return void
     */
    public function approveBookingRequest(Transactions $transaction): RedirectResponse
    {
        $transaction->load('transactionDetails','transactionItems.product');
        $statusTransaction = TransactionDetail::where('transactions_id', $transaction->id)
            ->where('category', 'Check')
            ->first();
        $warehouse_detail = TransactionDetail::where('transactions_id', $transaction->id)
            ->where('category', 'Warehouse')
            ->first();
        $warehouse = Warehouse::where('name', $warehouse_detail->value)->first();

        DB::transaction(function() use ($statusTransaction, $transaction, $warehouse) {
            //Create product journal for product information that has out from warehouse since booked
            foreach($transaction->transactionItems as $items){
                if ($items['status_booking'] === 'APPROVED') {
                    $product = Products::where('id', $items['product_id'])->first();
    
                    // Buat entry ProductJournal
                    ProductJournal::create([
                        'quantity' => $items['quantity'],
                        'amount' => $items['amount'],
                        'action' => 'OUT',
                        'warehouse_id' => $warehouse->id,
                        'transactions_id' => $transaction->id,
                        'product_id' => $product->id,
                    ]);
                }
            }

            //set status of booking request to approved since the product has checked all
            $statusTransaction->update(['value' => 'APPROVED']);
        });

        return redirect()->route('warehouse.booking-request')->with('success', 'Berhasil di approve!');
    }

    /**
     * Set description of rejected products
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Transactions $transactions
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setRejectDescription(Request $request, TransactionItem $transactionItem)
    {
        DB::transaction(function() use ($transactionItem, $request) {
            $transactionItem->update([
                'status_booking' => "REJECTED",
                'reject_description' => $request->value
            ]);
        });
        
        return back()->with('success', 'Berhasil di reject');
    }

    /**
     * Approve booking item
     * 
     * @param \App\Models\Transactions $transactions
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setApprovedStatus(TransactionItem $transactionItem)
    {
        DB::transaction(function() use ($transactionItem) {
            $transactionItem->update([
                'status_booking' => "APPROVED",
            ]);
        });
        
        return back()->with('success', 'Berhasil di approved');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function createBookingDnp()
    {
        $products = $this->productServices->getStockProducts("DNP");
        $booking_number = rand(100,999).'-'.rand(100,999);

        return Inertia::render('Sales/BookingItem/DNP/CreateBookingOrder', compact('products','booking_number'));
    }

    /**
     * Store a newly created resource in storage.
    */
    public function storeBookingDnp(Request $request)
    {
        // dd($request->all());
        $dataValidated = $request->validate([
            'document_code' => 'required|string',
            'total' => 'required|numeric',
            'products' => 'required|array',
            'products.*.unit' => "required|string",
            'products.*.quantity' => "required|numeric",
            'products.*.total' => "required|numeric",
            'products.*.product_id' => 'required|numeric',
            'transaction_details' => 'required|array',
            'transaction_details.*.name' => 'required|string',
            'transaction_details.*.category' => 'required|string',
            'transaction_details.*.value' => 'required|string',
            'transaction_details.*.data_type' => 'required|string',
        ]);

        DB::transaction(function() use ($dataValidated) {
            $txType = TransactionType::where('name', 'Booking Order')->first();
            
            $transaction = Transactions::create([
                'document_code' => $dataValidated['document_code'],
                'sub_total' => $dataValidated['total'],
                'correlation_id' => rand(10000,99999),
                'transaction_type_id' => $txType->id,
            ]);

            foreach ($dataValidated['products'] as $txItem){
                $product = Products::where('id', $txItem['product_id'])->first();

                TransactionItem::create([
                    'unit' => $txItem['unit'],
                    'quantity' => intval($txItem['quantity']),
                    'amount' => $txItem['total'],
                    'product_id' => $product->id,
                    'transactions_id' => $transaction->id,
                ]);
            }

            foreach ($dataValidated['transaction_details'] as $detail) {
                TransactionDetail::create([
                    'name' => $detail['name'],
                    'category' => $detail['category'],
                    'value' => $detail['value'],
                    'data_type' => $detail['data_type'],
                    'transactions_id' => $transaction->id,
                ]);
            }
        });

        return redirect()->route('sales.booking-item.index-booking-dnp')->with('success', 'Booking order berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function showBookingDnp(Transactions $transactions)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editBookingDnp(BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateBookingDnp(Request $request, BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyBookingDnp(BookingRequest $bookingRequest)
    {
        //
    }
}
