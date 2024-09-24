 <?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Dashboard Routes
Route::middleware(['auth', 'verified', 'secure.path'])->group(function() {
    Route::get('/finance/dashboard', fn() => Inertia::render('Finance/Dashboard'))->name('dashboard.finance');
    Route::get('/warehouse/dashboard', fn() => Inertia::render('Warehouse/Dashboard'))->name('dashboard.warehouse');
    Route::get('/procurement/dashboard', [App\Http\Controllers\DashboardController::class, 'indexProcurementDashboard'])->name('dashboard.procurement');
    Route::get('/admin/dashboard', fn() => Inertia::render('Admin/Dashboard'))->name('dashboard.admin');
    Route::get('/aging-finance/dashboard', fn() => Inertia::render('AgingFinance/Dashboard'))->name('dashboard.aging-finance');
    Route::get('/sales/dashboard', fn() => Inertia::render('Sales/Dashboard'))->name('dashboard.sales');
});

Route::middleware(['auth', 'secure.path', 'web'])->group(function() {

    //ADMIN ROUTES
    Route::prefix('admin')->name('admin.')->controller(App\Http\Controllers\UserController::class)->group(function() {
        Route::get('/sales-reports', fn() => Inertia::render('Admin/SalesReports'))->name('sales-reports');

        //USER
        Route::prefix('user-management')->controller(App\Http\Controllers\UserController::class)->group(function() {
            Route::get('/', 'index')->name('user-management');
            Route::get('/create-user', 'create')->name('create-user');
            Route::post('/create-user', 'store')->name('create-user.post');
            Route::get('/{user}/edit', 'edit')->name('edit-user');
            Route::patch('/{user}/update-user', 'update')->name('edit-user.patch');
            Route::delete('/{user}', 'destroy')->name('delete-user.delete');
        });

        //ROLE
        Route::prefix('role-management')->controller(App\Http\Controllers\RoleController::class)->group(function() {
            Route::get('/', 'index')->name('role-management');
            Route::get('/create-role', 'create')->name('create-role');
            Route::post('/create-role', 'store')->name('create-role.post');
            Route::get('/{role}/edit', 'edit')->name('edit-role');
            Route::patch('/{role}/update-role', 'update')->name('edit-role.patch');
            Route::delete('/{role}', 'destroy')->name('delete-role.delete');
        });

        //DIVISION
        Route::prefix('division-management')->controller(App\Http\Controllers\DivisionController::class)->group(function() {
            Route::get('/', 'index')->name('division-management');
            Route::get('/create-division', 'create')->name('create-division');
            Route::post('/create-division', 'store')->name('create-division.post');
            Route::get('/{division}/edit', 'edit')->name('edit-division');
            Route::patch('/{division}/update-division', 'update')->name('edit-division.patch');
            Route::delete('/{division}/delete-division', 'destroy')->name('delete-division.delete');
        });

        //Menu Access
        Route::prefix('menu-access-management')->controller(App\Http\Controllers\MenuAccessController::class)->group(function () {
            Route::get('/', 'index')->name("menu-access-management");
        });
    });

    // Procurement Routes
    Route::prefix('procurement')->name('procurement.')->group(function() {
        //Purchase Order
        Route::get('/purchase-order', [App\Http\Controllers\PurchaseOrderController::class, 'create'])->name('purchase-order');
        Route::post('/purchase-order', [App\Http\Controllers\PurchaseOrderController::class, 'store'])->name('create-po');
        Route::get('/purchase-orders', [App\Http\Controllers\PurchaseOrderController::class, 'index'])->name('purchase-order-list');
        Route::get('/{purchaseOrder}/detail', [App\Http\Controllers\PurchaseOrderController::class, 'show'])->name('purchase-order.detail');

        Route::get('/sales-order', fn() => Inertia::render('Procurement/ItemsReceipt/CreateSalesOrder'))->name('sales-order');
        Route::get('/aging', fn() => Inertia::render('Procurement/Transaction/Aging'))->name('aging');
        Route::get('/transaction-list', fn() => Inertia::render('Procurement/Transaction/TransactionList'))->name('transaction-list');
    });

    // Finance Routes
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/create-po', fn() => Inertia::render('Finance/Purchase/CreatePurchaseOrder'))->name('create-po');
        Route::get('/create-so', fn() => Inertia::render('Finance/ItemsReceipt/CreateSalesOrder'))->name('create-so'); 
        Route::get('/aging', fn() => Inertia::render('Finance/Bill/Aging'))->name('aging');
        Route::get('/invoices', fn() => Inertia::render('Finance/Bill/Sales'))->name('invoices');
        Route::get('/claim-promo', fn() => Inertia::render('Finance/Claim/ClaimPromo'))->name('claim-promo');
        Route::get('/claim-promo-list', fn() => Inertia::render('Finance/Claim/ListClaimPromo'))->name('list-claim-promo');
        Route::get('/form-claim', fn() => Inertia::render('Finance/Claim/FormClaim'))->name('form-claim-promo');
    });

    //warehouse Routes
    Route::prefix('warehouse')->name('warehouse.')->group(function() {
        Route::get('/stock-goods', fn() => Inertia::render('Warehouse/StockItems'))->name('stock-goods');
        Route::get('/stock-dnp-goods', fn() => Inertia::render('Warehouse/DnpWarehouse/Stocks'))->name('dnp-stock-goods');
        Route::get('/incoming-item', fn() => Inertia::render('Warehouse/IncomingItem'))->name('incoming-item');
        Route::get('/return-goods', fn() => Inertia::render('Warehouse/ReturnGoods'))->name('return-goods');
        Route::get('/return-item', fn() => Inertia::render('Warehouse/DnpWarehouse/ReturnItem'))->name('return-item');
        Route::get('/booking-requests', fn() => Inertia::render('Warehouse/BookingItem/BookingRequest'))->name('booking-request');
    });

    Route::prefix('aging-finance')->name('aging-finance.')->group(function() {
        Route::get('/aging', fn() => Inertia::render('AgingFinance/Transaction/Aging'))->name('aging');
        Route::get('/list-transactions', fn() => Inertia::render('AgingFinance/Transaction/ListTransaction'))->name('list-transaction');
    });
});

require __DIR__.'/auth.php';