<?php

namespace App\Http\Controllers;

use App\Imports\CustomerImport;
use App\Imports\PartiesImport;
use App\Imports\ProductMasterImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportProductMaster extends Controller
{
    public function importProducts(Request $request)
    {
        Excel::import(new ProductMasterImport, $request->attachment);
    }

    public function importVendors(Request $request) 
    {
        Excel::import(new PartiesImport, $request->attachment);
    }

    public function importCustomer(Request $request) 
    {
        $import = new CustomerImport();
        Excel::import($import, $request->attachment);

        return back()->with('success', $import->dataReceived);
    }
}
