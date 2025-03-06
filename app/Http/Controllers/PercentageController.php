<?php

namespace App\Http\Controllers;

use App\Models\Lookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PercentageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Lookup::where('category', 'PERCENTAGE')
            ->get()
            ->map(function ($item) {
                $item->value = floatval($item->value);

                return $item;
            });

        return Inertia::render('Admin/Percentage', [
            'data' => $data
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::transaction(function() use ($request) {
            Lookup::create([
                'label' => $request->label."%",
                'value' => $request->label / 100,
                'category' => 'PERCENTAGE',
            ]);
        });

        return back()->with('success', 'Berhasil membuat persentase');
    }

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
