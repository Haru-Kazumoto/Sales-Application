<?php

namespace App\Http\Controllers;

use App\Models\Lookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UnitController extends Controller
{
    public function createUnit()
    {
        $units = Lookup::where('category', 'UNIT')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Units', compact('units'));
    }

    public function storeUnit(Request $request)
    {
        $request->validate([
            'label' => 'required|string',
            'value' => "required|string",
        ]);

        DB::transaction(function() use ($request) {
            Lookup::create([
                'label' => $request->input('label'),
                'value' => $request->input('value'),
                'category' => "UNIT",
            ]);
        });

        return redirect()->route('admin.create-unit')->with('success', 'Unit berhasil ditambahkan');
    }

    public function update(Lookup $unit) 
    {
        return Inertia::render('Admin/UnitEdit', compact('unit'));
    }

    public function edit(Request $request, Lookup $unit)
    {
        $data = $request->validate([
            'label' => 'required|string',
            'value' => "required|string",
        ]);

        DB::transaction(function() use ($data, $unit) {
            $unit->update([
                'label' => $data['label'],
                'value' => $data['value'],
            ]);
        });

        return redirect()->route('admin.create-unit')->with('success', 'Unit berhasil di update!');
    }

    public function destroy(Lookup $unit) 
    {
        $unit->delete();

        return redirect()->route('admin.create-unit')->with('success', 'Unit berhasil di hapus!');
    }
}
