<?php

namespace App\Http\Controllers;

use App\Models\CustomerRegion;
use App\Models\Parties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CustomerRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function createAndIndex()
    {
        $regions = CustomerRegion::all();

        return Inertia::render('Admin/CustomerRegion/Index', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'region' => 'required|string',
            'transportation_cost' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {
            CustomerRegion::create([
                'region' => $request->region,
                'transportation_cost' => $request->transportation_cost,
            ]);
        });

        return back()->with('success', 'Berhasil membuat wilayah baru!');
    }

    public function createAssignCustomers(CustomerRegion $customerRegion)
    {
        $customers = Parties::where('type_parties', 'CUSTOMER')
            ->where('region_id', null)
            ->get();
        $assignedCustomers = Parties::where('type_parties', 'CUSTOMER')
            ->where('region_id', $customerRegion->id)
            ->orderByDesc('updated_at')
            ->get();

        return Inertia::render('Admin/CustomerRegion/AssignRegion', compact('customerRegion', 'customers', 'assignedCustomers'));
    }

    public function assignCustomers(CustomerRegion $customerRegion, Request $request)
    {
        DB::transaction(function() use ($customerRegion, $request) {
            $customers = $request->customers;

            foreach($customers as $customerData) 
            {
                $customer = Parties::where('id', $customerData['id'])->first();

                $customer->customerRegion()->associate($customerRegion);

                $customer->save();
            }
        });

        return back()->with('success', 'Customer berhasil diperbarui');
    }

    public function unassignCustomer(Parties $customer)
    {
        DB::transaction(function() use ($customer) {
            $customer->customerRegion()->dissociate();
            $customer->save();
        });

        return back()->with('success', 'Customer berhasil dihapus');
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
