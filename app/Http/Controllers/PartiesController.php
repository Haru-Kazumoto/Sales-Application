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
    public function createCustomer(Request $request)
    {
        // Ambil data Parties dengan eager loading untuk relasi partiesGroup
        $query = Parties::with('partiesGroup')
            ->where('type_parties', "CUSTOMER"); // Filter khusus untuk CUSTOMER

        // Filter berdasarkan field dan query yang diterima dari request
        if ($request->filled('filter_field') && $request->filled('filter_query')) {
            $field = $request->input('filter_field'); // Field yang dipilih (nama, tipe, nomor telepon)
            $value = $request->input('filter_query'); // Nilai filter

            // Tambahkan where dengan like untuk pencarian berdasarkan field yang dipilih
            $query->where($field, 'like', '%' . $value . '%');
        }

        // Urutkan berdasarkan created_at dan paginasi data
        $parties = $query->orderBy('created_at', 'desc')->paginate(10);

        // Pastikan total item sesuai dengan hasil yang difilter
        $parties->appends($request->only('filter_field', 'filter_query'));

        // Ambil data groups dan customer_type
        $groups = PartiesGroup::all();
        $customer_type = Lookup::where('category', 'TYPE_PARTIES')->get();

        // Kirim data ke Vue menggunakan Inertia
        return Inertia::render('Admin/Customer', compact('parties', 'groups', 'customer_type'));
    }

    public function createSupplier(Request $request)
    {
        // Ambil data Parties dengan eager loading untuk relasi partiesGroup
        $query = Parties::with('partiesGroup')
            ->where('type_parties', "VENDOR"); // Filter khusus untuk CUSTOMER

        // Filter berdasarkan field dan query yang diterima dari request
        if ($request->filled('filter_field') && $request->filled('filter_query')) {
            $field = $request->input('filter_field'); // Field yang dipilih (nama, tipe, nomor telepon)
            $value = $request->input('filter_query'); // Nilai filter

            // Tambahkan where dengan like untuk pencarian berdasarkan field yang dipilih
            $query->where($field, 'like', '%' . $value . '%');
        }

        // Urutkan berdasarkan created_at dan paginasi data
        $parties = $query->orderBy('created_at', 'desc')->paginate(10);

        // Pastikan total item sesuai dengan hasil yang difilter
        $parties->appends($request->only('filter_field', 'filter_query'));

        // Ambil data groups dan customer_type
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

    public function unAssignCustomerSales()
    

}
