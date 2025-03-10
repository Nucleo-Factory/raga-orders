<?php

namespace App\Livewire\Kanban;

use App\Models\KanbanBoard as KanbanBoardModel;
use App\Models\KanbanStatus;
use App\Models\PurchaseOrder;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class KanbanBoard extends Component {
    public $boardId;
    public $board;
    public $columns = [];
    public $tasks = [];
    public $tasksByColumn = [];

    public function mount($boardId = null) {
        // Si no se proporciona un ID de tablero, intentamos obtener el tablero predeterminado
        if (!$boardId) {
            // Obtener el tablero predeterminado para la compañía del usuario actual
            $companyId = auth()->user()->company_id ?? null;
            $this->board = KanbanBoardModel::where('company_id', $companyId)
                ->where('type', 'purchase_orders')
                ->where('is_active', true)
                ->first();

            if ($this->board) {
                $this->boardId = $this->board->id;
            }
        } else {
            $this->boardId = $boardId;
            $this->board = KanbanBoardModel::findOrFail($boardId);
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

            // Forzar la actualización de la vista
            $this->dispatch('refreshKanban');
        } catch (\Exception $e) {
            \Log::error("Error moving task: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.kanban.kanban-board', [
            'tasksByColumn' => $this->tasksByColumn
        ])->layout('layouts.app');
    }
}
