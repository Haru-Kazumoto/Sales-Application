<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\PromoProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PromoProgramController extends Controller
{

    public function index()
    {
        $promos = PromoProgram::select(
                '*', 
                DB::raw("CASE WHEN end_date < CURDATE() THEN 'BERAKHIR' ELSE 'AKTIF' END AS status")
            )
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Admin/ListPromoProgram', compact('promos'));
    }

    public function createPromo(Request $request)
    {
        $products = Products::all();
        return Inertia::render('Admin/PromoProgram',compact('products'));
    }
    
    public function storePromo(Request $request)
    {
        // Validasi input
        $dataValidated = $request->validate([
            'name' => "required|string",
            'description' => "required|string",
            'min' => "required|numeric",
            'max' => 'required|numeric',
            'start_date' => 'required|string',
            'end_date' => "required|string",
            'promo_value' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product_id' => 'required|numeric',
        ]);

        // Cek promo aktif untuk produk yang dipilih
        $activePromoProducts = Products::whereIn('id', collect($dataValidated['products'])
            ->pluck('product_id'))
            ->whereHas('promoProduct', function ($query) {
                $query->where('end_date', '>', now())
                    ->orWhereDate('end_date', today());
            })
            ->get();

        if ($activePromoProducts->isNotEmpty()) {
            $productNames = $activePromoProducts->pluck('name')->implode(', ');
            return back()->withErrors(['products' => 'Produk yang dipilih masih memiliki promo aktif: ' . $productNames]);
        }
 
        // Melakukan transaksi jika tidak ada promo aktif
        DB::transaction(function() use ($dataValidated) {
            $dataPromo = PromoProgram::create([
                'name' => $dataValidated['name'],
                'description' => $dataValidated['description'],
                'min' => $dataValidated['min'],
                'max' => $dataValidated['max'],
                'start_date' => $dataValidated['start_date'],
                'end_date' => $dataValidated['end_date'],
                'promo_value' => $dataValidated['promo_value'],
            ]);

            foreach ($dataValidated['products'] as $product) {
                // Update data produk dengan promo_product_id dari $dataPromo
                $dataProduct = Products::find($product['product_id']);
                $dataProduct->promo_product_id = $dataPromo->id;
                $dataProduct->save();
            }
        });

        return back()->with('success', 'Promo berhasil terbuat!');
    }

    public function show(PromoProgram $promoProgram)
    {
        $promoProgram->load('products');
    
        return Inertia::render('Admin/DetailPromoProgram', compact('promoProgram'));
    }

}
