<?php

namespace App\Http\Controllers;

use App\Models\Lookup;
use App\Models\Parties;
use App\Models\PartiesGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

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

        $parties->getCollection()->transform(function ($party) {
            $party->npwp_image_url = $party->npwp_image ? asset('storage/' . $party->npwp_image) : null;
            $party->ktp_image_url = $party->ktp_image ? asset('storage/' . $party->ktp_image) : null;
            return $party;
        });

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
            'npwp_image' => 'nullable|image|max:2048', // Maks 2MB
            'ktp_image' => 'nullable|image|max:2048', // Maks 2MB
            'segment_customer' => 'required|string',
            'company' => 'required|string'
        ]);

        DB::transaction(function() use ($request) {
            $parties_group = PartiesGroup::where('id', $request->input('parties_group_id'))->first();

            $npwpImagePath = $request->hasFile('npwp_image')
                ? $request->file('npwp_image')->storeAs(
                    'images/npwp',
                    'npwp_' . Str::random(6) . '_' . now()->format('Ymd') . '.' . $request->file('npwp_image')->getClientOriginalExtension(),
                    'public'
                )
                : null;

            $ktpImagePath = $request->hasFile('ktp_image')
                ? $request->file('ktp_image')->storeAs(
                    'images/ktp',
                    'ktp_' . Str::random(6) . '_' . now()->format('Ymd') . '.' . $request->file('ktp_image')->getClientOriginalExtension(),
                    'public'
                )
                : null;

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
                'npwp_image' => $npwpImagePath,
                'ktp_image' => $ktpImagePath,
                'segment_customer' => $request->segment_customer,
                'company' => $request->company
            ]);
        });

        return redirect()->route('admin.parties.customer')->with('success', 'Customer berhasil dibuat!');
    }

    public function edit(Parties $parties) 
    {
        $groups = PartiesGroup::all();
        $customer_type = Lookup::where('category', 'TYPE_PARTIES')->get();

        // Sertakan URL lengkap untuk gambar (jika ada)
        $parties->npwp_image = $parties->npwp_image ? asset('storage/' . $parties->npwp_image) : null;
        $parties->ktp_image = $parties->ktp_image ? asset('storage/' . $parties->ktp_image) : null;

        return Inertia::render('Admin/CustomerEdit', compact('groups', 'customer_type', 'parties'));
    }

    

    public function update(Request $request, Parties $parties)
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
            'npwp_image' => 'nullable|image|max:2048', // Maks 2MB
            'ktp_image' => 'nullable|image|max:2048', // Maks 2MB
            'segment_customer' => 'required|string',
            'company' => 'required|string'
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
                'segment_customer' => $request->segment_customer,
                'company' => $request->company
            ]);

            // Update gambar jika ada yang diupload
            if ($request->hasFile('npwp_image')) {
                // Hapus gambar lama jika ada
                if ($parties->npwp_image) {
                    Storage::delete('public/' . $parties->npwp_image);
                }
                // Simpan gambar baru
                $npwpImagePath = $request->file('npwp_image')->storeAs(
                    'images/npwp',
                    'npwp_' . Str::random(6) . '_' . now()->format('Ymd') . '.' . $request->file('npwp_image')->getClientOriginalExtension(),
                    'public'
                );
                $parties->npwp_image = $npwpImagePath;
            }

            if ($request->hasFile('ktp_image')) {
                // Hapus gambar lama jika ada
                if ($parties->ktp_image) {
                    Storage::delete('public/' . $parties->ktp_image);
                }
                // Simpan gambar baru
                $ktpImagePath = $request->file('ktp_image')->storeAs(
                    'images/ktp',
                    'ktp_' . Str::random(6) . '_' . now()->format('Ymd') . '.' . $request->file('ktp_image')->getClientOriginalExtension(),
                    'public'
                );
                $parties->ktp_image = $ktpImagePath;
            }

            // Simpan perubahan
            $parties->save();
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