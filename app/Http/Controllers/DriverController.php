<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DriverController extends Controller
{
    public function createDriver(Request $request) 
    {
        $query = Driver::query();

        // Filter berdasarkan field dan query yang diterima dari request
        if($request->filled('filter_field') && $request->filled('filter_query')) {
            $field = $request->input('filter_field'); // Field yang dipilih (nama, tipe, nomor telepon)
            $value = $request->input('filter_query'); // Nilai filter

            // Tambahkan where dengan like untuk pencarian berdasarkan field yang dipilih
            $query->where($field, 'like', '%' . $value . '%');
        }

        // Urutkan berdasarkan created_at dan paginasi data
        $drivers = $query->orderBy('created_at', 'desc')->paginate(10);

        // Pastikan total item sesuai dengan hasil yang difilter
        $drivers->appends($request->only('filter_field', 'filter_query'));

        return Inertia::render('Admin/Driver', compact('drivers'));
    }

    public function storeDriver(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
        ]);
    
        DB::transaction(function () use ($request) {
            Driver::create([
                'code' => $request->input('code'),
                'name' => $request->input('name'),
            ]);
        });
    
        return redirect()->route('admin.create-driver')->with('success', 'Driver berhasil dibuat!');
    }
    

    public function updateDriver(Request $request, Driver $driver)
    {
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
        ]);
    
        DB::transaction(function () use ($request, $driver) {
            $driver->update([
                'code' => $request->input('code'),
                'name' => $request->input('name'),
            ]);
        });
    
        return redirect()->route('admin.create-driver')->with('success', 'Driver berhasil di update!');
    }
    
    public function editDriver(Driver $driver)
    {
        return Inertia::render('Admin/DriverEdit', compact('driver'));
    }


    public function deleteDriver(Driver $driver)
    {
        DB::transaction(function () use ($driver) {
            $driver->delete();
        });
    
        return redirect()->route('admin.create-driver')->with('success', 'Driver berhasil dihapus!');
    }
    
}
