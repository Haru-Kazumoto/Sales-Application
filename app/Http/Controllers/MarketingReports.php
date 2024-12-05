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
    public function index()
    {
        // $data = DB::select("
        //     SELECT 
        //         t.transaction_type_id AS transaction_type_id,
        //         YEAR(ti.created_at) AS tahun,
        //         (
        //             SELECT transaction_details.value 
        //             FROM transaction_details 
        //             WHERE transaction_details.transactions_id = t.id 
        //               AND transaction_details.category = 'Allocation'
        //         ) AS pt,
        //         MONTH(ti.created_at) AS bulan,
        //         (
        //             SELECT transaction_details.value 
        //             FROM transaction_details 
        //             WHERE transaction_details.transactions_id = t.id 
        //               AND transaction_details.category = 'Salesman'
        //         ) AS salesman,
        //         (
        //             SELECT transaction_details.value 
        //             FROM transaction_details 
        //             WHERE transaction_details.transactions_id = t.id 
        //               AND transaction_details.category = 'Customer'
        //         ) AS customer,
        //         t.document_code AS no_po_co,
        //         p.name AS produk,
        //         p.package AS kemasan,
        //         p.category AS golongan,
        //         pt.name AS segmen,
        //         (
        //             SELECT transaction_details.value 
        //             FROM transaction_details 
        //             WHERE transaction_details.transactions_id = t.id 
        //               AND transaction_details.category = 'Shipping Warehouse'
        //         ) AS delivery,
        //         '' AS alokasi,
        //         COALESCE(p.redemp_price, 0) AS harga_tebus,
        //         0 AS angkutan,
        //         COALESCE(p.normal_margin, 0) AS margin_normal,
        //         COALESCE(p.oh_depo, 0) AS oh_depo,
        //         0 AS cashback_pph4,
        //         CAST(
        //             COALESCE(
        //                 (
        //                     SELECT transaction_details.value 
        //                     FROM transaction_details 
        //                     WHERE transaction_details.transactions_id = t.id 
        //                       AND transaction_details.category = 'Unloading Cost'
        //                 ), 
        //                 0
        //             ) AS UNSIGNED
        //         ) AS bongkar,
        //         COALESCE(p.saving, 0) AS saving,
        //         COALESCE(p.bad_debt_dd, 0) AS bad_debt,
        //         COALESCE(p.saving_marketing, 0) AS saving_marketing,
        //         COALESCE(
        //             (
        //                 (
        //                     (
        //                         (
        //                             0 + p.normal_margin
        //                         ) + p.oh_depo
        //                     ) + 0
        //                 ) + CAST(
        //                     COALESCE(
        //                         (
        //                             SELECT transaction_details.value 
        //                             FROM transaction_details 
        //                             WHERE transaction_details.transactions_id = t.id 
        //                               AND transaction_details.category = 'Unloading Cost'
        //                         ), 
        //                         0
        //                     ) AS UNSIGNED
        //                 )
        //             ) + p.saving + p.bad_debt_dd + p.saving_marketing
        //         ) * 0.11 AS ppn_11,
        //         COALESCE(
        //             p.retail_price, 
        //             0
        //         ) AS hj_customer,
        //         ti.quantity AS qty,
        //         COALESCE(
        //             (
        //                 ti.quantity * p.retail_price
        //             ), 
        //             0
        //         ) AS total_grand_omzet
        //     FROM 
        //         transaction_items ti
        //     JOIN 
        //         transactions t ON t.id = ti.transactions_id
        //     JOIN 
        //         products p ON p.id = ti.product_id
        //     JOIN 
        //         product_type pt ON pt.id = p.product_type_id
        // ");
        $data = DB::select("SELECT * FROM report_marketing WHERE transaction_type_id = 8 LIMIT 100");

        return Inertia::render('Marketing/Reports', [
            'data' => $data
        ]);
    }

    public function exportReport()
    {
        return Excel::download(new MarketingReportExport(), 'reports_'.Carbon::now()->format('F_Y_M').'.xlsx');
    }
}
