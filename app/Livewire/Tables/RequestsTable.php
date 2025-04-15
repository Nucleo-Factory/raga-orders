<?php

namespace App\Livewire\Tables;

use App\Models\AuthorizationRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderComment;
use App\Services\AuthorizationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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

        // Guardar datos originales antes de cambiar el estado
        $originalData = $request->data;
        $operationType = $request->operation_type;
        $authorizable = $request->authorizable;

        // Aprobar la solicitud en el servicio (esto solo cambia el estado)
        if ($service->approve($request)) {
            // Para operaciones que requieren crear un comentario
            if ($operationType === 'attach_file_to_comment' &&
                $request->authorizable_type === PurchaseOrder::class &&
                $authorizable) {

                // Extraer los datos necesarios
                $commentText = $originalData['comment'] ?? 'Comentario sin contenido';

                try {
                    // Crear el comentario en la tabla purchase_order_comments
                    $comment = new PurchaseOrderComment();
                    $comment->purchase_order_id = $authorizable->id;
                    $comment->user_id = $request->requester_id;
                    $comment->comment = $commentText;
                    $comment->operacion = 'Comentario con archivo (Aprobado)';
                    $comment->save();

                    // Guardar la información de sesión para que el usuario adjunte el archivo
                    Session::flash('comment_attachment_approved', true);
                    Session::flash('comment_id', $comment->id);
                    Session::flash('purchase_order_id', $authorizable->id);

                    Log::info('Comentario creado después de aprobación desde Livewire', [
                        'comment_id' => $comment->id,
                        'purchase_order_id' => $authorizable->id,
                        'request_id' => $request->id
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error al crear comentario desde Livewire', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            // Para operaciones de subir archivo
            elseif ($operationType === 'upload_file' &&
                    $request->authorizable_type === PurchaseOrder::class &&
                    $authorizable) {

                // No creamos comentario para subir archivo, solo guardamos la información de sesión
                Session::flash('file_upload_approved', true);
                Session::flash('purchase_order_id', $authorizable->id);
                // Incluir los datos originales
                Session::flash('approved_file_data', $originalData);

                Log::info('Solicitud de archivo aprobada desde Livewire', [
                    'purchase_order_id' => $authorizable->id,
                    'request_id' => $request->id,
                    'file_data' => $originalData
                ]);
            }

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

        // Obtener todos los IDs de solicitudes relacionadas con PurchaseOrder
        $poAuthorizableIds = $requests->filter(function($request) {
            return $request->authorizable_type === 'App\\Models\\PurchaseOrder';
        })->pluck('authorizable_id')->toArray();

        // Si hay solicitudes relacionadas con PurchaseOrder
        if (!empty($poAuthorizableIds)) {
            // Hacer una consulta directa para obtener los números de orden
            $pos = PurchaseOrder::whereIn('id', $poAuthorizableIds)
                     ->pluck('order_number', 'id')
                     ->toArray();

            // Asignar los números de orden a las solicitudes correspondientes
            foreach ($requests as $request) {
                if ($request->authorizable_type === 'App\\Models\\PurchaseOrder' &&
                    isset($pos[$request->authorizable_id])) {
                    $request->order_number = $pos[$request->authorizable_id];
                }
            }
        }

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
