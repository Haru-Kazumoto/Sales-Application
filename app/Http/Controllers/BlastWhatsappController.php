<?php

namespace App\Http\Controllers;

use App\Http\Services\WhatsappService;
use App\Models\Parties;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlastWhatsappController extends Controller
{
    private $messageTemplate;
    private $whatsappService;

    public function __construct(MessageTemplateController $messageTemplateController, WhatsappService $whatsappService)
    {
        $this->messageTemplate = $messageTemplateController;
        $this->whatsappService = $whatsappService;
    }

    public function sendWhatsapp(Request $request)
    {
        $request->validate([
            'aging_id' => 'array|required'
        ]);

        // Ambil semua transaksi sekaligus dengan menghitung `total_left`
        $transactions = Transactions::with('transactionDetails')
            ->whereIn('id', $request->aging_id)
            ->selectRaw('transactions.*, 
                (total - COALESCE((SELECT SUM(total_paid) FROM invoice_payment WHERE transaction_id = transactions.id), 0)) as total_left'
            )
            ->get();

        foreach ($transactions as $data) {
            $salesman_detail = TransactionDetail::where('transactions_id', $data->id)
                ->where('category', 'Salesman')
                ->first();
            $customer_detail = TransactionDetail::where('transactions_id', $data->id)
                ->where('category', 'Customer')
                ->first();

            $salesman = User::where('fullname', $salesman_detail->value)->first();
            $customer = Parties::where('name', $customer_detail->value)->first();

            // Kirim pesan ke Salesman
            $this->messageTemplate->sendBlastWhatsapp(1, $salesman->phone, [
                [
                    'key' => 'salesman',
                    'value' => $salesman->fullname,
                ],
                [
                    'key' => 'jatuh_tempo',
                    'value' => Carbon::parse($data->due_date)->translatedFormat('d F Y'),
                ],
                [
                    'key' => 'nama_customer',
                    'value' => $customer->name,
                ],
                [
                    'key' => 'sisa_tagihan',
                    'value' => number_format($data->total_left, 2, ',', '.'),
                ],
            ]);

            // Kirim pesan ke Customer
            $this->messageTemplate->sendBlastWhatsapp(2, $customer->phone, [
                [
                    'key' => 'nama_customer',
                    'value' => $customer->name,
                ],
                [
                    'key' => 'jatuh_tempo',
                    'value' => Carbon::parse($data->due_date)->translatedFormat('d F Y'),
                ],
                [
                    'key' => 'sisa_tagihan',
                    'value' => number_format($data->total_left, 2, ',', '.'),
                ],
            ]);
        }

        return back()->with('success', 'Pesan whatsapp terkirim!');
    }

    public function sendMessageToSupplier()
    {
        $dataSuppliers = DB::table('product_in_gap')
            ->select('usr_id', 'name as supplier_name', 'phone', 'product_name', 'quantity')
            ->get()
            ->groupBy('usr_id'); 

        foreach ($dataSuppliers as $supplierId => $products) {
            $supplier = $products->first();

            $chat_message = "Selamat sore, {$supplier->supplier_name}. Berikut adalah barang Anda yang belum terkirim ke PT DANITAMA NIAGA PRIMA:\n";

            foreach ($products as $index => $product) {
                $chat_message .= ($index + 1) . ". {$product->product_name} - {$product->quantity}\n";
            }

            $this->whatsappService->sendMessage($supplier->phone, $chat_message);
        }

        return back()->with('success', 'Pesan terkirim ke semua supplier!');
    }

    public function sendMessageNoticeProductsToSalesmanAndPrincipal()
    {
        $stagnation_products = DB::table('product_journal as pj')
            ->join('products as p', 'p.id', '=', 'pj.product_id')
            ->join('warehouse as w', 'w.id', '=', 'pj.warehouse_id')
            ->join('parties as pt', 'pt.id', '=', 'p.supplier_id')
            ->select([
                'pj.id',
                'p.name',
                'p.code',
                'p.unit',
                'pj.batch_code',
                DB::raw('MAX(DATE_FORMAT(pj.expiry_date, "%Y-%m")) AS expiry_date'), // Ambil expiry_date terbaru
                'w.name as warehouse_name',
                DB::raw('SUM(CASE WHEN pj.action = "IN" THEN pj.quantity ELSE 0 END) - 
                        SUM(CASE WHEN pj.action = "OUT" THEN pj.quantity ELSE 0 END) AS last_stock'),
                'pt.name as supplier_name',
                'pt.phone as supplier_phone',
                DB::raw('DATEDIFF(pj.stagnation_limit_date, 
                        (SELECT CAST(value AS DATE) 
                        FROM transaction_details 
                        WHERE transaction_details.category = "Delivery Date" 
                        AND transaction_details.transactions_id = pj.transactions_id
                        LIMIT 1)) AS jumlah_hari')
            ])
            ->whereRaw('DATE(pj.stagnation_limit_date) > CURRENT_TIMESTAMP()')
            ->groupBy(
                'pj.id', 
                'p.name', 
                'p.code', 
                'p.unit', 
                'pj.batch_code', 
                'w.name', 
                'pt.name', 
                'pt.phone', 
                'pj.stagnation_limit_date'
            )
            ->get();

        $salesman_numbers = DB::table('users as u')
            ->join('divisions as d', 'd.id', '=', 'u.division_id')  // Join dengan tabel divisions
            ->select('u.phone')
            ->where('d.division_name', 'SALES')  // Pastikan division_name adalah 'SALESMAN'
            ->get()
            ->pluck('phone');

        // Kirim pesan ke Principal (tanpa duplikasi nomor)
        $principal_phones = $stagnation_products->pluck('supplier_phone')->unique();

        foreach ($principal_phones as $principalPhone) {
            if (!$principalPhone) continue; // Lewati jika tidak ada nomor principal

            $chat_message = "Hi, Principal. Berikut adalah daftar barang yang slow living, tolong dikeluarkan:\n";

            foreach ($stagnation_products as $product) {
                if ($product->supplier_phone == $principalPhone) {
                    $chat_message .= "{$product->name} : {$product->last_stock} {$product->unit} ({$product->jumlah_hari} hari) - Expiry Date: {$product->expiry_date}\n";
                }
            }

            $this->whatsappService->sendMessage($principalPhone, $chat_message);
        }

        // Kirim pesan ke semua Salesman
        $salesman_message = "Hi, Salesman. Berikut adalah daftar barang yang slow living, tolong dikeluarkan:\n";
        foreach ($stagnation_products as $product) {
            $salesman_message .= "{$product->name} : {$product->last_stock} {$product->unit} ({$product->jumlah_hari} hari) - Expiry Date: {$product->expiry_date}\n";
        }

        foreach ($salesman_numbers as $salesmanPhone) {
            $this->whatsappService->sendMessage($salesmanPhone, $salesman_message);
        }

        return back()->with('success', 'Pesan terkirim ke Principal dan Salesman!');
    }



}
