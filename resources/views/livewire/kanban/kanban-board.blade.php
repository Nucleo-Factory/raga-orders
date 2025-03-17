<div class="container w-full px-4 mx-auto">
    <div class="flex w-full pb-4 overflow-x-auto kanban-container" wire:poll.10s>
        @if(!$board)
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">No hay tableros Kanban disponibles</h3>
                <p class="mt-2 text-gray-600">No se encontró ningún tablero Kanban para tu compañía. Contacta al administrador para crear uno.</p>
            </div>
        @else
            @foreach($columns as $column)
                <div class="flex-shrink-0 p-3 mx-2 bg-gray-100 rounded-lg w-80 kanban-column">
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
                                ghostClass: 'bg-gray-100',
                                chosenClass: 'bg-gray-200',
                                dragClass: 'cursor-grabbing',
                                forceFallback: true,
                                fallbackClass: 'sortable-fallback',
                                fallbackOnBody: true,
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

    <style>
        .kanban-container {
            display: flex;
            overflow-x: auto;
            padding-bottom: 1rem;
            margin: 0 -0.5rem;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
            scrollbar-width: thin;
            max-width: 1430px
        }

        .kanban-column {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sortable-fallback {
            opacity: 0.8;
            transform: rotate(2deg);
            min-height: 180px !important;
            width: 320px !important;
        }

        .task-card {
            transition: transform 0.2s ease;
            width: 100%;
        }

        .task-card:hover {
            transform: translateY(-2px);
        }
    </style>
</div>
