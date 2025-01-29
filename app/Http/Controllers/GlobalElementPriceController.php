<?php

namespace App\Http\Controllers;

use App\Models\GlobalElementPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class GlobalElementPriceController extends Controller
{
    public function index()
    {
        $data = GlobalElementPrice::all();
        return Inertia::render('Admin/GlobalElementPrice', ['data' => $data]);
    }

    public function create()
    {
        return Inertia::render('Admin/CreateGlobalElementPrice');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_element' => 'required|string|max:255',
            'price_element' => 'required|numeric',
        ]);

        DB::transaction(function() use ($request) {
            GlobalElementPrice::create([
                'name_element' => $request->name_element,
                'price_element' => $request->price_element,
            ]);
        });

        return redirect()->route('admin.global-element-prices.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit(GlobalElementPrice $globalElementPrice)
    {
        return Inertia::render('Admin/EditGlobalElementPrice', ['element' => $globalElementPrice]);
    }

    public function update(Request $request, GlobalElementPrice $globalElementPrice)
    {
        $request->validate([
            'name_element' => 'required|string|max:255',
            'price_element' => 'required|numeric',
        ]);

        DB::transaction(function() use ($request, $globalElementPrice) {
            $globalElementPrice->update([
                'name_element' => $request->name_element,
                'price_element' => $request->price_element,
            ]);
        });

        return redirect()->route('admin.global-element-prices.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(GlobalElementPrice $globalElementPrice)
    {
        DB::transaction(function() use ($globalElementPrice) {
            $globalElementPrice->delete();
        });

        return redirect()->route('admin.global-element-prices.index')->with('success', 'Data berhasil dihapus!');
    }
}
