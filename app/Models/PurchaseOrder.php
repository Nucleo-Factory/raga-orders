<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'order_number',
        'status',
        'kanban_status_id',
        'total_amount',
        'notes',

        // Vendor information
        'vendor_id',
        'vendor_direccion',
        'vendor_codigo_postal',
        'vendor_pais',
        'vendor_estado',
        'vendor_telefono',

        // Ship to information
        'ship_to_nombre',
        'ship_to_direccion',
        'ship_to_codigo_postal',
        'ship_to_pais',
        'ship_to_estado',
        'ship_to_telefono',

        // Bill to information
        'bill_to_nombre',
        'bill_to_direccion',
        'bill_to_codigo_postal',
        'bill_to_pais',
        'bill_to_estado',
        'bill_to_telefono',

        // Order details
        'order_date',
        'currency',
        'incoterms',
        'payment_terms',
        'order_place',
        'email_agent',

        // Totals
        'net_total',
        'additional_cost',
        'total',

        // Dimensiones
        'length',
        'width',
        'height',
        'volume',
        'weight_kg',
        'weight_lb',

        // Fechas
        'date_required_in_destination',
        'date_planned_pickup',
        'date_actual_pickup',
        'date_estimated_hub_arrival',
        'date_actual_hub_arrival',
        'date_etd',
        'date_atd',
        'date_eta',
        'date_ata',
        'date_consolidation',
        'release_date',

        // Costos
        'insurance_cost',
        'ground_transport_cost_1',
        'ground_transport_cost_2',
        'estimated_pallet_cost',
        'other_costs',
        'other_expenses',

        // Comentarios
        'comments',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'status' => 'string',
        'net_total' => 'decimal:2',
        'additional_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'volume' => 'decimal:3',
        'weight_kg' => 'decimal:2',
        'weight_lb' => 'decimal:2',
        'insurance_cost' => 'decimal:2',
        'order_date' => 'date',
        'date_required_in_destination' => 'datetime',
        'date_planned_pickup' => 'datetime',
        'date_actual_pickup' => 'datetime',
        'date_estimated_hub_arrival' => 'datetime',
        'date_actual_hub_arrival' => 'datetime',
        'date_etd' => 'datetime',
        'date_atd' => 'datetime',
        'date_eta' => 'datetime',
        'date_ata' => 'datetime',
        'date_consolidation' => 'datetime',
        'release_date' => 'datetime',
    ];

    /**
     * Get the company that owns the purchase order.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the products for the purchase order.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'purchase_order_product')
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }

    /**
     * Get the boarding documents for the purchase order.
     */
    public function boardingDocuments(): HasMany
    {
        return $this->hasMany(BoardingDocument::class);
    }

    /**
     * Get the tracking data for the purchase order.
     */
    public function trackingData(): HasMany
    {
        return $this->hasMany(TrackingDataPO::class);
    }

    /**
     * Get the kanban status for the purchase order.
     */
    public function kanbanStatus(): BelongsTo
    {
        return $this->belongsTo(KanbanStatus::class);
    }

    /**
     * Move the purchase order to a new kanban status.
     */
    public function moveToKanbanStatus(KanbanStatus $status): self
    {
        $this->update(['kanban_status_id' => $status->id]);
        return $this;
    }

    /**
     * Move the purchase order to the next kanban status.
     */
    public function moveToNextKanbanStatus(): ?self
    {
        if ($this->kanbanStatus && $nextStatus = $this->kanbanStatus->nextStatus()) {
            return $this->moveToKanbanStatus($nextStatus);
        }
        return null;
    }

    /**
     * Move the purchase order to the previous kanban status.
     */
    public function moveToPreviousKanbanStatus(): ?self
    {
        if ($this->kanbanStatus && $prevStatus = $this->kanbanStatus->previousStatus()) {
            return $this->moveToKanbanStatus($prevStatus);
        }
        return null;
    }
}
