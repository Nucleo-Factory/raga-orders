<?php

namespace App\Livewire\ShippingDocumentation;

use App\Models\KanbanBoard;
use App\Models\KanbanStatus;
use App\Models\ShippingDocument;
use App\Models\PurchaseOrder;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\ShippingDocumentComment;
use Livewire\WithFileUploads;
use App\Services\TrackingService;

class ShippingDocumentationKanban extends Component
{
    use WithFileUploads;

    public $boardId;
    public $board;
    public $columns = [];
    public $documents = [];
    public $documentsByColumn = [];
    public $currentDocumentId;
    public $newColumnId;
    public $currentDocument = null;
    public $comment;
    // Variables para el HUB
    public $actual_hub_id;
    // Añadir estas propiedades para los campos del formulario
    public $tracking_id;
    public $booking_code;
    public $container_number;
    public $mbl_number;
    public $hbl_number;
    public $release_date;
    public $instruction_date;
    public $comentario_documento;
    public $file = null;


    public function mount($boardId = null)
    {
        // If no board ID is provided, try to get the shipping documentation board
        if (!$boardId) {
            $companyId = auth()->user()->company_id ?? null;
            $this->board = KanbanBoard::where('company_id', $companyId)
                ->where('type', 'shipping_documentation')
                ->where('is_active', true)
                ->first();

            if ($this->board) {
                $this->boardId = $this->board->id;
            }
        } else {
            $this->boardId = $boardId;
            $this->board = KanbanBoard::findOrFail($boardId);
        }

        $this->loadData();
    }

    public function loadData()
    {
        $this->loadColumns();
        $this->loadDocuments();
        $this->organizeDocumentsByColumn();
    }

    protected function loadColumns()
    {
        if (!$this->board) {
            $this->columns = [];
            return;
        }

        $this->columns = $this->board->statuses()
            ->orderBy('position')
            ->get()
            ->map(function ($status) {
                return [
                    'id' => $status->id,
                    'name' => $status->name,
                    'color' => $status->color,
                    'position' => $status->position,
                ];
            })
            ->toArray();

        \Log::info("Available columns: " . json_encode(collect($this->columns)->pluck('name', 'id')));
    }

    protected function loadDocuments()
    {
        if (!$this->board) {
            $this->documents = [];
            return;
        }

        // Get shipping documents with their associated purchase orders
        $shippingDocs = ShippingDocument::with(['purchaseOrders', 'company'])
            ->get();

        $this->documents = $shippingDocs->map(function ($doc) {
            // Intentar extraer kanban_status_id de las notas
            $kanbanStatusId = null;

            if ($doc->notes && strpos($doc->notes, 'KANBAN_STATUS_ID:') !== false) {
                preg_match('/KANBAN_STATUS_ID:(\d+)/', $doc->notes, $matches);
                if (isset($matches[1])) {
                    $kanbanStatusId = $matches[1];
                    \Log::info("Extracted kanban_status_id from notes: $kanbanStatusId for document ID: {$doc->id}");
                }
            }

            // Si no se encontró en las notas, mapear según el estado
            if (!$kanbanStatusId) {
                // Map the status from shipping document to kanban status
                $kanbanStatus = null;

                switch ($doc->status) {
                    case 'draft':
                        $kanbanStatus = $this->board->statuses()->where('name', 'like', '%gestion documental%')
                            ->first();
                        break;
                    case 'pending':
                        $kanbanStatus = $this->board->statuses()->where('name', 'like', '%coordinación de salida%')
                            ->first();
                        break;
                    case 'approved':
                        $kanbanStatus = $this->board->statuses()->where('name', 'like', '%notificación de arribo%')
                            ->first();
                        break;
                    case 'in_transit':
                        $kanbanStatus = $this->board->statuses()->where('name', 'like', '%tránsito%')
                            ->first();
                        break;
                    case 'delivered':
                        $kanbanStatus = $this->board->statuses()->where('name', 'like', '%liberación%')
                            ->first();
                        break;
                    default:
                        $kanbanStatus = $this->board->defaultStatus();
                }

                $kanbanStatusId = $kanbanStatus ? $kanbanStatus->id : null;

                // Si no se encontró ningún estado de Kanban, usar el predeterminado
                if (!$kanbanStatusId) {
                    $defaultStatus = $this->board->defaultStatus();
                    $kanbanStatusId = $defaultStatus ? $defaultStatus->id : null;
                }
            }

            return [
                'id' => 'DOC-' . $doc->id,
                'document_id' => $doc->id,
                'document_number' => $doc->document_number,
                'po_count' => $doc->purchaseOrders->count(),
                'company' => $doc->company->name ?? 'N/A',
                'weight_kg' => $doc->total_weight_kg ?? 0,
                'creation_date' => $doc->creation_date ? $doc->creation_date->format('d/m/Y') : 'N/A',
                'estimated_departure_date' => $doc->estimated_departure_date ? $doc->estimated_departure_date->format('d/m/Y') : 'N/A',
                'estimated_arrival_date' => $doc->estimated_arrival_date ? $doc->estimated_arrival_date->format('d/m/Y') : 'N/A',
                'hub_location' => $doc->hub_location ?? 'N/A',
                'status' => $doc->status,
                'kanban_status_id' => $kanbanStatusId,
            ];
        })->toArray();
    }

    protected function organizeDocumentsByColumn()
    {
        \Log::info("organizeDocumentsByColumn: Starting method");
        $this->documentsByColumn = [];

        // Initialize empty arrays for each column
        foreach ($this->columns as $column) {
            $this->documentsByColumn[$column['id']] = [];
        }

        // Organize documents by column
        foreach ($this->documents as $document) {
            $statusId = $document['kanban_status_id'];
            \Log::info("organizeDocumentsByColumn: Document {$document['id']} has kanban_status_id: $statusId");

            // If the document has a valid status ID and the status exists in our columns
            if ($statusId && isset($this->documentsByColumn[$statusId])) {
                $this->documentsByColumn[$statusId][] = $document;
                \Log::info("organizeDocumentsByColumn: Added document to column $statusId");
            } else {
                // If the document doesn't have a valid status, put it in the first column
                if (!empty($this->columns)) {
                    $firstColumnId = $this->columns[0]['id'];
                    $this->documentsByColumn[$firstColumnId][] = $document;
                    \Log::info("organizeDocumentsByColumn: Document has invalid status, added to first column ($firstColumnId)");
                }
            }
        }

        // Log column counts for debugging
        foreach ($this->columns as $column) {
            $count = count($this->documentsByColumn[$column['id']]);
            \Log::info("organizeDocumentsByColumn: Column {$column['id']} ({$column['name']}) has $count documents");
        }
    }

    public function moveDocument($documentId, $newColumnId)
    {
        // Log detallado
        \Log::info("Moving document $documentId to column $newColumnId");

        // Validar los datos de entrada
        if (empty($documentId) || empty($newColumnId)) {
            \Log::error("Invalid parameters: documentId=$documentId, newColumnId=$newColumnId");
            return;
        }

        // Extract the document ID from the document ID string
        $shippingDocId = str_replace('DOC-', '', $documentId);

        // Find the shipping document
        $shippingDoc = ShippingDocument::find($shippingDocId);

        if (!$shippingDoc) {
            \Log::error("Shipping document not found: $shippingDocId");
            return;
        }

        // Find the kanban status
        $kanbanStatus = KanbanStatus::find($newColumnId);

        if (!$kanbanStatus) {
            \Log::error("Kanban status not found: $newColumnId");
            return;
        }

        // Log the actual status name for debugging
        \Log::info("Kanban status name: " . $kanbanStatus->name);

        // Map the kanban status back to a shipping document status
        $newStatus = 'draft'; // Default
        $statusName = strtolower($kanbanStatus->name);

        if (str_contains($statusName, 'gestion documental')) {
            $newStatus = 'draft';
        } elseif (str_contains($statusName, 'coordinación de salida') || str_contains($statusName, 'coordinacion de salida') || str_contains($statusName, 'zarpe')) {
            $newStatus = 'pending';
        } elseif (str_contains($statusName, 'en tránsito') || str_contains($statusName, 'en transito') || str_contains($statusName, 'seguimiento')) {
            $newStatus = 'in_transit';
        } elseif (str_contains($statusName, 'entrega') || str_contains($statusName, 'liberación') || str_contains($statusName, 'liberacion') || str_contains($statusName, 'facturación')) {
            $newStatus = 'delivered';
        } elseif (str_contains($statusName, 'notificación de arribo') || str_contains($statusName, 'notificacion de arribo')) {
            $newStatus = 'approved';
        } elseif (str_contains($statusName, 'digitaciones')) {
            $newStatus = 'approved'; // O podrías usar otro estado apropiado
        } elseif (str_contains($statusName, 'transito interno destino')) {
            $newStatus = 'in_transit';
        } elseif (str_contains($statusName, 'archivado')) {
            $newStatus = 'delivered';
        }

        // Log para verificar el mapeo
        \Log::info("Mapping status from '$statusName' to '$newStatus'");

        // Guardar la información del kanban en el campo notes
        try {
            $shippingDoc->status = $newStatus;

            // Almacenar el kanban_status_id en las notas
            $notes = $shippingDoc->notes ?? '';
            $kanbanInfo = "KANBAN_STATUS_ID:" . $newColumnId;

            // Eliminar cualquier información anterior del kanban
            if (strpos($notes, 'KANBAN_STATUS_ID:') !== false) {
                $notes = preg_replace('/KANBAN_STATUS_ID:\d+/', $kanbanInfo, $notes);
            } else {
                // Añadir al principio o al final de las notas
                $notes = $notes ? ($notes . "\n" . $kanbanInfo) : $kanbanInfo;
            }

            $shippingDoc->notes = $notes;
            $result = $shippingDoc->save();

            \Log::info("Document updated with new status and kanban info in notes. Result: " . ($result ? 'success' : 'failed'));
        } catch (\Exception $e) {
            \Log::error("Error updating document: " . $e->getMessage());
        }

        // Actualizar en memoria para esta sesión
        foreach ($this->documents as &$document) {
            if ($document['id'] === 'DOC-' . $shippingDocId) {
                $document['kanban_status_id'] = $newColumnId;
                $document['status'] = $newStatus;
                \Log::info("Updated document in memory: ID={$document['id']}, kanban_status_id=$newColumnId");
                break;
            }
        }

        // Reorganizar los documentos por columna
        $this->organizeDocumentsByColumn();
    }

    public function setCurrentDocument($documentId, $newColumnId)
    {
        $this->currentDocumentId = $documentId;
        $this->newColumnId = $newColumnId;

        // Find the current document from the loaded documents
        foreach ($this->documents as $document) {
            if ($document['id'] == $documentId) {
                $this->currentDocument = $document;
                break;
            }
        }
    }

    public function setComments($documentId, $comment)
    {
        \Log::info("Setting comments for document $documentId: " . $comment);

        if (empty($comment)) {
            \Log::info("Empty comment, not saving anything");
            return;
        }

        try {
            // Extraer el ID del documento
            $shippingDocId = str_replace('DOC-', '', $documentId);

            // Buscar el documento
            $doc = ShippingDocument::find($shippingDocId);

            if (!$doc) {
                \Log::error("Document not found for comment: $shippingDocId");
                return;
            }

            // Usar el modelo ShippingDocumentComment
            $commentModel = new ShippingDocumentComment();
            $commentModel->shipping_document_id = $shippingDocId;
            $commentModel->user_id = auth()->id();
            $commentModel->comment = $comment;
            $commentModel->save();

            \Log::info("Comment saved with ID: " . $commentModel->id);

            // También podemos actualizar el campo notes del documento si existe
            if (Schema::hasColumn('shipping_documents', 'notes')) {
                $doc->notes = ($doc->notes ? $doc->notes . "\n" : '') . $comment;
                $doc->save();
                \Log::info("Comment also saved to shipping document notes field");
            }

            // Reset the comment in the component after saving
            $this->comment = '';

        } catch (\Exception $e) {
            \Log::error("Error saving comment: " . $e->getMessage());
        }
    }

    public function addDataToDocument($documentId) {
        \Log::info("addDataToDocument: Processing document $documentId");

        // Extract the document ID from the document ID string
        $shippingDocId = str_replace('DOC-', '', $documentId);

        // Find the shipping document
        $shippingDoc = ShippingDocument::find($shippingDocId);

        if (!$shippingDoc) {
            \Log::error("Shipping document not found: $shippingDocId");
            return;
        }

        try {
            // Update shipping document fields based on column ID
            $this->updateDocumentFields($shippingDoc);

            // Save the document
            $shippingDoc->save();

            // Process file upload if a file exists
            if ($this->file) {
                // Add file to media library
                $media = $shippingDoc->addMedia($this->file->getRealPath())
                    ->usingName($this->file->getClientOriginalName())
                    ->withCustomProperties([
                        'stage' => 'attachment',
                        'comment' => $this->comment ?? null,
                        'uploaded_by' => auth()->id() ?: 'system'
                    ])
                    ->toMediaCollection('shipping_documents');

                \Log::info("File uploaded with ID: " . $media->id);

                // Reset the file upload field
                $this->file = null;
            }

        } catch (\Exception $e) {
            \Log::error("Error adding data to document: " . $e->getMessage());
        }
    }

    // Helper method to update document fields based on column
    private function updateDocumentFields($shippingDoc)
    {
        // Check which column we're updating for and apply specific field updates
        if ($this->newColumnId == $this->columns[0]['id'] && $this->release_date) {
            $shippingDoc->release_date = $this->release_date;
        } elseif ($this->newColumnId == $this->columns[1]['id']) {
            // Update fields for column 2
            if ($this->tracking_id) {
                $shippingDoc->tracking_id = $this->tracking_id;
            }
            if ($this->booking_code) {
                $shippingDoc->booking_code = $this->booking_code;
            }
            if ($this->container_number) {
                $shippingDoc->container_number = $this->container_number;
            }
            if ($this->mbl_number) {
                $shippingDoc->mbl_number = $this->mbl_number;
            }
        } elseif ($this->newColumnId == 14 && $this->instruction_date) { // Column "Digitaciones" (ID 14)
            $shippingDoc->instruction_date = $this->instruction_date;
        }

        return $shippingDoc;
    }

    // First, add a method that handles everything in one go
    public function saveAndMoveDocument()
    {
        \Log::info("saveAndMoveDocument: Starting with documentId={$this->currentDocumentId}, newColumnId={$this->newColumnId}");

        try {
            // Extract the document ID from the document ID string
            $shippingDocId = str_replace('DOC-', '', $this->currentDocumentId);

            // Find the shipping document
            $shippingDoc = ShippingDocument::find($shippingDocId);

            if (!$shippingDoc) {
                \Log::error("Shipping document not found: $shippingDocId");
                return;
            }

            // Validar el tracking cuando estamos moviendo a la columna 2 y hay datos ingresados
            if ($this->newColumnId == $this->columns[1]['id'] &&
                (!empty($this->tracking_id) || !empty($this->booking_code) || !empty($this->container_number) || !empty($this->mbl_number))) {

                $trackingValid = $this->validateTracking();

                \Log::info("Tracking validation result: " . ($trackingValid ? 'success' : 'failed'));

                if($trackingValid) {
                    $shippingDoc->tracking_id = $this->tracking_id;
                    $shippingDoc->booking_code = $this->booking_code;
                    $shippingDoc->container_number = $this->container_number;
                    $shippingDoc->mbl_number = $this->mbl_number;
                    $shippingDoc->hbl_number = $this->hbl_number;
                    $shippingDoc->save();
                }

                if (!$trackingValid) {
                    \Log::error("Tracking validation failed: ID not found in tracking systems");
                    return;
                }
            }

            if(!empty($this->instruction_date)) {
                $shippingDoc->instruction_date = $this->instruction_date;
                $shippingDoc->save();
            }

            // 1. Save comment if exists
            if (!empty($this->comment)) {
                // Usar el modelo ShippingDocumentComment
                $commentModel = new ShippingDocumentComment();
                $commentModel->shipping_document_id = $shippingDocId;
                $commentModel->user_id = auth()->id();
                $commentModel->comment = $this->comment;
                $commentModel->save();

                \Log::info("Comment saved with ID: " . $commentModel->id);

                // También podemos actualizar el campo notes del documento si existe
                if (Schema::hasColumn('shipping_documents', 'notes')) {
                    $oldNotes = $shippingDoc->notes ?? '';
                    $shippingDoc->notes = ($oldNotes ? $oldNotes . "\n" : '') . $this->comment;
                    \Log::info("Comment also added to shipping document notes field");
                }
            }

            // 2. Update document fields
            $this->updateDocumentFields($shippingDoc);

            // 3. Process file upload if a file exists
            if ($this->file) {
                // Add file to media library
                $media = $shippingDoc->addMedia($this->file->getRealPath())
                    ->usingName($this->file->getClientOriginalName())
                    ->withCustomProperties([
                        'stage' => 'attachment',
                        'comment' => $this->comment ?? null,
                        'uploaded_by' => auth()->id() ?: 'system'
                    ])
                    ->toMediaCollection('shipping_documents');

                \Log::info("File uploaded with ID: " . $media->id);
            }

            // 4. Move document to new column (this includes saving the document)
            $this->moveDocument($this->currentDocumentId, $this->newColumnId);

        } catch (\Exception $e) {
            \Log::error("Error in saveAndMoveDocument: " . $e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Error al actualizar el documento: ' . $e->getMessage()
            ]);
        }

        // Reset all form fields
        $this->resetFormFields();
    }

    /**
     * Valida que el tracking_id, booking_code o container_number sea válido en la API de Porth
     * @return bool
     */
    private function validateTracking()
    {
        try {
            // Determinar qué ID vamos a validar, en orden de prioridad
            $trackingToValidate = $this->tracking_id ?: ($this->booking_code ?: $this->container_number);

            if (empty($trackingToValidate)) {
                return true; // Si no hay nada que validar, consideramos que es válido
            }

            // Usar el servicio de tracking para validar
            $trackingService = new TrackingService();

            // Primero intentamos con Porth porque es para shipping documents
            $porthResult = $trackingService->getPorthTracking($trackingToValidate);

            if ($porthResult) {
                \Log::info("Tracking validation successful with Porth API");
                return true;
            }

        } catch (\Exception $e) {
            \Log::error("Error validating tracking:", [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    // Add this new method to reset all form fields
    private function resetFormFields()
    {
        $this->currentDocumentId = null;
        $this->newColumnId = null;
        $this->currentDocument = null;
        $this->comment = '';
        $this->tracking_id = null;
        $this->booking_code = null;
        $this->container_number = null;
        $this->mbl_number = null;
        $this->release_date = null;
        $this->instruction_date = null;
        $this->comentario_documento = null;
        $this->file = null;
    }

    public function render()
    {
        return view('livewire.shipping-documentation.shipping-documentation-kanban');
    }
}
