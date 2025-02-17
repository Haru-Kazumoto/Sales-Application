<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use App\Models\SubShipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubShippingController extends Controller
{
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $shipping = Shipping::where('name', $request->shipping_name)->first();

        DB::transaction(function() use ($request, $shipping) {
            SubShipping::create([
                'name' => str_replace(' ','_', $request->name),
                'shipping_id' => $shipping->id
            ]);
        });

        return back()->with('success', 'Berhasil membuat sub delivery!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubShipping $subShipping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubShipping $subShipping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubShipping $subShipping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubShipping $subShipping)
    {
        //
    }
}
