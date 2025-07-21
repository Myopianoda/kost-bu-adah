<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/midtrans/notification', [WebhookController::class, 'handle']);

Route::get('/tes-api', function () {
    return response()->json(['pesan' => 'Route API berhasil diakses!']);
});

