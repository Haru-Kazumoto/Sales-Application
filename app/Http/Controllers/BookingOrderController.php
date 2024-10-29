<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductServices;
use App\Http\Services\TransactionServices;
use App\Models\BookingRequest;
use App\Models\Products;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BookingOrderController extends Controller
{

    protected $productServices;
    protected $transactionServices;

    public function __construct(ProductServices $productServices, TransactionServices $transactionServices)
    {
        $this->productServices = $productServices;
        $this->transactionServices = $transactionServices;
    }

    public function indexOrder(Request $request)
    {
        $txType = TransactionType::where('name', 'Booking Order')->first();
        $booking_request_order = Transactions::where('transaction_type_id', $txType->id)
            ->with('transactionDetails','transactionItems.product')
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('Warehouse/BookingItem/BookingRequest', compact('booking_request_order'));
    }

    /**
     * Display a listing of the resource.
     */
    public function indexOrderDnp(Request $request)
    {
        $filter_field = $request->filter_field;
        $filter_query = $request->filter_query;
        $txType = TransactionType::where('name', 'Booking Order')->first();

        $booking_request_products = TransactionItem::whereHas('transaction', function($query) use ($txType) {
                $query->where('transaction_type_id', $txType->id);
            })
            ->with('transaction.transactionDetails', 'product')
            ->paginate(10);
        // dd($booking_request_products);

        return Inertia::render('Sales/BookingItem/DNP/ListBookingOrder', compact('booking_request_products'));
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
    public function showBookingDnp(BookingRequest $bookingRequest)
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
