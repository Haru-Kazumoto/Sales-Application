<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransportRequest;
use App\Models\Parties;
use App\Models\PartiesGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TransportController extends Controller
{
    public function createTransport(Request $request): Response
    {
        $query = Parties::with('partiesGroup')
            ->whereHas('partiesGroup', function($query) {
                $query->where('name', 'ANGKUTAN');
            });

        // Filter berdasarkan field dan query yang diterima dari request
        if($request->filled('filter_field') && $request->filled('filter_query')) {
            $field = $request->input('filter_field'); // Field yang dipilih (nama, tipe, nomor telepon)
            $value = $request->input('filter_query'); // Nilai filter

            // Tambahkan where dengan like untuk pencarian berdasarkan field yang dipilih
            $query->where($field, 'like', '%' . $value . '%');
        }

        // Urutkan berdasarkan created_at dan paginasi data
        $transports = $query->orderBy('created_at', 'desc')->paginate(500);

        // Pastikan total item sesuai dengan hasil yang difilter
        $transports->appends($request->only('filter_field', 'filter_query'));

        return Inertia::render('Admin/Transports', compact('transports'));
    }

    public function storeTransport(CreateTransportRequest $request): RedirectResponse
    {
        // dd($request->all());
        $data = $request->validated();
        // dd($data['number_plate']);

        DB::transaction(function() use ($data) {
            $partiesGroup = PartiesGroup::where('name', 'Angkutan')->first();

            Parties::create([
                'name' => $data['name'],
                'code' => $data['code'],
                'number_plate' => $data['number_plate'],
                'type_parties' => $data['type'],
                'phone' => $data['phone'],
                'city' => $data['city'],
                'address' => $data['address'],
                'parties_group_id' => $partiesGroup->id,
            ]);
        });

        return redirect()->route('admin.create-transports')->with('success', 'Transport berhasil ditambah!');
    }

    public function update(Parties $parties) 
    {
        $parties->load('partiesGroup');

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
                'number_plate' => $data['number_plate'],
                'type_parties' => $data['type'],
                'phone' => $data['phone'],
                'city' => $data['city'],
                'address' => $data['address'],
                'parties_group_id' => $partiesGroup->id,
            ]);
        });

        return redirect()->route('admin.create-transports')->with('success', 'Berhasil mengupdate data transport!');
    }

    public function destroy(Parties $parties): RedirectResponse
    {
        DB::transaction(function() use ($parties) {
            $parties->delete();
        });

        return redirect()->route('admin.create-transports')->with('success', 'Transport berhasil dihapus!');
    }

}
