<?php

namespace App\Livewire\Forms;

use App\Models\ShippingDocument;
use Livewire\Component;
use App\Services\TrackingService;
use Illuminate\Support\Facades\Log;

class PucharseOrderConsolidateDetail extends Component {
    public $shippingDocumentId;
    public $relatedPurchaseOrders = [];
    public $totalConsolidated = 0;
    public $sortField = 'po_number';
    public $sortDirection = 'asc';
    public $shippingDocument = null;
    public $totalWeight = 0;
    public $poCount = 0;
    public $trackingData = null;
    public $loadingTracking = false;

    public function mount($id = null) {
        $this->shippingDocumentId = $id;
        $this->loadRelatedPurchaseOrders();
        $this->loadTrackingData();
    }

    public function sortBy($field) {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadRelatedPurchaseOrders();
    }

    public function loadRelatedPurchaseOrders() {
        if (!$this->shippingDocumentId) {
            return;
        }

        // Try to find by numeric ID first
        if (is_numeric($this->shippingDocumentId)) {
            $shippingDocument = ShippingDocument::with(['purchaseOrders' => function($query) {
                $this->applySorting($query);
            }, 'company'])->find($this->shippingDocumentId);
        } else {
            // If not numeric, try to find by document_number
            $shippingDocument = ShippingDocument::with(['purchaseOrders' => function($query) {
                $this->applySorting($query);
            }, 'company'])->where('document_number', $this->shippingDocumentId)->first();
        }

        if (!$shippingDocument) {
            return;
        }

        \Log::info('Loaded shipping document:', [
            'id' => $shippingDocument->id,
            'tracking_id' => $shippingDocument->tracking_id
        ]);

        // Store the shipping document for the view
        $this->shippingDocument = $shippingDocument;
        $this->totalWeight = $shippingDocument->total_weight_kg;
        $this->poCount = $shippingDocument->purchaseOrders->count();

        $this->relatedPurchaseOrders = $shippingDocument->purchaseOrders->map(function($order) {
            // Get color based on status
            $statusColor = match($order->status) {
                'pending' => 'yellow',
                'approved' => 'blue',
                'shipped' => 'indigo',
                'delivered' => 'green',
                'cancelled' => 'red',
                default => 'gray'
            };

            // Count items
            $itemsCount = $order->products->count();

            return [
                'id' => $order->id,
                'po_number' => $order->order_number,
                'supplier' => $order->vendor->name ?? $order->vendor_id,
                'items_count' => $itemsCount,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'status_color' => $statusColor
            ];
        })->toArray();

        // Calculate total
        $this->totalConsolidated = $shippingDocument->purchaseOrders->sum('total_amount');
    }

    /**
     * Apply sorting to the purchase orders query
     */
    private function applySorting($query) {
        if ($this->sortField === 'po_number') {
            $query->orderBy('order_number', $this->sortDirection);
        } elseif ($this->sortField === 'supplier') {
            $query->orderBy('vendor_id', $this->sortDirection);
        } elseif ($this->sortField === 'total_amount') {
            $query->orderBy('total_amount', $this->sortDirection);
        } elseif ($this->sortField === 'status') {
            $query->orderBy('status', $this->sortDirection);
        }
        return $query;
    }

    public function loadTrackingData()
    {
        $this->loadingTracking = true;

        $trackingId = $this->shippingDocument->tracking_id ?? null;
        Log::info('Loading tracking data for document:', [
            'shipping_document_id' => $this->shippingDocument->id ?? null,
            'tracking_id' => $trackingId
        ]);

        $trackingService = new TrackingService();
        $this->trackingData = $trackingService->getTracking($trackingId);

        $this->loadingTracking = false;
    }

    public function render() {
        return view('livewire.forms.pucharse-order-consolidate-detail')->layout('layouts.app');
    }
}
