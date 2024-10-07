<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return response()->json(["ok"=>"ok"],200);
});


Route::post("/test-message-template",[App\Http\Controllers\MessageTemplateController::class,"store"]);

Route::post("/test-send-msg",[App\Http\Controllers\MessageTemplateController::class,"sendWhatsapp"]);
