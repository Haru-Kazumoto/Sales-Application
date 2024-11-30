<?php

namespace App\Http\Controllers;

use App\Imports\ProductMasterImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportProductMaster extends Controller
{
    public function importProducts(Request $request)
    {
        Excel::import(new ProductMasterImport, $request->attachment);
    }
}
