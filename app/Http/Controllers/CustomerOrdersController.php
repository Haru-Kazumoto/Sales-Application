<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrders;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        //
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
