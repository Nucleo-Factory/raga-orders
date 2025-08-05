<?php

use Illuminate\Support\Facades\Route;
use RagaOrders\POConfirmation\Http\Controllers\POConfirmationController;

$prefix = config('po-confirmation.route_prefix', 'po');
$middleware = config('po-confirmation.route_middleware', ['web']);

Route::middleware($middleware)
    ->prefix($prefix)
    ->group(function () {

        // Public confirmation routes
        Route::get('/confirm/{hash}', [POConfirmationController::class, 'show'])
            ->name('po.confirm');

        Route::post('/confirm/{hash}', [POConfirmationController::class, 'confirm'])
            ->name('po.confirm.process');

        Route::get('/success', [POConfirmationController::class, 'success'])
            ->name('po.confirm.success');

        Route::get('/error', [POConfirmationController::class, 'error'])
            ->name('po.confirm.error');
    });
