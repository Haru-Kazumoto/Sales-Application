<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateProductsByElementPrice;
use App\Models\GlobalElementPrice;
use App\Models\Products;
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
            $oldPrice = $globalElementPrice->price_element;

            $globalElementPrice->update([
                'name_element' => $request->name_element,
                'price_element' => $request->price_element,
            ]);

            $column = match ($request->name_element) {
                'BUDGET MARKETING' => 'saving_marketing',
                'BAD DEBT' => 'bad_debt_dd',
                default => null
            };

            // Check if the element name is 'BUDGET MARKETING' or 'BAD DEBT'
            if($globalElementPrice->wasChanged('price_element')) {
                // Update the products based on the element price since the price_element is changed
                // UpdateProductsByElementPrice::dispatch($column, $request->price_element);
                Products::query()
                    ->when($column, function($query) use ($column, $request) {
                        $query->chunkById(1000, function($products) use ($column, $request) {
                            $products->each(function($product) use ($column, $request) {
                                $product->update([
                                    $column => $request->price_element
                                ]);
                            });
                        });
                    });
            }
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
