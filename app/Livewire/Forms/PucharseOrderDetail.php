<?php

namespace App\Livewire\Forms;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Services\TrackingService;
use Livewire\WithFileUploads;
use App\Services\AuthorizationService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderComment;

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

    public $comment = '';
    public $attachment = null;

    public $fileUploadApproved = false;
    public $commentAttachmentApproved = false;
    public $approvedCommentData = null;

    protected AuthorizationService $authorizationService;

    public function boot()
    {
        $this->authorizationService = app(AuthorizationService::class);
    }

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

        // Verificar si hay una aprobación de archivo pendiente
        $this->fileUploadApproved = Session::has('file_upload_approved') &&
                                    Session::get('purchase_order_id') == $id;

        // Verificar si hay una aprobación de comentario con archivo pendiente
        $this->commentAttachmentApproved = Session::has('comment_attachment_approved') &&
                                         Session::get('purchase_order_id') == $id &&
                                         Session::has('comment_id');

        // Log de diagnóstico sobre las variables de sesión
        \Log::info('Variables de sesión en mount', [
            'fileUploadApproved' => $this->fileUploadApproved,
            'commentAttachmentApproved' => $this->commentAttachmentApproved,
            'session_comment_id' => Session::get('comment_id'),
            'session_purchase_order_id' => Session::get('purchase_order_id')
        ]);

        // Cargar los comentarios y archivos adjuntos después de verificar las aprobaciones
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
        try {
            // 1. Load all comments from database (both pending and approved)
            \Log::info('Loading comments for PO', [
                'purchase_order_id' => $this->purchaseOrder->id
            ]);

            // Get all comments for this purchase order
            $comments = PurchaseOrderComment::with(['user.roles', 'media', 'authorizations'])
                ->where('purchase_order_id', $this->purchaseOrder->id)
                ->latest()
                ->get();

            \Log::info('Comments found', [
                'count' => $comments->count(),
                'purchase_order_id' => $this->purchaseOrder->id,
                'comment_ids' => $comments->pluck('id')->toArray()
            ]);

            // Process comments based on their status
            $processedComments = $comments->map(function($comment) {
                $attachment = $comment->getFirstMedia('attachments');

                // Log for diagnostics
                \Log::info('Processing comment', [
                    'comment_id' => $comment->id,
                    'purchase_order_id' => $comment->purchase_order_id,
                    'user_id' => $comment->user_id,
                    'operation' => $comment->operacion,
                    'status' => $comment->status,
                    'comment_text' => $comment->comment,
                    'has_attachment' => $attachment ? true : false
                ]);

                // Convert comment status to display text
                $statusDisplay = 'Pendiente';
                if ($comment->isApproved()) {
                    $statusDisplay = 'Aprobado';
                } elseif ($comment->isRejected()) {
                    $statusDisplay = 'Rechazado';
                }

                // Format the comment for display
                return [
                    'id' => $comment->id,
                    'user_name' => $comment->user->name ?? 'Usuario',
                    'user_role' => $comment->getRole() ?? 'Sin rol',
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at,
                    'status' => $statusDisplay,
                    'operation' => $comment->operacion ?? 'Detalle PO',
                    'attachment' => $attachment ? [
                        'name' => $attachment->file_name,
                        'url' => $attachment->getUrl(),
                        'type' => strtoupper($attachment->extension),
                    ] : null
                ];
            })->toArray();

            $this->comments = $processedComments;

            \Log::info('Total comments loaded', [
                'count' => count($this->comments)
            ]);

            // Sort by date (newest first)
            usort($this->comments, function($a, $b) {
                return $b['created_at'] <=> $a['created_at'];
            });
        } catch (\Exception $e) {
            \Log::error('Error loading comments and attachments', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->comments = [];
        }
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
            // Si hay una aprobación previa, permitir la subida directa del archivo
            if ($this->fileUploadApproved) {
                // Subir el archivo usando Spatie Media Library
                $media = $this->purchaseOrder
                    ->addMedia($this->newFile->getRealPath())
                    ->usingName(pathinfo($this->newFile->getClientOriginalName(), PATHINFO_FILENAME))
                    ->usingFileName($this->newFile->getClientOriginalName())
                    ->withCustomProperties([
                        'uploaded_by' => auth()->user()->name ?? 'Usuario',
                    ])
                    ->toMediaCollection('attachments');

                \Log::info('Archivo subido después de aprobación', [
                    'media_id' => $media->id,
                    'purchase_order_id' => $this->purchaseOrder->id,
                    'file_name' => $this->newFile->getClientOriginalName()
                ]);

                session()->flash('message', 'Archivo subido correctamente');

                // Limpiar el campo y la bandera de aprobación
                $this->newFile = null;
                $this->fileUploadApproved = false;

                // Limpiar la sesión
                Session::forget(['file_upload_approved', 'purchase_order_id', 'approved_file_data']);

                // Recargar archivos
                $this->loadCommentsAndAttachments();

                return;
            }

            // Verificar si hay una solicitud pendiente
            if ($this->authorizationService->isOperationPending('upload_file', $this->purchaseOrder)) {
                session()->flash('error', 'Ya existe una solicitud de autorización pendiente para subir un archivo');
                return;
            }

            // Crear una solicitud de autorización
            $authRequest = $this->authorizationService->createRequest(
                $this->purchaseOrder,
                'upload_file',
                [
                    'file_name' => $this->newFile->getClientOriginalName(),
                    'file_size' => $this->newFile->getSize(),
                    'uploaded_by' => auth()->user()->name ?? 'Usuario',
                ]
            );

            \Log::info('Solicitud de autorización para archivo creada', [
                'request_id' => $authRequest->id,
                'purchase_order_id' => $this->purchaseOrder->id,
                'file_name' => $this->newFile->getClientOriginalName()
            ]);

            session()->flash('message', 'Solicitud de autorización para subir archivo creada. Pendiente de aprobación.');

            // Limpiar el campo
            $this->newFile = null;

            // Recargar comentarios para mostrar la solicitud pendiente
            $this->loadCommentsAndAttachments();
        } catch (\Exception $e) {
            \Log::error('Error al subir archivo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'purchase_order_id' => $this->purchaseOrder->id ?? null
            ]);
            session()->flash('error', 'Error al subir archivo: ' . $e->getMessage());
        }
    }

    public function uploadFileAction() {
        $this->validate([
            'newFile' => 'required|file|max:10240', // 10MB max
        ]);

        $this->uploadFile();
    }

    public function setComments()
    {
        // If we're attaching a file to an approved comment, we only need the file
        if ($this->commentAttachmentApproved) {
            $this->validate([
                'attachment' => 'required|file|max:10240', // 10MB max
            ], [
                'attachment.required' => 'Por favor, seleccione un archivo para adjuntar al comentario aprobado.'
            ]);
        }
        // In normal mode, we need at least a comment or an attachment
        elseif (empty(trim($this->comment)) && !$this->attachment) {
            return;
        }

        try {
            // Log for diagnostics
            \Log::info('setComments called with values', [
                'commentAttachmentApproved' => $this->commentAttachmentApproved,
                'comment_id' => Session::get('comment_id'),
                'attachment' => $this->attachment ? true : false,
                'purchase_order_id' => Session::get('purchase_order_id'),
                'comment_text' => $this->comment
            ]);

            // If there's an attachment and a previous approval, allow direct upload
            if ($this->attachment && $this->commentAttachmentApproved) {
                // Find the approved comment by ID
                $commentId = Session::get('comment_id');
                if ($commentId) {
                    $commentModel = \App\Models\PurchaseOrderComment::find($commentId);

                    if ($commentModel) {
                        \Log::info('Found comment to attach file to', [
                            'comment_id' => $commentId,
                            'purchase_order_id' => $commentModel->purchase_order_id,
                            'attachment_name' => $this->attachment->getClientOriginalName()
                        ]);

                        try {
                            // Attach the file to the comment
                            $media = $commentModel
                                ->addMedia($this->attachment->getRealPath())
                                ->usingName(pathinfo($this->attachment->getClientOriginalName(), PATHINFO_FILENAME))
                                ->usingFileName($this->attachment->getClientOriginalName())
                                ->toMediaCollection('attachments');

                            \Log::info('File attached to comment', [
                                'media_id' => $media->id,
                                'comment_id' => $commentModel->id,
                                'file_name' => $this->attachment->getClientOriginalName()
                            ]);

                            session()->flash('message', 'Archivo adjuntado correctamente al comentario');

                            // Clean up fields and flags
                            $this->comment = '';
                            $this->attachment = null;
                            $this->commentAttachmentApproved = false;

                            // Clean up session
                            Session::forget(['comment_attachment_approved', 'comment_id', 'purchase_order_id']);

                            // Reload comments
                            $this->loadCommentsAndAttachments();

                            return;
                        } catch (\Exception $mediaException) {
                            \Log::error('Error attaching file to comment', [
                                'error' => $mediaException->getMessage(),
                                'trace' => $mediaException->getTraceAsString()
                            ]);

                            throw $mediaException;
                        }
                    } else {
                        \Log::error('Comment not found with ID ' . $commentId);
                    }

                    session()->flash('error', 'No se encontró el comentario aprobado para adjuntar el archivo');
                    return;
                }
            }

            // Crear el comentario sin estado
            $commentModel = new \App\Models\PurchaseOrderComment();
            $commentModel->purchase_order_id = $this->purchaseOrder->id;
            $commentModel->user_id = auth()->id();
            $commentModel->comment = $this->comment;
            $commentModel->operacion = 'Detalle PO';
            $commentModel->save();

            // Si hay un archivo adjunto, crear autorización
            if ($this->attachment) {
                // Crear solicitud de autorización
                $authRequest = $commentModel->createAuthorizationRequest(
                    'attach_file_to_comment',
                    [
                        'comment_id' => $commentModel->id,
                        'file_name' => $this->attachment->getClientOriginalName(),
                        'file_size' => $this->attachment->getSize(),
                        'uploaded_by' => auth()->user()->name ?? 'Usuario',
                    ]
                );
            }

            // Log for diagnostics
            \Log::info('Comment created', [
                'comment_id' => $commentModel->id,
                'purchase_order_id' => $this->purchaseOrder->id,
                'user_id' => auth()->id(),
                'status' => $commentModel->status
            ]);

            // If there's an attachment, create authorization request
            if ($this->attachment) {
                session()->flash('message', 'Comentario creado. Pendiente de aprobación para adjuntar archivo.');
            } else {
                // No attachment, just show success message
                session()->flash('message', 'Comentario agregado correctamente');
            }

            // Clean up fields
            $this->comment = '';
            $this->attachment = null;

            // Reload comments to show the new ones
            $this->loadCommentsAndAttachments();

        } catch (\Exception $e) {
            \Log::error("Error setting comments: " . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'purchase_order_id' => $this->purchaseOrder->id ?? null
            ]);
            session()->flash('error', 'Error al agregar el comentario: ' . $e->getMessage());
        }
    }

    /**
     * Método de diagnóstico para verificar los comentarios de la orden de compra
     */
    public function debugComments()
    {
        try {
            // Consulta directa a la base de datos
            $comments = DB::table('purchase_order_comments')
                ->where('purchase_order_id', $this->purchaseOrder->id)
                ->get();

            // Log para diagnóstico
            \Log::info('Debug comentarios - Consulta directa', [
                'purchase_order_id' => $this->purchaseOrder->id,
                'comment_count' => $comments->count(),
                'comments' => $comments->toArray()
            ]);

            // Usar relación del modelo
            $modelComments = $this->purchaseOrder->comments()->get();

            \Log::info('Debug comentarios - Relación del modelo', [
                'purchase_order_id' => $this->purchaseOrder->id,
                'comment_count' => $modelComments->count(),
                'comments' => $modelComments->toArray()
            ]);

            session()->flash('message', 'Verificación de comentarios completada. Revise los logs para más detalles.');
            $this->loadCommentsAndAttachments(); // Recargar después de la verificación

        } catch (\Exception $e) {
            \Log::error('Error en debugComments', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Error al verificar comentarios: ' . $e->getMessage());
        }
    }

    /**
     * Método específico para diagnosticar problemas con adjuntar archivos a comentarios aprobados
     */
    public function debugAttachments()
    {
        try {
            // 1. Verificar si hay un archivo seleccionado
            if ($this->attachment) {
                \Log::info('Archivo seleccionado para diagnóstico', [
                    'file_name' => $this->attachment->getClientOriginalName(),
                    'file_size' => $this->attachment->getSize(),
                    'mime_type' => $this->attachment->getMimeType()
                ]);
            } else {
                \Log::warning('No hay archivo seleccionado para diagnóstico');
            }

            // 2. Verificar si hay un ID de comentario en la sesión
            $commentId = Session::get('comment_id');
            if ($commentId) {
                \Log::info('ID de comentario encontrado en sesión', [
                    'comment_id' => $commentId
                ]);

                // 3. Intentar recuperar el comentario de la BD
                $comment = \App\Models\PurchaseOrderComment::find($commentId);
                if ($comment) {
                    \Log::info('Comentario encontrado en la BD', [
                        'comment' => $comment->toArray(),
                        'existing_media' => $comment->getMedia('attachments')->count()
                    ]);

                    // 4. Verificar si la tabla media tiene registros para este comentario
                    $mediaItems = DB::table('media')
                        ->where('model_type', 'App\\Models\\PurchaseOrderComment')
                        ->where('model_id', $commentId)
                        ->get();

                    \Log::info('Media asociados al comentario según tabla media', [
                        'count' => $mediaItems->count(),
                        'items' => $mediaItems->toArray()
                    ]);

                    // 5. Si hay archivo seleccionado, intentar adjuntarlo manualmente
                    if ($this->attachment) {
                        try {
                            // 5.1 Método normal
                            $media = $comment->addMedia($this->attachment->getRealPath())
                                ->usingName(pathinfo($this->attachment->getClientOriginalName(), PATHINFO_FILENAME))
                                ->usingFileName($this->attachment->getClientOriginalName())
                                ->toMediaCollection('attachments');

                            \Log::info('Archivo adjuntado manualmente durante diagnóstico', [
                                'media_id' => $media->id,
                                'collection' => 'attachments'
                            ]);

                            // Notificar éxito
                            session()->flash('message', 'Archivo adjuntado manualmente durante diagnóstico');
                            $this->loadCommentsAndAttachments();
                        } catch (\Exception $e) {
                            \Log::error('Error al adjuntar archivo manualmente durante diagnóstico', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                            session()->flash('error', 'Error al adjuntar archivo: ' . $e->getMessage());
                        }
                    }
                } else {
                    \Log::warning('El comentario con ID ' . $commentId . ' no existe en la BD');
                }
            } else {
                \Log::warning('No hay ID de comentario en la sesión');
            }

            // 6. Verificar variables de sesión relacionadas
            \Log::info('Variables de sesión actuales', [
                'comment_attachment_approved' => Session::has('comment_attachment_approved'),
                'comment_id' => Session::get('comment_id'),
                'purchase_order_id' => Session::get('purchase_order_id')
            ]);

            session()->flash('message', 'Diagnóstico de archivos adjuntos completado. Verifique los logs.');
        } catch (\Exception $e) {
            \Log::error('Error en debugAttachments', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error durante el diagnóstico: ' . $e->getMessage());
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
