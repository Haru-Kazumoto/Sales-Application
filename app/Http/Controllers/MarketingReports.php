<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MarketingReports extends Controller
{
    public function index()
    {
        $data = DB::select("
            SELECT 
    t.transaction_type_id AS transaction_type_id,
    YEAR(ti.created_at) AS tahun,
    (SELECT transaction_details.value 
     FROM transaction_details 
     WHERE transaction_details.transactions_id = t.id 
       AND transaction_details.category = 'Allocation' LIMIT 1) AS pt,
    MONTH(ti.created_at) AS bulan,
    (SELECT transaction_details.value 
     FROM transaction_details 
     WHERE transaction_details.transactions_id = t.id 
       AND transaction_details.category = 'Salesman' LIMIT 1) AS salesman,
    (SELECT transaction_details.value 
     FROM transaction_details 
     WHERE transaction_details.transactions_id = t.id 
       AND transaction_details.category = 'Customer' LIMIT 1) AS customer,
    t.document_code AS no_po_co,
    p.name AS produk,
    p.package AS kemasan,
    p.category AS golongan,
    pt.name AS segmen,
    (SELECT transaction_details.value 
     FROM transaction_details 
     WHERE transaction_details.transactions_id = t.id 
       AND transaction_details.category = 'Shipping Warehouse' LIMIT 1) AS delivery,
    '' AS alokasi,
    COALESCE(p.redemp_price, 0) AS harga_tebus,
    0 AS angkutan,
    COALESCE(p.normal_margin, 0) AS margin_normal,
    COALESCE(p.oh_depo, 0) AS oh_depo,
    0 AS cashback_pph4,
    CAST(COALESCE((SELECT transaction_details.value 
                   FROM transaction_details 
                   WHERE transaction_details.transactions_id = t.id 
                     AND transaction_details.category = 'Unloading Cost' LIMIT 1), 0) AS UNSIGNED) AS bongkar,
    COALESCE(p.saving, 0) AS saving,
    COALESCE(p.bad_debt_dd, 0) AS bad_debt,
    COALESCE(p.saving_marketing, 0) AS saving_marketing,
    COALESCE(((((((((0 + p.normal_margin) + p.oh_depo) + 0) 
    + CAST(COALESCE((SELECT transaction_details.value 
                     FROM transaction_details 
                     WHERE transaction_details.transactions_id = t.id 
                       AND transaction_details.category = 'Unloading Cost' LIMIT 1), 0) AS UNSIGNED)) 
    + p.saving) + p.bad_debt_dd) + p.saving_marketing) * 0.11), 0) AS ppn_11,
    COALESCE((((((((((p.redemp_price + 0) + p.normal_margin) + p.oh_depo) + 0) 
    + CAST(COALESCE((SELECT transaction_details.value 
                     FROM transaction_details 
                     WHERE transaction_details.transactions_id = t.id 
                       AND transaction_details.category = 'Unloading Cost' LIMIT 1), 0) AS UNSIGNED)) 
    + p.saving) + p.bad_debt_dd) + p.saving_marketing) + COALESCE(((((((((0 + p.normal_margin) 
    + p.oh_depo) + 0) + CAST(COALESCE((SELECT transaction_details.value 
                                           FROM transaction_details 
                                           WHERE transaction_details.transactions_id = t.id 
                                             AND transaction_details.category = 'Unloading Cost' LIMIT 1), 0) AS UNSIGNED)) 
    + p.saving) + p.bad_debt_dd) + p.saving_marketing) * 0.11), 0)), 0) AS hj_normal,
    COALESCE(p.retail_price, 0) AS hj_customer,
    COALESCE((p.retail_price - 0), 0) AS selisih,
    ti.quantity AS qty,
    COALESCE((ti.quantity * (p.retail_price - 0)), 0) AS surplus,
    COALESCE((p.redemp_price * ti.quantity), 0) AS total_harga_tebus,
    COALESCE((0 * ti.quantity), 0) AS total_harga_angkutan,
    COALESCE((p.oh_depo * ti.quantity), 0) AS total_oh,
    COALESCE((0 * ti.quantity), 0) AS total_cashback,
    COALESCE((CAST(COALESCE((SELECT transaction_details.value 
                             FROM transaction_details 
                             WHERE transaction_details.transactions_id = t.id 
                               AND transaction_details.category = 'Unloading Cost' LIMIT 1), 0) AS UNSIGNED) 
    * ti.quantity), 0) AS total_bongkar,
    COALESCE((p.bad_debt_dd * ti.quantity), 0) AS total_bd,
    COALESCE((p.saving_marketing * ti.quantity), 0) AS total_saving_marketing,
    (COALESCE(((((((((0 + p.normal_margin) + p.oh_depo) + 0) 
    + CAST(COALESCE((SELECT transaction_details.value 
                     FROM transaction_details 
                     WHERE transaction_details.transactions_id = t.id 
                       AND transaction_details.category = 'Unloading Cost' LIMIT 1), 0) AS UNSIGNED)) 
    + p.saving) + p.bad_debt_dd) + p.saving_marketing) * 0.11), 0) 
    * ti.quantity) AS total_pph,
    COALESCE((p.normal_margin * ti.quantity), 0) AS total_margin,
    COALESCE((((COALESCE((p.normal_margin * ti.quantity), 0) 
    + COALESCE((p.saving_marketing * ti.quantity))) 
    + COALESCE((p.bad_debt_dd * ti.quantity), 0)) 
    + COALESCE((ti.quantity * (p.retail_price - 0)), 0)), 0) AS total_nett_margin,
    COALESCE((p.retail_price * ti.quantity), 0) AS total_grand_omzet
FROM 
    (((transaction_items ti 
    JOIN transactions t ON (t.id = ti.transactions_id)) 
    JOIN products p ON (p.id = ti.product_id)) 
    JOIN product_type pt ON (pt.id = p.product_type_id));");

        return Inertia::render('Marketing/Reports', [
            'data' => $data
        ]);
    }
}
