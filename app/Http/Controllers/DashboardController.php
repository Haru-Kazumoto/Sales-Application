<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function indexProcurementDashboard(): Response
    {
        $purchaseOrders = PurchaseOrder::all();
        $countTotalPo = PurchaseOrder::all()->count();
        $countTotalLocatedDKU = PurchaseOrder::where('located', 'DKU')->count();
        $countTotalLocatedDNP = PurchaseOrder::where('located', 'DNP')->count();

        return Inertia::render('Procurement/Dashboard', compact('countTotalPo', 'countTotalLocatedDKU', 'countTotalLocatedDNP', 'purchaseOrders'));
    }

    public function indexFinanceDashboard(): Response
    {
        return Inertia::render('Finance/Dashboard');
    }

    public function indexWarehouseDashboard(): Response
    {
        return Inertia::render('Warehouse/Dashboard');
    }

    public function indexAdminDashboard(): Response
    {
        return Inertia::render('Admin/Dashboard');
    }

    public function indexAgingFinanceDashboard(): Response
    {
        return Inertia::render('AgingFinance/Dashboard');
    }

    public function indexSalesDashboard(): Response
    {
        return Inertia::render('Sales/Dashboard');
    }
}
