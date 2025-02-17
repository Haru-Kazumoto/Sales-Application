<?php

namespace App\Http\Controllers;

use App\Http\Services\ShippingServices;
use App\Models\Shipping;
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
    
    public function indexDepoShipping()
    {
        $shipping_name = "DEPO";
        $subShippings = $this->shippingServices->indexSubShippingByName($shipping_name);
        $countSubShipping = $this->shippingServices->countSubShippingByName($shipping_name);

        return Inertia::render('Admin/ProductManagement/ProductPricing/DEPO', compact('subShippings','countSubShipping'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
