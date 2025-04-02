<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TrackingService
{
    protected $ship24ApiKey;
    protected $porthApiKey;

    public function __construct()
    {
        $this->ship24ApiKey = config('services.ship24.api_key');
        $this->porthApiKey = config('services.porth.api_key');
    }

    public function getShip24Tracking($trackingNumber)
    {
        try {
            // Esta es una implementación de ejemplo - ajusta según la documentación real de Ship24
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->ship24ApiKey,
            ])->post('https://api.ship24.com/v1/trackers', [
                'trackingNumber' => $trackingNumber,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getPorthTracking($trackingNumber)
    {
        try {
            // Esta es una implementación de ejemplo - ajusta según la documentación real de Porth
            $response = Http::withHeaders([
                'X-API-Key' => $this->porthApiKey,
            ])->get("https://api.porth.com/v1/tracking/{$trackingNumber}");

            return $response->json();
        } catch (\Exception $e) {
            return null;
        }
    }

    // Método para obtener datos de prueba para el frontend
    public function getMockTrackingData()
    {
        return [
            'events' => [
                [
                    'date' => '2024-03-20 08:00:00',
                    'status' => 'Order Created',
                    'location' => 'Shanghai, China',
                    'description' => 'Shipping order has been created'
                ],
                [
                    'date' => '2024-03-21 10:30:00',
                    'status' => 'Picked Up',
                    'location' => 'Shanghai Port',
                    'description' => 'Package has been picked up by carrier'
                ],
                [
                    'date' => '2024-03-23 15:45:00',
                    'status' => 'In Transit',
                    'location' => 'Pacific Ocean',
                    'description' => 'Package is in transit to destination'
                ],
                [
                    'date' => '2024-03-25 09:15:00',
                    'status' => 'Arrived at Port',
                    'location' => 'Los Angeles, USA',
                    'description' => 'Package has arrived at destination port'
                ],
            ],
            'estimated_delivery' => '2024-03-27',
            'current_status' => 'In Transit'
        ];
    }
}
