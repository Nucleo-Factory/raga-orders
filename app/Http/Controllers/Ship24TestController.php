<?php

namespace App\Http\Controllers;

use App\Services\Ship24TrackerService;
use App\Services\TrackingService;
use App\Models\Ship24Tracker;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Ship24TestController extends Controller
{
    protected $trackerService;
    protected $trackingService;

    public function __construct(Ship24TrackerService $trackerService, TrackingService $trackingService)
    {
        $this->trackerService = $trackerService;
        $this->trackingService = $trackingService;
        
        // Solo permitir en entornos de desarrollo
        if (!app()->environment(['local', 'development', 'staging'])) {
            abort(404);
        }
    }

    /**
     * Test crear tracker manualmente
     */
    public function createTracker(Request $request): JsonResponse
    {
        $request->validate([
            'tracking_number' => 'required|string',
            'carrier_code' => 'nullable|string',
            'origin_country' => 'nullable|string|max:3',
            'destination_country' => 'nullable|string|max:3',
        ]);

        try {
            $tracker = $this->trackerService->createTracker(
                $request->tracking_number,
                $request->carrier_code,
                null, // No PO ID para test manual
                $request->origin_country,
                $request->destination_country
            );

            if ($tracker) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tracker creado exitosamente',
                    'data' => [
                        'id' => $tracker->id,
                        'tracking_number' => $tracker->tracking_number,
                        'ship24_tracker_id' => $tracker->ship24_tracker_id,
                        'status' => $tracker->status,
                        'carrier_code' => $tracker->carrier_code,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo crear el tracker en Ship24'
                ], 422);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creando tracker: ' . $e->getMessage(),
                'trace' => app()->environment('local') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Test obtener status de tracker
     */
    public function getTrackerStatus(Request $request): JsonResponse
    {
        $request->validate([
            'tracking_number' => 'required|string',
        ]);

        try {
            $trackingData = $this->trackerService->getTrackerStatus($request->tracking_number);

            if ($trackingData) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status obtenido exitosamente',
                    'data' => $trackingData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo obtener el status del tracker'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo status: ' . $e->getMessage(),
                'trace' => app()->environment('local') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Test el método principal de tracking (con fallback)
     */
    public function testTrackingService(Request $request): JsonResponse
    {
        $request->validate([
            'tracking_number' => 'required|string',
        ]);

        try {
            $trackingData = $this->trackingService->getShip24Tracking($request->tracking_number);

            return response()->json([
                'success' => true,
                'message' => 'Tracking service ejecutado',
                'data' => $trackingData,
                'method_used' => $trackingData ? 'ship24' : 'none'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en tracking service: ' . $e->getMessage(),
                'trace' => app()->environment('local') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Listar todos los trackers en la base de datos
     */
    public function listTrackers(): JsonResponse
    {
        try {
            $trackers = Ship24Tracker::with('purchaseOrder:id,order_number')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function ($tracker) {
                    return [
                        'id' => $tracker->id,
                        'tracking_number' => $tracker->tracking_number,
                        'ship24_tracker_id' => $tracker->ship24_tracker_id,
                        'status' => $tracker->status,
                        'carrier_code' => $tracker->carrier_code,
                        'purchase_order' => $tracker->purchaseOrder ? [
                            'id' => $tracker->purchaseOrder->id,
                            'order_number' => $tracker->purchaseOrder->order_number
                        ] : null,
                        'last_update' => $tracker->last_ship24_update,
                        'last_webhook' => $tracker->last_webhook_received,
                        'created_at' => $tracker->created_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Trackers listados exitosamente',
                'data' => $trackers,
                'total' => $trackers->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error listando trackers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simular webhook para testing
     */
    public function simulateWebhook(Request $request): JsonResponse
    {
        $request->validate([
            'tracking_number' => 'required|string',
            'status_category' => 'nullable|string',
            'estimated_delivery' => 'nullable|date',
        ]);

        try {
            // Crear datos de webhook simulados
            $webhookData = [
                'trackingNumber' => $request->tracking_number,
                'statistics' => [
                    'statusCategory' => $request->status_category ?? 'in_transit'
                ],
                'delivery' => [
                    'estimatedDeliveryDate' => $request->estimated_delivery ?? now()->addDays(5)->toISOString()
                ],
                'events' => [
                    [
                        'occurrenceDatetime' => now()->toISOString(),
                        'status' => 'Package in transit',
                        'location' => 'Test facility'
                    ]
                ]
            ];

            $processed = $this->trackerService->processWebhookData($webhookData);

            return response()->json([
                'success' => $processed,
                'message' => $processed ? 'Webhook simulado procesado' : 'Error procesando webhook simulado',
                'webhook_data' => $webhookData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error simulando webhook: ' . $e->getMessage(),
                'trace' => app()->environment('local') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Test completo de integración
     */
    public function integrationTest(): JsonResponse
    {
        try {
            $testTrackingNumber = 'TEST' . time();
            $results = [];

            // 1. Crear tracker
            $results['step1'] = 'Creando tracker...';
            $tracker = $this->trackerService->createTracker($testTrackingNumber, 'TEST');
            
            if (!$tracker) {
                return response()->json([
                    'success' => false,
                    'message' => 'Falló en paso 1: No se pudo crear tracker'
                ], 422);
            }
            
            $results['step1'] = "✅ Tracker creado: ID {$tracker->id}";

            // 2. Simular webhook
            $results['step2'] = 'Simulando webhook...';
            $webhookData = [
                'trackingNumber' => $testTrackingNumber,
                'statistics' => ['statusCategory' => 'in_transit'],
                'delivery' => ['estimatedDeliveryDate' => now()->addDays(3)->toISOString()],
                'events' => [
                    [
                        'occurrenceDatetime' => now()->toISOString(),
                        'status' => 'Integration test package',
                        'location' => 'Test center'
                    ]
                ]
            ];

            $webhookProcessed = $this->trackerService->processWebhookData($webhookData);
            $results['step2'] = $webhookProcessed ? '✅ Webhook procesado' : '❌ Error procesando webhook';

            // 3. Verificar datos del tracker
            $results['step3'] = 'Verificando datos...';
            $tracker->refresh();
            $trackingData = $tracker->getFormattedTrackingData();
            $results['step3'] = $trackingData ? '✅ Datos formateados correctamente' : '❌ Error en formato de datos';

            // 4. Test del TrackingService
            $results['step4'] = 'Probando TrackingService...';
            $serviceData = $this->trackingService->getShip24Tracking($testTrackingNumber);
            $results['step4'] = $serviceData ? '✅ TrackingService funcionando' : '❌ Error en TrackingService';

            return response()->json([
                'success' => true,
                'message' => 'Test de integración completado',
                'results' => $results,
                'test_data' => [
                    'tracking_number' => $testTrackingNumber,
                    'tracker_id' => $tracker->id,
                    'formatted_data' => $trackingData,
                    'service_data' => $serviceData
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en test de integración: ' . $e->getMessage(),
                'trace' => app()->environment('local') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}