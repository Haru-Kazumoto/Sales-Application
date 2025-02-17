<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;

class ShippingServices
{
    public function indexSubShippingByName(string $shippingName)
    {
        $shipping = DB::table('shipping')->where('shipping.name', $shippingName)->first();

        return DB::table('sub_shipping as ss')
            ->leftJoin('product_prices as pp', 'pp.sub_shipping_id', '=', 'ss.id')
            ->where('ss.shipping_id', $shipping->id)
            ->select([
                'ss.id',
                'ss.name',
                DB::raw('COUNT(pp.id) as product_price_count')
            ])
            ->groupBy('ss.id', 'ss.name')
            ->get();
    }


    public function countSubShippingByName(string $shippingName)
    {
        $shipping = DB::table('shipping')->where('shipping.name', $shippingName)->first();
        return DB::table('sub_shipping as ss')
            ->where('ss.shipping_id', $shipping->id)
            ->count();
    }
}
