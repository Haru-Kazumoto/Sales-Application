<?php

namespace App\Http\Controllers;

use App\Http\Services\ShippingServices;
use App\Models\Dimention;
use App\Models\GlobalElementPrice;
use App\Models\Lookup;
use App\Models\Parties;
use App\Models\ProductType;
use App\Models\RegionDelivery;
use App\Models\Shipping;
use App\Models\SubShipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ShippingController extends Controller
{
    private $shippingServices;

    public function __construct(ShippingServices $shippingServices)
    {   
        $this->shippingServices = $shippingServices;
    }

    public function indexDoShipping()
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
            ->get();
            // ->paginate(20);

        return Inertia::render("Admin/ProductManagement/ProductPricing/DO",[
            'product_prices' => $product_prices
        ]);
    }

    public function createDoPricing()
    {
        $dimensions = DB::table('dimention')->get();
        $global_element = DB::table('global_element_price')->get();
        $region_delivery = DB::table('region_delivery')->get();
        $products = DB::table('products as p')
            ->select(['p.id', 'p.name', 'p.unit', 'p.code'])
            // ->whereNotExists(function ($query) {
            //     $query->select(DB::raw(1))
            //         ->from('product_prices as price')
            //         ->join('shipping', 'shipping.id', '=', 'price.shipping_id')
            //         ->whereColumn('price.product_id', '=', 'p.id')
            //         ->where('shipping.name', '=','DO');
            // })
            ->get();

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/PricingDO',[
            'products' => $products,
            'utils' => [
                'dimensions' => $dimensions,
                'global_element' => $global_element,
                'region_delivery' => $region_delivery
            ]
        ]);
    }
    
    public function indexDepoShipping()
    {
        $shipping_name = "DEPO";
        $subShippings = $this->shippingServices->indexSubShippingByName($shipping_name);
        $countSubShipping = $this->shippingServices->countSubShippingByName($shipping_name);

        return Inertia::render('Admin/ProductManagement/ProductPricing/DEPO', compact('subShippings','countSubShipping'));
    }

    public function indexProductsDepo(SubShipping $subShipping)
    {
        $product_prices = DB::table('product_prices as pr')
            ->join('products as p','p.id','=','pr.product_id')
            ->join('sub_shipping as ss','ss.id','=','pr.sub_shipping_id')
            ->where('ss.id','=',$subShipping->id)
            ->select([
                'p.id as product_id',
                'p.name as product_name',
                'p.unit',
                'p.code',
                'pr.*',
            ])
            ->get();

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/IndexProductsDEPO', [
            'product_prices' => $product_prices,
            'subShipping' => $subShipping
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createDepoPricing(SubShipping $subShipping)
    {
        $dimensions = Dimention::all();
        $global_element = GlobalElementPrice::all();
        $region_delivery = RegionDelivery::all();
        $percentages = Lookup::where('category', 'PERCENTAGE')->get();
        $products = DB::table('products as p')
            ->select(['p.id', 'p.name', 'p.unit', 'p.code'])
            ->whereNotExists(function ($query) use ($subShipping) {
                $query->select(DB::raw(1))
                    ->from('product_prices as price')
                    ->join('sub_shipping as ss', 'ss.id', '=', 'price.shipping_id')
                    ->whereColumn('price.product_id', '=', 'p.id')
                    ->where('ss.name', '=',$subShipping->name);
            })
            ->get();

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/PricingDEPO', [
            'utils' => [
                'dimensions' => $dimensions,
                'global_element' => $global_element,
                'region_delivery' => $region_delivery,
                'percentages' => $percentages
            ],
            'subShipping' => $subShipping,
            'products' => $products,
        ]);
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
    public function show(Shipping $shipping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipping $shipping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipping $shipping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipping $shipping)
    {
        //
    }
}
