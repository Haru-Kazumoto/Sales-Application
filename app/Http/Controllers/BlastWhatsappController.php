<?php

namespace App\Http\Controllers;

use App\Models\Parties;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use App\Models\User;
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

        foreach($request->aging_id as $index => $id)
        {
            $data = Transactions::with('transactionDetails')->find($id);

            $salesman_detail = TransactionDetail::where('transactions_id', $data->id)
                ->where('category', 'Salesman')
                ->first();
            $customer_detail = TransactionDetail::where('transactions_id', $data->id)
                ->where('category', 'Customer')
                ->first();

            $salesman = User::where('fullname', $salesman_detail->value)->first();
            $customer = Parties::where('name', $customer_detail->value)->first();
            // dd($customer->name, $salesman->fullname);
            // send to sales
            $this->messageTemplate->sendBlastWhatsapp(1, $salesman->phone, [
                [
                    "key" => 'salesman',
                    'value' => $salesman->fullname,
                ]
            ]);

            // send to customer
            $this->messageTemplate->sendBlastWhatsapp(2, $customer->phone, [
                [
                    "key" => 'nama_customer',
                    'value' => $customer->name,
                ]
            ]);
        }

        return back()->with('success', 'Pesan whatsapp terkirim!');
    }
}
