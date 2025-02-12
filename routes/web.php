 <?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/inspect/po-document',function() {
//     return view('documents.purchase-order-document');
// });

// Route::get('/inspect/sso-document', function() {
//     return view('documents.sub-sales-order-document');
// });

// Route::get('/test-view', function() {
//     return Inertia::render('Test');
// });


// Dashboard Routes
Route::middleware(['auth', 'verified', 'secure.path'])->group(function() {
    Route::get('/dashboard-finance', [App\Http\Controllers\DashboardController::class, 'indexFinanceDashboard'])->name('dashboard.finance');
    Route::get('/dashboard-warehouse', [App\Http\Controllers\DashboardController::class, 'indexWarehouseDashboard'])->name('dashboard.warehouse');
    Route::get('/dashboard-procurement', [App\Http\Controllers\DashboardController::class, 'indexProcurementDashboard'])->name('dashboard.procurement');
    Route::get('/dashboard-admin',[App\Http\Controllers\DashboardController::class, 'indexAdminDashboard'])->name('dashboard.admin');
    Route::get('/dashboard-aging_finance',[App\Http\Controllers\DashboardController::class, 'indexAgingFinanceDashboard'])->name('dashboard.aging-finance');
    Route::get('/dashboard-sales', [App\Http\Controllers\DashboardController::class, 'indexSalesDashboard'])->name('dashboard.sales');
    Route::get('/dashboard-marketing', [App\Http\Controllers\DashboardController::class, 'indexMarketingDashboard'])->name('dashboard.marketing');
    Route::get('/dashboard-cashier', [App\Http\Controllers\DashboardController::class, 'indexCashierDashboard'])->name('dashboard.cashier');
    Route::get('/dashboard-invoiceist', [App\Http\Controllers\DashboardController::class, 'indexInvoiceistDashboard'])->name('dashboard.invoiceist');
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
            Route::put('/update-customer/{parties}', [App\Http\Controllers\PartiesController::class, 'update'])->name('parties.customer.update');
            Route::delete('/delete-customer/{parties}', [App\Http\Controllers\PartiesController::class, 'destroyCustomer'])->name('parties.customer.delete');

            Route::post('/import-customer', [App\Http\Controllers\ImportProductMaster::class, 'importCustomer'])->name('customer.import');
            Route::get('/export-customer-template', [App\Http\Controllers\PartiesController::class,'exportTemplateExcel'])->name('customer.download-template');
        });

        Route::prefix('supplier-management')->group(function() {
            Route::get('', [App\Http\Controllers\PartiesController::class, 'createSupplier'])->name('parties.supplier');
            Route::post('/create-supplier', [App\Http\Controllers\PartiesController::class, 'storeSupplier'])->name('parties.supplier.post');
            Route::get('/edit-supplier/{parties}', [App\Http\Controllers\PartiesController::class, 'editSupplier'])->name('parties.supplier.edit');
            Route::patch('/update-supplier/{parties}', [App\Http\Controllers\PartiesController::class, 'updateSupplier'])->name('parties.supplier.update');
            Route::delete('/delete-supplier/{parties}', [App\Http\Controllers\PartiesController::class, 'destroySupplier'])->name('parties.supplier.delete');

            Route::post('/import-vendors', [App\Http\Controllers\ImportProductMaster::class, 'importVendors'])->name('vendors.import');
        });

        Route::prefix('product-management')->group(function () {
            Route::get('', [App\Http\Controllers\ProductsController::class, 'index'])->name('products');
            Route::get('/create', [App\Http\Controllers\ProductsController::class, 'create'])->name('products.create');
            Route::post('/post-product', [App\Http\Controllers\ProductsController::class, 'store'])->name('products.post');
            Route::get('/edit/{product}', [App\Http\Controllers\ProductsController::class, 'edit'])->name('products.edit');
            Route::patch('/update/{product}', [App\Http\Controllers\ProductsController::class, 'update'])->name('products.update');
            Route::delete('/delete/{product}', [App\Http\Controllers\ProductsController::class, 'destroy'])->name('products.delete');

            //IMPORT
            Route::post('/import-products', [App\Http\Controllers\ImportProductMaster::class, 'importProducts'])->name('products.import');
        });

        Route::prefix('transport-management')->group(function() {
            Route::get('', [App\Http\Controllers\TransportController::class, 'index'])->name('index-transports');
            Route::get('/create',[App\Http\Controllers\TransportController::class, 'create'])->name('create-transports');
            Route::post('/post', [App\Http\Controllers\TransportController::class, 'storeTransport'])->name('transport.post');
            Route::get('/edit/{parties}', [App\Http\Controllers\TransportController::class, 'update'])->name('transport.edit');
            Route::patch('/update/{parties}', [App\Http\Controllers\TransportController::class, 'edit'])->name('transport.update');
            Route::delete('/delete/{parties}', [App\Http\Controllers\TransportController::class, 'destroy'])->name('transport.delete');

            Route::post('/import-transport', [App\Http\Controllers\TransportController::class,'importTransports'])->name('transport.import');
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

        Route::prefix('promo-program')->group(function() {
            Route::get('', [App\Http\Controllers\PromoProgramController::class,'index'])->name('index-promo');
            Route::get('/create-promo', [App\Http\Controllers\PromoProgramController::class,'createPromo'])->name('create-promo');
            Route::post('/store-promo', [App\Http\Controllers\PromoProgramController::class, 'storePromo'])->name('store-promo');
            Route::get('/detail/{promoProgram}', [App\Http\Controllers\PromoProgramController::class, 'show'])->name('detail-promo');
        });

        Route::prefix('customer-sales')->group(function() {
            Route::get('', [App\Http\Controllers\UserController::class, 'indexSalesUser'])->name('index-sales');
            Route::get('/assign/{user}',[App\Http\Controllers\UserController::class, 'assignCustomerSales'])->name('assign-customer-sales');
            Route::patch('/assign/{customer}/to/{sales}', [App\Http\Controllers\CustomerController::class, 'assignCustomerToSales'])->name('assign-customer-to-sales');
            Route::patch('/unassign/{customer}', [App\Http\Controllers\CustomerController::class, 'unAssignCustomerSales'])->name('unassign-customer-from-sales');
        });

        Route::prefix('trade-promo')->group(function() {
            Route::get('', [App\Http\Controllers\TradePromoController::class, 'index'])->name('trade-promo.index');
            Route::get('/create', [App\Http\Controllers\TradePromoController::class, 'create'])->name('trade-promo.create');
            Route::post('/post', [App\Http\Controllers\TradePromoController::class, 'store'])->name('trade-promo.store');
            Route::get('/edit/{tradePromo}', [App\Http\Controllers\TradePromoController::class, 'edit'])->name('trade-promo.edit');
            Route::put('/update/{tradePromo}', [App\Http\Controllers\TradePromoController::class, 'update'])->name('trade-promo.update');

            //not implemented yet
            Route::delete('/delete/{tradePromo}', [App\Http\Controllers\TradePromoController::class, 'deletePromo'])->name('trade-promo.delete');

            Route::patch('/shutdown-promo/{tradePromo}', [App\Http\Controllers\TradePromoController::class, 'shutdownPromo'])->name('trade-promo.shutdown');
            Route::patch('/add-quota', [App\Http\Controllers\TradePromoController::class, 'addQuotaPromo'])->name('trade-promo.add-quota');

            Route::patch('/assign/product/{tradePromo}',[App\Http\Controllers\TradePromoController::class,'assignProduct'])->name('trade-promo.assign-product');
            Route::patch('/unassign/product/{tradePromo}',[App\Http\Controllers\TradePromoController::class,'unassignProduct'])->name('trade-promo.unassign-product');
        });

        Route::prefix('customer-region')->group(function() {
            Route::get('', [App\Http\Controllers\CustomerRegionController::class, 'createAndIndex'])->name('customer-region.index');
            Route::post('', [App\Http\Controllers\CustomerRegionController::class, 'store'])->name('customer-region.store');
            Route::get('/{customerRegion}/assign', [App\Http\Controllers\CustomerRegionController::class, 'createAssignCustomers'])->name('customer-region.assign');
            Route::patch('/region/{customerRegion}/assign-customers', [App\Http\Controllers\CustomerRegionController::class, 'assignCustomers'])->name('customer-region.assign-all');
            Route::patch('/customer/{customer}/unassign', [App\Http\Controllers\CustomerRegionController::class, 'unassignCustomer'])->name('customer-region.unassign');
        });

        Route::prefix('region-delivery')->group(function() {
            Route::get('',[App\Http\Controllers\RegionDeliveryController::class, 'index'])->name('region-delivery.index');
            Route::get('/create', [App\Http\Controllers\RegionDeliveryController::class, 'create'])->name('region-delivery.create');
            Route::post('/store', [App\Http\Controllers\RegionDeliveryController::class, 'store'])->name('region-delivery.store');
            Route::get('/edit/{regionDelivery}', [App\Http\Controllers\RegionDeliveryController::class, 'edit'])->name('region-delivery.edit');
            Route::put('/update/{regionDelivery}', [App\Http\Controllers\RegionDeliveryController::class, 'update'])->name('region-delivery.update');
            Route::delete('/delete/{regionDelivery}', [App\Http\Controllers\RegionDeliveryController::class, 'delete'])->name('region-delivery.delete');
        });

        Route::prefix('global-element-prices')->group(function() {
            Route::get('', [App\Http\Controllers\GlobalElementPriceController::class, 'index'])->name('global-element-prices.index');
            Route::get('/create', [App\Http\Controllers\GlobalElementPriceController::class, 'create'])->name('global-element-prices.create');
            Route::post('/post', [App\Http\Controllers\GlobalElementPriceController::class, 'store'])->name('global-element-prices.store');
            Route::get('/edit/{globalElementPrice}', [App\Http\Controllers\GlobalElementPriceController::class, 'edit'])->name('global-element-prices.edit');
            Route::put('/update/{globalElementPrice}', [App\Http\Controllers\GlobalElementPriceController::class, 'update'])->name('global-element-prices.update');
            Route::delete('/delete/{globalElementPrice}', [App\Http\Controllers\GlobalElementPriceController::class, 'destroy'])->name('global-element-prices.destroy');
        });

        Route::prefix('dimentions')->group(function() {
            Route::get('/', [App\Http\Controllers\DimentionController::class, 'index'])->name('dimentions.index');
            Route::get('/create', [App\Http\Controllers\DimentionController::class, 'create'])->name('dimentions.create');
            Route::post('/', [App\Http\Controllers\DimentionController::class, 'store'])->name('dimentions.store');
            Route::get('/{dimention}/edit', [App\Http\Controllers\DimentionController::class, 'edit'])->name('dimentions.edit');
            Route::put('/{dimention}', [App\Http\Controllers\DimentionController::class, 'update'])->name('dimentions.update');
            Route::delete('/{dimention}', [App\Http\Controllers\DimentionController::class, 'destroy'])->name('dimentions.destroy');
        });
    });

    // Procurement Routes
    Route::name('procurement.')->group(function() {
        //Purchase Order
        Route::get('/purchase-order', [App\Http\Controllers\PurchaseOrderController::class, 'create'])->name('purchase-order');
        Route::post('/purchase-order', [App\Http\Controllers\PurchaseOrderController::class, 'store'])->name('create-po');
        Route::get('/purchase-orders', [App\Http\Controllers\PurchaseOrderController::class, 'index'])->name('purchase-order-list');
        Route::get('/po-set-number-plate', [App\Http\Controllers\PurchaseOrderController::class, 'indexNewPo'])->name('set-number-plates');        
        Route::patch('/set-number-plate', [App\Http\Controllers\PurchaseOrderController::class, 'updateNumberPlate'])->name('update-number-plate');
        Route::get('/purchase-order/detail/{transaction}', [App\Http\Controllers\PurchaseOrderController::class, 'show'])->name('purchase-order.detail');
        Route::get('/generate-po-document/{transactions}', [App\Http\Controllers\PurchaseOrderController::class, 'generatePurchaseOrderDocument'])->name('generate-po-document');
        Route::get('/purchase-order/edit/{transaction}', [App\Http\Controllers\PurchaseOrderController::class,'edit'])->name('purchase-order.edit');
        Route::put('/purchase-order/update/{transaction}', [App\Http\Controllers\PurchaseOrderController::class,'update'])->name('purchase-order.update');

        //Sub sales Order
        Route::get('/sub-sales-order', [App\Http\Controllers\SubSalesOrderController::class, 'create'])->name('sales-order');
        Route::post('/sub-sales-order', [App\Http\Controllers\SubSalesOrderController::class, 'store'])->name('sales-order.post');
        Route::get('/sub-sales-orders', [App\Http\Controllers\SubSalesOrderController::class, 'index'])->name('sales-order-list');
        Route::get('/sub-sales-order/detail/{transaction}', [App\Http\Controllers\SubSalesOrderController::class, 'show'])->name('sales-order.detail');
        Route::get('/sub-sales-order/{po_number}', [App\Http\Controllers\PurchaseOrderController::class, 'getDataByPoNumber'])->name('get-data-po');
        Route::get('/generate-sso-document/{transactions}', [App\Http\Controllers\SubSalesOrderController::class, 'generateSubSalesOrderDocument'])->name('generate-sso-document');

        //DO Travel Document
        Route::get('/do-travel-document', [App\Http\Controllers\DoTravelDocument::class, 'create'])->name('do.create');
        Route::get('/do-travel-document/detail/{transactions}', [App\Http\Controllers\DoTravelDocument::class, 'detailTravelDocument'])->name('do.detail');
        Route::post('/do-travel-document/post', [App\Http\Controllers\DoTravelDocument::class, 'store'])->name('do.store');
        Route::get('/index-do-travel-document', [App\Http\Controllers\DoTravelDocument::class,'index'])->name('do.index');
        Route::get('/do-travel-document/show/{transactions}', [App\Http\Controllers\DoTravelDocument::class, 'show'])->name('do.show');

        Route::get('/aging', fn() => Inertia::render('Procurement/Transaction/Aging'))->name('aging');
        Route::get('/transaction-list', fn() => Inertia::render('Procurement/Transaction/TransactionList'))->name('transaction-list');
    });

    // Finance Routes
    Route::name('finance.')->group(function () {
        Route::get('/aging', fn() => Inertia::render('Finance/Bill/Aging'))->name('aging');
        Route::get('/invoices', fn() => Inertia::render('Finance/Bill/Sales'))->name('invoices');
        
        Route::get('/claim-promo', [App\Http\Controllers\ClaimPromoController::class, 'indexClaimPromo'])->name('claim.index');
        Route::get('/detail-claim/{transactions}', [App\Http\Controllers\ClaimPromoController::class, 'showClaimPromo'])->name('claim-promo.detail');
        Route::get('/claim-promo-list', [App\Http\Controllers\ClaimPromoController::class, 'indexDataClaimPromo'])->name('list-claim-promo');
        Route::get('/form-claim', [App\Http\Controllers\ClaimPromoController::class, 'createClaim'])->name('form-claim-promo.get');
        Route::post('/form-claim', [App\Http\Controllers\ClaimPromoController::class, 'createClaim'])->name('form-claim-promo');
        Route::post('/post/claim', [App\Http\Controllers\ClaimPromoController::class, 'storeClaim'])->name('claim.post');
        Route::patch('/update-status-payment/{transactions}', [App\Http\Controllers\ClaimPromoController::class, 'changeStatusPaymentClaim'])->name('change-status');

        Route::get('/export-claim', [App\Http\Controllers\ClaimPromoController::class,'exportClaim'])->name('export-claim');

        // Route::get('/form-claim', [App\Http\Controllers\ClaimPromoController::class, 'createClaim'])->name('form-claim-promo');
        // Route::get('/test', fn() => Inertia::render('Test'));
    });

    //warehouse Routes
    Route::name('warehouse.')->group(function() {
        Route::get('/stock-goods', [App\Http\Controllers\ProductsController::class, 'indexAllWarehouseProducts'])->name('stock-goods');
        Route::get('/items-out', [App\Http\Controllers\ProductsController::class, 'indexItemsOut'])->name('items-out');

        Route::post('/send-to-supplier', [App\Http\Controllers\BlastWhatsappController::class, 'sendMessageToSupplier'])->name('send-message-supplier');
        Route::post('/send-notice-products-report', [App\Http\Controllers\BlastWhatsappController::class, 'sendMessageNoticeProductsToSalesmanAndPrincipal'])->name('send-notice-products-report');

        Route::get('/dnp-stock-goods', [App\Http\Controllers\ProductsController::class, 'indexDNPWarehouseProducts'])->name('dnp-stock-goods');
        Route::get('/dku-stock-goods', [App\Http\Controllers\ProductsController::class, 'indexDKUWarehouseProducts'])->name('dku-stock-goods');
        Route::get('/dnp-expired-stocks', [App\Http\Controllers\ProductsController::class, 'indexDnpWarehouseExpiredProducts'])->name('dnp-expired-stocks');
        Route::get('/dku-expired-stocks', [App\Http\Controllers\ProductsController::class, 'indexDkuWarehouseExpiredProducts'])->name('dku-expired-stocks');
        Route::get('/return-broken-goods', fn() => Inertia::render('Warehouse/BrokenGoods/ReturnBrokenGoods'))->name('return-broken-goods');
        Route::get('/destruction-broken-goods', fn() => Inertia::render('Warehouse/BrokenGoods/DestructionGoods'))->name('destruction-broken-goods');
        
        Route::get('/incoming-item', [App\Http\Controllers\ProductsController::class, 'incomingProducts'])->name('incoming-item');
        Route::get('/incoming-item/{sso_number}', [App\Http\Controllers\SubSalesOrderController::class, 'getDataBySsoNumber'])->name('process-sso-data');
        Route::post('/store-products', [App\Http\Controllers\ProductsController::class,'storeProducts'])->name('store-products');
        Route::get('/gradual-delivery', [App\Http\Controllers\ProductsController::class, 'indexGradualDeliveryProducts'])->name('gradual-delivery');
        Route::post('/gradual-delivery/post',[App\Http\Controllers\ProductsController::class, 'storeGradualDeliveryProducts'])->name('gradual-delivery.post');
        
        // reStoreStockProduct

        Route::get('/travel-document', [App\Http\Controllers\CustomerOrdersController::class, 'createTravelDocument'])->name('travel-document');
        Route::get('/list-travel-document', [App\Http\Controllers\CustomerOrdersController::class, 'detailTravelDocument'])->name('list-travel-document');
        Route::get('/create-travel-document/{transactions}', [App\Http\Controllers\CustomerOrdersController::class, 'detailTravelDocument'])->name('create-travel-document');
        Route::post('/store-travel-document', [App\Http\Controllers\CustomerOrdersController::class, 'storeTravelDocument'])->name('travel-document.post');
        Route::get('/index-travel-document', [App\Http\Controllers\CustomerOrdersController::class, 'indexTravelDocuments'])->name('index-travel-document');
        Route::get('/generate-travel-document/{transactions}', [App\Http\Controllers\CustomerOrdersController::class, 'generateTravelDocument'])->name('travel-document.generate-document');

        Route::get('/return-item', fn() => Inertia::render('Warehouse/DnpWarehouse/ReturnItem'))->name('return-item');
        Route::get('/booking-requests', [App\Http\Controllers\BookingOrderController::class, 'indexOrder'])->name('booking-request');
        Route::get('/booking-requests/detail/{transactions}', [App\Http\Controllers\BookingOrderController::class, 'showOrder'])->name('detail-booking-request');
        Route::patch('/booking-requests/reject-description/{transactions}', [App\Http\Controllers\BookingOrderController::class, 'setRejectDescription'])->name('set-reject-description');
        Route::patch('/booking-requests/approve/{transactionItem}', [App\Http\Controllers\BookingOrderController::class, 'setApprovedStatus'])->name('set-approved');
        Route::patch('/booking-requests/approve-request/{transaction}',[App\Http\Controllers\BookingOrderController::class, 'approveBookingRequest'] )->name('approve-request');

        // EXPORT PRODUCTS
        Route::get('/export-products', [App\Http\Controllers\ProductsController::class, 'exportProducts'])->name('product.export');
    });

    //Aging Finance Routes
    Route::name('aging-finance.')->group(function() {
        Route::get('/aging', [App\Http\Controllers\InvoiceController::class, 'indexAging'])->name('aging');
        Route::get('/list-transactions', fn() => Inertia::render('AgingFinance/Transaction/ListTransaction'))->name('list-transaction');
        //nanti ganti by id (model)
        Route::get('/pay', fn() => Inertia::render('AgingFinance/Transaction/DetailTransaction'))->name('detail-transaction.pay');
        Route::get('/invoice-dnp', [App\Http\Controllers\InvoiceController::class, 'listDnpInvoice'])->name('invoice-dnp');
        Route::get('/invoice-dku', [App\Http\Controllers\InvoiceController::class, 'listDkuInvoice'])->name('invoice-dku');
        Route::get('/list-invoices', [App\Http\Controllers\InvoiceController::class, 'indexInvoices'])->name('list-invoice');
        Route::get('/create-invoice/{transactions}', [App\Http\Controllers\InvoiceController::class, 'createInvoice'])->name('create-invoice');
        Route::post('/store-invoice', [App\Http\Controllers\InvoiceController::class, 'storeInvoice'])->name('invoice.store');
        Route::get('/invoice/detail/{transactions}', [App\Http\Controllers\InvoiceController::class, 'showDetailInvoice'])->name('detail-invoice');
        Route::post('/invoice/pay/{transactions}', [App\Http\Controllers\InvoiceController::class, 'invoicePayment'])->name('invoice.pay');

        Route::get('/whatsapp-message', [App\Http\Controllers\MessageTemplateController::class, 'createTemplateMessage'])->name('whatsapp-message');
        Route::get('/test-send-message', [App\Http\Controllers\MessageTemplateController::class, 'createTest'])->name('test-whatsapp-message');
        Route::patch('/store-template/{messageTemplate}', [App\Http\Controllers\MessageTemplateController::class, 'storeTemplate'])->name('store-template');
        Route::post('/save-message', [App\Http\Controllers\MessageTemplateController::class, 'saveMessage'])->name('save-message');

        Route::post('/send-message', [App\Http\Controllers\BlastWhatsappController::class, 'sendWhatsapp'])->name('send.message');
        Route::get('/export-invoices', [App\Http\Controllers\InvoiceController::class, 'exportInvoice'])->name('invoice.export');

        Route::get('/process-co', [App\Http\Controllers\CustomerOrdersController::class, 'indexCoNeedApprove'])->name('co.process');
        Route::patch('/process-co/{transactions}', [App\Http\Controllers\CustomerOrdersController::class, 'processCustomerOrder'])->name('co.process.patch');

        // INVOICEIST
        Route::get('/generate-invoice/{transactions}', [App\Http\Controllers\InvoiceController::class, 'generateInvoiceDocument'])->name('preview-invoice');
    });

    Route::name('sales.')->group(function() {
        Route::get('/create-co-dnp', [App\Http\Controllers\CustomerOrdersController::class, 'createDnp'])->name('create-co');
        Route::get('/create-co-office', [App\Http\Controllers\CustomerOrdersController::class, 'createOffice'])->name('create-co-office');
        Route::get('/create-co-dku', [App\Http\Controllers\CustomerOrdersController::class, 'createDku'])->name('create-co-dku');
        Route::post('/post-create-co-dnp', [App\Http\Controllers\CustomerOrdersController::class, 'storeDnp'])->name('create-co-dnp.post');
        Route::post('/post-create-co-dku', [App\Http\Controllers\CustomerOrdersController::class, 'storeDku'])->name('create-co-dku.post');
        Route::get('/list-co', [App\Http\Controllers\CustomerOrdersController::class, 'index'])->name('list-co');
        Route::get('/customer-order/detail/{transactions}', [App\Http\Controllers\CustomerOrdersController::class, 'show'])->name('detail-co');
        Route::post('/re-store-products/{transactionItem}', [App\Http\Controllers\ProductsController::class,'reStoreStockProduct'])->name('restore-products');
        Route::patch('/submit-discounts/{transactionItem}', [App\Http\Controllers\CustomerOrdersController::class,'setDiscount'])->name('new-discount');

        // Booking Item
        Route::name('booking-item.')->prefix('booking-item/dnp')->group(function() {
            Route::get('', [App\Http\Controllers\BookingOrderController::class, 'indexOrderDnp'])->name('index-booking-dnp');
            Route::get('/create', [App\Http\Controllers\BookingOrderController::class, 'createBookingDnp'])->name('create-booking-dnp');
            Route::post('/store', [App\Http\Controllers\BookingOrderController::class, 'storeBookingDnp'])->name('store-booking-dnp');
        });

        Route::name('booking-item.')->prefix('booking-item/dku')->group(function() {
            Route::get('', [App\Http\Controllers\BookingOrderController::class, 'indexOrderDku'])->name('index-booking-dku');
            Route::get('/create', [App\Http\Controllers\BookingOrderController::class, 'createBookingDku'])->name('create-booking-dku');
        });

        Route::get('/generate-confirmation-order/{transactions}', [App\Http\Controllers\CustomerOrdersController::class, 'generateConfirmationOrder'])->name('preview-confirmation-order');
    });

    Route::name('marketing.')->group(function() {
        Route::prefix('salesman-target')->group(function() {
            Route::get('', [App\Http\Controllers\UserTargetController::class, 'index'])->name('index-target');
            Route::get('/create-target/{user}', [App\Http\Controllers\UserTargetController::class, 'create'])->name('create-target');
            Route::patch('/update-target/{user}', [App\Http\Controllers\UserTargetController::class, 'update'])->name('update-target');
        });

        Route::prefix('draf-co')->group(function() {
            Route::get('', [App\Http\Controllers\CustomerOrdersController::class, 'indexDrafCustomerOrder'])->name('draf-co');
            Route::patch('/process-co/{transactions}',[App\Http\Controllers\CustomerOrdersController::class, 'processDrafCustomerOrder'])->name('draf-co.process');
        });

        Route::prefix('marketing-reports')->group(function() {
            Route::get('', [App\Http\Controllers\MarketingReports::class, 'index'])->name('index-reports');
            // marketing.marketing-reports.export-report
            Route::get('/export', [App\Http\Controllers\MarketingReports::class, 'exportReport'])->name('export-report');
        });
    });
});

require __DIR__.'/auth.php';