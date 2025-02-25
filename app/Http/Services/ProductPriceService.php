<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;

class ProductPriceService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getProductPricesFromSubShippingId(?int $subShippingId, ?string $search, int $perPage)
    {
        $data = DB::table('product_prices as pr')
            ->join('products as p','p.id','=','pr.product_id')
            ->join('sub_shipping as ss','ss.id','=','pr.sub_shipping_id');

        if(!is_null($subShippingId))
        {
            $data->where('ss.id','=',$subShippingId);
        }

        if(!is_null($search))
        {
            $data->where('p.name' ,'LIKE',"%{$search}%");
        }

        return $data
            ->orderByDesc('pr.created_at')
            ->select([
                'p.id as product_id',
                'p.name as product_name',
                'p.unit',
                'p.code',
                'pr.*',
            ])->paginate($perPage);
    }
}
