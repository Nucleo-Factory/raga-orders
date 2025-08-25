<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\Ship24WebhookController;
use App\Http\Controllers\Ship24TestController;

// Rutas públicas (sin autenticación)
Route::get('/status', function () {
    return response()->json([
        'status' => 'API funcionando correctamente',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});

// Public endpoint for creating purchase orders from external API
Route::post('/purchase-orders', [PurchaseOrderController::class, 'createFromApi']);

// Ship24 webhook endpoints (públicos - no requieren autenticación)
Route::post('/webhooks/ship24', [Ship24WebhookController::class, 'handle']);
Route::post('/webhooks/ship24/test', [Ship24WebhookController::class, 'test']); // Solo para desarrollo

// Ship24 testing endpoints (solo desarrollo/staging)
Route::prefix('ship24/test')->group(function () {
    Route::post('/create-tracker', [Ship24TestController::class, 'createTracker']);
    Route::post('/get-status', [Ship24TestController::class, 'getTrackerStatus']);
    Route::post('/tracking-service', [Ship24TestController::class, 'testTrackingService']);
    Route::get('/list-trackers', [Ship24TestController::class, 'listTrackers']);
    Route::post('/simulate-webhook', [Ship24TestController::class, 'simulateWebhook']);
    Route::get('/integration-test', [Ship24TestController::class, 'integrationTest']);
});

// Rutas protegidas con autenticación de token API
Route::middleware('api.token')->group(function () {

    // Información del usuario autenticado
    Route::get('/user', function (Request $request) {
        return response()->json([
            'user' => $request->user(),
            'company' => $request->user()->company
        ]);
    });

    // Ejemplo: Obtener órdenes de compra del usuario
    Route::get('/my-purchase-orders', function (Request $request) {
        $user = $request->user();
        $orders = $user->company ?
            $user->company->purchaseOrders()->with(['vendor', 'products'])->paginate(10) :
            collect([]);

        return response()->json([
            'data' => $orders,
            'user' => $user->name,
            'company' => $user->company->name ?? 'Sin compañía'
        ]);
    });

    // Ejemplo: Crear una nueva orden de compra
    Route::post('/my-purchase-orders', function (Request $request) {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'description' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0'
        ]);

        // Aquí iría la lógica para crear la orden
        return response()->json([
            'message' => 'Orden de compra creada exitosamente',
            'data' => $validated,
            'created_by' => $request->user()->name
        ], 201);
    });

    // Ejemplo: Obtener estadísticas del usuario
    Route::get('/dashboard-stats', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'stats' => [
                'total_orders' => $user->company ? $user->company->purchaseOrders()->count() : 0,
                'pending_orders' => $user->company ? $user->company->purchaseOrders()->where('status', 'pending')->count() : 0,
                'completed_orders' => $user->company ? $user->company->purchaseOrders()->where('status', 'completed')->count() : 0,
            ],
            'user_info' => [
                'name' => $user->name,
                'email' => $user->email,
                'company' => $user->company->name ?? 'Sin compañía'
            ]
        ]);
    });

    // Ejemplo: Actualizar perfil del usuario
    Route::put('/profile', function (Request $request) {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'phone' => 'sometimes|string|max:20'
        ]);

        $user = $request->user();
        $user->update($validated);

        return response()->json([
            'message' => 'Perfil actualizado exitosamente',
            'user' => $user->fresh()
        ]);
    });
});
