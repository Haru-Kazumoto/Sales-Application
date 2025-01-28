<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\TradePromo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TradePromoController extends Controller
{
    public function __construct() {
        //
    }

    public function index()
    {
        $data = TradePromo::query()
            ->selectRaw("*")
            ->orderByRaw("is_active DESC, created_at DESC")
            ->paginate(10);

        return Inertia::render('Admin/TradePromo/TradePromo', compact('data'));
    }

    public function create() 
    {
        $products = DB::table('products')->get();
        return Inertia::render('Admin/TradePromo/CreateTradePromo', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grosir_account' => 'string|required',
            'discount_price' => 'numeric|required',
            'quota' => 'numeric|required',
            'products' => 'array|nullable',
        ]);
        
        DB::transaction(function () use ($request) {
            $tradePromo = TradePromo::create([
                'grosir_account' => $request->grosir_account,
                'discount_price' => $request->discount_price,
                'quota' => $request->quota,
                'is_active' => true,
            ]);

            if ($request->has('products') && !empty($request->products) && is_array($request->products)) {
                foreach ($request->products as $product) {
                    $tradePromo->products()->attach($product['id']);
                }
            }
        });

        return redirect()->route('admin.trade-promo.index')->with('success', 'Promo beli berhasil dibuat!');
    }

    public function edit(TradePromo $tradePromo)
    {
        $tradePromo->load('products');
        $products = DB::table('products')->get();
        $registered_products = DB::table('products')
            ->join('product_trade_promo', 'products.id', '=', 'product_trade_promo.product_id')
            ->where('product_trade_promo.trade_promo_id', $tradePromo->id)
            ->select(['products.id', 'products.name','products.code','products.unit'])
            ->get();

        return Inertia::render('Admin/TradePromo/EditTradePromo', compact('tradePromo','products','registered_products'));
    }

    public function assignProduct(Request $request, TradePromo $tradePromo): RedirectResponse
    {
        $request->validate(['product_id' => 'required|numeric']);

        $tradePromo->products()->attach($request->product_id);

        return back()->with('success', 'Produk berhasil ditambahkan ke promo beli!');
    }

    public function unassignProduct(Request $request, TradePromo $tradePromo)
    {
        $request->validate(['product_id' => 'required|numeric']);

        $tradePromo->products()->detach($request->product_id);

        return back()->with('success', 'Produk berhasil dihapus dari promo beli!');
    }

    public function update(TradePromo $tradePromo, Request $request)
    {
        $request->validate([
            'grosir_account' => 'string|required',
            'discount_price' => 'numeric|required',
            'quota' => 'numeric|required',
        ]);

        DB::transaction(function() use ($tradePromo, $request) {
            $tradePromo->update([
                'grosir_account' => $request->grosir_account,
                'discount_price' => $request->discount_price,
                'quota' => $request->quota,
            ]);
        });

        return redirect()->route('admin.trade-promo.index')->with('success', 'Promo beli berhasil diupdate!');
    }

    public function deletePromo(TradePromo $tradePromo)
    {
        DB::transaction(function() use ($tradePromo) {
            $tradePromo->products()->detach();
            $tradePromo->delete();
        });

        return back()->with('success', 'Promo beli berhasil dihapus!');
    }

    public function shutdownPromo(TradePromo $tradePromo)
    {
        DB::transaction(function() use ($tradePromo) {
            $tradePromo->update(['is_active' => false]);
        });

        return back()->with('success', 'Promo dinon-aktifkan.');
    }

    public function addQuotaPromo(TradePromo $tradePromo, Request $request)
    {
        DB::transaction(function () use ($tradePromo, $request) {
            $tradePromo->update(['quota' => $request->quota]);
        });

        return back()->with('success', 'Kuota promo berhasil ditambah!');
    }

}
