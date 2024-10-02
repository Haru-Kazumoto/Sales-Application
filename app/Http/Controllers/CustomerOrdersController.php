<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrderProduct;
use App\Models\CustomerOrders;
use App\Models\ProductCustomerOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $coNumber = 'CO/'.rand(000000,999999);
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');

        return Inertia::render('Sales/Sale/CreateCO', compact('coNumber', 'dateNow'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());

        $request->validate([
            'customer_order_number' => 'required|string',
            'order_created' => 'required',
            'customer_name' => 'required|string',
            'legality' => 'required|string',
            'customer_address' => 'required|string',
            'term' => 'required|string',
            'due_date' => 'required|string',
            'salesman' => 'required|string',
            'amount' => 'required|numeric',
            'discount_total' => 'required|numeric',
            'sub_total' => 'required|numeric',
            'total_after_ppn' => 'required|numeric',
            'total' => 'required|numeric',
            'status_co' => 'required|string',
            'productCustomerOrders' => 'required|array',
            'productCustomerOrders.*.product_name' => 'required|string',
            'productCustomerOrders.*.quantity' => 'required|numeric',
            'productCustomerOrders.*.package' => 'required|string',
            'productCustomerOrders.*.price' => 'required|numeric',
            'productCustomerOrders.*.discount_1' => 'nullable|numeric',
            'productCustomerOrders.*.total_price_discount_1' => 'nullable|numeric',
            'productCustomerOrders.*.discount_2' => 'nullable|numeric',
            'productCustomerOrders.*.total_price_discount_2' => 'nullable|numeric',
            'productCustomerOrders.*.discount_3' => 'nullable|numeric',
            'productCustomerOrders.*.total_price_discount_3' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            $dataCO = CustomerOrders::create([
                'customer_order_number' => $request->input('customer_order_number'),
                'order_created' => $request->input('order_created'),
                'customer_name' => $request->input('customer_name'),
                'legality' => $request->input('legality'),
                'customer_address' => $request->input('customer_address'),
                'term' => $request->input('term'),
                'due_date' => $request->input('due_date'),
                'salesman' => $request->input('salesman'),
                'amount' => $request->input('amount'),
                'discount_total' => $request->input('discount_total'),
                'sub_total' => $request->input('sub_total'),
                'total_after_ppn' => $request->input('total_after_ppn'),
                'total' => $request->input('total'),
                'status_co' => $request->input('status_co')
            ]);

            foreach($request->input('productCustomerOrders') as $product) {
                CustomerOrderProduct::create([
                    'product_name' => $product['product_name'],
                    'quantity' => $product['quantity'],
                    'package' => $product['package'],
                    'price' => $product['price'],
                    'discount_1' => $product['discount_1'],
                    'total_price_discount_1' => $product['total_price_discount_1'],
                    'discount_2' => $product['discount_2'],
                    'total_price_discount_2' => $product['total_price_discount_2'],
                    'discount_3' => $product['discount_3'],
                    'total_price_discount_3' => $product['total_price_discount_3'],
                    'customer_order_id' => $dataCO->id,
                ]);
            }

            DB::commit();

            return redirect(route('sales.list-co'))->with('success', 'CO berhasil dibuat');
        } catch(\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('failed', 'Gagal membaut CO: '.$e->getMessage());
        }
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
