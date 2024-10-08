 <?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/inspect/po-document',function() {
    return view('documents.purchase-order-document');
});

Route::get('/inspect/sso-document', function() {
    return view('documents.sub-sales-order-document');
});

Route::get('/test-view', function() {
    return Inertia::render('Test');
});


// Dashboard Routes
Route::middleware(['auth', 'verified', 'secure.path'])->group(function() {
    Route::get('/dashboard-finance', [App\Http\Controllers\DashboardController::class, 'indexFinanceDashboard'])->name('dashboard.finance');
    Route::get('/dashboard-warehouse', [App\Http\Controllers\DashboardController::class, 'indexWarehouseDashboard'])->name('dashboard.warehouse');
    Route::get('/dashboard-procurement', [App\Http\Controllers\DashboardController::class, 'indexProcurementDashboard'])->name('dashboard.procurement');
    Route::get('/dashboard-admin',[App\Http\Controllers\DashboardController::class, 'indexAdminDashboard'])->name('dashboard.admin');
    Route::get('/dashboard-aging-finance',[App\Http\Controllers\DashboardController::class, 'indexAgingFinanceDashboard'])->name('dashboard.aging-finance');
    Route::get('/dashboard-sales', [App\Http\Controllers\DashboardController::class, 'indexSalesDashboard'])->name('dashboard.sales');
    Route::get('/dashboard-marketing', [App\Http\Controllers\DashboardController::class, 'indexMarketingDashboard'])->name('dashboard.marketing');
});

Route::middleware(['auth', 'secure.path', 'web'])->group(function() {

    //ADMIN ROUTES
    Route::name('admin.')->group(function() {
        Route::get('/sales-reports', fn() => Inertia::render('Admin/SalesReports'))->name('sales-reports');

        //USER
        Route::prefix('user-management')->group(function() {
            Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('user-management');
            Route::get('/create-user', [App\Http\Controllers\UserController::class,'create'])->name('create-user');
            Route::post('/create-user', [App\Http\Controllers\UserController::class,'store'])->name('create-user.post');
            Route::get('/{user}/edit', [App\Http\Controllers\UserController::class,'edit'])->name('edit-user');
            Route::patch('/{user}/update-user', [App\Http\Controllers\UserController::class,'update'])->name('edit-user.patch');
            Route::delete('/{user}', [App\Http\Controllers\UserController::class,'destroy'])->name('delete-user.delete');
        });

        //ROLE
        Route::prefix('role-management')->group(function() {
            Route::get('/', [App\Http\Controllers\RoleController::class,'index'])->name('role-management');
            Route::get('/create-role', [App\Http\Controllers\RoleController::class,'create'])->name('create-role');
            Route::post('/create-role', [App\Http\Controllers\RoleController::class,'store'])->name('create-role.post');
            Route::get('/{role}/edit', [App\Http\Controllers\RoleController::class,'edit'])->name('edit-role');
            Route::patch('/{role}/update-role', [App\Http\Controllers\RoleController::class,'update'])->name('edit-role.patch');
            Route::delete('/{role}', [App\Http\Controllers\RoleController::class,'destroy'])->name('delete-role.delete');
        });

        //DIVISION
        Route::prefix('division-management')->group(function() {
            Route::get('/', [App\Http\Controllers\DivisionController::class,'index'])->name('division-management');
            Route::get('/create-division', [App\Http\Controllers\DivisionController::class,'create'])->name('create-division');
            Route::post('/create-division', [App\Http\Controllers\DivisionController::class,'store'])->name('create-division.post');
            Route::get('/{division}/edit', [App\Http\Controllers\DivisionController::class,'edit'])->name('edit-division');
            Route::patch('/{division}/update-division', [App\Http\Controllers\DivisionController::class,'update'])->name('edit-division.patch');
            Route::delete('/{division}/delete-division', [App\Http\Controllers\DivisionController::class,'destroy'])->name('delete-division.delete');
        });

        //Menu Access
        Route::prefix('menu-access-management')->group(function () {
            Route::get('/', [App\Http\Controllers\MenuAccessController::class,'index'])->name("menu-access-management");
        });

        Route::prefix('customer-management')->group(function() {
            Route::get('', [App\Http\Controllers\PartiesController::class, 'createCustomer'])->name('parties.customer');
            Route::post('/create-customer', [App\Http\Controllers\PartiesController::class, 'store'])->name('parties.customer.post');
            Route::get('/edit-customer/{parties}', [App\Http\Controllers\PartiesController::class, 'edit'])->name('parties.customer.edit');
            Route::patch('/update-customer/{parties}', [App\Http\Controllers\PartiesController::class, 'update'])->name('parties.customer.update');
            Route::delete('/delete-customer/{parties}', [App\Http\Controllers\PartiesController::class, 'destroyCustomer'])->name('parties.customer.delete');
        });

        Route::prefix('supplier-management')->group(function() {
            Route::get('', [App\Http\Controllers\PartiesController::class, 'createSupplier'])->name('parties.supplier');
            Route::post('/create-supplier', [App\Http\Controllers\PartiesController::class, 'storeSupplier'])->name('parties.supplier.post');
            Route::get('/edit-supplier/{parties}', [App\Http\Controllers\PartiesController::class, 'editSupplier'])->name('parties.supplier.edit');
            Route::patch('/update-supplier/{parties}', [App\Http\Controllers\PartiesController::class, 'updateSupplier'])->name('parties.supplier.update');
            Route::delete('/delete-supplier/{parties}', [App\Http\Controllers\PartiesController::class, 'destroySupplier'])->name('parties.supplier.delete');
        });

        Route::prefix('product-management')->group(function () {
            Route::get('', [App\Http\Controllers\ProductsController::class, 'createProduct'])->name('products');
            Route::post('/create-product', [App\Http\Controllers\ProductsController::class, 'store'])->name('products.post');
            Route::get('/edit-product/{product}', [App\Http\Controllers\ProductsController::class, 'edit'])->name('products.edit');
            Route::patch('/update-product/{product}', [App\Http\Controllers\ProductsController::class, 'update'])->name('products.update');
            Route::delete('/delet-product/{product}', [App\Http\Controllers\ProductsController::class, 'destroy'])->name('products.delete');
        });
    });

    // Procurement Routes
    Route::name('procurement.')->group(function() {
        //Purchase Order
        Route::get('/purchase-order', [App\Http\Controllers\PurchaseOrderController::class, 'create'])->name('purchase-order');
        Route::post('/purchase-order', [App\Http\Controllers\PurchaseOrderController::class, 'store'])->name('create-po');
        Route::get('/purchase-orders', [App\Http\Controllers\PurchaseOrderController::class, 'index'])->name('purchase-order-list');
        Route::get('/purchase-order/detail/{transaction}', [App\Http\Controllers\PurchaseOrderController::class, 'show'])->name('purchase-order.detail');
        Route::get('/generate-po-document/{transactions}', [App\Http\Controllers\PurchaseOrderController::class, 'generatePurchaseOrderDocument'])->name('generate-po-document');

        //Sub sales Order
        Route::get('/sub-sales-order', [App\Http\Controllers\SubSalesOrderController::class, 'create'])->name('sales-order');
        Route::post('/sub-sales-order', [App\Http\Controllers\SubSalesOrderController::class, 'store'])->name('sales-order.post');
        Route::get('/sub-sales-orders', [App\Http\Controllers\SubSalesOrderController::class, 'index'])->name('sales-order-list');
        Route::get('/sub-sales-order/detail/{transaction}', [App\Http\Controllers\SubSalesOrderController::class, 'show'])->name('sales-order.detail');
        Route::get('/sub-sales-order/{po_number}', [App\Http\Controllers\PurchaseOrderController::class, 'getDataByPoNumber'])->name('get-data-po');
        Route::get('/generate-sso-document/{transactions}', [App\Http\Controllers\SubSalesOrderController::class, 'generateSubSalesOrderDocument'])->name('generate-sso-document');


        Route::get('/aging', fn() => Inertia::render('Procurement/Transaction/Aging'))->name('aging');
        Route::get('/transaction-list', fn() => Inertia::render('Procurement/Transaction/TransactionList'))->name('transaction-list');
    });

    // Finance Routes
    Route::name('finance.')->group(function () {
        Route::get('/create-po', fn() => Inertia::render('Finance/Purchase/CreatePurchaseOrder'))->name('create-po');
        Route::get('/create-so', fn() => Inertia::render('Finance/ItemsReceipt/CreateSalesOrder'))->name('create-so'); 
        Route::get('/aging', fn() => Inertia::render('Finance/Bill/Aging'))->name('aging');
        Route::get('/invoices', fn() => Inertia::render('Finance/Bill/Sales'))->name('invoices');
        Route::get('/claim-promo', fn() => Inertia::render('Finance/Claim/ClaimPromo'))->name('claim-promo');
        Route::get('/detail-claim', fn() => Inertia::render('Finance/Claim/ClaimPromoDetail'))->name('claim-promo.detail');
        Route::get('/claim-promo-list', fn() => Inertia::render('Finance/Claim/ListClaimPromo'))->name('list-claim-promo');
        Route::get('/form-claim', fn() => Inertia::render('Finance/Claim/FormClaim'))->name('form-claim-promo');
        // Route::get('/test', fn() => Inertia::render('Test'));
    });

    //warehouse Routes
    Route::name('warehouse.')->group(function() {
        Route::get('/stock-goods', fn() => Inertia::render('Warehouse/StockItems'))->name('stock-goods');
        Route::get('/dnp-stock-goods', fn() => Inertia::render('Warehouse/DnpWarehouse/Stocks'))->name('dnp-stock-goods');
        Route::get('/dnp-expired-stocks', fn() => Inertia::render('Warehouse/DnpWarehouse/ExpiredStocks'))->name('dnp-expired-stocks');
        Route::get('/dku-stock-goods', fn() => Inertia::render('Warehouse/DkuWarehouse/Stocks'))->name('dku-stock-goods');
        Route::get('/dku-expired-stocks', fn() => Inertia::render('Warehouse/DkuWarehouse/ExpiredStocks'))->name('dku-expired-stocks');
        Route::get('/incoming-item', fn() => Inertia::render('Warehouse/IncomingItem'))->name('incoming-item');
        Route::get('/return-broken-goods', fn() => Inertia::render('Warehouse/BrokenGoods/ReturnBrokenGoods'))->name('return-broken-goods');
        Route::get('/destruction-broken-goods', fn() => Inertia::render('Warehouse/BrokenGoods/DestructionGoods'))->name('destruction-broken-goods');
        Route::get('/travel-document', fn() => Inertia::render('Warehouse/TravelDocument'))->name('travel-document');
        Route::get('/list-travel-document', fn() => Inertia::render('Warehouse/ListTravelDocument'))->name('list-travel-document');
        Route::get('/create-travel-document', fn() => Inertia::render('Warehouse/CreateTravelDocument'))->name('create-travel-document');
        Route::get('/return-item', fn() => Inertia::render('Warehouse/DnpWarehouse/ReturnItem'))->name('return-item');
        Route::get('/booking-requests', fn() => Inertia::render('Warehouse/BookingItem/BookingRequest'))->name('booking-request');
        // Route::get('/list-travel-document', fn() => Inertia::render);
    });

    //Aging Finance Routes
    Route::name('aging-finance.')->group(function() {
        Route::get('/aging', fn() => Inertia::render('AgingFinance/Transaction/Aging'))->name('aging');
        Route::get('/list-transactions', fn() => Inertia::render('AgingFinance/Transaction/ListTransaction'))->name('list-transaction');
        //nanti ganti by id (model)
        Route::get('/pay', fn() => Inertia::render('AgingFinance/Transaction/DetailTransaction'))->name('detail-transaction.pay');
        Route::get('/invoice-dnp', fn() => Inertia::render('AgingFinance/Sales/InvoiceDNP'))->name('invoice-dnp');
        Route::get('/create-invoice-dnp', fn() => Inertia::render('AgingFinance/Sales/CreateInvoiceDNP'))->name('create-invoice-dnp');

        Route::get('/whatsapp-message', fn() => Inertia::render('AgingFinance/WhatsappMessage'))->name('whatsapp-message');
        Route::get('/test-send-message', [App\Http\Controllers\MessageTemplateController::class, 'create'])->name('test-whatsapp-message');
        Route::post('/save-message', [App\Http\Controllers\MessageTemplateController::class, 'saveMessage'])->name('save-message');
    });

    Route::name('sales.')->group(function() {
        Route::get('/create-co', [App\Http\Controllers\CustomerOrdersController::class, 'create'])->name('create-co');
        Route::post('/post-create-co', [App\Http\Controllers\CustomerOrdersController::class, 'store'])->name('create-co.post');
        Route::get('/list-co', [App\Http\Controllers\CustomerOrdersController::class, 'index'])->name('list-co');
        Route::get('/customer-order/detail/{transactions}', [App\Http\Controllers\CustomerOrdersController::class, 'show'])->name('detail-co');
    });
});

require __DIR__.'/auth.php';