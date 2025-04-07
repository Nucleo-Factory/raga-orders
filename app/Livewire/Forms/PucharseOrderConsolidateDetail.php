<?php

namespace App\Livewire\Forms;

use App\Models\ShippingDocument;
use Livewire\Component;
use App\Services\TrackingService;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class PucharseOrderConsolidateDetail extends Component {
    use WithFileUploads;

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
    public $comments = [];
    public $attachments = [];
    public $newComment = '';
    public $uploadFile;
    public $hubLocation = null;
    public $commentSortField = 'created_at';
    public $commentSortDirection = 'desc';

    public function mount($id = null) {
        $this->shippingDocumentId = $id;
        $this->loadRelatedPurchaseOrders();
        $this->loadTrackingData();
        $this->loadComments();
        $this->loadAttachedFiles();
        $this->loadHubLocation();

        // Establecer valores por defecto para variable de ordenamiento
        $this->commentSortField = 'created_at';
        $this->commentSortDirection = 'desc';
    }

    public function loadHubLocation() {
        $this->hubLocation = $this->shippingDocument->purchaseOrders->first()->actualHub->name;
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

    /**
     * Load comments related to the shipping document
     */
    public function loadComments()
    {
        if (!$this->shippingDocument) {
            return;
        }

        Log::info('Loading comments for document:', [
            'shipping_document_id' => $this->shippingDocument->id
        ]);

        // Load comments from the shipping_document_comments table
        $this->comments = $this->shippingDocument->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'user_name' => $comment->user->name ?? 'Usuario', // Formato esperado por la tabla
                    'content' => $comment->comment ?? $comment->content ?? '', // Contenido del comentario
                    'created_at' => $comment->created_at,
                    'type' => 'comment' // Necesario para identificar el tipo en la tabla
                ];
            })
            ->toArray();
    }

    /**
     * Load files attached to the shipping document
     */
    public function loadAttachedFiles()
    {
        if (!$this->shippingDocument) {
            return;
        }

        Log::info('Loading attached files for document:', [
            'shipping_document_id' => $this->shippingDocument->id
        ]);

        // Load files using Spatie Media Library
        $this->attachments = $this->shippingDocument->getMedia('shipping_documents')
            ->map(function($media) {
                return [
                    'id' => $media->id,
                    'user_name' => $this->getUserNameById($media->getCustomProperty('uploaded_by')) ?? 'Usuario',
                    'filename' => $media->file_name,
                    'file_type' => strtoupper(pathinfo($media->file_name, PATHINFO_EXTENSION)),
                    'file_size' => $this->formatFileSize($media->size),
                    'created_at' => $media->created_at,
                    'url' => $media->getUrl(),
                    'type' => 'attachment' // Necesario para identificar el tipo en la tabla
                ];
            })
            ->toArray();
    }

    /**
     * Get a username by user ID
     */
    private function getUserNameById($userId)
    {
        if (!$userId) return 'Usuario del sistema';

        // Verificar si el valor es un número (ID) o un texto (nombre)
        if (is_numeric($userId)) {
            // Es un ID, buscamos el usuario por ID
            $user = \App\Models\User::find($userId);
            return $user ? $user->name : 'Usuario del sistema';
        } else {
            // Es un nombre, lo devolvemos directamente
            return $userId;
        }
    }

    /**
     * Sort comments and attachments
     */
    public function sortComments($field)
    {
        if ($this->commentSortField === $field) {
            $this->commentSortDirection = $this->commentSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->commentSortField = $field;
            $this->commentSortDirection = 'asc';
        }

        // Recargar con orden actualizado
        $this->sortCommentsAndAttachments();
    }

    protected function sortCommentsAndAttachments()
    {
        $allItems = array_merge($this->comments, $this->attachments);

        usort($allItems, function ($a, $b) {
            $fieldA = $a[$this->commentSortField] ?? '';
            $fieldB = $b[$this->commentSortField] ?? '';

            if ($this->commentSortField === 'created_at') {
                $timeA = strtotime($fieldA);
                $timeB = strtotime($fieldB);
                return $this->commentSortDirection === 'asc'
                    ? $timeA - $timeB
                    : $timeB - $timeA;
            }

            return $this->commentSortDirection === 'asc'
                ? strcmp($fieldA, $fieldB)
                : strcmp($fieldB, $fieldA);
        });

        // Separar de nuevo los elementos ordenados
        $this->comments = array_values(array_filter($allItems, function($item) {
            return $item['type'] === 'comment';
        }));

        $this->attachments = array_values(array_filter($allItems, function($item) {
            return $item['type'] === 'attachment';
        }));
    }

    /**
     * Process file upload
     */
    public function uploadFileAction()
    {
        // Eliminar el dd para permitir que la función se ejecute
        // dd($this->uploadFile);

        // Añadir log inmediatamente al entrar en el método
        Log::info('uploadFileAction method called', [
            'has_file' => $this->uploadFile ? 'yes' : 'no',
        ]);

        $this->validate([
            'uploadFile' => 'required|file|max:10240', // 10MB max
        ]);

        try {
            if (!$this->shippingDocument) {
                throw new \Exception('No shipping document loaded');
            }

            Log::info('Attempting to upload file', [
                'file_name' => $this->uploadFile->getClientOriginalName(),
                'file_size' => $this->uploadFile->getSize()
            ]);

            // Add file to media library - AQUÍ ESTÁ EL CAMBIO
            $media = $this->shippingDocument->addMedia($this->uploadFile->getRealPath())
                ->usingName($this->uploadFile->getClientOriginalName())
                ->withCustomProperties([
                    'uploaded_by' => auth()->id() ?? null, // Usar auth()->id() en lugar del nombre
                ])
                ->toMediaCollection('shipping_documents');

            // Reset the file upload field
            $this->uploadFile = null;

            // Refresh the files list
            $this->loadAttachedFiles();

            session()->flash('message', 'Archivo subido exitosamente');
        } catch (\Exception $e) {
            Log::error('Error uploading file: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            session()->flash('error', 'Error al subir el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Add a new comment to the shipping document
     */
    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|min:3|max:500',
        ]);

        try {
            if (!$this->shippingDocument) {
                throw new \Exception('No shipping document loaded');
            }

            // Create a new comment through the relationship
            $comment = $this->shippingDocument->comments()->create([
                'comment' => $this->newComment,
                'user_id' => auth()->id(),
            ]);

            // Clear the input field
            $this->newComment = '';

            // Refresh the comments list
            $this->loadComments();

            session()->flash('message', 'Comentario añadido correctamente');
        } catch (\Exception $e) {
            Log::error('Error adding comment: ' . $e->getMessage());
            session()->flash('error', 'Error al añadir el comentario: ' . $e->getMessage());
        }
    }

    /**
     * Get a user-friendly file type based on the file extension
     */
    private function getFileTypeFromName($fileName)
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        return match(strtolower($extension)) {
            'pdf' => 'Documento PDF',
            'jpg', 'jpeg', 'png', 'gif' => 'Imagen',
            'doc', 'docx' => 'Documento Word',
            'xls', 'xlsx' => 'Hoja de cálculo',
            'ppt', 'pptx' => 'Presentación',
            'zip', 'rar' => 'Archivo comprimido',
            default => 'Documento'
        };
    }

    /**
     * Format file size in a user-friendly format
     */
    private function formatFileSize($size)
    {
        if (!$size) return '0 KB';

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $size = max($size, 0);
        $pow = floor(($size ? log($size) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $size /= (1 << (10 * $pow));

        return round($size, 2) . ' ' . $units[$pow];
    }

    /**
     * Delete a file
     */
    public function deleteFile($mediaId)
    {
        try {
            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($mediaId);

            // Ensure the media belongs to the current shipping document
            if ($media->model_id != $this->shippingDocument->id) {
                throw new \Exception('The file does not belong to this shipping document');
            }

            // Delete the media
            $media->delete();

            // Refresh the files list
            $this->loadAttachedFiles();

            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Archivo eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting file: ' . $e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Error al eliminar el archivo'
            ]);
        }
    }

    public function updatedUploadFile()
    {
        Log::info('updatedUploadFile triggered', [
            'has_file' => $this->uploadFile ? 'yes' : 'no'
        ]);

        // Este método se ejecuta automáticamente cuando se selecciona un archivo
        // Puedes poner aquí parte de la lógica para verificar que está funcionando
    }

    public function render() {
        return view('livewire.forms.pucharse-order-consolidate-detail')->layout('layouts.app');
    }
}
