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


}
