<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductServices;
use App\JournalAction;
use App\Models\Lookup;
use App\Models\Parties;
use App\Models\ProductJournal;
use App\Models\Products;
use App\Models\ProductType;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductsController extends Controller
{
    private $productServices;

    public function __construct(ProductServices $productServices)
    {
        $this->productServices = $productServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createProduct(Request $request)
    {
        $query = Products::with(['productType','parties']);

         // Filter berdasarkan field dan query yang diterima dari request
        if($request->filled('filter_field') && $request->filled('filter_query')) {
            $field = $request->input('filter_field'); // Field yang dipilih (nama, tipe, nomor telepon)
            $value = $request->input('filter_query'); // Nilai filter

            // Tambahkan where dengan like untuk pencarian berdasarkan field yang dipilih
            if($field === "supplier") {
                $query->whereHas('parties', function ($q) use ($value) {
                    $q->where('name', 'like', '%' . $value . '%'); // Menggunakan relasi parties
                });
            } else {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        // Urutkan berdasarkan created_at dan paginasi data
        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        // Pastikan total item sesuai dengan hasil yang difilter
        $products->appends($request->only('filter_field', 'filter_query'));

        $product_type = ProductType::all();
        $units = Lookup::where('category', 'UNIT')->get();
        $suppliers = Parties::with('partiesGroup')->where('type_parties', "VENDOR")->get();

        return Inertia::render('Admin/Products', compact(
            'products',
            'product_type',
            'units',
            'suppliers'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validasi input
        $request->validate([
            'code' => 'required|string',
            'unit' => 'required|string',
            'name' => 'required|string',
            'category' => 'required|string',
            'redemp_price' => 'nullable|numeric',
            'retail_price' => 'nullable|numeric',
            'restaurant_price' => 'nullable|numeric',
            'price_3' => 'nullable|numeric',
            'dd_price' => 'nullable|numeric',
            'normal_margin' => 'nullable|numeric',
            'oh_depo' => 'nullable|numeric',
            'saving' => 'nullable|numeric',
            'bad_debt_dd' => 'nullable|numeric',
            'supplier_id' => 'nullable|numeric',
            'saving_marketing' => 'nullable|numeric',
            'product_type_id' => 'numeric|required',
        ]);

        
        // Menyimpan data ke tabel products
        DB::transaction(function() use ($request) {
            $product_type = ProductType::where('id', $request->input('product_type_id'))->first();
            $supplier = Parties::where('id', $request->input('supplier_id'))->first();

            $product = new Products();
            $product->code = $request->input('code');
            $product->unit = $request->input('unit');
            $product->name = $request->input('name');
            $product->category = $request->input('category');
            $product->redemp_price = $request->input('redemp_price');
            $product->retail_price = $request->input('retail_price');
            $product->restaurant_price = $request->input('restaurant_price');
            $product->price_3 = $request->input('price_3');
            $product->dd_price = $request->input('dd_price');
            $product->normal_margin = $request->input('normal_margin');
            $product->oh_depo = $request->input('oh_depo');
            $product->saving = $request->input('saving');
            $product->supplier_id = $supplier->id;
            $product->bad_debt_dd = $request->input('bad_debt_dd');
            $product->saving_marketing = $request->input('saving_marketing');
            $product->product_type_id = $product_type->id;

            // Simpan produk
            $product->save();
        });

        // Redirect dengan pesan sukses
        return redirect()->route('admin.products')->with('success', 'Product berhasil dibuat!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $product)
    {
        $product_type = ProductType::all();
        $units = Lookup::where('category', 'UNIT')->get();

        return Inertia::render('Admin/ProductsEdit', compact(
            'product_type',
            'units',
            'product'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $product)
    {
        // Validasi input
        $request->validate([
            'code' => 'required|string',
            'unit' => 'required|string',
            'name' => 'required|string',
            'category' => 'required|string',
            'redemp_price' => 'nullable|numeric',
            'retail_price' => 'nullable|numeric',
            'restaurant_price' => 'nullable|numeric',
            'price_3' => 'nullable|numeric',
            'dd_price' => 'nullable|numeric',
            'normal_margin' => 'nullable|numeric',
            'oh_depo' => 'nullable|numeric',
            'saving' => 'nullable|numeric',
            'bad_debt_dd' => 'nullable|numeric',
            'saving_marketing' => 'nullable|numeric',
            'product_type_id' => 'numeric|required',
        ]);

        // Lakukan update data
        DB::transaction(function() use ($request, $product) {
            $product_type = ProductType::where('id', $request->input('product_type_id'))->first();

            // Simpan perubahan
            $product->update([
                'code' => $request->input('code'),
                'unit' => $request->input('unit'),
                'name' => $request->input('name'),
                'category' => $request->input('category'),
                'redemp_price' => $request->input('redemp_price'),
                'retail_price' => $request->input('retail_price'),
                'restaurant_price' => $request->input('restaurant_price'),
                'price_3' => $request->input('price_3'),
                'dd_price' => $request->input('dd_price'),
                'normal_margin' => $request->input('normal_margin'),
                'oh_depo' => $request->input('oh_depo'),
                'saving' => $request->input('saving'),
                'bad_debt_dd' => $request->input('bad_debt_dd'),
                'saving_marketing' => $request->input('saving_marketing'),
                'product_type_id' => $product_type->id,
            ]);
        });

        // Redirect dengan pesan sukses
        return redirect()->route('admin.products')->with('success', 'Product berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Products $product)
    {
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product berhasil dihapus');
    }

    /**
     * Show the create form
     */
    public function incomingProducts()
    {   
        return Inertia::render('Warehouse/IncomingItem');
    }

    /**
     * Store new product to warehouse
     */
    public function storeProducts(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'document_code' => 'required|string',
            'transaction_details' => 'required|array',
            'transaction_details.*.name' => 'required|string',
            'transaction_details.*.category' => 'required|string',
            'transaction_details.*.value' => 'required|string',
            'transaction_details.*.data_type' => 'required|string',
            'transaction_items' => 'required|array',
            'transaction_items.*.unit' => 'required|string',
            'transaction_items.*.quantity' => 'required|numeric',
            'transaction_items.*.item_gap' => 'nullable|numeric',
            'transaction_items.*.gap_description' => 'nullable|string',
            'transaction_items.*.product_id' => 'required|numeric',
            'transaction_items.*.product' => 'required_if:transaction_items.*.product_id,null|array',
            'transaction_items.*.product.code' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.unit' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.name' => 'required_with:transaction_items.*.product|string',
        ]);

        // Gunakan transaksi database
        DB::transaction(function () use ($request) {
            $tx_type = TransactionType::where('name', 'Barang Masuk')->first();

            // Simpan transaksi
            $transaction = Transactions::create([
                'document_code' => $request->input('document_code'),
                'correlation_id' => rand(10000,88888),
                'created_by' => Auth::user()->id,
                'transaction_type_id' => $tx_type->id,
            ]);

            $find_sso = Transactions::where('document_code', $transaction->document_code)
                ->with('transactionDetails')
                ->first();
            if($find_sso) 
            {
                $transaction_details = $find_sso->transactionDetails;

                foreach($transaction_details as $detail) 
                {
                    if($detail->category === "Generate Status")
                    {
                        $detail->update(['value' => 'true']);
                    }
                }
            } else 
            {
                return back()->with('failed', 'SSO tidak ada.');
            }

            $expiryDate = null; // Variable to store expiry date
            $allocation = null;

            // Simpan transaction details
            foreach ($request->input('transaction_details') as $detail) {
                TransactionDetail::create([
                    'name' => $detail['name'],
                    'category' => $detail['category'],
                    'value' => $detail['value'],
                    'data_type' => $detail['data_type'],
                    'transactions_id' => $transaction->id,
                ]);

                // Cek apakah kategori adalah Expiry Date dan simpan value-nya
                if ($detail['category'] === 'Expiry Date') {
                    $expiryDate = $detail['value'];
                }

                if($detail['category'] === 'Allocation') {
                    $allocation = $detail['value'];
                }

            }

            // Simpan transaction items
            foreach ($request->input('transaction_items') as $txItem) {
                $product = Products::where('id', $txItem['product_id'])->first();
                $warehouse = Warehouse::where('name', $allocation)->first();

                //store to product journal
                ProductJournal::create([
                    'quantity' => $txItem['quantity'],
                    'amount' => $txItem['amount'],
                    'action' => "IN",
                    'expiry_date' => $expiryDate, // Gunakan expiry date yang sudah diambil sebelumnya
                    'transactions_id' => $transaction->id,
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                ]);

                // Simpan transaction item
                TransactionItem::create([
                    'unit' => $txItem['unit'],
                    'quantity' => $txItem['quantity'],
                    'amount' => $txItem['amount'],
                    'item_gap' => $txItem['item_gap'],
                    'gap_description' => $txItem['gap_description'],
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, // Menyimpan product_id hasil dari produk yang diambil atau baru
                ]);
            }
        });

        return redirect()->route('warehouse.incoming-item')->with('success', 'Barang berhasil masuk ke gudang!');
    }

    public function reStoreStockProduct(Request $request, TransactionItem $transactionItem)
    {
        // dd($request->all());
        DB::transaction(function() use ($transactionItem, $request) {
            $product = Products::where('id', $transactionItem['product_id'])->first();
            $warehouse = Warehouse::where('name', $request->allocation)->first();

            // $transactionItem->update([
            //     'quantity' => 
            // ]);

            ProductJournal::create([
                'quantity' => $request->quantity,
                'amount' => $request->amount,
                'action' => "IN",
                'expiry_date' => null, // Gunakan expiry date yang sudah diambil sebelumnya
                'transactions_id' => $transactionItem->transactions_id,
                'warehouse_id' => $warehouse->id,
                'product_id' => $product->id,
            ]);

        });

        return back()->with('success', 'Barang telah dikembalikan ke gudang');
    }

    /**
     * Index all stocks in warehouse
     */
    public function indexAllWarehouseProducts()
    {
        $products = $this->productServices->getStockProducts(null, null,10);
        // dd($products);

        return Inertia::render('Warehouse/StockItems', compact('products'));
    }

    /**
     * Index DNP Warehouse Products
     */
    public function indexDNPWarehouseProducts()
    {
        $products = $this->productServices->getStockProducts("DNP", null,15);
        // dd($products);

        return Inertia::render('Warehouse/DnpWarehouse/Stocks', compact('products'));
    }

    /**
     * Index DKU Warehouse Products
     */
    public function indexDKUWarehouseProducts()
    {
        $products = $this->productServices->getStockProducts("DKu", null,15);

        return Inertia::render('Warehouse/DkuWarehouse/Stocks', compact('products'));
    }

    /**
     * Index Expired products DKU
     */
    public function indexDkuWarehouseExpiredProducts()
    {
        $expiredProducts = $this->productServices->getExpiredProducts("DKU");

        return Inertia::render('Warehouse/DkuWarehouse/ExpiredStocks', compact('expiredProducts'));
    }

    /**
     * Index Expired products DNP
     */
    public function indexDnpWarehouseExpiredProducts()
    {
        $expiredProducts = $this->productServices->getExpiredProducts("DNP");

        return Inertia::render('Warehouse/DnpWarehouse/ExpiredStocks', compact('expiredProducts'));
    }
}
