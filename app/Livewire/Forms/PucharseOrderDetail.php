<?php

namespace App\Livewire\Forms;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Services\TrackingService;
use Livewire\WithFileUploads;

class PucharseOrderDetail extends Component
{
    use WithFileUploads;

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
    public $comments = [];
    public $attachments = [];
    public $commentSortField = 'created_at';
    public $commentSortDirection = 'desc';

    // Search and sorting variables
    public $search = '';
    public $sortField = 'material_id';
    public $sortDirection = 'asc';

    public $newFile;
    public $newComment = '';
    public $fileSelected = false;

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

        // Cargar los comentarios y archivos adjuntos
        $this->loadCommentsAndAttachments();
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
            $this->trackingData = $trackingService->getShip24Tracking($trackingId);

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

    protected function loadCommentsAndAttachments()
    {
        // Carga de comentarios desde la relación en el modelo
        $this->comments = $this->purchaseOrder->comments()
            ->latest()
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'user_name' => $comment->user->name ?? 'Usuario',  // Asumiendo que hay una relación user en el comentario
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at,
                    'type' => 'comment'
                ];
            })
            ->toArray();

        // Carga de archivos adjuntos usando Spatie Media Library
        $this->attachments = $this->purchaseOrder->getMedia('attachments')
            ->map(function($media) {
                return [
                    'id' => $media->id,
                    'user_name' => $media->custom_properties['uploaded_by'] ?? 'Usuario',  // Asumiendo que guardas quién subió el archivo
                    'filename' => $media->file_name,
                    'file_type' => strtoupper($media->extension),
                    'file_size' => $this->formatBytes($media->size),
                    'created_at' => $media->created_at,
                    'url' => $media->getUrl(),
                    'type' => 'attachment'
                ];
            })
            ->toArray();
    }

    // Función auxiliar para formatear bytes en unidades legibles
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    // Método para buscar en comentarios y archivos
    public function getFilteredCommentsAndAttachments()
    {
        $search = strtolower($this->search);

        $filteredComments = empty($search) ? $this->comments : array_filter($this->comments, function($comment) use ($search) {
            return str_contains(strtolower($comment['user_name']), $search) ||
                   str_contains(strtolower($comment['comment']), $search);
        });

        $filteredAttachments = empty($search) ? $this->attachments : array_filter($this->attachments, function($attachment) use ($search) {
            return str_contains(strtolower($attachment['user_name']), $search) ||
                   str_contains(strtolower($attachment['filename']), $search);
        });

        return [
            'comments' => array_values($filteredComments),
            'attachments' => array_values($filteredAttachments)
        ];
    }

    public function sortComments($field)
    {
        if ($this->commentSortField === $field) {
            $this->commentSortDirection = $this->commentSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->commentSortField = $field;
            $this->commentSortDirection = 'asc';
        }

        // Ordenar los comentarios y archivos
        $this->sortCommentsAndAttachments();
    }

    protected function sortCommentsAndAttachments()
    {
        $allItems = array_merge($this->comments, $this->attachments);

        usort($allItems, function ($a, $b) {
            $fieldA = $a[$this->commentSortField] ?? '';
            $fieldB = $b[$this->commentSortField] ?? '';

            return $this->commentSortDirection === 'asc'
                ? strcmp($fieldA, $fieldB)
                : strcmp($fieldB, $fieldA);
        });

        // Separar de nuevo los elementos ordenados
        $this->comments = array_filter($allItems, function($item) {
            return $item['type'] === 'comment';
        });

        $this->attachments = array_filter($allItems, function($item) {
            return $item['type'] === 'attachment';
        });
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|min:3',
        ]);

        try {
            // Crear el comentario en la base de datos
            $this->purchaseOrder->comments()->create([
                'user_id' => auth()->id() ?? 1,
                'comment' => $this->newComment,
            ]);

            session()->flash('message', 'Comentario añadido correctamente');

            // Recargar comentarios
            $this->loadCommentsAndAttachments();

            // Limpiar el campo
            $this->newComment = '';
        } catch (\Exception $e) {
            session()->flash('error', 'Error al añadir comentario: ' . $e->getMessage());
        }
    }

    public function uploadFile()
    {
        $this->validate([
            'newFile' => 'required|file|max:10240', // 10MB max
        ]);

        try {
            // Subir el archivo usando Spatie Media Library
            $this->purchaseOrder
                ->addMedia($this->newFile->getRealPath())
                ->usingName(pathinfo($this->newFile->getClientOriginalName(), PATHINFO_FILENAME))
                ->usingFileName($this->newFile->getClientOriginalName())
                ->withCustomProperties([
                    'uploaded_by' => auth()->user()->name ?? 'Usuario',
                ])
                ->toMediaCollection('attachments');

            session()->flash('message', 'Archivo subido correctamente');

            // Recargar archivos
            $this->loadCommentsAndAttachments();

            // Limpiar el campo
            $this->newFile = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al subir archivo: ' . $e->getMessage());
        }
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

        // Get filtered comments and attachments
        $filteredItems = $this->getFilteredCommentsAndAttachments();

        return view('livewire.forms.pucharse-order-detail', [
            'orderProducts' => $filteredProducts,
            'filteredComments' => $filteredItems['comments'],
            'filteredAttachments' => $filteredItems['attachments']
        ])->layout('layouts.app');
    }
}
