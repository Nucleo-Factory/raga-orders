<?php

namespace App\Livewire\ShippingDocumentation;

use App\Models\KanbanBoard;
use App\Models\KanbanStatus;
use App\Models\ShippingDocument;
use App\Models\PurchaseOrder;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ShippingDocumentationKanban extends Component
{
    public $boardId;
    public $board;
    public $columns = [];
    public $documents = [];
    public $documentsByColumn = [];

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
            // Get the kanban status ID from the shipping document or use the default
            $kanbanStatusId = null;

            // Map the status from shipping document to kanban status
            switch ($doc->status) {
                case 'draft':
                    $kanbanStatus = $this->board->statuses()->where('name', 'like', '%borrador%')->orWhere('name', 'like', '%draft%')->first();
                    break;
                case 'pending':
                    $kanbanStatus = $this->board->statuses()->where('name', 'like', '%pendiente%')->orWhere('name', 'like', '%pending%')->first();
                    break;
                case 'approved':
                    $kanbanStatus = $this->board->statuses()->where('name', 'like', '%aprobado%')->orWhere('name', 'like', '%approved%')->first();
                    break;
                case 'in_transit':
                    $kanbanStatus = $this->board->statuses()->where('name', 'like', '%tránsito%')->orWhere('name', 'like', '%transit%')->first();
                    break;
                case 'delivered':
                    $kanbanStatus = $this->board->statuses()->where('name', 'like', '%entregado%')->orWhere('name', 'like', '%delivered%')->first();
                    break;
                default:
                    $kanbanStatus = $this->board->defaultStatus();
            }

            $kanbanStatusId = $kanbanStatus ? $kanbanStatus->id : null;

            // If no kanban status was found, use the default
            if (!$kanbanStatusId) {
                $defaultStatus = $this->board->defaultStatus();
                $kanbanStatusId = $defaultStatus ? $defaultStatus->id : null;
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
        // Extract the document ID from the document ID string
        $shippingDocId = str_replace('DOC-', '', $documentId);

        // Find the shipping document
        $shippingDoc = ShippingDocument::find($shippingDocId);

        if ($shippingDoc) {
            // Find the kanban status
            $kanbanStatus = KanbanStatus::find($newColumnId);

            if ($kanbanStatus) {
                // Map the kanban status back to a shipping document status
                $newStatus = 'draft'; // Default

                // Map based on the kanban status name
                $statusName = strtolower($kanbanStatus->name);
                if (str_contains($statusName, 'pendiente') || str_contains($statusName, 'pending')) {
                    $newStatus = 'pending';
                } elseif (str_contains($statusName, 'aprobado') || str_contains($statusName, 'approved')) {
                    $newStatus = 'approved';
                } elseif (str_contains($statusName, 'tránsito') || str_contains($statusName, 'transit')) {
                    $newStatus = 'in_transit';
                } elseif (str_contains($statusName, 'entregado') || str_contains($statusName, 'delivered')) {
                    $newStatus = 'delivered';
                }

                // Update the shipping document status
                $shippingDoc->status = $newStatus;
                $shippingDoc->save();

                // Reload the data
                $this->loadData();
            }
        }
    }

    public function render()
    {
        return view('livewire.shipping-documentation.shipping-documentation-kanban');
    }
}
