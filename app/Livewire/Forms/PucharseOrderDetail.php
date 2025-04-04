<?php

namespace App\Livewire\Forms;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Services\TrackingService;

class PucharseOrderDetail extends Component
{
    public $purchaseOrder;
    public $purchaseOrderDetails;
    public $orderProducts = [];
    public $net_total = 0;
    public $additional_cost = 0;
    public $insurance_cost = 0;
    public $total = 0;
    public $loadingTracking = false;
    public $trackingData = [];
    public $shippingDocument;

    // Search and sorting variables
    public $search = '';
    public $sortField = 'material_id';
    public $sortDirection = 'asc';

    public function mount($id)
    {
        // Cargar la orden de compra con sus productos y hub relacionados
        $this->purchaseOrder = PurchaseOrder::with(['products', 'actualHub'])->findOrFail($id);
        $this->purchaseOrderDetails = PurchaseOrder::findOrFail($id);

        // Cargar los productos en el formato que necesitamos
        $this->loadOrderProducts();

        // Cargar los totales
        $this->loadTotals();

        // If this purchase order has a tracking ID, load the tracking data
        if ($this->purchaseOrder->tracking_id) {
            $this->loadTrackingData();
        }
    }

    protected function loadOrderProducts()
    {
        $this->orderProducts = [];

        foreach ($this->purchaseOrder->products as $product) {
            $this->orderProducts[] = [
                'id' => $product->id,
                'material_id' => $product->material_id,
                'description' => $product->description,
                'price_per_unit' => $product->pivot->unit_price,
                'quantity' => $product->pivot->quantity,
                'subtotal' => $product->pivot->unit_price * $product->pivot->quantity
            ];
        }
    }

    protected function loadTotals()
    {
        // Cargar los totales desde la orden de compra
        $this->net_total = $this->purchaseOrder->net_total ?? 0;
        $this->additional_cost = $this->purchaseOrder->additional_cost ?? 0;
        $this->insurance_cost = $this->purchaseOrder->insurance_cost ?? 0;
        $this->total = $this->purchaseOrder->total ?? 0;

        // Si no hay totales guardados, calcularlos
        if ($this->net_total == 0) {
            $this->calculateTotals();
        }
    }

    protected function calculateTotals()
    {
        // Calcular el total neto sumando los subtotales de todos los productos
        $this->net_total = 0;
        foreach ($this->orderProducts as $product) {
            $this->net_total += $product['subtotal'];
        }

        // Calcular el total final (neto + adicionales)
        $this->total = $this->net_total + $this->additional_cost + $this->insurance_cost;
    }

    public function sortBy($field)
    {
        // If clicking on the current sort field, reverse direction
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        // Sort the orderProducts array
        usort($this->orderProducts, function ($a, $b) {
            $fieldA = $a[$this->sortField];
            $fieldB = $b[$this->sortField];

            // Handle numeric fields
            if (is_numeric($fieldA) && is_numeric($fieldB)) {
                return $this->sortDirection === 'asc'
                    ? $fieldA <=> $fieldB
                    : $fieldB <=> $fieldA;
            }

            // Handle string fields
            return $this->sortDirection === 'asc'
                ? strcmp($fieldA, $fieldB)
                : strcmp($fieldB, $fieldA);
        });
    }

    public function loadTrackingData()
    {
        $this->loadingTracking = true;

        try {
            // Get the tracking ID from the purchase order directly
            $trackingId = $this->purchaseOrder->tracking_id ?? null;

            Log::info('Loading tracking data for purchase order:', [
                'purchase_order_id' => $this->purchaseOrder->id ?? null,
                'tracking_id' => $trackingId
            ]);

            $trackingService = new TrackingService();
            $this->trackingData = $trackingService->getTracking($trackingId);

            Log::info('Tracking data loaded successfully', [
                'has_timeline' => isset($this->trackingData['timeline']),
                'milestone' => $this->trackingData['current_phase'] ?? 'none'
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading tracking data', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->trackingData = [];
        }

        $this->loadingTracking = false;
    }


    public function render()
    {
        // Filter orderProducts if search is provided
        $filteredProducts = $this->orderProducts;
        if (!empty($this->search)) {
            $search = strtolower($this->search);
            $filteredProducts = array_filter($this->orderProducts, function($product) use ($search) {
                return
                    str_contains(strtolower($product['material_id']), $search) ||
                    str_contains(strtolower($product['description']), $search);
            });
        }

        return view('livewire.forms.pucharse-order-detail', [
            'orderProducts' => $filteredProducts
        ])->layout('layouts.app');
    }
}
