<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseOrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public endpoint for creating purchase orders from external API
Route::post('/purchase-orders', [PurchaseOrderController::class, 'createFromApi']);
