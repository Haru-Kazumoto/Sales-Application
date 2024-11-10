<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MarketingReports extends Controller
{
    public function index()
    {
        $data = DB::select("SELECT * FROM report_marketing");

        return Inertia::render('Marketing/Reports', compact('data'));
    }
}
