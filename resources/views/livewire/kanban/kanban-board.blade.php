@php
    // Esta variable ya no es necesaria, usamos las columnas del Kanban directamente
@endphp

<div class="w-full mx-auto">
    <div class="flex w-full gap-4 pb-4 overflow-x-auto kanban-container" wire:poll.10s>
        @if(!$board)
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">No hay tableros Kanban disponibles</h3>
                <p class="mt-2 text-gray-600">No se encontró ningún tablero Kanban para tu compañía. Contacta al administrador para crear uno.</p>
            </div>
        @else
            @foreach($columns as $column)
                <div class="flex-shrink-0 p-3 mx-2 rounded-lg w-80 kanban-column">
                    <h3 class="mb-4 text-lg font-bold text-[#2E2E2E] border-b-2 border-[#2E2E2E] px-2">
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
                                        $dispatch('open-modal', 'change-oc-stage');

                                        $wire.setCurrentTask(taskId, newColumn);
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

    <x-modal name="change-oc-stage" maxWidth="lg">
        <h3 class="mb-12 text-2xl font-bold">
            ¿Cambiar la Orden de compra de etapa?
        </h3>

        @if($currentTask)
        <div class="p-4 mb-4 rounded-lg bg-gray-50">
            <p class="text-lg font-semibold">PO: {{ $currentTask['po'] }}</p>
            <p>Ubicación: {{ $currentTask['company'] }}</p>
            <p>Fecha de orden: {{ $currentTask['order_date'] ?? 'N/A' }}</p>

            @php
                $fromColumn = collect($columns)->firstWhere('id', $currentTask['status']);
                $toColumn = collect($columns)->firstWhere('id', $newColumnId);
            @endphp

            @if($fromColumn && $toColumn)
            <div class="flex items-center mt-3 text-sm">
                <span class="px-2 py-1 mr-2 font-semibold text-white bg-blue-500 rounded-md">{{ $fromColumn['name'] }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                <span class="px-2 py-1 ml-2 font-semibold text-white bg-green-500 rounded-md">{{ $toColumn['name'] }}</span>
            </div>
            @endif
        </div>
        @endif

        <div class="mb-8">
            <x-form-select
                label=""
                name="etapa"
                :options="collect($columns)->pluck('name', 'id')->toArray()"
                optionPlaceholder="Seleccionar etapa"
                :value="$newColumnId"
            />
        </div>

        <x-form-textarea label="Comentarios" placeholder="Ingrese su texto..." class="mb-[0.375rem]" />

        <div class="mb-12">
            <button
                class="flex w-full items-center justify-center gap-[0.625rem] rounded-[0.375rem] border border-[#E2E8F0] bg-white px-4 py-2 font-inter text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"
                    fill="none">
                    <path
                        d="M7.39618 1.434L7.39621 1.43402L7.40063 1.42949C7.6625 1.16093 7.9751 0.947046 8.32027 0.800239C8.66545 0.653431 9.03634 0.576625 9.41144 0.574274C9.78653 0.571924 10.1584 0.644077 10.5053 0.786547C10.8523 0.929018 11.1676 1.13897 11.4328 1.40422C11.698 1.66948 11.9079 1.98476 12.0503 2.33177C12.1928 2.67878 12.2649 3.05061 12.2625 3.4257C12.2601 3.8008 12.1832 4.17168 12.0363 4.51684C11.8895 4.86199 11.6756 5.17455 11.407 5.43639L11.4069 5.43636L11.4024 5.44089L6.21774 10.6262L6.21773 10.6262L6.21486 10.6291C6.076 10.7703 5.91057 10.8826 5.72811 10.9595C5.54565 11.0364 5.34976 11.0764 5.15175 11.0773C4.95374 11.0781 4.75753 11.0397 4.57443 10.9643C4.39133 10.8889 4.22497 10.7781 4.08493 10.6381C3.9449 10.4981 3.83398 10.3317 3.75855 10.1486C3.68313 9.96555 3.6447 9.76935 3.64548 9.57134C3.64626 9.37333 3.68624 9.17743 3.76311 8.99495C3.83998 8.81247 3.95222 8.64702 4.09336 8.50813L4.09337 8.50814L4.0962 8.50531L8.92798 3.67353L9.16354 3.90909L4.33443 8.7382C4.22465 8.84513 4.13701 8.97264 4.07651 9.11347C4.01537 9.25581 3.98318 9.4089 3.98184 9.56381C3.98049 9.71872 4.01001 9.87234 4.06867 10.0157C4.12733 10.1591 4.21396 10.2894 4.3235 10.3989C4.43304 10.5084 4.5633 10.5951 4.70668 10.6537C4.85006 10.7124 5.00369 10.7419 5.1586 10.7406C5.3135 10.7392 5.46659 10.707 5.60893 10.6459C5.74975 10.5854 5.87725 10.4978 5.98417 10.388L11.1675 5.20533L11.1675 5.20531C11.3997 4.97314 11.5839 4.69753 11.7095 4.39419C11.8351 4.09086 11.8998 3.76575 11.8998 3.43742C11.8998 3.10909 11.8351 2.78398 11.7095 2.48065C11.5839 2.17731 11.3997 1.90169 11.1675 1.66953C10.9354 1.43737 10.6598 1.25321 10.3564 1.12756C10.0531 1.00192 9.72798 0.93725 9.39965 0.93725C9.07132 0.93725 8.74621 1.00192 8.44288 1.12756C8.13954 1.25321 7.86393 1.43737 7.63176 1.66953L7.63174 1.66955L2.44641 6.85556L2.44635 6.8555L2.44034 6.86173C1.74207 7.5847 1.35569 8.55301 1.36442 9.5581C1.37316 10.5632 1.7763 11.5246 2.48704 12.2354C3.19777 12.9461 4.15921 13.3492 5.1643 13.358C6.16939 13.3667 7.1377 12.9803 7.86067 12.2821L7.86073 12.2821L7.86685 12.276L13.1705 6.97296L13.4063 7.20896L8.1031 12.5122C7.32165 13.2936 6.26178 13.7327 5.15665 13.7327C4.05152 13.7327 2.99165 13.2936 2.2102 12.5122C1.42876 11.7308 0.989746 10.6709 0.989746 9.56575C0.989746 8.46063 1.42875 7.40077 2.21018 6.61933C2.21019 6.61932 2.2102 6.61931 2.2102 6.61931L7.39618 1.434Z"
                        fill="black" stroke="#0F172A" />
                </svg>

                <span>Adjuntar documentación...</span>
            </button>
        </div>

        <div class="flex gap-[1.875rem]">
            <x-black-btn x-on:click="$wire.moveTask($wire.currentTaskId, document.querySelector('select[name=etapa]').value); $dispatch('close-modal', 'change-oc-stage')" class="w-full">
                Continuar
            </x-black-btn>
            <x-white-btn x-on:click="$dispatch('close-modal', 'change-oc-stage')" class="w-full">
                Cancelar
            </x-white-btn>
        </div>
    </x-modal>

    <style>
        .kanban-container {
            display: flex;
            overflow-x: auto;
            padding-bottom: 1rem;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
            scrollbar-width: thin;
            max-height: 600px;
        }

        .kanban-column {
            height: 100%;
            min-height: 600px;
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
