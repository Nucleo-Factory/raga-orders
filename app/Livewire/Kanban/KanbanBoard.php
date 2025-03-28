<?php

namespace App\Livewire\Kanban;

use App\Models\KanbanBoard as KanbanBoardModel;
use App\Models\KanbanStatus;
use App\Models\PurchaseOrder;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class KanbanBoard extends Component {
    public $boardId;
    public $board;
    public $columns = [];
    public $tasks = [];
    public $tasksByColumn = [];
    public $boardType;
    public $currentTaskId;
    public $newColumnId;
    public $currentTask = null;

    public $actual_hub_id;

    public function mount($boardId = null) {
        // Determinar el tipo de tablero según la ruta actual
        $currentRoute = Route::currentRouteName();

        if ($currentRoute === 'purchase-orders.index') {
            $this->boardType = 'po_stages'; // Etapas PO
        } elseif ($currentRoute === 'shipping-documentation.index') {
            $this->boardType = 'shipping_documentation'; // Documentación de embarque
        } else {
            // Si no es ninguna de las rutas específicas, usar el tipo por defecto
            $this->boardType = 'purchase_orders';
        }

        // Si no se proporciona un ID de tablero, intentamos obtener el tablero según el tipo
        if (!$boardId) {
            // Obtener el tablero para la compañía del usuario actual según el tipo
            $companyId = auth()->user()->company_id ?? null;
            $this->board = KanbanBoardModel::where('company_id', $companyId)
                ->where('type', $this->boardType)
                ->where('is_active', true)
                ->first();

            if ($this->board) {
                $this->boardId = $this->board->id;
            }
        } else {
            $this->boardId = $boardId;
            $this->board = KanbanBoardModel::findOrFail($boardId);
            $this->boardType = $this->board->type;
        }

        $this->loadData();
    }

    public function loadData() {
        $this->loadColumns();
        $this->loadTasks();
        $this->organizeTasksByColumn();
    }

    public function loadColumns() {
        if (!$this->board) {
            $this->columns = [];
            return;
        }

        // Cargar las columnas (estados) del tablero
        $statuses = $this->board->statuses()->orderBy('position')->get();

        $this->columns = $statuses->map(function($status) {
            return [
                'id' => $status->id,
                'slug' => $status->slug,
                'name' => $status->name,
                'color' => $status->color,
                'position' => $status->position,
            ];
        })->toArray();
    }

    public function loadTasks() {
        if (!$this->board) {
            $this->tasks = [];
            return;
        }

        // Obtener el estado por defecto
        $defaultStatus = $this->board->statuses()->where('is_default', true)->first();

        // Obtener los IDs de los estados de este tablero
        $statusIds = collect($this->columns)->pluck('id')->toArray();

        // Cargar las órdenes de compra de la compañía del usuario
        $companyId = auth()->user()->company_id ?? null;
        $purchaseOrders = PurchaseOrder::with(['company', 'kanbanStatus'])
            ->where('company_id', $companyId)
            ->get();

        // Limpiar el array de tareas
        $this->tasks = [];

        foreach ($purchaseOrders as $order) {
            // Si la orden no tiene un estado de Kanban asignado, asignarle el estado por defecto
            if (!$order->kanban_status_id && $defaultStatus) {
                $order->update(['kanban_status_id' => $defaultStatus->id]);
                $order->refresh();
            }

            // Si después de intentar asignar un estado, sigue sin tenerlo, o si el estado no pertenece a este tablero, continuar
            if (!$order->kanban_status_id || !in_array($order->kanban_status_id, $statusIds)) {
                continue;
            }

            $this->tasks[] = [
                'id' => $order->id,
                'po' => $order->order_number,
                'vendor' => $order->vendor_id,
                'status' => $order->kanban_status_id,
                'status_slug' => $order->kanbanStatus->slug ?? 'unknown',
                'order_date' => $order->order_date ? $order->order_date->format('Y-m-d') : null,
                'requested_delivery_date' => $order->requested_delivery_date ? $order->requested_delivery_date->format('Y-m-d') : null,
                'total' => $order->total,
                'company' => $order->company->name ?? 'N/A',
                'created_at' => $order->created_at,
            ];
        }
    }

    public function organizeTasksByColumn() {
        $this->tasksByColumn = [];

        // Inicializar un array vacío para cada columna
        foreach ($this->columns as $column) {
            $this->tasksByColumn[$column['id']] = [];
        }

        // Organizar las tareas por columna
        foreach ($this->tasks as $task) {
            if (isset($this->tasksByColumn[$task['status']])) {
                $this->tasksByColumn[$task['status']][] = $task;
            }
        }

        // Ordenar las tareas por fecha de creación (de más nueva a más antigua) en cada columna
        foreach ($this->tasksByColumn as $columnId => $tasks) {
            usort($this->tasksByColumn[$columnId], function($a, $b) {
                return $b['created_at'] <=> $a['created_at'];
            });
        }
    }

    public function moveTask($taskId, $newStatus) {
        // Log para depuración
        \Log::info("Moving task $taskId to status $newStatus");

        try {
            // Actualizar directamente en la base de datos
            DB::table('purchase_orders')
                ->where('id', $taskId)
                ->update(['kanban_status_id' => $newStatus]);

            // Log para depuración
            \Log::info("Task moved successfully");

            // Recargar los datos
            $this->loadData();

            // Limpiar los datos temporales
            $this->currentTaskId = null;
            $this->newColumnId = null;
            $this->currentTask = null;

            // Forzar la actualización de la vista
            $this->dispatch('refreshKanban');
            $this->dispatch('purchaseOrderStatusUpdated');
        } catch (\Exception $e) {
            \Log::error("Error moving task: " . $e->getMessage());
        }
    }

    public function setCurrentTask($taskId, $newColumnId) {
        $this->currentTaskId = $taskId;
        $this->newColumnId = $newColumnId;

        // Buscar la tarea actual entre las tareas cargadas
        foreach ($this->tasks as $task) {
            if ($task['id'] == $taskId) {
                $this->currentTask = $task;
                break;
            }
        }
    }

    public function setActualHubId($taskId, $hubId) {
        \Log::info("Actual Hub ID updated: " . $this->actual_hub_id);

        DB::table('purchase_orders')
            ->where('id', $taskId)
            ->update(['actual_hub_id' => $this->actual_hub_id]);

        // Recargar los datos
        $this->loadData();

        // Limpiar los datos temporales
        $this->currentTaskId = null;
        $this->newColumnId = null;
        $this->currentTask = null;

        // Forzar la actualización de la vista
        $this->dispatch('refreshKanban');
        $this->dispatch('purchaseOrderStatusUpdated');
    }

    public function render()
    {
        return view('livewire.kanban.kanban-board', [
            'tasksByColumn' => $this->tasksByColumn,
            'boardType' => $this->boardType
        ])->layout('layouts.app');
    }
}
