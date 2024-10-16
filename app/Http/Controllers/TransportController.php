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
    public function createTransport(): Response
    {
        // $transports = DB::table('parties')
        //     ->join('parties_groups', 'parties.parties_group_id', '=', 'parties_groups.id')
        //     ->where('parties_groups.name', 'ANGKUTAN')
        //     ->orderBy('parties.created_at', 'desc')
        //     ->paginate(10);
        $transports = Parties::with('partiesGroup')
            ->whereHas('partiesGroup', function($query) {
                $query->where('name', 'ANGKUTAN');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
