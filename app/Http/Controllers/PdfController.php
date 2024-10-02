<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generatePurchaseOrderDocument() 
    {
        $purchaseOrder = PurchaseOrder::get();
    }
}
