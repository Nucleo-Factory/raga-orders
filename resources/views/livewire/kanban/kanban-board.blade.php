<div
    class="flex w-full p-4 space-x-4 overflow-x-auto"
    wire:poll.10s
>
    @if(!$board)
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700">No hay tableros Kanban disponibles</h3>
            <p class="mt-2 text-gray-600">No se encontró ningún tablero Kanban para tu compañía. Contacta al administrador para crear uno.</p>
        </div>
    @else
        @foreach($columns as $column)
            <div class="flex-shrink-0 p-3 bg-gray-100 rounded-lg">
                <h3 class="mb-3 text-lg font-bold">
                    {{ $column['name'] }}
                    <span class="ml-2 text-sm font-normal text-gray-600">
                        ({{ count($tasksByColumn[$column['id']]) }})
                    </span>
                </h3>

                <div
                    id="column-{{ $column['id'] }}"
                    data-column-id="{{ $column['id'] }}"
                    class="space-y-3 min-h-40"
                    x-data
                    x-init="
                        new Sortable($el, {
                            group: 'tasks',
                            animation: 150,
                            onEnd: function(evt) {
                                const taskId = evt.item.getAttribute('data-task-id');
                                const newColumn = evt.to.getAttribute('data-column-id');

                                if (evt.from.getAttribute('data-column-id') !== newColumn) {
                                    $wire.moveTask(taskId, newColumn);
                                }
                            }
                        })
                    "
                >
                    @foreach($tasksByColumn[$column['id']] as $task)
                        <div
                            class="cursor-move task-card"
                            data-task-id="{{ $task['id'] }}"
                        >
                            <x-kanban-card
                                :po="$task['po']"
                                :trackingId="$task['id']"
                                :hubLocation="$task['company']"
                                :leadTime="$task['order_date'] ?? 'N/A'"
                                :recolectaTime="$task['requested_delivery_date'] ?? 'N/A'"
                                :pickupTime="$task['requested_delivery_date'] ?? 'N/A'"
                                :totalWeight="number_format($task['total'] ?? 0, 2)"
                            />
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
