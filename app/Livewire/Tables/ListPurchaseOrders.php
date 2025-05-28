<?php

namespace App\Livewire\Tables;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;

class ListPurchaseOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $statusFilter = '';
    public $visibleColumns = [
        'order_number' => true,
        'vendor' => true,
        'status' => true,
        'order_date' => true,
        'total' => true,
        'actions' => true,
        'updated_at' => true,
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at', 'updated_at'],
        'sortDirection' => ['except' => 'desc'],
        'statusFilter' => ['except' => ''],
    ];

    public function toggleColumn($columnName)
    {
        if (isset($this->visibleColumns[$columnName])) {
            $this->visibleColumns[$columnName] = !$this->visibleColumns[$columnName];
        }
    }

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

    public function render()
    {
        $purchaseOrders = PurchaseOrder::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('order_number', 'like', '%' . $this->search . '%')
                        ->orWhere('vendor_id', 'like', '%' . $this->search . '%')
                        ->orWhere('notes', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.tables.list-purchase-orders', [
            'purchaseOrders' => $purchaseOrders
        ]);
    }
}
