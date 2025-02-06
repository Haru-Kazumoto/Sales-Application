<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransportRequest;
use App\Imports\TransportImport;
use App\Models\Parties;
use App\Models\PartiesGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class TransportController extends Controller
{
    public function index(Request $request): Response
    {
        $query = DB::table('parties as transport')
            ->join('parties_groups as group', 'transport.parties_group_id', '=', 'group.id')
            ->select([
                'transport.id',
                'transport.name',
                'transport.code',
                'transport.address',
                'transport.pic',
                'transport.pic_2',
                'transport.phone',
                'transport.phone_2'
            ])
            ->where('transport.type_parties','=','EXTERNAL_TRANSPORTATION')
            ->where('group.name','=','Angkutan');
            // ->paginate();

        // Filter berdasarkan field dan query yang diterima dari request
        if($request->filled('filter_field') && $request->filled('filter_query')) {
            $field = $request->input('filter_field'); // Field yang dipilih (nama, tipe, nomor telepon)
            $value = $request->input('filter_query'); // Nilai filter

            // Tambahkan where dengan like untuk pencarian berdasarkan field yang dipilih
            $query->where($field, 'like', '%' . $value . '%');
        }

        // Urutkan berdasarkan created_at dan paginasi data
        $transports = $query->orderBy('transport.created_at', 'desc')->paginate(500);

        // Pastikan total item sesuai dengan hasil yang difilter
        $transports->appends($request->only('filter_field', 'filter_query'));

        return Inertia::render('Admin/Transports', compact('transports'));
    }

    public function create(): Response
    {
        return Inertia::render('Admin/TransportCreate', [
            'transport_code' => "TRN-".rand(100000,999999)
        ]);
    }

    public function storeTransport(CreateTransportRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function() use ($data) {
            $partiesGroup = PartiesGroup::where('name', 'Angkutan')->first();

            Parties::create([
                'name' => $data['name'],
                'code' => $data['code'],
                'type_parties' => 'EXTERNAL_TRANSPORTATION',
                'pic' => $data['pic'],
                'phone' => $data['phone'],
                'pic_2' => $data['pic_2'],
                'phone_2' => $data['phone_2'],
                'address' => $data['address'],
                'parties_group_id' => $partiesGroup->id,
            ]);
        });

        return redirect()->route('admin.index-transports')->with('success', 'Transport berhasil ditambah!');
    }

    public function update(Parties $parties) 
    {
        return Inertia::render('Admin/TransportsEdit', compact('parties'));
    }

    public function edit(CreateTransportRequest $request, Parties $parties)
    {
        $data = $request->validated();

        DB::transaction(function() use ($data, $parties) {
            $partiesGroup = PartiesGroup::where('name', 'Angkutan')->first();
    
            $parties->update([
                'name' => $data['name'],
                'code' => $data['code'],
                'type_parties' => 'EXTERNAL_TRANSPORTATION',
                'pic' => $data['pic'],
                'phone' => $data['phone'],
                'pic_2' => $data['pic_2'],
                'phone_2' => $data['phone_2'],
                'address' => $data['address'],
                'parties_group_id' => $partiesGroup->id,
            ]);
        });

        return redirect()->route('admin.index-transports')->with('success', 'Berhasil mengupdate data transport!');
    }

    public function destroy(Parties $parties): RedirectResponse
    {
        DB::transaction(function() use ($parties) {
            $parties->delete();
        });

        return back()->with('success', 'Ekspedisi berhasil dihapus!');
    }

    public function importTransports(Request $request)
    {
        Excel::import(new TransportImport, $request->attachment);
    }

}
