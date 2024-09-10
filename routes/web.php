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
// });

// //First endpoint
Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/finance/dashboard', fn() => Inertia::render('Finance/Dashboard'))->name('dashboard.finance');
    Route::get('/warehouse/dashboard', fn() => Inertia::render('Warehouse/Dashboard'))->name('dashboard.warehouse');
    Route::get('/procurement/dashboard', fn() => Inertia::render('Procurement/Dashboard'))->name('dashboard.procurement');
});

Route::prefix('procurement')->middleware(['auth'])->group(function() {
    // Route::get('/')
});

// FINANCE ROUTES
Route::prefix('finance')->middleware(['auth'])->group(function () {
    Route::get('/create-po', function() {
        return Inertia::render('Finance/Purchase/CreatePurchaseOrder');
    });

    Route::get('/create-so', function() {
        return Inertia::render('Finance/ItemsReceipt/CreateSalesOrder');
    }); 

    Route::get('/aging', function() {
        return Inertia::render('Finance/Bill/Aging');
    });

    Route::get('/invoices', function() {
        return Inertia::render('Finance/Bill/Sales');
    });

    Route::get('/claim-promo', function() {
        return Inertia::render('Finance/ClaimPromo');
    });

});

require __DIR__.'/auth.php';