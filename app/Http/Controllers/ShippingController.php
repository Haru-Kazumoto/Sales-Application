<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductPriceService;
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
    private $productPriceService;

    public function __construct(
        ShippingServices $shippingServices,
        ProductPriceService $productPriceService
    )
    {   
        $this->shippingServices = $shippingServices;
        $this->productPriceService = $productPriceService;
    }

    public function indexDoShipping(Request $request)
    {
        $search_product = $request->query('search_product','');
        // $product_prices = $this->productPriceService->getProductPricesFromSubShippingId(null,$search_product, 15);
        $product_prices = DB::table('product_prices as pr')
            ->join('products as p','p.id','=','pr.product_id')
            ->join('shipping as s','s.name','=',DB::raw("'DO'"));
        
        if(!empty($search_product)) {
            $product_prices->where('p.name','LIKE',"%{$search_product}%");
        }

        $product_prices->orderByDesc('pr.created_at')
            ->select([
                'p.id as product_id',
                'p.name as product_name',
                'p.unit',
                'p.code',
                'pr.*',
            ])->paginate(15);

        return Inertia::render("Admin/ProductManagement/ProductPricing/DO",[
            'product_prices' => $product_prices,
            'filters' => [
                'search_product' => $search_product
            ]
        ]);
    }

    public function createDoPricing()
    {
        $dimensions = DB::table('dimention')->get();
        $global_element = DB::table('global_element_price')->get();
        $region_delivery = DB::table('region_delivery')->get();
        $products = DB::table('products as p')
            ->select(['p.id', 'p.name', 'p.unit', 'p.code'])
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
    
    public function indexDepoShipping(Request $request)
    {
        $shipping_name = "DEPO";
        $search_product = $request->query('search_product','');
        $subShippings = $this->shippingServices->indexSubShippingByName($shipping_name);
        $countSubShipping = $this->shippingServices->countSubShippingByName($shipping_name);

        return Inertia::render('Admin/ProductManagement/ProductPricing/DEPO', compact('subShippings','countSubShipping'));
    }

    public function indexDirectDepoShipping()
    {
        $shipping_name = "DIRECT_DEPO";
        $subShippings = $this->shippingServices->indexSubShippingByName($shipping_name);
        $countSubShipping = $this->shippingServices->countSubShippingByName($shipping_name);

        return Inertia::render('Admin/ProductManagement/ProductPricing/DIRECT_DEPO', compact('subShippings','countSubShipping'));
    }

    public function indexDirectShipping()
    {
        $shipping_name = "DIRECT";
        $subShippings = $this->shippingServices->indexSubShippingByName($shipping_name);
        $countSubShipping = $this->shippingServices->countSubShippingByName($shipping_name);

        return Inertia::render('Admin/ProductManagement/ProductPricing/DIRECT', compact('subShippings','countSubShipping'));
    }

    public function indexProductsDepo(Request $request, SubShipping $subShipping)
    {
        $search_product = $request->query('search_product','');
        $product_prices = $this->productPriceService->getProductPricesFromSubShippingId($subShipping->id,$search_product, 15);
        // $product_prices = DB::table('product_prices as pr')
        //     ->join('products as p','p.id','=','pr.product_id')
        //     ->join('shipping as s','s.name','=','DO');
        
        // if(!empty($search_product)) {
        //     $product_prices->where('p.name','LIKE',"%{$search_product}%");
        // }

        // $product_prices->orderByDesc('pr.created_at')
        //     ->select([
        //         'p.id as product_id',
        //         'p.name as product_name',
        //         'p.unit',
        //         'p.code',
        //         'pr.*',
        //     ])->paginate(15);

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/IndexProductsDEPO', [
            'product_prices' => $product_prices,
            'subShipping' => $subShipping,
            'filters' => [
                'search_product' => $search_product
            ]
        ]);
    }

    public function indexProductsDirectDepo(Request $request, SubShipping $subShipping)
    {
        $search_product = $request->query('search_product','');
        $product_prices = $this->productPriceService->getProductPricesFromSubShippingId($subShipping->id,$search_product, 15);

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/IndexProductsDIRECT_DEPO', [
            'product_prices' => $product_prices,
            'subShipping' => $subShipping,
            'filters' => [
                'search_product' => $search_product
            ]
        ]);
    }

    public function indexProductsDirect(Request $request, SubShipping $subShipping)
    {
        $search_product = $request->query('search_product');
        $product_prices = $this->productPriceService->getProductPricesFromSubShippingId($subShipping->id,$search_product, 15);

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/IndexProductsDIRECT', [
            'product_prices' => $product_prices,
            'subShipping' => $subShipping,
            'filters' => [
                'search_product' => $search_product
            ]
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

    public function createDirectDepoPricing(SubShipping $subShipping)
    {
        $dimensions = Dimention::all();
        $global_element = GlobalElementPrice::all();
        $region_delivery = RegionDelivery::all();
        $percentages = Lookup::where('category', 'PERCENTAGE')->get();
        $products = DB::table('products as p')
            ->select(['p.id', 'p.name', 'p.unit', 'p.code'])
            ->get();
        

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/PricingDIRECT_DEPO', [
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

    public function createDirectPricing(SubShipping $subShipping)
    {
        $dimensions = Dimention::all();
        $global_element = GlobalElementPrice::all();
        $region_delivery = RegionDelivery::all();
        $percentages = Lookup::where('category', 'PERCENTAGE')->get();
        $products = DB::table('products as p')
            ->select(['p.id', 'p.name', 'p.unit', 'p.code'])
            ->get();

        return Inertia::render('Admin/ProductManagement/ProductPricing/Pricing/PricingDIRECT', [
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
