<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductPriceRequest;
use App\Models\ProductPrice;
use App\Models\Products;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductPricingController extends Controller
{

    private static string $VIEW_PATH = 'Admin/ProductManagement/ProductPricing';
    
    public function indexDO()
    {
        $product_prices = DB::table('product_prices as pr')
            ->join('products as p','p.id','=','pr.product_id')
            ->join('shipping as sp','sp.id','=','pr.shipping_id')
            ->where('sp.name','=','DO')
            ->select([
                'p.id as product_id',
                'p.name as product_name',
                'p.unit',
                'p.code',
                'pr.*',
                'sp.name'
            ])
            ->paginate(20);

        return Inertia::render(self::$VIEW_PATH . '/DO',[
            'product_prices' => $product_prices
        ]);
    }

    public function createDOPrice()
    {
        $dimensions = DB::table('dimention')->get();
        $global_element = DB::table('global_element_price')->get();
        $region_delivery = DB::table('region_delivery')->get();
        $products = DB::table('products as p')
            ->select(['p.id', 'p.name', 'p.unit', 'p.code'])
            ->whereNotExists(function ($query) {
                $query->from('product_prices as price')
                    ->join('shipping', 'shipping.id', '=', 'price.shipping_id')
                    ->whereColumn('price.product_id', '=', 'p.id')
                    ->where('shipping.name', '=','DO');
            })
            ->get();

        return Inertia::render(self::$VIEW_PATH . '/Pricing/PricingDO',[
            'products' => $products,
            'utils' => [
                'dimensions' => $dimensions,
                'global_element' => $global_element,
                'region_delivery' => $region_delivery
            ]
        ]);
    }

    public function storeDOPrice(ProductPriceRequest $request)
    {
        $request->validated();

        $product = DB::table('products')
            ->where('products.id','=',$request->product_id)
            ->first();
        $shipping = DB::table('shipping')
            ->where('shipping.name','=', $request->delivery_type)
            ->first();

        DB::transaction(function() use ($request, $shipping, $product) {
            ProductPrice::create([
                'redemp_price' => $request->redemp_price,
                'all_segment_price' => $request->all_segment_price,
                'transportation_cost' => $request->transportation_cost,
                'oh_depo' => $request->oh_depo,
                'budget_marketing' => $request->budget_marketing,
                'bad_debt' => $request->bad_debt,
                'saving' => $request->saving,
                'margin_all_segment' => $request->margin_all_segment,
                'product_id' => $product->id,
                'shipping_id' => $shipping->id
            ]);
        });

        return redirect()->route('admin.pricing.do')->with('success',"Harga produk berhasil disubmit!");
    }

    public function editDOPrice(ProductPrice $productPrice)
    {
        $dimensions = DB::table('dimention')->get();
        $global_element = DB::table('global_element_price')->get();
        $region_delivery = DB::table('region_delivery')->get();
        $prices = DB::table('product_prices as pp')
            ->join('products as p', 'p.id','=','pp.product_id')
            ->select([
                'pp.id',
                'pp.transportation_cost',
                'pp.redemp_price',
                'pp.all_segment_price',
                'pp.oh_depo',
                'pp.budget_marketing',
                'pp.margin_all_segment',
                'p.id as product_id',
                'p.name',
                'p.unit',
                'p.code'
            ])
            ->where('pp.id', $productPrice->id)
            ->first();

        return Inertia::render(self::$VIEW_PATH . '/Pricing/EditDO',[
            'data' => $prices,
            'utils' => [
                'dimensions' => $dimensions,
                'global_element' => $global_element,
                'region_delivery' => $region_delivery
            ]
        ]);
    }

    public function updateDOPrice(ProductPriceRequest $request, ProductPrice $productPrice)
    {
        $request->validated();

        DB::transaction(function() use ($request, $productPrice) {
            $productPrice->update([
                'redemp_price' => $request->redemp_price,
                'all_segment_price' => $request->all_segment_price,
                'transportation_cost' => $request->transportation_cost,
                'oh_depo' => $request->oh_depo,
                'budget_marketing' => $request->budget_marketing,
                'bad_debt' => $request->bad_debt,
                'saving' => $request->saving,
                'margin_all_segment' => $request->margin_all_segment,
            ]);
        });

        return redirect()->route('admin.pricing.do')->with('success',"Harga produk berhasil diperbarui!");
    }

    public function deleteDOPrice(ProductPrice $productPrice)
    {   
        DB::transaction(function() use ($productPrice) {
            $productPrice->delete();
        });

        return back()->with('success', 'Harga produk berhasil dihapus!');
    }

    public function indexDEPO()
    {
        return Inertia::render(self::$VIEW_PATH . '/DEPO');
    }

    public function indexDIRECT_DEPO()
    {
        return Inertia::render(self::$VIEW_PATH . '/DIRECT_DEPO');
    }

    public function indexDIRECT()
    {
        return Inertia::render(self::$VIEW_PATH . '/DIRECT');
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
