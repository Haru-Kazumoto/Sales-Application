<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

// Route::get('/')->middleware('redirect.by.role');

// Route::get('/dashboard', function() {
//     return Inertia::render('Marketing/Dashboard');
// });

// Route::get('/customer-order', function() {
//     return Inertia::render('Marketing/CustomerOrder');
// });

// Route::get('/customer-order/create', function() {
//     return Inertia::render('Marketing/CustomerOrderCreate');
// });

// Route::get('/products', function() {
//     return Inertia::render('Marketing/Products');
// });

// Route::get('/customers', function(): Response {
//     return Inertia::render('Marketing/Customer');
// })

// Dashboard Routes
Route::middleware(['auth', 'verified', 'secure.path'])->group(function() {
    Route::get('/finance/dashboard', fn() => Inertia::render('Finance/Dashboard'))->name('dashboard.finance');
    Route::get('/warehouse/dashboard', fn() => Inertia::render('Warehouse/Dashboard'))->name('dashboard.warehouse');
    Route::get('/procurement/dashboard', fn() => Inertia::render('Procurement/Dashboard'))->name('dashboard.procurement');
});

// Procurement Routes
Route::prefix('procurement')->middleware(['auth', 'secure.path'])->name('procurement.')->group(function() {
    Route::get('/purchase-order', fn() => Inertia::render('Procurement/Purchase/CreatePurchaseOrder'))->name('purchase-order');
    Route::get('/sales-order', fn() => Inertia::render('Procurement/ItemsReceipt/CreateSalesOrder'))->name('sales-order');
    Route::get('/aging', fn() => Inertia::render('Procurement/Transaction/Aging'))->name('aging');
    Route::get('/transaction-list', fn() => Inertia::render('Procurement/Transaction/TransactionList'))->name('transaction-list');
});

// Finance Routes
Route::prefix('finance')->middleware(['auth', 'secure.path'])->name('finance.')->group(function () {
    Route::get('/create-po', fn() => Inertia::render('Finance/Purchase/CreatePurchaseOrder'))->name('create-po');
    Route::get('/create-so', fn() => Inertia::render('Finance/ItemsReceipt/CreateSalesOrder'))->name('create-so'); 
    Route::get('/aging', fn() => Inertia::render('Finance/Bill/Aging'))->name('aging');
    Route::get('/invoices', fn() => Inertia::render('Finance/Bill/Sales'))->name('invoices');
    Route::get('/claim-promo', fn() => Inertia::render('Finance/ClaimPromo'))->name('claim-promo');
});


require __DIR__.'/auth.php';