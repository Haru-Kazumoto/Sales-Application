<?php

namespace App\Http\Controllers;

use App\Http\Services\PurchaseOrderService;
use App\Models\Lookup;
use App\Models\PurchaseOrderProduct;
use App\Models\PurchaseOrder;
use App\Models\StoreHouse;
use App\Models\SubSalesOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseOrderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $purchase_orders = PurchaseOrder::with('purchaseOrderProducts')->get();

        return Inertia::render('Procurement/Purchase/ListPurchaseOrders', compact('purchase_orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $payment_terms = Lookup::where('category', 'PAYMENT_TERM')->get();
        $store_locations = Lookup::where('category', 'STORE_LOCATION')->get();

        return Inertia::render('Procurement/Purchase/CreatePurchaseOrder', [
            'po_number' => 'PO-'.rand(100000, 6666666),
            'storehouses' => StoreHouse::all(),
            'payment_terms' => $payment_terms,
            'store_locations' => $store_locations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());
        
        $request->validate([
            'purchase_order_number' => 'required|string|unique:purchase_orders,purchase_order_number',
            'supplier' => 'required|string',
            'storehouse' => 'required|string',
            'located' => 'required|string',
            'purchase_order_date' => 'required|date',
            'send_date' => 'required|date',
            'payment_term' => 'required|string',
            'due_date' => 'required|date',
            'transportation' => 'required|string',
            'sender' => 'required|string',
            'delivery_type' => 'required|string',
            'employee_name' => 'required|string',
            'notes' => 'string',
            'sub_total' => 'required|numeric', // Digunakan untuk angka desimal atau integer
            'total_price' => 'required|numeric', // Digunakan untuk angka desimal atau integer
            'total_ppn' => 'required|numeric',
            'purchase_order_products' => 'required|array',
            'purchase_order_products.*.product_code' => 'required|string',
            'purchase_order_products.*.product_name' => 'required|string',
            'purchase_order_products.*.amount' => 'required|numeric',
            'purchase_order_products.*.package' => 'required|string',
            'purchase_order_products.*.product_price' => 'required|numeric',
            'purchase_order_products.*.ppn' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $dataPO = PurchaseOrder::create([
                'purchase_order_number' => $request->input('purchase_order_number'),
                'supplier' => $request->input('supplier'),
                'storehouse' => $request->input('storehouse'),
                'located' => $request->input('located'),
                'purchase_order_date' => $request->input('purchase_order_date'),
                'send_date' => $request->input('send_date'),
                'payment_term' => $request->input('payment_term'),
                'due_date' => $request->input('due_date'),
                'transportation' => $request->input('transportation'),
                'sender' => $request->input('sender'),
                'delivery_type' => $request->input('delivery_type'),
                'employee_name' => $request->input('employee_name'),
                'notes' => $request->input('notes'),
                'sub_total' => $request->input('sub_total'),
                'total_price' => $request->input('total_price'),
                'total_ppn' => $request->input('total_ppn')
            ]);

            foreach($request->input('purchase_order_products') as $product) {
                PurchaseOrderProduct::create([
                    'product_code' => $product['product_code'],
                    'product_name' => $product['product_name'],
                    'amount' => $product['amount'],
                    'package' => $product['package'],
                    'product_price' => $product['product_price'],
                    'total_price' => $product['total_price'],
                    'ppn' => $product['ppn'],
                    'purchase_order_id' => $dataPO->id
                ]);
            }

            DB::commit();
            
            return redirect()->back()->with('success', 'Purchase Order Berhasil Tersubmit!');
        } catch(\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Error: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('purchaseOrderProducts');
        
        return Inertia::render('Procurement/Purchase/PurchaseOrderDetail', compact('purchaseOrder'));
    }

     /**
     * Get all products data from PO number
     */
    public function getProductsByPoNumber(string $purchase_order_number)
    {      
        $purchaseOrder = PurchaseOrder::with('purchaseOrderProducts')
            ->where('purchase_order_number', $purchase_order_number)
            ->first();

        if(!$purchaseOrder) 
        {
            return redirect()->back()->with('failed', 'Nomor PO salah atau tidak ditemukan');
        }

        $isPurchaseOrderHasUsed = SubSalesOrder::where('purchase_order_number', $purchaseOrder->purchase_order_number)
            ->exists();

        if($isPurchaseOrderHasUsed) 
        {
            return redirect()->back()->with('failed', 'Nomor PO telah digunakan!');
        }

        return Inertia::render('Procurement/ItemsReceipt/CreateSalesOrder', compact('purchaseOrder'));
    }

    public function generatePurchaseOrderDocument(PurchaseOrder $purchaseOrder) 
    {
        $purchaseOrder->load('purchaseOrderProducts');

        $data = [
            'purchase_order' => $purchaseOrder,
        ];
        
        $pdf = Pdf::loadView('documents.purchase-order-document', $data);

        //set to true after generated
        $purchaseOrder->update([
            'isDocumentHasGenerated' => true,
        ]);
        
        return $pdf->stream('purchase_order_'.rand(100000,900000).'_.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }
}
