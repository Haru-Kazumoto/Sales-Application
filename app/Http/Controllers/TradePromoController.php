<?php

namespace App\Http\Controllers;

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

    public function create()
    {
        $data = TradePromo::query()
            ->selectRaw("*")
            ->orderByRaw("is_active DESC, created_at DESC")
            ->paginate(10);

        return Inertia::render('Admin/TradePromo/CreateTradePromo', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grosir_account' => 'string|required',
            'discount_price' => 'numeric|required',
            'quota' => 'numeric|required',
        ]);

        DB::transaction(function () use ($request) {
            TradePromo::create([
                'grosir_account' => $request->grosir_account,
                'discount_price' => $request->discount_price,
                'quota' => $request->quota,
                'is_active' => true,
            ]);
        });

        return back()->with('success', 'Promo beli berhasil dibuat!');
    }

    public function edit(TradePromo $tradePromo)
    {
        return Inertia::render('Admin/TradePromo/EditTradePromo', compact('tradePromo'));
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

        return redirect()->route('')->with('success', 'Promo beli berhasil diupdate!');
    }

    public function deletePromo()
    {
        //
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
