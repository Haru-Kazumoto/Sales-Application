<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/dashboard', function() {
    return Inertia::render('Marketing/Dashboard');
});

Route::get('/customer-order', function() {
    return Inertia::render('Marketing/CustomerOrder');
});

Route::get('/customer-order/create', function() {
    return Inertia::render('Marketing/CustomerOrderCreate');
});

Route::get('/products', function() {
    return Inertia::render('Marketing/Products');
});

Route::get('/customers', function(): Response {
    return Inertia::render('Marketing/Customer');
});