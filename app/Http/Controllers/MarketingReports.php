<?php

namespace App\Http\Controllers;

use App\Exports\MarketingReportExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class MarketingReports extends Controller
{
    public function index(){
        $data = DB::select("SELECT * FROM report_marketing");

        return Inertia::render('Marketing/Reports', [
            'data' => $data
        ]);
    }

    public function exportReport()
    {
        return Excel::download(new MarketingReportExport(), 'reports_'.Carbon::now()->format('F_Y_M').'.xlsx');
    }
}
