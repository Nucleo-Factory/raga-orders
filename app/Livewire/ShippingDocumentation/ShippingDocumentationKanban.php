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

class ShippingDocumentationKanban extends Component
{
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
    public $release_date;
    public $instruction_date;

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

        // Reset current document data
        $this->currentDocumentId = null;
        $this->newColumnId = null;
        $this->currentDocument = null;
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
        } catch (\Exception $e) {
            \Log::error("Error saving comment: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.shipping-documentation.shipping-documentation-kanban');
    }
}
