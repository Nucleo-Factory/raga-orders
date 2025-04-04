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
    public $attachedFiles = [];
    public $newComment = '';
    public $uploadFile;

    public function mount($id = null) {
        $this->shippingDocumentId = $id;
        $this->loadRelatedPurchaseOrders();
        $this->loadTrackingData();
        $this->loadComments();
        $this->loadAttachedFiles();
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
                // Log the comment to see what fields are available
                Log::info('Comment data:', ['comment' => $comment->toArray()]);

                return [
                    'id' => $comment->id,
                    'content' => $comment->comment ?? $comment->content ?? '', // Try both possible field names
                    'created_at' => $comment->created_at,
                    'user' => [
                        'id' => $comment->user->id ?? null,
                        'name' => $comment->user->name ?? 'Usuario',
                        'avatar' => $comment->user->profile_photo_url ?? null,
                        'initial' => substr($comment->user->name ?? 'U', 0, 1),
                    ]
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
        $this->attachedFiles = $this->shippingDocument->getMedia('shipping_documents')
            ->map(function($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->file_name,
                    'path' => $media->getPath(),
                    'url' => $media->getUrl(),
                    'type' => $this->getFileTypeFromName($media->file_name),
                    'size' => $media->size,
                    'size_formatted' => $this->formatFileSize($media->size),
                    'created_at' => $media->created_at,
                    'user' => [
                        'id' => $media->getCustomProperty('uploaded_by') ?? null,
                        'name' => $this->getUserNameById($media->getCustomProperty('uploaded_by')) ?? 'Usuario del sistema',
                    ],
                    'custom_properties' => $media->custom_properties
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

        $user = \App\Models\User::find($userId);
        return $user ? $user->name : 'Usuario del sistema';
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
        } catch (\Exception $e) {
            Log::error('Error adding comment: ' . $e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Error al añadir el comentario'
            ]);
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
     * Process file upload
     */
    public function updatedUploadFile()
    {
        $this->validate([
            'uploadFile' => 'file|max:10240', // 10MB max
        ]);

        try {
            if (!$this->shippingDocument) {
                throw new \Exception('No shipping document loaded');
            }

            // Add file to media library
            $media = $this->shippingDocument->addMedia($this->uploadFile->getRealPath())
                ->usingName($this->uploadFile->getClientOriginalName())
                ->withCustomProperties([
                    'stage' => 'attachment',
                    'comment' => null,
                    'uploaded_by' => auth()->id() ?: 'system'
                ])
                ->toMediaCollection('shipping_documents');

            // Reset the file upload field
            $this->uploadFile = null;

            // Refresh the files list
            $this->loadAttachedFiles();

            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Archivo subido exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading file: ' . $e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Error al subir el archivo: ' . $e->getMessage()
            ]);
        }
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

    public function render() {
        return view('livewire.forms.pucharse-order-consolidate-detail')->layout('layouts.app');
    }
}
