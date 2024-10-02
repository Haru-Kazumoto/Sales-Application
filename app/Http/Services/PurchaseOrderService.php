<?php

namespace App\Http\Services;
use App\Models\PurchaseOrder;

class PurchaseOrderService 
{
    public function getPurchaseOrderByPoNumber(string $purchase_order_number)
    {
        $purchaseOrder = PurchaseOrder::with('purchaseOrderProducts')
            ->where('purchase_order_number', $purchase_order_number)
            ->firstOrFail();

        return $purchaseOrder;
    }
}