<?php

namespace App\Livewire\Tables;

use App\Models\AuthorizationRequest;
use App\Services\AuthorizationService;
use Livewire\Component;
use Livewire\WithPagination;


class RequestsTable extends Component {

    use WithPagination;

    public $actions = false;
    public $search = '';
    public $filters = [
        'operation' => ''
    ];

    protected $listeners = [
        'refreshRequests' => '$refresh'
    ];

    public function mount($actions = false) {
        $this->actions = $actions;
    }

    public function approve($requestId)
    {
        $request = AuthorizationRequest::findOrFail($requestId);
        $service = app(AuthorizationService::class);

        if ($service->approve($request)) {
            $this->dispatch('showAlert', [
                'type' => 'success',
                'message' => 'Solicitud aprobada correctamente.'
            ]);
        }
    }

    public function reject($requestId)
    {
        $request = AuthorizationRequest::findOrFail($requestId);
        $service = app(AuthorizationService::class);

        if ($service->reject($request)) {
            $this->dispatch('showAlert', [
                'type' => 'success',
                'message' => 'Solicitud rechazada correctamente.'
            ]);
        }
    }

    public function render() {
        $query = AuthorizationRequest::with(['requester', 'authorizer'])
                                   ->where('status', 'pending'); // Solo pendientes

        // Aplicar búsqueda
        if ($this->search) {
            $query->where(function($q) {
                $q->where('operation_type', 'like', '%' . $this->search . '%')
                  ->orWhere('operation_id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('requester', function($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Aplicar filtro de operación
        if ($this->filters['operation']) {
            $query->where('operation_type', $this->filters['operation']);
        }

        $requests = $query->latest()->paginate(10);

        // Obtener operaciones únicas solo de solicitudes pendientes para el filtro
        $operationTypes = AuthorizationRequest::where('status', 'pending')
                                           ->select('operation_type')
                                           ->distinct()
                                           ->pluck('operation_type');

        return view('livewire.tables.requests-table', [
            'requests' => $requests,
            'operationTypes' => $operationTypes
        ]);
    }
}
