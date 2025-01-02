<?php

namespace App\Http\Controllers;

use App\Http\Services\ClaimPromoServices;
use App\Models\Products;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ClaimPromoController extends Controller
{
    private $claimPromoService;

    public function __construct(ClaimPromoServices $claimPromoServices)
    {
        $this->claimPromoService = $claimPromoServices;
    }

    public function indexClaimPromo()
    {
        $claims = $this->claimPromoService->getDataClaims(15);

        return Inertia::render('Finance/Claim/ClaimPromo', compact('claims'));
    }

    public function createClaim(Request $request)
    {
        // Bagian tahun
        $year = date('Y');

        if ($request->isMethod('post')) {
            // Simpan ID ke sesi
            $request->session()->put('selected_ids', $request->input('co_id'));

            // Redirect ke halaman GET form tanpa data di URL
            return redirect()->route('finance.form-claim-promo.get');
        }

        // Ambil ID dari sesi
        $coIds = $request->session()->get('selected_ids', []);
        $choosen_datas = TransactionItem::whereIn('id', $coIds)
            ->with('transaction.transactionDetails', 'product')
            ->get();

        // Mencari document_code terakhir dan menambahkan increment
        $lastDocument = Transactions::whereYear('created_at', $year)  // Filter by the current year
                        ->orderBy('id', 'desc')                    // Sort by the latest entry
                        ->first();

        // Tentukan angka increment berikutnya
        $nextIncrement = $lastDocument ? intval(substr($lastDocument->document_code, 0, 3)) + 1 : 1;

        // Format document_code menjadi 3 digit
        $incrementFormatted = str_pad($nextIncrement, 3, '0', STR_PAD_LEFT);

        // Gabungkan ke dalam document_code
        $document_code = "{$incrementFormatted}/DKU/ACC/I/{$year}";

        return Inertia::render('Finance/Claim/FormClaim', [
            'choosen_datas' => $choosen_datas,
            'document_code' => $document_code,
        ]);
    }



    public function storeClaim(Request $request)
    {
        $request->validate([
            'document_code' => 'required|string',
            'sub_total' => 'required|numeric', 
            'total' => 'required|numeric', 
            'transaction_details' => 'required|array',
            'transaction_details.*.name' => 'required|string',
            'transaction_details.*.category' => 'required|string',
            'transaction_details.*.value' => 'required|string',
            'transaction_details.*.data_type' => 'required|string',
            'transaction_items' => 'required|array',
            'transaction_items.*.unit' => 'required|string',
            'transaction_items.*.quantity' => 'required|numeric',
            'transaction_items.*.tax_amount' => 'nullable|numeric',
            'transaction_items.*.amount' => 'required|numeric',
            'transaction_items.*.product_id' => 'required|numeric',
            'transaction_items.*.total_price' => 'required|numeric',
            'transaction_items.*.product' => 'required_if:transaction_items.*.product_id,null|array',
            'transaction_items.*.product.code' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.unit' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.name' => 'required_with:transaction_items.*.product|string',
        ]);

        DB::transaction(function() use ($request) {
            $transaction_details = $request->transaction_details;
            $transaction_items = $request->transaction_items;
            $tx_type = TransactionType::where('name', 'Klaim Promo')->first();

            $transaction = Transactions::create([
                'document_code' => $request->document_code,
                'correlation_id' => rand(10000,99999),
                'created_by' => Auth::user()->id,
                'sub_total' => $request->sub_total,
                'total' => $request->total,
                'transaction_type_id' => $tx_type->id,
            ]);

            //process transaction items
            foreach($transaction_items as $item) 
            {
                // update the claimed data items 
                $data_item = TransactionItem::find($item['id']);
                $data_item->update(['has_claimed' => true]);
                $product = Products::where('id', $item['product_id'])->first();

                // Simpan transaction item
                TransactionItem::create([
                    'unit' => $item['unit'],
                    'quantity' => $item['quantity'],
                    'tax_amount' => $item['tax_amount'],
                    'amount' => $item['amount'],
                    'tax_id' => $item['tax_id'],
                    'total_price' => $item['total_price'],
                    'discount_1' => $item['discount_1'],
                    'discount_2' => $item['discount_2'],
                    'discount_3' => $item['discount_3'],
                    // 'total_price_discount' => $item['total_price_discount'],
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, // Menyimpan product_id dari produk yang ditemukan atau baru
                ]);
            }

            foreach($transaction_details as $detail)
            {
                TransactionDetail::create([
                    'name' => $detail['name'],
                    'category' => $detail['category'],
                    'value' => $detail['value'],
                    'data_type' => $detail['data_type'],
                    'transactions_id' => $transaction->id,
                ]);
            }
        });

        return redirect()->route('finance.claim.index')->with('success', 'Klaim promo berhasil dibuat!');
    }

    public function indexDataClaimPromo()
    {
        $txType = TransactionType::where('name', 'Klaim Promo')->first();
        $result = DB::table('transactions as tx')
            ->select([
                'tx.id',
                'tx.document_code as claim_number',
                'tx.total as total_claim',
                'tx.created_at as claim_date',
                DB::raw("
                    (SELECT 
                        CASE 
                            WHEN tx_details.value = 'false' THEN 'UNPAID' 
                            WHEN tx_details.value = 'true' THEN 'PAID' 
                            ELSE NULL 
                        END
                    FROM transaction_details tx_details 
                    WHERE tx_details.category = 'Claim Payment' 
                    AND tx_details.transactions_id = tx.id) as status_payment_claim
                "),
                DB::raw("
                    (SELECT 
                        tx_details.value 
                    FROM transaction_details tx_details 
                    WHERE tx_details.category = 'Distributor' 
                    AND tx_details.transactions_id = tx.id) as distributor
                ")
            ])
            ->where('tx.transaction_type_id', $txType->id)
            ->orderByDesc('tx.created_at')
            ->get();       

        return Inertia::render('Finance/Claim/ListClaimPromo', compact('result'));
    }

    public function changeStatusPaymentClaim(Transactions $transactions)
    {
        DB::transaction(function() use ($transactions) {
            // Memastikan transaksi memuat transactionDetails
            $transactions = Transactions::with('transactionDetails')
                ->whereHas('transactionDetails', function($query) {
                    $query->where('category', 'Claim Payment')
                        ->where('value', 'UNPAID');
                })
                ->find($transactions->id); // Menggunakan find untuk memastikan ID benar

            if ($transactions) {
                foreach ($transactions->transactionDetails as $detail) {
                    if ($detail->category === 'Claim Payment' && $detail->value === 'UNPAID') {
                        $detail->update(['value' => 'PAID']);
                    }
                }
            }
        });

        return back()->with('success', 'Berhasil memperbarui status!');
    }

    public function showClaimPromo(Transactions $transactions)
    {
        $transactions->load('transactionItems', 'transactionDetails');

        dd($transactions->transactionItems->toArray());

        return Inertia::render('Finance/Claim/ClaimPromoDetail', compact('transactions'));
    }
}
