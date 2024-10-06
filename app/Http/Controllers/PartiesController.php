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
        $parties = Parties::where('type_parties', "CUSTOMER")->paginate(10);
        $groups = PartiesGroup::all();
        $customer_type = Lookup::where('category', 'TYPE_PARTIES')->get();

        return Inertia::render('Admin/Customer', compact('parties','groups', 'customer_type'));
    }

    public function createSupplier()
    {
        $parties = Parties::where('type_parties', "VENDOR")->paginate(10);
        $groups = PartiesGroup::all();
        $supplier_type = Lookup::where('category', 'TYPE_PARTIES')->get();

        return Inertia::render('Admin/Supplier', compact('parties','groups', 'supplier_type'));
    }

    public function storeSupplier(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'type_parties' => 'required|string',
            'phone' => 'nullable|string',
            'fax' => 'nullable|string',
            'handphone' => 'nullable|string',
            'email' => 'nullable|string',
            'website' => 'nullable|string',
            'npwp' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'country' => 'nullable|string',
            'description' => 'nullable|string',
            'parties_group_id' => 'numeric|required',
            'legality' => 'required|string',
        ]);

        DB::transaction(function() use ($request) {
            $parties_group = PartiesGroup::where('id', $request->input('parties_group_id'))->first();

            Parties::create([
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'type_parties' => $request->input('type_parties'),
                'phone' => $request->input('phone'),
                'fax' => $request->input('fax'),
                'handphone' => $request->input('handphone'),
                'email' => $request->input('email'),
                'website' => $request->input('website'),
                'npwp' => $request->input('npwp'),
                'contact_person' => $request->input('contact_person'),
                'address' => $request->input('address'),
                'postal_code' => $request->input('postal_code'),
                'city' => $request->input('city'),
                'province' => $request->input('province'),
                'country' => $request->input('country'),
                'description' => $request->input('description'),
                'legality' => $request->input('legality'),
                'parties_group_id' => $parties_group->id,
            ]);
        });

        return redirect()->route('admin.parties.supplier')->with('success', 'Supplier berhasil dibuat!');

    }

    public function store(Request $request)
    {

        // TODO: implement store logic
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'type_parties' => 'required|string',
            'phone' => 'nullable|string',
            'fax' => 'nullable|string',
            'handphone' => 'nullable|string',
            'email' => 'nullable|string',
            'website' => 'nullable|string',
            'npwp' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'term_payment' => 'nullable|numeric',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'country' => 'nullable|string',
            'description' => 'nullable|string',
            'parties_group_id' => 'numeric|required',
            'legality' => 'required|string',
        ]);

        DB::transaction(function() use ($request) {
            $parties_group = PartiesGroup::where('id', $request->input('parties_group_id'))->first();

            Parties::create([
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'type_parties' => $request->input('type_parties'),
                'phone' => $request->input('phone'),
                'fax' => $request->input('fax'),
                'handphone' => $request->input('handphone'),
                'email' => $request->input('email'),
                'website' => $request->input('website'),
                'npwp' => $request->input('npwp'),
                'contact_person' => $request->input('contact_person'),
                'address' => $request->input('address'),
                'postal_code' => $request->input('postal_code'),
                'city' => $request->input('city'),
                'province' => $request->input('province'),
                'country' => $request->input('country'),
                'description' => $request->input('description'),
                'term_payment' => $request->input('term_payment'),
                'legality' => $request->input('legality'),
                'parties_group_id' => $parties_group->id,
            ]);
        });

        return redirect()->route('admin.parties')->with('success', 'Customer berhasil dibuat!');
    }
}
