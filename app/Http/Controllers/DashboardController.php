<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function indexProcurementDashboard()
    {
        $purchaseOrders = PurchaseOrder::all();
        $countTotalPo = PurchaseOrder::all()->count();
        $countTotalLocatedDKU = PurchaseOrder::where('located', 'DKU')->count();
        $countTotalLocatedDNP = PurchaseOrder::where('located', 'DNP')->count();

        return Inertia::render('Procurement/Dashboard', compact('countTotalPo', 'countTotalLocatedDKU', 'countTotalLocatedDNP', 'purchaseOrders'));
    }
}
