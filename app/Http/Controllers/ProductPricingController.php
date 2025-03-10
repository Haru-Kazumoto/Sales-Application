<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductPriceRequest;
use App\Models\Dimention;
use App\Models\GlobalElementPrice;
use App\Models\Lookup;
use App\Models\ProductPrice;
use App\Models\Products;
use App\Models\RegionDelivery;
use App\Models\SubShipping;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductPricingController extends Controller
{

    private static string $VIEW_PATH = 'Admin/ProductManagement/ProductPricing';
    
    

    public function storeDOPrice(Request $request)
    {
        // dd($request->all());

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
                'pp.rounded_all_segment_price',
                'pp.saving',
                'pp.percentage',
                'p.id as product_id',
                'p.name',
                'p.unit',
                'p.code'
            ])
            ->where('pp.id', $productPrice->id)
            ->first();

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/EditDO',[
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
                'rounded_all_segment_price' => (int) $request->rounded_all_segment_price
            ]);
        });

        return redirect()->route('admin.pricing.do.edit', $productPrice->shipping_id)->with('success',"Harga produk berhasil diperbarui!");
    }

    public function deletePrice(ProductPrice $productPrice)
    {   
        DB::transaction(function() use ($productPrice) {
            $productPrice->delete();
        });

        return back()->with('success', 'Harga produk berhasil dihapus!');
    }

    public function storeDepoPrices(Request $request, SubShipping $subShipping)
    {
        // dd($request->all());
        DB::transaction(function() use ($request, $subShipping) {
            ProductPrice::create([
                'redemp_price' => $request->redemp_price,
                'retail_price' => $request->retail_price,
                'grosir_price' => $request->grosir_price,
                'end_user_price' => $request->end_user_price,
                'all_segment_price' => $request->all_segment_price,
                'percentage' => $request->percentage,
                'transportation_cost' => $request->transportation_cost,
                'oh_depo' => $request->oh_depo,
                'budget_marketing' => $request->budget_marketing,
                'bad_debt' => $request->bad_debt,
                'saving' => $request->saving,
                'margin_retail' => $request->margin_retail,
                'margin_grosir' => $request->margin_grosir,
                'margin_end_user' => $request->margin_end_user,
                'margin_all_segment' => $request->margin_all_segment,
                'rounded_all_segment_price' => $request->rounded_all_segment_price,
                'product_id' => $request->product_id,
                'shipping_id' => $subShipping->shipping_id,
                'sub_shipping_id' => $subShipping->id
            ]);
        });

        return redirect()->route('admin.pricing.depo')->with('success','Berhasil membuat barang!');
    }

    public function editDepoPrice(ProductPrice $productPrice) 
    {
        $dimensions = Dimention::all();
        $global_element = GlobalElementPrice::all();
        $region_delivery = RegionDelivery::all();
        $percentages = Lookup::where('category', 'PERCENTAGE')->get();
        $products = DB::table('products as p')
            ->select(['p.id', 'p.name', 'p.unit', 'p.code'])
            ->get();
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
                'pp.rounded_all_segment_price',
                'pp.percentage',
                'p.id as product_id',
                'p.name',
                'p.unit',
                'p.code'
            ])
            ->where('pp.id', $productPrice->id)
            ->first();

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/EditDepoPrice', [
            'utils' => [
                'dimensions' => $dimensions,
                'global_element' => $global_element,
                'region_delivery' => $region_delivery,
                'percentages' => $percentages
            ],
            'products' => $products,
            'data' => $prices
        ]);
    }

    public function updateDepoPrice(Request $request, ProductPrice $productPrice)
    {
        // dd($request->all());
        DB::transaction(function() use ($request, $productPrice) {
            $productPrice->update([
                'redemp_price' => $request->redemp_price,
                'retail_price' => $request->retail_price,
                'grosir_price' => $request->grosir_price,
                'end_user_price' => $request->end_user_price,
                'all_segment_price' => $request->all_segment_price,
                'percentage' => $request->percentage,
                'transportation_cost' => $request->transportation_cost,
                'oh_depo' => $request->oh_depo,
                'budget_marketing' => $request->budget_marketing,
                'bad_debt' => $request->bad_debt,
                'saving' => $request->saving,
                'margin_retail' => $request->margin_retail,
                'margin_grosir' => $request->margin_grosir,
                'margin_end_user' => $request->margin_end_user,
                'margin_all_segment' => $request->margin_all_segment,
                'rounded_all_segment_price' => $request->rounded_all_segment_price,
                'product_id' => $request->product_id,
            ]);
        });

        return redirect()->route('admin.pricing.depo.index.products', $productPrice->sub_shipping_id)->with('success','Berhasil memperbarui barang!');
    }

    public function storeDirectDepoPrices(Request $request, SubShipping $subShipping)
    {
        // dd($request->all());
        DB::transaction(function() use ($request, $subShipping) {
            ProductPrice::create([
                'redemp_price' => $request->redemp_price,
                'retail_price' => $request->retail_price,
                'grosir_price' => $request->grosir_price,
                'end_user_price' => $request->end_user_price,
                'all_segment_price' => $request->all_segment_price,
                'percentage' => $request->percentage,
                'transportation_cost' => $request->transportation_cost,
                'oh_depo' => $request->oh_depo,
                'budget_marketing' => $request->budget_marketing,
                'bad_debt' => $request->bad_debt,
                'saving' => $request->saving,
                'margin_retail' => $request->margin_retail,
                'margin_grosir' => $request->margin_grosir,
                'margin_end_user' => $request->margin_end_user,
                'margin_all_segment' => $request->margin_all_segment,
                'rounded_all_segment_price' => $request->rounded_all_segment_price,
                'product_id' => $request->product_id,
                'shipping_id' => $subShipping->shipping_id,
                'sub_shipping_id' => $subShipping->id
            ]);
        });

        return redirect()->route('admin.pricing.direct-depo')->with('success','Berhasil membuat barang!');
    }

    public function editDirectDepoPrice(ProductPrice $productPrice)
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
                'pp.rounded_all_segment_price',
                'pp.saving',
                'pp.percentage',
                'p.id as product_id',
                'p.name',
                'p.unit',
                'p.code'
            ])
            ->where('pp.id', $productPrice->id)
            ->first();
        $products = DB::table('products as p')
            ->select(['p.id', 'p.name', 'p.unit', 'p.code'])
            ->get();

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/EditDirectDepoPrice',[
            'data' => $prices,
            'utils' => [
                'dimensions' => $dimensions,
                'global_element' => $global_element,
                'region_delivery' => $region_delivery
            ],
            'products' => $products
        ]);
    }

    public function updateDirectDepoPrice(Request $request, ProductPrice $productPrice)
    {
        // dd($request->all());
        DB::transaction(function() use ($request, $productPrice) {
            $productPrice->update([
                'redemp_price' => $request->redemp_price,
                'retail_price' => $request->retail_price,
                'grosir_price' => $request->grosir_price,
                'end_user_price' => $request->end_user_price,
                'all_segment_price' => $request->all_segment_price,
                'percentage' => $request->percentage,
                'transportation_cost' => $request->transportation_cost,
                'oh_depo' => $request->oh_depo,
                'budget_marketing' => $request->budget_marketing,
                'bad_debt' => $request->bad_debt,
                'saving' => $request->saving,
                'margin_retail' => $request->margin_retail,
                'margin_grosir' => $request->margin_grosir,
                'margin_end_user' => $request->margin_end_user,
                'margin_all_segment' => $request->margin_all_segment,
                'rounded_all_segment_price' => $request->rounded_all_segment_price,
                'product_id' => $request->product_id,
            ]);
        });

        return redirect()->route('admin.pricing.direct-depo.index.products', $productPrice->sub_shipping_id)->with('success','Berhasil memperbarui barang!');
    }

    public function storeDirectPrices(Request $request, SubShipping $subShipping)
    {
        
        
        DB::transaction(function() use ($request, $subShipping) {
            ProductPrice::create([
                'redemp_price' => $request->redemp_price,
                'retail_price' => $request->retail_price,
                'grosir_price' => $request->grosir_price,
                'end_user_price' => $request->end_user_price,
                'all_segment_price' => $request->all_segment_price,
                'percentage' => $request->percentage,
                'transportation_cost' => $request->transportation_cost,
                'oh_depo' => $request->oh_depo,
                'budget_marketing' => $request->budget_marketing,
                'bad_debt' => $request->bad_debt,
                'saving' => $request->saving,
                'margin_retail' => $request->margin_retail,
                'margin_grosir' => $request->margin_grosir,
                'margin_end_user' => $request->margin_end_user,
                'margin_all_segment' => $request->margin_all_segment,
                'rounded_all_segment_price' => $request->rounded_all_segment_price,
                'product_id' => $request->product_id,
                'shipping_id' => $subShipping->shipping_id,
                'sub_shipping_id' => $subShipping->id
            ]);
        });

        return redirect()->route('admin.pricing.direct.index.products', $subShipping->id)->with('success','Berhasil membuat barang!');
    }

    public function editDirectPrice(ProductPrice $productPrice)
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
                'pp.rounded_all_segment_price',
                'pp.saving',
                'pp.percentage',
                'p.id as product_id',
                'p.name',
                'p.unit',
                'p.code'
            ])
            ->where('pp.id', $productPrice->id)
            ->first();
        $products = DB::table('products as p')
            ->select(['p.id', 'p.name', 'p.unit', 'p.code'])
            ->get();

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/EditDirectPrice',[
            'data' => $prices,
            'utils' => [
                'dimensions' => $dimensions,
                'global_element' => $global_element,
                'region_delivery' => $region_delivery
            ],
            'products' => $products
        ]);
    }

    public function updateDirectPrice(Request $request, ProductPrice $productPrice)
    {
        // dd($request->all());
        DB::transaction(function() use ($request, $productPrice) {
            $productPrice->update([
                'redemp_price' => $request->redemp_price,
                'retail_price' => $request->retail_price,
                'grosir_price' => $request->grosir_price,
                'end_user_price' => $request->end_user_price,
                'all_segment_price' => $request->all_segment_price,
                'percentage' => $request->percentage,
                'transportation_cost' => $request->transportation_cost,
                'oh_depo' => $request->oh_depo,
                'budget_marketing' => $request->budget_marketing,
                'bad_debt' => $request->bad_debt,
                'saving' => $request->saving,
                'margin_retail' => $request->margin_retail,
                'margin_grosir' => $request->margin_grosir,
                'margin_end_user' => $request->margin_end_user,
                'margin_all_segment' => $request->margin_all_segment,
                'rounded_all_segment_price' => $request->rounded_all_segment_price,
                'product_id' => $request->product_id,
            ]);
        });

        return redirect()->route('admin.pricing.direct.index.products', $productPrice->sub_shipping_id)->with('success','Berhasil memperbarui barang!');
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
