<?php

namespace App\Livewire\Tables;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class CustomPurchaseOrdersTable extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $statusFilter = '';
    public $consolidableFilter = '';
    public $selected = [];
    public $selectAll = false;
    public $currentPageOrders = null;
    public $release_date = '';
    public $comment_release = '';
    public $file = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'statusFilter' => ['except' => ''],
        'consolidableFilter' => ['except' => ''],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingConsolidableFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        $currentPageIds = $this->getCurrentPageOrders()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        
        if ($value) {
            // Add current page IDs to selected (if not already selected)
            $this->selected = array_unique(array_merge($this->selected, $currentPageIds));
        } else {
            // Remove current page IDs from selected
            $this->selected = array_diff($this->selected, $currentPageIds);
        }
    }

    public function updatedPage()
    {
        $this->currentPageOrders = null; // Reset cache
        $this->updateSelectAllState();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'selected') {
            $this->updateSelectAllState();
        }
    }

    public function updateSelectAllState()
    {
        $currentPageIds = $this->getCurrentPageOrders()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $selectedCurrentPageIds = array_intersect($this->selected, $currentPageIds);
        
        $this->selectAll = count($selectedCurrentPageIds) === count($currentPageIds) && count($currentPageIds) > 0;
    }

    public function getCurrentPageOrders()
    {
        if ($this->currentPageOrders === null) {
            $this->currentPageOrders = $this->getPurchaseOrdersQuery()->paginate($this->perPage)->getCollection();
        }
        return $this->currentPageOrders;
    }

    public function getHasSelectedOrdersProperty()
    {
        return count($this->selected) > 0;
    }

    public function createShippingDocument()
    {
        if (empty($this->release_date)) {
            session()->flash('error', 'La fecha de release es obligatoria.');
            return;
        }

        if (count($this->selected) === 0) {
            session()->flash('error', 'No hay órdenes seleccionadas para crear el documento de embarque.');
            return;
        }

        // Get the selected purchase orders
        $selectedOrders = PurchaseOrder::whereIn('id', $this->selected)->get();

        \Log::info('Iniciando creación de documento de embarque', [
            'selected_count' => count($this->selected),
            'selected_ids' => $this->selected,
            'orders_found' => $selectedOrders->count()
        ]);

        // Check if any of the selected orders are already in a shipping document
        $alreadyConsolidated = [];
        foreach ($selectedOrders as $order) {
            $existingConsolidations = \DB::table('purchase_order_shipping_document')
                ->where('purchase_order_id', $order->id)
                ->count();
            
            if ($existingConsolidations > 0) {
                $alreadyConsolidated[] = $order->order_number;
            }
        }

        if (!empty($alreadyConsolidated)) {
            \Log::warning('Órdenes ya consolidadas detectadas', ['orders' => $alreadyConsolidated]);
            session()->flash('error', 'Las siguientes órdenes ya están consolidadas: ' . implode(', ', $alreadyConsolidated));
            return;
        }

        // Check if all selected orders can be consolidated together
        if (!PurchaseOrder::canBeConsolidatedTogether($selectedOrders)) {
            // If not, check which orders are not consolidable individually
            $nonConsolidableOrders = $selectedOrders->filter(function($order) {
                return !$order->isConsolidable();
            });

            if ($nonConsolidableOrders->count() > 0) {
                $orderNumbers = $nonConsolidableOrders->pluck('order_number')->join(', ');
                session()->flash('error', "Las siguientes órdenes no son consolidables individualmente: {$orderNumbers}");
            } else {
                // If all orders are consolidable individually, then the total weight is outside the range
                $totalWeight = $selectedOrders->sum('weight_kg');
                session()->flash('error', "El peso total de las órdenes seleccionadas ({$totalWeight} kg) está fuera del rango permitido para consolidación (5001-15000 kg).");
            }
            return;
        }

        // Start a database transaction
        \DB::beginTransaction();

        try {
            // Create a new shipping document
            $shippingDocument = new \App\Models\ShippingDocument();
            $shippingDocument->company_id = $selectedOrders->first()->company_id;
            $shippingDocument->document_number = 'DOC-' . date('YmdHis') . '-' . rand(1000, 9999);
            $shippingDocument->status = 'draft';
            $shippingDocument->creation_date = now();

            // Set the release date from the modal input
            $shippingDocument->release_date = $this->release_date;

            // Set estimated dates based on the first order's dates
            $firstOrder = $selectedOrders->first();
            if ($firstOrder->date_required_in_destination) {
                $shippingDocument->estimated_arrival_date = $firstOrder->date_required_in_destination;
                // Set estimated departure date 7 days before estimated arrival
                $shippingDocument->estimated_departure_date = $firstOrder->date_required_in_destination->copy()->subDays(7);
            }

            // Set hub location if available
            if ($firstOrder->ship_to_nombre) {
                $shippingDocument->hub_location = $firstOrder->ship_to_nombre;
            }

            // Calculate total weight
            $totalWeight = $selectedOrders->sum('weight_kg');
            $shippingDocument->total_weight_kg = $totalWeight;

            // Save the shipping document
            $shippingDocument->save();

            // Guardar el comentario en la tabla shipping_document_comments si existe
            if (!empty($this->comment_release)) {
                // Asumiendo que tienes un modelo ShippingDocumentComment
                $comment = new \App\Models\ShippingDocumentComment();
                $comment->shipping_document_id = $shippingDocument->id;
                $comment->user_id = auth()->id() ?: 1;
                $comment->comment = $this->comment_release;
                $comment->save();
            }

            // Guardar el archivo adjunto si existe
            if ($this->file) {
                // Usar Media Library en lugar del enfoque anterior
                $shippingDocument->addMedia($this->file->getRealPath())
                    ->usingName($this->file->getClientOriginalName())
                    ->withCustomProperties([
                        'stage' => 'creation',
                        'comment' => $this->comment_release ?: null,
                        'uploaded_by' => auth()->id() ?: 'system'
                    ])
                    ->toMediaCollection('shipping_documents');
            }

            // Associate purchase orders with the shipping document
            \Log::info('Iniciando asociación de órdenes', [
                'shipping_document_id' => $shippingDocument->id,
                'orders_to_associate' => $selectedOrders->pluck('id')->toArray()
            ]);

            foreach ($selectedOrders as $order) {
                \Log::info('Procesando orden para attach', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'shipping_document_id' => $shippingDocument->id
                ]);

                // Check if this relationship already exists (protection against duplicates)
                $existingRelation = \DB::table('purchase_order_shipping_document')
                    ->where('purchase_order_id', $order->id)
                    ->where('shipping_document_id', $shippingDocument->id)
                    ->exists();

                if ($existingRelation) {
                    \Log::warning('Relación ya existe, saltando attach', [
                        'order_id' => $order->id,
                        'shipping_document_id' => $shippingDocument->id
                    ]);
                    continue;
                }

                // Update the order status to 'shipped'
                $order->status = 'shipped';
                $order->save();

                // Associate the order with the shipping document
                $shippingDocument->purchaseOrders()->attach($order->id, [
                    'notes' => 'Agregado automáticamente al crear el documento de embarque'
                ]);

                \Log::info('Attach exitoso', [
                    'order_id' => $order->id,
                    'shipping_document_id' => $shippingDocument->id
                ]);
            }

            // Commit the transaction
            \DB::commit();

            // Show success message
            session()->flash('message', 'Documento de embarque ' . $shippingDocument->document_number . ' creado exitosamente con ' . count($this->selected) . ' órdenes y un peso total de ' . number_format($totalWeight, 0) . ' kg.');

            // Reset selection after creating document
            $this->selected = [];
            $this->selectAll = false;

            // Reset the release date and other fields after successful creation
            $this->release_date = '';
            $this->comment_release = '';
            $this->file = null;

        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            \DB::rollBack();

            \Log::error('Error al crear documento de embarque', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'selected_orders' => $this->selected,
                'release_date' => $this->release_date
            ]);

            // Show error message
            session()->flash('error', 'Error al crear el documento de embarque: ' . $e->getMessage());
        }
    }

    public function openReleaseModal()
    {
        if (count($this->selected) === 0) {
            session()->flash('error', 'No hay órdenes seleccionadas para crear el documento de embarque.');
            return;
        }

        // Get the selected purchase orders
        $selectedOrders = PurchaseOrder::whereIn('id', $this->selected)->get();

        // Check if all selected orders can be consolidated together
        if (!PurchaseOrder::canBeConsolidatedTogether($selectedOrders)) {
            // If not, check which orders are not consolidable individually
            $nonConsolidableOrders = $selectedOrders->filter(function($order) {
                return !$order->isConsolidable();
            });

            if ($nonConsolidableOrders->count() > 0) {
                $orderNumbers = $nonConsolidableOrders->pluck('order_number')->join(', ');
                session()->flash('error', "Las siguientes órdenes no son consolidables individualmente: {$orderNumbers}");
            } else {
                // If all orders are consolidable individually, then the total weight is outside the range
                $totalWeight = $selectedOrders->sum('weight_kg');
                session()->flash('error', "El peso total de las órdenes seleccionadas ({$totalWeight} kg) está fuera del rango permitido para consolidación (5001-15000 kg).");
            }
            return;
        }

        // Open the modal
        $this->dispatch('open-modal', 'modal-hub-teorico');
    }

    public function addReleaseDate()
    {
        // Validate release date
        $this->validate([
            'release_date' => 'required|date',
            'file' => 'nullable|file|max:5120|mimes:xls,xlsx,pdf', // Validar archivo: máx 5MB, formatos permitidos
        ], [
            'release_date.required' => 'La fecha de release es obligatoria.',
            'release_date.date' => 'La fecha de release debe ser una fecha válida.',
            'file.file' => 'El archivo adjunto debe ser un archivo válido.',
            'file.max' => 'El archivo adjunto no debe exceder 5MB.',
            'file.mimes' => 'El archivo adjunto debe ser de tipo: xls, xlsx, pdf.',
        ]);

        // Close the modal
        $this->dispatch('close-modal', 'modal-hub-teorico');

        // Proceed with shipping document creation
        $this->createShippingDocument();

        // Show success message
        $this->dispatch('open-modal', 'modal-consolidate-order');
    }

    protected function getPurchaseOrdersQuery()
    {
        return PurchaseOrder::query()
            ->with('shippingDocuments') // Eager load shipping documents for consolidation info
            ->when($this->search, function ($query) {
                $searchTerm = strtolower($this->search);
                $query->where(function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(order_number) LIKE ?', ['%' . $searchTerm . '%'])
                        ->orWhereRaw('LOWER(CAST(vendor_id AS TEXT)) LIKE ?', ['%' . $searchTerm . '%'])
                        ->orWhereRaw('LOWER(notes) LIKE ?', ['%' . $searchTerm . '%']);
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->consolidableFilter, function ($query) {
                if ($this->consolidableFilter === 'yes') {
                    $query->where('weight_kg', '>', 5000)->where('weight_kg', '<=', 15000);
                } elseif ($this->consolidableFilter === 'no') {
                    $query->where(function ($query) {
                        $query->where('weight_kg', '<=', 5000)
                            ->orWhere('weight_kg', '>', 15000);
                    });
                }
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function getRagaColorPalette()
    {
        return [
            '#FFE6E6', // Light pink
            '#E6F3FF', // Light blue  
            '#E6FFE6', // Light green
            '#FFF0E6', // Light orange
            '#F0E6FF', // Light purple
            '#FFFFE6', // Light yellow
            '#E6FFFF', // Light cyan
            '#FFE6F0', // Light rose
        ];
    }

    public function getConsolidationColorMap($purchaseOrders)
    {
        $colorMap = [];
        $colors = $this->getRagaColorPalette();
        $colorIndex = 0;
        
        foreach ($purchaseOrders as $order) {
            if ($order->shippingDocuments->isNotEmpty()) {
                $shippingDocId = $order->shippingDocuments->first()->id;
                
                if (!isset($colorMap[$shippingDocId])) {
                    $colorMap[$shippingDocId] = $colors[$colorIndex % count($colors)];
                    $colorIndex++;
                }
            }
        }
        
        return $colorMap;
    }

    public function render()
    {
        $purchaseOrders = $this->getPurchaseOrdersQuery()->paginate($this->perPage);
        
        // Update the select all state on each render
        $this->updateSelectAllState();
        
        // Get consolidation color mapping
        $consolidationColorMap = $this->getConsolidationColorMap($purchaseOrders->getCollection());

        return view('livewire.tables.custom-purchase-orders-table', [
            'purchaseOrders' => $purchaseOrders,
            'consolidationColorMap' => $consolidationColorMap
        ]);
    }
}
