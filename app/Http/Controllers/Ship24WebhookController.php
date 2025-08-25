<?php

namespace App\Http\Controllers;

use App\Services\Ship24TrackerService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Ship24WebhookController extends Controller
{
    protected $trackerService;

    public function __construct(Ship24TrackerService $trackerService)
    {
        $this->trackerService = $trackerService;
    }

    /**
     * Handle incoming Ship24 webhook
     */
    public function handle(Request $request): Response
    {
        try {
            Log::info('Ship24 webhook received', [
                'headers' => $request->headers->all(),
                'payload' => $request->all()
            ]);

            // Validar que el webhook viene de Ship24
            if (!$this->validateWebhook($request)) {
                Log::warning('Invalid Ship24 webhook signature');
                return response('Unauthorized', 401);
            }

            $payload = $request->json()->all();

            // Validar estructura básica del payload
            if (!isset($payload['data']) || !is_array($payload['data'])) {
                Log::warning('Invalid webhook payload structure', ['payload' => $payload]);
                return response('Invalid payload', 400);
            }

            // Procesar cada tracking en el payload
            $processedCount = 0;
            foreach ($payload['data'] as $trackingData) {
                if ($this->trackerService->processWebhookData($trackingData)) {
                    $processedCount++;
                }
            }

            Log::info('Ship24 webhook processed successfully', [
                'total_trackings' => count($payload['data']),
                'processed_count' => $processedCount
            ]);

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Error processing Ship24 webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);

            return response('Internal Server Error', 500);
        }
    }

    /**
     * Validar que el webhook viene realmente de Ship24
     */
    private function validateWebhook(Request $request): bool
    {
        // Ship24 no envía signature por defecto, pero podemos validar otros aspectos
        $userAgent = $request->header('User-Agent');
        
        // Validar User-Agent de Ship24 (esto puede cambiar, revisar documentación)
        if (!str_contains($userAgent, 'Ship24')) {
            Log::warning('Webhook with suspicious User-Agent', ['user_agent' => $userAgent]);
        }

        // Validar Content-Type
        if (!str_contains($request->header('Content-Type', ''), 'application/json')) {
            Log::warning('Webhook with invalid Content-Type', [
                'content_type' => $request->header('Content-Type')
            ]);
            return false;
        }

        // TODO: Implementar validación de IP si Ship24 proporciona rangos específicos
        $clientIp = $request->ip();
        Log::info('Webhook from IP', ['ip' => $clientIp]);

        return true;
    }

    /**
     * Endpoint para testing (solo en desarrollo/staging)
     */
    public function test(Request $request): Response
    {
        if (!app()->environment(['local', 'development', 'staging'])) {
            return response('Not Found', 404);
        }

        Log::info('Ship24 webhook test endpoint called', ['payload' => $request->all()]);

        // Simular webhook data para testing
        $testData = [
            'data' => [
                [
                    'trackingNumber' => $request->input('tracking_number', 'TEST123456789'),
                    'statistics' => [
                        'statusCategory' => 'in_transit'
                    ],
                    'delivery' => [
                        'estimatedDeliveryDate' => '2025-08-30T10:00:00Z'
                    ],
                    'events' => [
                        [
                            'occurrenceDatetime' => '2025-08-23T08:00:00Z',
                            'status' => 'Package received',
                            'location' => 'Origin facility'
                        ]
                    ]
                ]
            ]
        ];

        $processedCount = 0;
        foreach ($testData['data'] as $trackingData) {
            if ($this->trackerService->processWebhookData($trackingData)) {
                $processedCount++;
            }
        }

        return response()->json([
            'message' => 'Test webhook processed',
            'processed_count' => $processedCount,
            'test_data' => $testData
        ]);
    }
}