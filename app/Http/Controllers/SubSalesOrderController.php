<?php

namespace App\Http\Controllers;

use App\Http\Services\PurchaseOrderService;
use App\Models\PurchaseOrder;
use App\Models\SubSalesOrder;
use App\Models\SubSalesOrderProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SubSalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sub_sales_orders = SubSalesOrder::with('subSalesOrderProducts')->get();

        return Inertia::render("Procurement/ItemsReceipt/ListSalesOrders", compact('sub_sales_orders'));
    }

   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Procurement/ItemsReceipt/CreateSalesOrder');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'purchase_order_number' => 'required|string',
            'proof_number' => 'required|string',
            'sales_order_number' => 'required|string',
            'order_date' => 'required|string',
            'located' => 'required|string',
            'supplier' => 'required|string',
            'storehouse' => 'required|string',
            'send_date' => 'required|string',
            'transportation' => 'required|string',
            'sender' => 'required|string',
            'delivery_type' => 'required|string',
            'employee_name' => 'required|string',
            'sub_total' => 'required|numeric',
            'total_after_ppn' => 'required|numeric',
            'total_price' => 'required|numeric',
            'note' => 'nullable|string',
            'subSalesOrderProducts' => 'required|array',
            'subSalesOrderProducts.*.product_code' => 'required|string',
            'subSalesOrderProducts.*.product_name' => 'required|string',
            'subSalesOrderProducts.*.amount' => 'required|numeric',
            'subSalesOrderProducts.*.package' => 'required|string',
            'subSalesOrderProducts.*.product_price' => 'required|numeric',
            'subSalesOrderProducts.*.ppn' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $dataSso = SubSalesOrder::create([
                'purchase_order_number' => $request->input('purchase_order_number'),
                'proof_number' => $request->input('proof_number'),
                'sales_order_number' => $request->input('sales_order_number'),
                'order_date' => $request->input('order_date'),
                'located' => $request->input('located'),
                'supplier' => $request->input('supplier'),
                'storehouse' => $request->input('storehouse'),
                'send_date' => $request->input('send_date'),
                'transportation' => $request->input('transportation'),
                'sender' => $request->input('sender'),
                'delivery_type' => $request->input('delivery_type'),
                'employee_name' => $request->input('employee_name'),
                'sub_total' => $request->input('sub_total'),
                'total_after_ppn' => $request->input('total_after_ppn'),
                'note' => $request->input('note'),
                'total_price' => $request->input('total_price'),
            ]);

            foreach($request->input('subSalesOrderProducts') as $ssoProduct) {
                SubSalesOrderProduct::create([
                    'product_code' => $ssoProduct['product_code'],
                    'product_name' => $ssoProduct['product_name'],
                    'amount' => $ssoProduct['amount'],
                    'package' => $ssoProduct['package'],
                    'product_price' => $ssoProduct['product_price'],
                    'total_price' => $ssoProduct['total_price'],
                    'ppn' => $ssoProduct['ppn'],
                    'sub_sales_order_id' => $dataSso->id
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Sales order berhasil dibuat');
        } catch(\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('failed', 'Gagal membuat sub sales order'.$e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(SubSalesOrder $subSalesOrder)
    {
        $subSalesOrder->load('subSalesOrderProducts');

        return Inertia::render('Procurement/ItemsReceipt/SubSalesOrderDetail', compact('subSalesOrder'));
    }

    /**
     * Generate document SO to PDF
     */
    public function generateSubSalesOrderDocument(SubSalesOrder $subSalesOrder)
    {
        $subSalesOrder->load('subSalesOrderProducts');

        $pdf = Pdf::loadView('documents.sub-sales-order-document', compact('subSalesOrder'));

        return $pdf->stream('sub_sales_order_'.rand(100000,900000).'_.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubSalesOrder $subSalesOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubSalesOrder $subSalesOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubSalesOrder $subSalesOrder)
    {
        //
    }
}
