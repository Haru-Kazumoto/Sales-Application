<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCustomerRequest;
use App\Http\Services\LookupServices;
use App\Http\Services\PartiesServices;
use App\Models\Lookup;
use App\Models\Parties;
use App\Models\PartiesGroup;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CustomerController extends Controller
{

    protected $partiesService;
    protected $lookupService;

    public function __construct(PartiesServices $partiesServices, LookupServices $lookupServices)
    {
        $this->partiesService = $partiesServices;
        $this->lookupService = $lookupServices;
    }

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
    public function createCustomer(CreateCustomerRequest $createRequest)
    {
        $filter_field = $createRequest->filled('filter_field');
        $filter_query = $createRequest->filled('filter_query');

        // Ambil data Parties dengan eager loading untuk relasi partiesGroup
        $query = Parties::with('partiesGroup')->where('type_parties', "CUSTOMER"); // Filter khusus untuk CUSTOMER

        // Filter berdasarkan field dan query yang diterima dari createRequest
        if ($filter_field && $filter_query) {
            $field = $createRequest->input('filter_field'); // Field yang dipilih (nama, tipe, nomor telepon)
            $value = $createRequest->input('filter_query'); // Nilai filter

            // Tambahkan where dengan like untuk pencarian berdasarkan field yang dipilih
            $query->where($field, 'like', '%' . $value . '%');
        }

        // Urutkan berdasarkan created_at dan paginasi data
        $parties = $query->orderBy('created_at', 'desc')->paginate(10);

        // Pastikan total item sesuai dengan hasil yang difilter
        $parties->appends($createRequest->only('filter_field', 'filter_query'));

        // Ambil data groups dan customer_type
        $groups = PartiesGroup::all();
        $customer_type = $this->lookupService->getAllLookupBy('category', 'TYPE_PARTIES');

        // Kirim data ke Vue menggunakan Inertia
        return Inertia::render('Admin/Customer', compact('parties', 'groups', 'customer_type'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Assigning  a customer to a group of sales.
     * 
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function assignCustomerToSales(Parties $customer,User $sales): RedirectResponse
    {
        DB::transaction(function() use ($sales, $customer) {

            $customer->user()->associate($sales);

            $customer->save();
        });

        return back()->with('success', 'Berhasil menambahkan customer ke sales');
    }

    // public function unAssignCustomerSales(Parties $customer)
    // {

    // }


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
    public function edit(Parties $parties) 
    {
        $groups = PartiesGroup::all();
        $customer_type = Lookup::where('category', 'TYPE_PARTIES')->get();

        return Inertia::render('Admin/CustomerEdit', compact('groups', 'customer_type', 'parties'));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCustomer(Parties $parties) 
    {
        $parties->delete();

        return redirect()->route('admin.parties.customer')->with('success', 'Customer berhasil dihapus!');
    }
}
