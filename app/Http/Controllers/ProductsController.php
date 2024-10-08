<?php

namespace App\Http\Controllers;

use App\Models\Lookup;
use App\Models\Products;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createProduct()
    {
        $products = Products::with('productType')->orderBy('created_at', 'desc')->paginate(10);
        $product_type = ProductType::all();
        $units = Lookup::where('category', 'UNIT')->get();

        return Inertia::render('Admin/Products', compact(
            'products',
            'product_type',
            'units',
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
            'saving_marketing' => 'nullable|numeric',
            'product_type_id' => 'numeric|required',
        ]);

        
        // Menyimpan data ke tabel products
        DB::transaction(function() use ($request) {
            $product_type = ProductType::where('id', $request->input('product_type_id'))->first();

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
            $product->bad_debt_dd = $request->input('bad_debt_dd');
            $product->saving_marketing = $request->input('saving_marketing');
            $product->product_type_id = $product_type->id;

            // Simpan perubahan
            $product->save();
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
}
