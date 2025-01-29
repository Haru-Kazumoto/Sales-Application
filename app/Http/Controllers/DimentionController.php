<?php

namespace App\Http\Controllers;

use App\Models\Dimention;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DimentionController extends Controller
{
    public function index()
    {
        $dimentions = Dimention::all();
        return Inertia::render('Admin/Dimention', [
            'data' => $dimentions
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/CreateDimention');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dimention_name' => 'required|string|max:255',
            'min_value' => 'required|integer',
            'max_value' => 'required|integer|gte:min_value',
            'price_dimention' => 'required|numeric',
        ]);

        Dimention::create($validated);
        return redirect()->route('admin.dimentions.index')->with('success', 'Dimensi berhasil dibuat!');
    }

    public function edit(Dimention $dimention) // Route Model Binding
    {
        return Inertia::render('Admin/EditDimention', [
            'dimention' => $dimention
        ]);
    }

    public function update(Request $request, Dimention $dimention)
    {
        $validated = $request->validate([
            'dimention_name' => 'required|string|max:255',
            'min_value' => 'required|integer',
            'max_value' => 'required|integer|gte:min_value',
            'price_dimention' => 'required|numeric',
        ]);

        $dimention->update($validated);
        return redirect()->route('admin.dimentions.index')->with('success', 'Dimensi berhasil diperbarui!');
    }

    public function destroy(Dimention $dimention)
    {
        $dimention->delete();
        return redirect()->route('admin.dimentions.index');
    }
}
