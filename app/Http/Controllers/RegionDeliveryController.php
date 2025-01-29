<?php

namespace App\Http\Controllers;

use App\Models\RegionDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RegionDeliveryController extends Controller
{
    public function index() 
    {
        $data = RegionDelivery::all();

        return Inertia::render('Admin/RegionDelivery', compact('data'));
    }

    public function create()
    {
        return Inertia::render('Admin/CreateRegionDelivery');
    }

    public function store(Request $request) {
        $request->validate([
            'region_name' => 'required|string',
            'region_code' => 'required|string',
            'region_price' => 'required|numeric',
        ]);

        DB::transaction(function() use ($request) {
            RegionDelivery::create([
                'region_name' => $request->region_name,
                'region_code' => $request->region_code,
                'region_price' => $request->region_price,
            ]);
        });

        return redirect()->route('admin.region-delivery.index')->with('success', 'Harga perwilayah berhasil dibuat');
    }
}
