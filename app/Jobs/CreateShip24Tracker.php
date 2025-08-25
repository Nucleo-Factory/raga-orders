<?php

namespace App\Jobs;

use App\Models\PurchaseOrder;
use App\Services\Ship24TrackerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateShip24Tracker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $purchaseOrder;
    protected $trackingNumber;
    protected $carrierCode;

    /**
     * Create a new job instance.
     */
    public function __construct(PurchaseOrder $purchaseOrder, ?string $carrierCode = null)
    {
        $this->purchaseOrder = $purchaseOrder;
        $this->trackingNumber = $purchaseOrder->tracking_id;
        $this->carrierCode = $carrierCode;
        
        // Configurar la cola y el delay
        $this->onQueue('ship24');
        $this->delay(now()->addSeconds(10)); // Pequeño delay para evitar race conditions
    }

    /**
     * Execute the job.
     */
    public function handle(Ship24TrackerService $trackerService): void
    {
        try {
            Log::info('Processing CreateShip24Tracker job', [
                'purchase_order_id' => $this->purchaseOrder->id,
                'tracking_number' => $this->trackingNumber,
                'carrier_code' => $this->carrierCode
            ]);

            // Verificar que el PO aún existe y tiene tracking_id
            $this->purchaseOrder->refresh();
            
            if (!$this->purchaseOrder->tracking_id) {
                Log::warning('PurchaseOrder no longer has tracking_id, skipping tracker creation', [
                    'purchase_order_id' => $this->purchaseOrder->id
                ]);
                return;
            }

            // Verificar si ya existe un tracker activo
            $existingTracker = $this->purchaseOrder->ship24Tracker()
                ->where('tracking_number', $this->trackingNumber)
                ->where('status', 'active')
                ->first();

            if ($existingTracker) {
                Log::info('Tracker already exists for this PO, skipping creation', [
                    'purchase_order_id' => $this->purchaseOrder->id,
                    'tracker_id' => $existingTracker->id
                ]);
                return;
            }

            // Crear el tracker
            $tracker = $trackerService->createTrackerForPurchaseOrder($this->purchaseOrder);

            if ($tracker) {
                Log::info('Ship24 tracker created successfully via job', [
                    'purchase_order_id' => $this->purchaseOrder->id,
                    'tracker_id' => $tracker->id,
                    'ship24_tracker_id' => $tracker->ship24_tracker_id
                ]);
            } else {
                Log::error('Failed to create Ship24 tracker via job', [
                    'purchase_order_id' => $this->purchaseOrder->id,
                    'tracking_number' => $this->trackingNumber
                ]);
                
                // Re-queue el job para reintento con backoff
                $this->release(300); // Reintentar en 5 minutos
            }

        } catch (\Exception $e) {
            Log::error('Error in CreateShip24Tracker job', [
                'purchase_order_id' => $this->purchaseOrder->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Si es un error recoverable, reintentamos
            if ($this->attempts() < 3) {
                $this->release(600); // Reintentar en 10 minutos
            } else {
                Log::error('Max attempts reached for CreateShip24Tracker job', [
                    'purchase_order_id' => $this->purchaseOrder->id,
                    'attempts' => $this->attempts()
                ]);
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('CreateShip24Tracker job failed permanently', [
            'purchase_order_id' => $this->purchaseOrder->id,
            'tracking_number' => $this->trackingNumber,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }
}