<?php

namespace App\Http\Controllers;

use App\Models\Lookup;
use App\Models\Parties;
use App\Models\PartiesGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PartiesController extends Controller
{
    public function createCustomer()
    {
        $parties = Parties::where('type_parties', "CUSTOMER")->orderBy('created_at', 'desc')->paginate(10);
        $groups = PartiesGroup::all();
        $customer_type = Lookup::where('category', 'TYPE_PARTIES')->get();

        return Inertia::render('Admin/Customer', compact('parties','groups', 'customer_type'));
    }

    public function createSupplier()
    {
        $parties = Parties::where('type_parties', "VENDOR")->orderBy('created_at', 'desc')->paginate(10);
        $groups = PartiesGroup::all();
        $supplier_type = Lookup::where('category', 'TYPE_PARTIES')->get();

        return Inertia::render('Admin/Supplier', compact('parties','groups', 'supplier_type'));
    }

    public function storeSupplier(Request $request)
    {

        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'legality' => 'required|string',
            'type_parties' => 'required|string',
            'parties_group_id' => 'numeric|required',
            'npwp' => 'nullable|string',
            'phone' => 'nullable|string',
            'city' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function() use ($request) {
            $parties_group = PartiesGroup::where('id', $request->input('parties_group_id'))->first();

            Parties::create([
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'legality' => $request->input('legality'),
                'type_parties' => $request->input('type_parties'),
                'parties_group_id' => $parties_group->id,
                'npwp' => $request->input('npwp'),
                'phone' => $request->input('phone'),
                'city' => $request->input('city'),
                'address' => $request->input('address'),
            ]);
        });

        return redirect()->route('admin.parties.supplier')->with('success', 'Supplier berhasil dibuat!');

    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'legality' => 'required|string',
            'type_parties' => 'required|string',
            'parties_group_id' => 'numeric|required',
            'term_payment' => 'nullable|numeric',
            'npwp' => 'nullable|string',
            'phone' => 'nullable|string',
            'city' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function() use ($request) {
            $parties_group = PartiesGroup::where('id', $request->input('parties_group_id'))->first();

            Parties::create([
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'legality' => $request->input('legality'),
                'type_parties' => $request->input('type_parties'),
                'parties_group_id' => $parties_group->id,
                'term_payment' => $request->input('term_payment'),
                'npwp' => $request->input('npwp'),
                'phone' => $request->input('phone'),
                'fax' => $request->input('fax'),
                'city' => $request->input('city'),
            'address' => $request->input('address'),
            ]);
        });

        return redirect()->route('admin.parties.customer')->with('success', 'Customer berhasil dibuat!');
    }

    public function edit(Parties $parties) 
    {
        $groups = PartiesGroup::all();
        $customer_type = Lookup::where('category', 'TYPE_PARTIES')->get();

        return Inertia::render('Admin/CustomerEdit', compact('groups', 'customer_type', 'parties'));
    }

    public function update(Request $request, Parties $parties) // Menggunakan model binding
    {
        // dd($request->all());

        // Validasi input
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'legality' => 'required|string',
            'type_parties' => 'required|string',
            'parties_group_id' => 'numeric|required',
            'term_payment' => 'nullable|numeric',
            'npwp' => 'nullable|string',
            'phone' => 'nullable|string',
            'city' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function() use ($request, $parties) {
            // Ambil data parties_group
            $parties_group = PartiesGroup::where('id', $request->input('parties_group_id'))->first();

            // Perbarui data partai
            $parties->update([
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'legality' => $request->input('legality'),
                'type_parties' => $request->input('type_parties'),
                'parties_group_id' => $parties_group->id,
                'term_payment' => $request->input('term_payment'),
                'npwp' => $request->input('npwp'),
                'phone' => $request->input('phone'),
                'city' => $request->input('city'),
                'address' => $request->input('address'),
            ]);
        });

        return redirect()->route('admin.parties.customer')->with('success', 'Customer berhasil diperbarui!');
    }

    public function destroyCustomer(Parties $parties) 
    {
        $parties->delete();

        return redirect()->route('admin.parties.customer')->with('success', 'Customer berhasil dihapus!');
    }

    public function updateSupplier(Request $request, Parties $parties)
    {
        // Validasi input
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'legality' => 'required|string',
            'type_parties' => 'required|string',
            'parties_group_id' => 'numeric|required',
            'npwp' => 'nullable|string',
            'phone' => 'nullable|string',
            'city' => 'nullable|string',
            'address' => 'nullable|string',
        ]);


        DB::transaction(function() use ($request, $parties) {
            // Ambil data parties_group
            $parties_group = PartiesGroup::where('id', $request->input('parties_group_id'))->first();

            // Perbarui data partai
            $parties->update([
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'legality' => $request->input('legality'),
                'type_parties' => $request->input('type_parties'),
                'parties_group_id' => $parties_group->id,
                'npwp' => $request->input('npwp'),
                'phone' => $request->input('phone'),
                'city' => $request->input('city'),
                'address' => $request->input('address'),
            ]);
        });

        return redirect()->route('admin.parties.supplier')->with('success', 'Supplier berhasil diupdate!');
    }

    public function editSupplier(Parties $parties) 
    {
        $groups = PartiesGroup::all();
        $supplier_type = Lookup::where('category', 'TYPE_PARTIES')->get();

        return Inertia::render('Admin/SupplierEdit', compact('groups', 'supplier_type', 'parties'));
    }

    public function destroySupplier(Parties $parties)
    {
        $parties->delete();

        return redirect()->route('admin.parties.supplier')->with('success', 'Supplier berhasil dihapus!');
    }
}
