<?php

namespace App\Http\Controllers;

use App\Models\StoreHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StorehouseController extends Controller
{
    // Create & Filter Storehouses
    public function createStorehouse(Request $request)
    {
        $query = StoreHouse::query();

        // Filter berdasarkan field dan query yang diterima dari request
        if ($request->filled('filter_field') && $request->filled('filter_query')) {
            $field = $request->input('filter_field');
            $value = $request->input('filter_query');
            $query->where($field, 'like', '%' . $value . '%');
        }

        // Urutkan berdasarkan created_at dan paginasi data
        $storehouses = $query->orderBy('created_at', 'desc')->paginate(10);

        // Pastikan total item sesuai dengan hasil yang difilter
        $storehouses->appends($request->only('filter_field', 'filter_query'));

        return Inertia::render('Admin/Storehouse', compact('storehouses'));
    }

    // Store new Storehouse
    public function storeStorehouse(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'name' => 'required|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            StoreHouse::create($validated);
        });

        return redirect()->route('admin.create-storehouse')->with('success', 'Gudang berhasil dibuat');
    }

    // Show the form for editing the specified storehouse
    public function editStorehouse(StoreHouse $storehouse)
    {
        return Inertia::render('Admin/StorehouseEdit', compact('storehouse'));
    }

    // Update the specified storehouse in the database
    public function updateStorehouse(Request $request, StoreHouse $storehouse)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'name' => 'required|max:255',
        ]);

        DB::transaction(function () use ($storehouse, $validated) {
            $storehouse->update($validated);
        });

        return redirect()->route('admin.create-storehouse')->with('success', 'Gudang berhasil diupdate');
    }

    // Delete the specified storehouse from the database
    public function deleteStorehouse(StoreHouse $storehouse)
    {
        DB::transaction(function () use ($storehouse) {
            $storehouse->delete();
        });

        return redirect()->route('admin.create-storehouse')->with('success', 'Gudang berhasil dihapus');
    }
}
