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

        Route::prefix('transport-management')->group(function() {
            Route::get('', [App\Http\Controllers\TransportController::class, 'createTransport'])->name('create-transports');
            Route::post('/post', [App\Http\Controllers\TransportController::class, 'storeTransport'])->name('transport.post');
            Route::get('/edit/{parties}', [App\Http\Controllers\TransportController::class, 'update'])->name('transport.edit');
            Route::patch('/update/{parties}', [App\Http\Controllers\TransportController::class, 'edit'])->name('transport.update');
            Route::delete('/delete/{parties}', [App\Http\Controllers\TransportController::class, 'destroy'])->name('transport.delete');
        }); 

        Route::prefix('unit-management')->group(function() {
            Route::get('', [App\Http\Controllers\UnitController::class, 'createUnit'])->name('create-unit');
            Route::get('/edit/{unit}', [App\Http\Controllers\UnitController::class, 'update'])->name('unit.edit');
            Route::post('/post', [App\Http\Controllers\UnitController::class, 'storeUnit'])->name('unit.post');
            Route::patch('/update/{unit}', [App\Http\Controllers\UnitController::class, 'edit'])->name('unit.update');
            Route::delete('/delete/{unit}', [App\Http\Controllers\UnitController::class, 'destroy'])->name('unit.delete');
        });

        Route::prefix('driver-management')->group(function() {
            Route::get('', [App\Http\Controllers\DriverController::class, 'createDriver'])->name('create-driver');
            Route::get('/edit/{driver}', [App\Http\Controllers\DriverController::class, 'editDriver'])->name('driver.edit');
            Route::post('/post', [App\Http\Controllers\DriverController::class, 'storeDriver'])->name('driver.post');
            Route::patch('/update/{driver}', [App\Http\Controllers\DriverController::class, 'updateDriver'])->name('driver.update');
            Route::delete('/delete/{driver}', [App\Http\Controllers\DriverController::class, 'deleteDriver'])->name('driver.delete');
        }); 

        Route::prefix('storehouse-management')->group(function() {
            Route::get('', [App\Http\Controllers\StorehouseController::class, 'createStorehouse'])->name('create-storehouse');
            Route::get('/edit/{storehouse}', [App\Http\Controllers\StorehouseController::class, 'editStorehouse'])->name('storehouse.edit');
            Route::post('/post', [App\Http\Controllers\StorehouseController::class, 'storeStorehouse'])->name('storehouse.post');
            Route::patch('/update/{storehouse}', [App\Http\Controllers\StorehouseController::class, 'updateStorehouse'])->name('storehouse.update');
            Route::delete('/delete/{storehouse}', [App\Http\Controllers\StorehouseController::class, 'deleteStorehouse'])->name('storehouse.delete');
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
        Route::get('/stock-goods', [App\Http\Controllers\ProductsController::class, 'indexAllWarehouseProducts'])->name('stock-goods');

        Route::get('/dnp-stock-goods', [App\Http\Controllers\ProductsController::class, 'indexDNPWarehouseProducts'])->name('dnp-stock-goods');
        Route::get('/dku-stock-goods', [App\Http\Controllers\ProductsController::class, 'indexDKUWarehouseProducts'])->name('dku-stock-goods');
        Route::get('/dnp-expired-stocks', [App\Http\Controllers\ProductsController::class, 'indexDnpWarehouseExpiredProducts'])->name('dnp-expired-stocks');
        Route::get('/dku-expired-stocks', [App\Http\Controllers\ProductsController::class, 'indexDkuWarehouseExpiredProducts'])->name('dku-expired-stocks');
        Route::get('/return-broken-goods', fn() => Inertia::render('Warehouse/BrokenGoods/ReturnBrokenGoods'))->name('return-broken-goods');
        Route::get('/destruction-broken-goods', fn() => Inertia::render('Warehouse/BrokenGoods/DestructionGoods'))->name('destruction-broken-goods');
        
        Route::get('/incoming-item', [App\Http\Controllers\ProductsController::class, 'incomingProducts'])->name('incoming-item');
        Route::get('/incoming-item/{sso_number}', [App\Http\Controllers\SubSalesOrderController::class, 'getDataBySsoNumber'])->name('process-sso-data');
        Route::post('/store-products', [App\Http\Controllers\ProductsController::class,'storeProducts'])->name('store-products');

        Route::get('/travel-document', [App\Http\Controllers\CustomerOrdersController::class, 'createTravelDocument'])->name('travel-document');
        Route::get('/list-travel-document', [App\Http\Controllers\CustomerOrdersController::class, 'detailTravelDocument'])->name('list-travel-document');
        Route::get('/create-travel-document/{transactions}', [App\Http\Controllers\CustomerOrdersController::class, 'detailTravelDocument'])->name('create-travel-document');
        Route::post('/store-travel-document', [App\Http\Controllers\CustomerOrdersController::class, 'storeTravelDocument'])->name('travel-document.post');
        Route::get('/index-travel-document', [App\Http\Controllers\CustomerOrdersController::class, 'indexTravelDocuments'])->name('index-travel-document');

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
        Route::get('/invoice-dnp', [App\Http\Controllers\InvoiceController::class, 'listDnpInvoice'])->name('invoice-dnp');
        Route::get('/invoice-dku', [App\Http\Controllers\InvoiceController::class, 'listDkuInvoice'])->name('invoice-dku');
        Route::get('/list-invoices', [App\Http\Controllers\InvoiceController::class, 'indexInvoices'])->name('list-invoice');
        Route::get('/create-invoice/{transactions}', [App\Http\Controllers\InvoiceController::class, 'createInvoice'])->name('create-invoice');
        Route::post('/store-invoice', [App\Http\Controllers\InvoiceController::class, 'storeInvoice'])->name('invoice.store');

        Route::get('/whatsapp-message', fn() => Inertia::render('AgingFinance/WhatsappMessage'))->name('whatsapp-message');
        Route::get('/test-send-message', [App\Http\Controllers\MessageTemplateController::class, 'create'])->name('test-whatsapp-message');
        Route::post('/save-message', [App\Http\Controllers\MessageTemplateController::class, 'saveMessage'])->name('save-message');
    });

    Route::name('sales.')->group(function() {
        Route::get('/create-co-dnp', [App\Http\Controllers\CustomerOrdersController::class, 'createDnp'])->name('create-co');
        Route::get('/create-co-dku', [App\Http\Controllers\CustomerOrdersController::class, 'createDku'])->name('create-co-dku');
        Route::post('/post-create-co-dnp', [App\Http\Controllers\CustomerOrdersController::class, 'storeDnp'])->name('create-co-dnp.post');
        Route::post('/post-create-co-dku', [App\Http\Controllers\CustomerOrdersController::class, 'storeDku'])->name('create-co-dku.post');
        Route::get('/list-co', [App\Http\Controllers\CustomerOrdersController::class, 'index'])->name('list-co');
        Route::get('/customer-order/detail/{transactions}', [App\Http\Controllers\CustomerOrdersController::class, 'show'])->name('detail-co');
    });
});

require __DIR__.'/auth.php';