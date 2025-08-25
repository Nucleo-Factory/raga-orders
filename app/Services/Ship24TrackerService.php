<?php

namespace App\Services;

use App\Models\Ship24Tracker;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Ship24TrackerService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.ship24.com/public/v1';

    public function __construct()
    {
        $this->apiKey = config('services.ship24.api_key');
    }

    /**
     * Crear un nuevo tracker en Ship24 y en nuestra base de datos
     */
    public function createTracker(
        string $trackingNumber, 
        ?string $carrierCode = null, 
        ?int $purchaseOrderId = null,
        ?string $originCountry = null,
        ?string $destinationCountry = null
    ): ?Ship24Tracker {
        try {
            Log::info('Creating Ship24 tracker', [
                'tracking_number' => $trackingNumber,
                'carrier_code' => $carrierCode,
                'purchase_order_id' => $purchaseOrderId
            ]);

            // Verificar si ya existe un tracker para este tracking number
            $existingTracker = Ship24Tracker::byTrackingNumber($trackingNumber)->first();
            if ($existingTracker && $existingTracker->isActiveInShip24()) {
                Log::info('Tracker already exists', ['tracker_id' => $existingTracker->id]);
                return $existingTracker;
            }

            // Preparar datos para Ship24
            $trackerData = [
                'trackingNumber' => $trackingNumber
            ];

            if ($carrierCode) {
                $trackerData['courierCode'] = $carrierCode;
            }

            if ($originCountry) {
                $trackerData['originCountryCode'] = $originCountry;
            }

            if ($destinationCountry) {
                $trackerData['destinationCountryCode'] = $destinationCountry;
            }

            // Crear tracker en Ship24
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/trackers', $trackerData);

            if ($response->failed()) {
                Log::error('Failed to create Ship24 tracker', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'tracking_number' => $trackingNumber
                ]);
                return null;
            }

            $responseData = $response->json();
            $ship24TrackerId = $responseData['data']['tracker']['trackerId'] ?? null;

            if (!$ship24TrackerId) {
                Log::error('No tracker ID returned from Ship24', ['response' => $responseData]);
                return null;
            }

            // Crear o actualizar registro en nuestra base de datos
            $tracker = Ship24Tracker::updateOrCreate(
                ['tracking_number' => $trackingNumber],
                [
                    'purchase_order_id' => $purchaseOrderId,
                    'ship24_tracker_id' => $ship24TrackerId,
                    'carrier_code' => $carrierCode,
                    'origin_country' => $originCountry,
                    'destination_country' => $destinationCountry,
                    'status' => 'active',
                    'last_ship24_update' => now(),
                ]
            );

            Log::info('Ship24 tracker created successfully', [
                'tracker_id' => $tracker->id,
                'ship24_tracker_id' => $ship24TrackerId
            ]);

            return $tracker;

        } catch (\Exception $e) {
            Log::error('Error creating Ship24 tracker', [
                'error' => $e->getMessage(),
                'tracking_number' => $trackingNumber,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Obtener estado actual de un tracker desde Ship24
     */
    public function getTrackerStatus(string $trackingNumber): ?array
    {
        try {
            $tracker = Ship24Tracker::byTrackingNumber($trackingNumber)->first();
            
            if (!$tracker || !$tracker->ship24_tracker_id) {
                Log::warning('No tracker found for tracking number', ['tracking_number' => $trackingNumber]);
                return null;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/trackers/' . $tracker->ship24_tracker_id);

            if ($response->failed()) {
                Log::error('Failed to get tracker status from Ship24', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'tracker_id' => $tracker->ship24_tracker_id
                ]);
                return null;
            }

            $data = $response->json();
            
            // Actualizar nuestros datos locales
            if (isset($data['data']['trackings'][0])) {
                $trackingData = $data['data']['trackings'][0];
                $tracker->update([
                    'tracking_data' => $trackingData,
                    'current_phase' => $trackingData['statistics']['statusCategory'] ?? null,
                    'estimated_delivery' => isset($trackingData['delivery']['estimatedDeliveryDate']) 
                        ? \Carbon\Carbon::parse($trackingData['delivery']['estimatedDeliveryDate']) 
                        : null,
                    'last_ship24_update' => now(),
                ]);
            }

            return $tracker->getFormattedTrackingData();

        } catch (\Exception $e) {
            Log::error('Error getting tracker status', [
                'error' => $e->getMessage(),
                'tracking_number' => $trackingNumber
            ]);
            return null;
        }
    }

    /**
     * Eliminar un tracker de Ship24
     */
    public function deleteTracker(string $trackingNumber): bool
    {
        try {
            $tracker = Ship24Tracker::byTrackingNumber($trackingNumber)->first();
            
            if (!$tracker || !$tracker->ship24_tracker_id) {
                return false;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->delete($this->baseUrl . '/trackers/' . $tracker->ship24_tracker_id);

            if ($response->successful()) {
                $tracker->update(['status' => 'expired']);
                Log::info('Tracker deleted from Ship24', ['tracker_id' => $tracker->id]);
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Error deleting tracker', [
                'error' => $e->getMessage(),
                'tracking_number' => $trackingNumber
            ]);
            return false;
        }
    }

    /**
     * Procesar datos recibidos de webhook
     */
    public function processWebhookData(array $webhookData): bool
    {
        try {
            $trackingNumber = $webhookData['trackingNumber'] ?? null;
            
            if (!$trackingNumber) {
                Log::warning('No tracking number in webhook data', ['data' => $webhookData]);
                return false;
            }

            $tracker = Ship24Tracker::byTrackingNumber($trackingNumber)->first();
            
            if (!$tracker) {
                Log::warning('No tracker found for webhook', ['tracking_number' => $trackingNumber]);
                return false;
            }

            $tracker->updateFromWebhook($webhookData);
            
            Log::info('Tracker updated from webhook', [
                'tracker_id' => $tracker->id,
                'tracking_number' => $trackingNumber,
                'status' => $tracker->status
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error processing webhook data', [
                'error' => $e->getMessage(),
                'data' => $webhookData
            ]);
            return false;
        }
    }

    /**
     * Crear tracker para un PurchaseOrder existente
     */
    public function createTrackerForPurchaseOrder(PurchaseOrder $purchaseOrder): ?Ship24Tracker
    {
        if (!$purchaseOrder->tracking_id) {
            Log::info('No tracking_id for purchase order', ['po_id' => $purchaseOrder->id]);
            return null;
        }

        // Intentar obtener información adicional del PO
        $carrierCode = $this->guessCarrierFromTrackingNumber($purchaseOrder->tracking_id);
        $originCountry = $this->getOriginCountryFromPO($purchaseOrder);
        $destinationCountry = $this->getDestinationCountryFromPO($purchaseOrder);

        return $this->createTracker(
            $purchaseOrder->tracking_id,
            $carrierCode,
            $purchaseOrder->id,
            $originCountry,
            $destinationCountry
        );
    }

    /**
     * Intentar adivinar el carrier desde el tracking number
     */
    private function guessCarrierFromTrackingNumber(string $trackingNumber): ?string
    {
        // Patrones básicos para carriers comunes
        $patterns = [
            'UPS' => '/^1Z[A-Z0-9]{16}$/',
            'FEDX' => '/^[0-9]{12,14}$/',
            'DHL' => '/^[0-9]{10,11}$/',
            'USPS' => '/^(94|93|92|91)[0-9]{20}$/',
        ];

        foreach ($patterns as $carrier => $pattern) {
            if (preg_match($pattern, $trackingNumber)) {
                return $carrier;
            }
        }

        return null;
    }

    /**
     * Obtener país de origen desde PO
     */
    private function getOriginCountryFromPO(PurchaseOrder $purchaseOrder): ?string
    {
        // TODO: Implementar lógica basada en vendor o datos del PO
        return null;
    }

    /**
     * Obtener país de destino desde PO
     */
    private function getDestinationCountryFromPO(PurchaseOrder $purchaseOrder): ?string
    {
        // TODO: Implementar lógica basada en ship_to o datos del PO
        return null;
    }
}
