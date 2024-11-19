<?php

namespace App\Http\Controllers;

use App\Models\Parties;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlastWhatsappController extends Controller
{
    private $messageTemplate;

    public function __construct(MessageTemplateController $messageTemplateController)
    {
        $this->messageTemplate = $messageTemplateController;
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

}
