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

        // ALTERNATIVA: Buscar asociaciones en la tabla pivot si existe
        $kanbanAssociations = [];
        try {
            if (Schema::hasTable('kanban_shipping_document')) {
                $associations = DB::table('kanban_shipping_document')->get();
                foreach ($associations as $assoc) {
                    $kanbanAssociations[$assoc->shipping_document_id] = $assoc->kanban_status_id;
                }
            }
        } catch (\Exception $e) {
            \Log::error("Error fetching from pivot table: " . $e->getMessage());
        }

        $this->documents = $shippingDocs->map(function ($doc) use ($kanbanAssociations) {
            // Intentar obtener el kanban_status_id de la tabla pivot
            $kanbanStatusId = $kanbanAssociations[$doc->id] ?? null;

            // Si no existe en la tabla pivot, mapearlo según el estado actual
            if (!$kanbanStatusId) {
                // Map the status from shipping document to kanban status
                switch ($doc->status) {
                    case 'draft':
                        $kanbanStatus = $this->board->statuses()->where('name', 'like', '%borrador%')
                            ->orWhere('name', 'like', '%draft%')
                            ->orWhere('name', 'like', '%gestion documental%')
                            ->first();
                        break;
                    case 'pending':
                        $kanbanStatus = $this->board->statuses()->where('name', 'like', '%pendiente%')
                            ->orWhere('name', 'like', '%pending%')
                            ->orWhere('name', 'like', '%coordinación de salida%')
                            ->orWhere('name', 'like', '%zarpe%')
                            ->first();
                        break;
                    case 'approved':
                        $kanbanStatus = $this->board->statuses()->where('name', 'like', '%aprobado%')
                            ->orWhere('name', 'like', '%approved%')
                            ->orWhere('name', 'like', '%notificación de arribo%')
                            ->first();
                        break;
                    case 'in_transit':
                        $kanbanStatus = $this->board->statuses()->where('name', 'like', '%tránsito%')
                            ->orWhere('name', 'like', '%transit%')
                            ->orWhere('name', 'like', '%seguimiento%')
                            ->first();
                        break;
                    case 'delivered':
                        $kanbanStatus = $this->board->statuses()->where('name', 'like', '%entregado%')
                            ->orWhere('name', 'like', '%delivered%')
                            ->orWhere('name', 'like', '%liberación%')
                            ->orWhere('name', 'like', '%entrega%')
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

                // Guardar la asociación en la tabla pivot si existe
                if ($kanbanStatusId) {
                    try {
                        if (Schema::hasTable('kanban_shipping_document')) {
                            DB::table('kanban_shipping_document')
                                ->updateOrInsert(
                                    ['shipping_document_id' => $doc->id],
                                    [
                                        'kanban_status_id' => $kanbanStatusId,
                                        'updated_at' => now(),
                                        'created_at' => now()
                                    ]
                                );
                        }
                    } catch (\Exception $e) {
                        \Log::error("Error saving to pivot table: " . $e->getMessage());
                    }
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
        $this->documentsByColumn = [];

        // Initialize empty arrays for each column
        foreach ($this->columns as $column) {
            $this->documentsByColumn[$column['id']] = [];
        }

        // Organize documents by column
        foreach ($this->documents as $document) {
            $statusId = $document['kanban_status_id'];

            // If the document has a valid status ID and the status exists in our columns
            if ($statusId && isset($this->documentsByColumn[$statusId])) {
                $this->documentsByColumn[$statusId][] = $document;
            } else {
                // If the document doesn't have a valid status, put it in the first column
                if (!empty($this->columns)) {
                    $firstColumnId = $this->columns[0]['id'];
                    $this->documentsByColumn[$firstColumnId][] = $document;
                }
            }
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

        // Map based on the kanban status name - utilizando nombres exactos de tus columnas
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
        }

        // Log para verificar el mapeo
        \Log::info("Mapping status from '$statusName' to '$newStatus'");

        // Update the shipping document status (solo actualizamos el status, no el kanban_status_id)
        $oldStatus = $shippingDoc->status;
        $shippingDoc->status = $newStatus;
        $result = $shippingDoc->save();

        \Log::info("Document status updated from $oldStatus to $newStatus. Result: " . ($result ? 'success' : 'failed'));

        // ALTERNATIVA: Almacenar la relación en una tabla pivot si existe
        try {
            // Si existe una tabla pivot (similar a como funciona KanbanBoard)
            if (Schema::hasTable('kanban_shipping_document')) {
                // Eliminar cualquier entrada existente
                DB::table('kanban_shipping_document')
                    ->where('shipping_document_id', $shippingDocId)
                    ->delete();

                // Crear nueva entrada
                DB::table('kanban_shipping_document')->insert([
                    'shipping_document_id' => $shippingDocId,
                    'kanban_status_id' => $newColumnId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                \Log::info("Document association with kanban status stored in pivot table");
            }
        } catch (\Exception $e) {
            \Log::error("Error updating pivot table: " . $e->getMessage());
        }

        // Temporal: Guardar la asociación en memoria para esta sesión
        foreach ($this->documents as &$document) {
            if ($document['id'] == 'DOC-' . $shippingDocId) {
                $document['kanban_status_id'] = $newColumnId;
                break;
            }
        }

        // Reload the data
        $this->loadData();

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
