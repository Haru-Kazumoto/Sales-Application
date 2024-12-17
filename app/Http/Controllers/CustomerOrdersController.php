<?php

namespace App\Http\Controllers;

use App\Http\Services\CustomerOrderServices;
use App\Http\Services\PartiesServices;
use App\Http\Services\ProductServices;
use App\Models\Lookup;
use App\Models\Parties;
use App\Models\ProductJournal;
use App\Models\Products;
use App\Models\TransactionDetail;
use App\Models\TransactionItem;
use App\Models\Transactions;
use App\Models\TransactionType;
use App\Models\Warehouse;
use App\Utils\DocumentNumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CustomerOrdersController extends Controller
{
    protected $productServices;
    protected $customerOrderServices;
    protected $partiesService;

    public function __construct(ProductServices $productServices, CustomerOrderServices $customerOrderServices, PartiesServices $partiesServices)
    {
        $this->productServices = $productServices;
        $this->customerOrderServices = $customerOrderServices;
        $this->partiesService = $partiesServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer_orders = Transactions::with('transactionType', 'transactionDetails', 'transactionItems')
            ->where('status', '<>', 'PENDING')
            ->whereHas('transactionType', function ($query) {
                $query->whereIn('name', ['Sales Order', 'Sales Order Need Approval']);
            })
            ->whereHas('transactionDetails', function ($query) {
                $query
                    ->where('category', 'Discount Submission')
                    ->where('value', 'NOT SUBMIT');
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $draf_customer_orders = Transactions::with('transactionType', 'transactionDetails', 'transactionItems')
            ->where('status', '<>', 'PENDING')
            ->whereHas('transactionType', function ($query) {
                $query->whereIn('name', ['Sales Order', 'Sales Order Need Approval']);
            })
            ->whereHas('transactionDetails', function ($query) {
                $query
                    ->where('category', 'Discount Submission')
                    ->where('value', 'SUBMIT');
            })
            // ->whereHas('transactionDetails', function ($query) {
            //     $query
            //         ->where('category', 'Submission Status')
            //         ->where('value', 'true');
            // })
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('Sales/Sale/ListCO', compact('customer_orders', 'draf_customer_orders'));
    }

    /**
     * Index draf customer order on marketing page
     *
     * @return Response
     */
    public function indexDrafCustomerOrder()
    {
        $customer_orders = Transactions::with('transactionType', 'transactionDetails', 'transactionItems')
            ->whereHas('transactionType', function ($query) {
                $query->whereIn('name', ['Sales Order', 'Sales Order Need Approval']);
            })
            ->whereHas('transactionDetails', function ($query) {
                $query
                    ->where('category', 'Discount Submission')
                    ->where('value', 'SUBMIT');
            })
            ->whereHas('transactionDetails', function ($query) {
                $query
                    ->where('category', 'Submission Status')
                    ->where('value', '-'); //it means pending
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return Inertia::render('Marketing/DrafCustomerOrder', compact('customer_orders'));
    }

    /**
     *
     *
     * @param \App\Models\Transactions $transactions
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processDrafCustomerOrder(Transactions $transactions, Request $request)
    {
        DB::transaction(function () use ($transactions, $request) {
            $tx_details = $transactions->transactionDetails;
            $valueRequest = $request->valueRequest;

            foreach ($tx_details as $detail) {
                if ($detail->category === 'Submission Status') {
                    $detail->update(['value' => $valueRequest]);
                }
            }
        });

        return redirect()->route('marketing.draf-co')->with('success', 'Berhasil diapprove!');
    }

    /**
     * Set discount on customer order has submission discount in it
     *
     * @param \App\Models\Transactions $transactions
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setDiscount(TransactionItem $transactionItem, Request $request)
    {
        DB::transaction(function () use ($transactionItem, $request) {
            $transactionItem->update([
                'amount' => $request->newAmount,
                'total_price' => $request->newTotalPrice,
            ]);
        });

        return back()->with('success', 'Diskon berhasil dimasukan!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createDnp(): Response
    {
        $coNumber = DocumentNumberGenerator::generateCoNumber(
            'CO-',
            'transactions',
            'document_code',
        );
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $customers = Parties::where('type_parties', 'CUSTOMER')
            ->where('users_id', Auth::user()->id)
            ->with('partiesGroup')
            ->get();
        $payment_terms = Lookup::where('category', 'PAYMENT_TERM')->get();
        $products = $this->productServices->getStockProducts("DNP");
        $all_products = Products::query()->get();

        return Inertia::render('Sales/Sale/CreateCoDnp', [
            'coNumber' => $coNumber,
            'dateNow' => $dateNow,
            'customers' => $customers,
            'payment_terms' => $payment_terms,
            'products' => $products,
            'all_products' => $all_products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createDku(): Response
    {
        $coNumber = DocumentNumberGenerator::generateCoNumber(
            'CO-',
            'transactions',
            'document_code',
        );
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $customers = Parties::where('type_parties', 'CUSTOMER')
            ->where('users_id', Auth::user()->id)
            ->get();
        $payment_terms = Lookup::where('category', 'PAYMENT_TERM')->get();
        $products = $this->productServices->getStockProducts("DKU");
        $all_products = Products::query()->get();

        return Inertia::render('Sales/Sale/CreateCoDku', [
            'coNumber' => $coNumber,
            'dateNow' => $dateNow,
            'customers' => $customers,
            'payment_terms' => $payment_terms,
            'products' => $products,
            'all_products' => $all_products,
        ]);
    }

    public function getBatchCode($product_id, $sell_quantity): array
    {
        $result = [];
        $needed_stock = $sell_quantity;

        $batch_codes = DB::table("stock_product_by_batch_code")
            ->where("product_id", "=", $product_id)
            ->where("last_stock", ">", 0)
            ->orderBy("first_in_date", "asc")
            ->get();

        if (count($batch_codes) < 1) {
            return [];
        }


        foreach ($batch_codes as $code) {
            $remaining_stock = $code->last_stock;

            if ($needed_stock > 0) {

                $result[] = [
                    "code" => $code->batch_code,
                    "out_stock" => $remaining_stock < $needed_stock ? $remaining_stock : $needed_stock
                ];

                $needed_stock -= $remaining_stock;
            }
        }

        return $result;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeDnp(Request $request)
    {
        // dd($request->all()); // Digunakan untuk debugging

        // Validasi input request
        $request->validate([
            'document_code' => 'required|string',
            'term_of_payment' => 'required|numeric',
            'due_date' => 'required|date',
            'sub_total' => 'required|numeric',
            'total' => 'required|numeric',
            'tax_amount' => 'nullable|numeric',
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
            'transaction_items.*.discount_1' => 'nullable|numeric',
            'transaction_items.*.discount_2' => 'nullable|numeric',
            'transaction_items.*.discount_3' => 'nullable|numeric',
            'transaction_items.*.product' => 'required_if:transaction_items.*.product_id,null|array',
            'transaction_items.*.product.code' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.unit' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.name' => 'required_with:transaction_items.*.product|string',
        ]);

        // Gunakan transaksi database
        DB::transaction(function () use ($request) {
            $tx_type = TransactionType::where('name', 'Sales Order Need Approval')->first(); //8
            $delivery = $request->transaction_details[4]['value'];
            $customer_name = $request->transaction_details[1]['value']; //customer name

            $customer = Parties::where('name', $customer_name)->first();

            // Simpan customer order
            $transaction = Transactions::create([
                'document_code' => $request->input('document_code'),
                'correlation_id' => rand(10000, 88888),
                'created_by' => Auth::user()->id,
                'term_of_payment' => $request->input('term_of_payment'),
                'due_date' => $request->input('due_date'),
                'description' => $request->input('description'),
                'sub_total' => $request->input('sub_total'),
                'total' => $request->input('total'),
                'tax_amount' => $request->input('tax_amount'),
                'transaction_type_id' => 70,
                'customer_id' => $customer->id,
                'status' => 'PENDING',
            ]);

            // Simpan transaction details
            foreach ($request->input('transaction_details') as $detail) {
                TransactionDetail::create([
                    'name' => $detail['name'],
                    'category' => $detail['category'],
                    'value' => $detail['value'],
                    'data_type' => $detail['data_type'],
                    'transactions_id' => $transaction->id,
                ]);
            }

            // Simpan transaction items
            foreach ($request->input('transaction_items') as $txItem) {
                $product = Products::where('id', $txItem['product_id'])->first();
                $warehouse = Warehouse::where('name', 'DNP')->first();

                // Simpan transaction item
                TransactionItem::create([
                    'unit' => $txItem['unit'],
                    'quantity' => $txItem['quantity'],
                    'tax_amount' => $txItem['tax_amount'],
                    'amount' => $txItem['amount'],
                    'tax_id' => $txItem['tax_id'],
                    'total_price' => $txItem['total_price'],
                    'discount_1' => $txItem['discount_1'],
                    'discount_2' => $txItem['discount_2'],
                    'discount_3' => $txItem['discount_3'],
                    // 'total_price_discount' => $txItem['total_price_discount'],
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, // Menyimpan product_id dari produk yang ditemukan atau baru
                ]);

                // decrease quantity at warehouse if the delivery is not 'DIRECT', 'DIRECT_DEPO', 'DO'
                if ($delivery && !in_array($delivery, ['DIRECT', 'DIRECT_DEPO', 'DO'])) {
                    foreach ($txItem['product_journals'] as $journal) {
                        // ngedebet jumlah barang dari satu product dengan batch_code metode FIFO
                        $batch_codes = $this->getBatchCode($product->id, $journal["quantity"]);
                        if (count($batch_codes) > 0) {
                            foreach ($batch_codes as $batch_code) {
                                ProductJournal::create([
                                    'quantity' => $batch_code["out_stock"],
                                    'amount' => $txItem['amount'],
                                    'action' => $journal['action'],
                                    'batch_code' => $batch_code["code"],
                                    // 'expiry_date' => $journal['expiry_date'],
                                    'transactions_id' => $transaction->id,
                                    'warehouse_id' => $warehouse->id,
                                    'product_id' => $product->id,
                                ]);
                            }
                        }
                    }
                }
            }
        });

        return redirect()->route('sales.create-co')->with('success', 'Customer Order berhasil disubmit!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeDku(Request $request)
    {
        // dd($request->all()); // Digunakan untuk debugging

        // Validasi input request
        $request->validate([
            'document_code' => 'required|string',
            'term_of_payment' => 'required|numeric',
            'due_date' => 'required|date',
            'sub_total' => 'required|numeric',
            'total' => 'required|numeric',
            'tax_amount' => 'nullable|numeric',
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
            'transaction_items.*.discount_1' => 'nullable|numeric',
            'transaction_items.*.discount_2' => 'nullable|numeric',
            'transaction_items.*.discount_3' => 'nullable|numeric',
            'transaction_items.*.product' => 'required_if:transaction_items.*.product_id,null|array',
            'transaction_items.*.product.code' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.unit' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.name' => 'required_with:transaction_items.*.product|string',
        ]);

        // Gunakan transaksi database
        DB::transaction(function () use ($request) {
            $tx_type = TransactionType::where('name', 'Sales Order Need Approval')->first();
            $delivery = $request->transaction_details[4]['value'];
            $customer_name = $request->transaction_details[1]['value']; //customer name

            $customer = Parties::where('name', $customer_name)->first();

            // Simpan customer order
            $transaction = Transactions::create([
                'document_code' => $request->input('document_code'),
                'correlation_id' => rand(10000, 88888),
                'created_by' => Auth::user()->id,
                'term_of_payment' => $request->input('term_of_payment'),
                'due_date' => $request->input('due_date'),
                'description' => $request->input('description'),
                'sub_total' => $request->input('sub_total'),
                'total' => $request->input('total'),
                'tax_amount' => $request->input('tax_amount'),
                'transaction_type_id' => 70, // Atur transaction_type_id untuk CO
                'customer_id' => $customer->id,
                'status' => 'PENDING',
            ]);

            // Simpan transaction details
            foreach ($request->input('transaction_details') as $detail) {
                TransactionDetail::create([
                    'name' => $detail['name'],
                    'category' => $detail['category'],
                    'value' => $detail['value'],
                    'data_type' => $detail['data_type'],
                    'transactions_id' => $transaction->id,
                ]);
            }

            // Simpan transaction items
            foreach ($request->input('transaction_items') as $txItem) {
                $product = Products::where('id', $txItem['product_id'])->first();
                $warehouse = Warehouse::where('name', 'DKU')->first();

                // Simpan transaction item
                TransactionItem::create([
                    'unit' => $txItem['unit'],
                    'quantity' => $txItem['quantity'],
                    'tax_amount' => $txItem['tax_amount'],
                    'amount' => $txItem['amount'],
                    'tax_id' => 3,
                    'total_price' => $txItem['total_price'],
                    'discount_1' => $txItem['discount_1'],
                    'discount_2' => $txItem['discount_2'],
                    'discount_3' => $txItem['discount_3'],
                    // 'total_price_discount' => $txItem['total_price_discount'],
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, // Menyimpan product_id dari produk yang ditemukan atau baru
                ]);

                // decrease quantity at warehouse if the delivery is not 'DIRECT', 'DIRECT_DEPO', 'DO'
                if ($delivery && !in_array($delivery, ['DIRECT', 'DIRECT_DEPO', 'DO'])) {
                    foreach ($txItem['product_journals'] as $journal) {
                        // ngedebet jumlah barang dari satu product dengan batch_code metode FIFO
                        $batch_codes = $this->getBatchCode($product->id, $journal["quantity"]);
                        if (count($batch_codes) > 0) {
                            foreach ($batch_codes as $batch_code) {
                                ProductJournal::create([
                                    'quantity' => $batch_code["out_stock"],
                                    'amount' => $txItem['amount'],
                                    'action' => $journal['action'],
                                    'batch_code' => $batch_code["code"],
                                    // 'expiry_date' => $journal['expiry_date'],
                                    'transactions_id' => $transaction->id,
                                    'warehouse_id' => $warehouse->id,
                                    'product_id' => $product->id,
                                ]);
                            }
                        }
                    }
                }
            }
        });

        return redirect()->route('sales.create-co-dku')->with('success', 'Customer Order berhasil disubmit!');
    }

    public function indexCoNeedApprove()
    {
        // panggil view invoice_need_approval
        $data = DB::table("invoice_need_approval")
            ->where('status', '<>', 'PENDING')
            ->orderBy("tanggal_co", "desc")
            ->get();
        $not_process_yet_data = DB::table("invoice_need_approval")
            ->where('status', 'PENDING')
            ->orderBy("tanggal_co", "desc")
            ->get();

        return Inertia::render('AgingFinance/Transaction/ApprovalCo', compact('data', 'not_process_yet_data'));
    }

    // bug harusnya datnaya itu muncul karna data nya setelah di approve jadi 8 bukan 70
    public function processCustomerOrder(Transactions $transactions, Request $request)
    {
        DB::transaction(function () use ($transactions, $request) {
            $status = $request->valueRequest;
            $notes = $request->notes;

            $transactions->update([
                'transaction_type_id' => 8,
                'status' => $status,
                'approval_notes' => $notes,
                'approved_by' => Auth::user()->id,
                'approve_at' => Carbon::now(),
            ]);

            // create logic to handle if the $status is REJECT then restore (insert) items to the product_journals again

            // $warehouse = Warehouse::where('name', $request->warehouse)->first();
            // $trx_items = $transactions->transactionItems()->get();

            // if($status === "APPROVE") {
            //     foreach ($trx_items as $trx_item) {
            //         $batch_codes = $this->getBatchCode($trx_item->product_id, $trx_item->quantity);
            //         if (count($batch_codes) > 0 ) {
            //             foreach ($batch_codes as $batch_code) {
            //                 ProductJournal::create([
            //                     'quantity' => $batch_code["out_stock"],
            //                     'amount' => $trx_item->amount * $batch_code["out_stock"],
            //                     'action' => "OUT",
            //                     'batch_code' => $batch_code["code"],
            //                     // 'expiry_date' => $journal['expiry_date'],
            //                     'transactions_id' => $transactions->id,
            //                     'warehouse_id' => $warehouse->id,
            //                     'product_id' => $trx_item->product_id,
            //                 ]);
            //             }
            //         }
            //     }
            // }
        });

        return redirect()->route('aging-finance.co.process')->with('success', 'CO Berhasil di proses!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transactions $transactions)
    {
        $transactions->load('transactionType', 'transactionDetails', 'transactionItems.product');

        return Inertia::render('Sales/Sale/DetailCO', ['customer_order' => $transactions]);
    }

    /**
     * Display the listing view travel document
     */
    public function createTravelDocument()
    {
        $customer_orders_dnp = $this->customerOrderServices->getTransactions("Sales Order", "false", 15, 'Warehouse', 'DNP');
        $customer_orders_dku = $this->customerOrderServices->getTransactions("Sales Order", "false", 15, 'Warehouse', 'DKU');

        return Inertia::render('Warehouse/ListTravelDocument', compact('customer_orders_dnp', 'customer_orders_dku'));
    }

    /**
     * Index list of travel documents
     */
    public function indexTravelDocuments()
    {
        $travel_documents_dnp = DB::table('transactions as t')
            ->join('transaction_details as td', function ($join) {
                $join->on('td.transactions_id', '=', 't.id')
                    ->where('td.category', '=', 'Warehouse')
                    ->where('td.value', '=', 'DNP');
            })
            ->select(
                't.id',
                't.document_code',
                DB::raw("(SELECT VALUE FROM transaction_details WHERE category = 'Customer' AND transactions_id = t.id) AS customer"),
                DB::raw("(SELECT VALUE FROM transaction_details WHERE category = 'Delivery' AND transactions_id = t.id) AS ekspedisi"),
                DB::raw("(SELECT VALUE FROM transaction_details WHERE category = 'Number Plate' AND transactions_id = t.id) AS no_pol"),
                DB::raw("(SELECT VALUE FROM transaction_details WHERE category = 'Driver' AND transactions_id = t.id) AS driver"),
                DB::raw("(SELECT VALUE FROM transaction_details WHERE category = 'Warehouse' AND transactions_id = t.id) AS gudang")
            )
            ->where('t.transaction_type_id', 65)
            ->get();

        $travel_documents_dku = DB::table('transactions as t')
            ->join('transaction_details as td', function ($join) {
                $join->on('td.transactions_id', '=', 't.id')
                     ->where('td.category', '=', 'Warehouse')
                     ->where('td.value', '=', 'DKU');
            })
            ->select(
                't.id',
                't.document_code',
                DB::raw("(SELECT VALUE FROM transaction_details WHERE category = 'Customer' AND transactions_id = t.id) AS customer"),
                DB::raw("(SELECT VALUE FROM transaction_details WHERE category = 'Delivery' AND transactions_id = t.id) AS ekspedisi"),
                DB::raw("(SELECT VALUE FROM transaction_details WHERE category = 'Number Plate' AND transactions_id = t.id) AS no_pol"),
                DB::raw("(SELECT VALUE FROM transaction_details WHERE category = 'Driver' AND transactions_id = t.id) AS driver"),
                DB::raw("(SELECT VALUE FROM transaction_details WHERE category = 'Warehouse' AND transactions_id = t.id) AS gudang")
            )
            ->where('t.transaction_type_id', 65)
            ->get();

        return Inertia::render('Warehouse/IndexTravelDocument', compact('travel_documents_dnp', 'travel_documents_dku'));
    }

    /**
     * Show the detail of travel document
     */
    public function detailTravelDocument(Transactions $transactions)
    {
        $transports = $this->partiesService->getPartiesByGroupAndType('VENDOR', 'Angkutan');

        // Load the necessary relationships
        $transactions->load('transactionType', 'transactionDetails', 'transactionItems.product');

        // Find the transactionDetails with category 'Warehouse' and get its value
        $prefixDocument = $transactions->transactionDetails
            ->where('category', 'Warehouse')
            ->pluck('value') // Get the value field
            ->first(); // Get the first result, in case there are multiple results

        // Set default value if no value found
        if (!$prefixDocument) {
            $prefixDocument = 'DNP'; // Default value if prefix not found
        }

        // Generate the document code with the prefixDocument
        $document_code = $prefixDocument . '/BKS/SJ/' . rand(10000, 99999);


        return Inertia::render('Warehouse/CreateTravelDocument', compact('transactions', 'document_code', 'transports'));
    }

    /**
     * Store a newly created Travel document resource in storage.
     */
    public function storeTravelDocument(Request $request)
    {

        $request->validate([
            'document_code' => 'required|string',
            'sub_total' => 'required|numeric',
            'total' => 'required|numeric',
            'tax_amount' => 'nullable|numeric',
            'transaction_details' => 'required|array',
            'transaction_details.*.name' => 'required|string',
            'transaction_details.*.category' => 'required|string',
            'transaction_details.*.value' => 'required|string',
            'transaction_details.*.data_type' => 'required|string',
            'transaction_items' => 'required|array',
            'transaction_items.*.unit' => 'required|string',
            'transaction_items.*.quantity' => 'required|numeric',
            'transaction_items.*.product_id' => 'required|numeric',
            'transaction_items.*.product' => 'required_if:transaction_items.*.product_id,null|array',
            'transaction_items.*.product.code' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.unit' => 'required_with:transaction_items.*.product|string',
            'transaction_items.*.product.name' => 'required_with:transaction_items.*.product|string',
        ]);

        // Gunakan transaksi database
        DB::transaction(function () use ($request) {
            $tx_type = TransactionType::where('name', 'Surat Jalan')->first();

            $transaction = Transactions::create([
                'document_code' => $request->input('document_code'),
                'correlation_id' => rand(10000, 99999),
                'sub_total' => $request->input('sub_total'),
                'term_of_payment' => $request->input('term_of_payment'),
                'due_date' => $request->input('due_date'),
                'total' => $request->input('total'),
                'tax_amount' => $request->input('tax_amount'),
                'created_by' => Auth::user()->id,
                'description' => $request->input('description'),
                'transaction_type_id' => $tx_type->id,
            ]);

            $tx_details = $request->transaction_details;
            $tx = Transactions::where('document_code', $tx_details[0]['value'])->first();
            if ($tx) {
                $status = TransactionDetail::where('transactions_id', $tx->id)
                    ->where('category', 'Generating')
                    ->first();
                $status->update(['value' => 'true']);
            }

            // Simpan transaction details
            foreach ($request->transaction_details as $detail) {
                TransactionDetail::create([
                    'name' => $detail['name'],
                    'category' => $detail['category'],
                    'value' => $detail['value'],
                    'data_type' => $detail['data_type'],
                    'transactions_id' => $transaction->id,
                ]);
            }

            // Simpan transaction items
            foreach ($request->transaction_items as $txItem) {
                $product = Products::find($txItem['product_id']);

                // Simpan transaction item
                TransactionItem::create([
                    'unit' => $txItem['unit'],
                    'quantity' => $txItem['quantity'],
                    'tax_amount' => $txItem['tax_amount'],
                    'amount' => $txItem['amount'],
                    'total_price' => $txItem['total_price'],
                    'discount_1' => $txItem['discount_1'],
                    'discount_2' => $txItem['discount_2'],
                    'discount_3' => $txItem['discount_3'],
                    'transactions_id' => $transaction->id,
                    'product_id' => $product->id, // Menyimpan product_id dari produk yang ditemukan atau baru
                ]);
            }
        });

        return redirect()->route('warehouse.travel-document')->with('success', 'Surat jalan berhasil dibuat!');
    }

    public function generateTravelDocument(Transactions $transactions)
    {
        // Memuat relasi yang diperlukan dan memfilter product journals dengan action 'IN'
        $transactions
            ->load([
                'transactionDetails',
            ]);

        // Mengambil detail berdasarkan kategori
        $customer = $transactions->transactionDetails->firstWhere('category', "Customer")->value ?? null;
        $co_number = $transactions->transactionDetails->firstWhere('category', "CO Number")->value ?? null;
        $customer_address = $transactions->transactionDetails->firstWhere('category', "Customer Address")->value ?? null;
        $number_plate = $transactions->transactionDetails->firstWhere('category', "Number Plate")->value ?? null;
        $travel_document_date = Carbon::parse($transactions->transactionDetails->firstWhere('category', 'Travel Document Date')->value)->format('d F Y');
        $warehouse = $transactions->transactionDetails->firstWhere('category', 'Warehouse')->value ?? '';

        $products = DB::table('transaction_items as ti')
            ->join('transactions as t', 'ti.transactions_id', '=', 't.id')
            ->join('product_journal as pj', 'pj.transactions_id', '=', 't.id')
            ->join('products as pd', 'pd.id', '=', 'pj.product_id')
            ->select(
                't.id',
                'pj.quantity',
                'ti.unit',
                'pj.batch_code',
                'pd.name'
            )
            ->where('t.document_code', $co_number)
            ->get();

        // Data yang akan dikirimkan ke view PDF
        $data = [
            'travel_document' => $transactions, // document_code, produk
            'products' => $products,
            'customer' => $customer, // nama customer
            'customer_address' => $customer_address,
            'number_plate' => $number_plate,
            'travel_document_date' => $travel_document_date,
            'warehouse' => $warehouse,
        ];

        $pdf = Pdf::loadView('documents.travel-document', $data);

        return $pdf->stream('travel_document_' . rand(10000, 90000) . '.pdf');
    }
}
