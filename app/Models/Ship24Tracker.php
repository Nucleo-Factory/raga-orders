<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ship24Tracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'tracking_number',
        'ship24_tracker_id',
        'carrier_code',
        'origin_country',
        'destination_country',
        'status',
        'tracking_data',
        'current_phase',
        'estimated_delivery',
        'last_ship24_update',
        'last_webhook_received',
    ];

    protected $casts = [
        'tracking_data' => 'array',
        'estimated_delivery' => 'datetime',
        'last_ship24_update' => 'datetime',
        'last_webhook_received' => 'datetime',
    ];

    /**
     * Get the purchase order that owns the tracker.
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Scope para trackers activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope para trackers por número de tracking
     */
    public function scopeByTrackingNumber($query, $trackingNumber)
    {
        return $query->where('tracking_number', $trackingNumber);
    }

    /**
     * Verifica si el tracker está activo en Ship24
     */
    public function isActiveInShip24(): bool
    {
        return in_array($this->status, ['active', 'pending']) && !empty($this->ship24_tracker_id);
    }

    /**
     * Obtiene los datos de tracking en formato compatible con la interfaz actual
     */
    public function getFormattedTrackingData(): array
    {
        if (empty($this->tracking_data)) {
            return [];
        }

        // Convertir datos de Ship24 al formato esperado por el frontend
        return [
            'raw_data' => $this->tracking_data,
            'current_phase' => $this->current_phase,
            'estimated_delivery' => $this->estimated_delivery?->toISOString(),
        ];
    }

    /**
     * Actualiza los datos de tracking desde un webhook
     */
    public function updateFromWebhook(array $webhookData): void
    {
        $this->update([
            'tracking_data' => $webhookData,
            'current_phase' => $webhookData['statistics']['statusCategory'] ?? $this->current_phase,
            'estimated_delivery' => isset($webhookData['delivery']['estimatedDeliveryDate']) 
                ? \Carbon\Carbon::parse($webhookData['delivery']['estimatedDeliveryDate']) 
                : $this->estimated_delivery,
            'last_webhook_received' => now(),
            'status' => $this->determineStatusFromWebhook($webhookData),
        ]);
    }

    /**
     * Determina el estado basado en los datos del webhook
     */
    private function determineStatusFromWebhook(array $webhookData): string
    {
        $statusCategory = $webhookData['statistics']['statusCategory'] ?? '';
        
        return match($statusCategory) {
            'delivered' => 'delivered',
            'exception', 'expired' => 'error',
            'in_transit', 'out_for_delivery' => 'active',
            default => $this->status,
        };
    }
}