@php
    // Esta variable ya no es necesaria, usamos las columnas del Kanban directamente
@endphp

<div class="w-full mx-auto">
    <div class="flex w-full gap-4 pb-4 overflow-x-auto kanban-container" wire:poll.10s>
        @if (!$board)
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">No hay tableros Kanban disponibles</h3>
                <p class="mt-2 text-gray-600">No se encontró ningún tablero Kanban para tu compañía. Contacta al
                    administrador para crear uno.</p>
            </div>
        @else
            @foreach ($columns as $column)
                <div class="flex-shrink-0 p-3 mx-2 rounded-lg kanban-column w-80">
                    <h3 class="mb-4 border-b-2 border-[#2E2E2E] px-2 text-lg font-bold text-[#2E2E2E]">
                        {{ $column['name'] }}
                        <span class="ml-2 text-sm font-normal text-gray-600">
                            ({{ count($tasksByColumn[$column['id']]) }})
                        </span>
                    </h3>

                    <div id="column-{{ $column['id'] }}" data-column-id="{{ $column['id'] }}" class="space-y-3 min-h-40"
                        x-data x-init="new Sortable($el, {
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

                                console.log(taskId, newColumn);
                                if (evt.from.getAttribute('data-column-id') !== newColumn) {
                                    $dispatch('open-modal', 'change-oc-stage');

                                    $wire.setCurrentTask(taskId, newColumn);
                                }
                            }
                        })">
                        @foreach ($tasksByColumn[$column['id']] as $task)
                            <div class="cursor-move task-card" data-task-id="{{ $task['id'] }}">
                                <x-kanban-card :id="$task['id']" :purchaseOrder="$task" :po="$task['po']" :trackingId="$task['id']" :hubLocation="$task['company']" :leadTime="$task['order_date'] ?? 'N/A'"
                                    :recolectaTime="$task['requested_delivery_date'] ?? 'N/A'" :pickupTime="$task['requested_delivery_date'] ?? 'N/A'" :totalWeight="number_format($task['total'] ?? 0, 2)" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <x-modal-success name="success-modal" show="true">
        <div>
            @if ($currentTask)
                <p>PO: {{ $currentTask['po'] }}</p>
            @endif
        </div>
    </x-modal-success>

    <x-modal name="change-oc-stage" maxWidth="lg">
        <h3 class="mb-2 text-lg font-bold text-center text-light-blue">
            ¿Cambiar la Orden de compra de etapa?
        </h3>

        @if ($currentTask)
            <div class="mb-5 text-center">
                <p class="text-[#171717] underline underline-offset-4">PO: {{ $currentTask['po'] }}</p>
            </div>
        @endif

        <div class="mb-8">
            <x-form-select label="" name="etapa" :options="collect($columns)->pluck('name', 'id')->toArray()" optionPlaceholder="Seleccionar etapa"
                :value="$newColumnId" />
        </div>

        <x-form-textarea label="Comentarios" placeholder="Ingrese su texto..." class="mb-[0.375rem]" />

        <div class="mb-12 space-y-2">
            <x-secondary-button class="group flex w-full items-center justify-center gap-[0.625rem]">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="22" viewBox="0 0 21 22"
                    fill="none">
                    <path
                        d="M19.1525 9.89897L10.1369 18.9146C8.08662 20.9648 4.7625 20.9648 2.71225 18.9146C0.661997 16.8643 0.661998 13.5402 2.71225 11.49L11.7279 2.47435C13.0947 1.10751 15.3108 1.10751 16.6776 2.47434C18.0444 3.84118 18.0444 6.05726 16.6776 7.42409L8.01555 16.0862C7.33213 16.7696 6.22409 16.7696 5.54068 16.0862C4.85726 15.4027 4.85726 14.2947 5.54068 13.6113L13.1421 6.00988"
                        stroke="#565AFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="transition-colors duration-500 group-hover:stroke-dark-blue group-active:stroke-neutral-blue group-disabled:stroke-[#C2C2C2]" />
                </svg>

                <span>Adjuntar documentación...</span>
            </x-secondary-button>
            <div class="flex flex-col text-sm text-[#A5A3A3]">
                <span>Tipo de formato .xls .xlsx .pdf</span>
                <span>Tamaño máximo 5MB</span>
            </div>
        </div>

        <div class="flex gap-[1.875rem]">
            <x-secondary-button x-on:click="$dispatch('close-modal', 'change-oc-stage')" class="w-full">
                Cancelar
            </x-secondary-button>
            <x-primary-button
                x-on:click="$wire.moveTask($wire.currentTaskId, document.querySelector('select[name=etapa]').value); $dispatch('close-modal', 'change-oc-stage')"
                class="w-full">
                Continuar
            </x-primary-button>
        </div>
    </x-modal>

    <x-modal name="change-validate" maxWidth="lg">
        <h3 class="mb-2 text-lg font-bold text-center text-light-blue">
            ¿Cambiar la Orden de compra de etapa?
        </h3>

        @if ($currentTask)
            <div class="mb-5 text-center">
                <p class="text-[#171717] underline underline-offset-4">PO: {{ $currentTask['po'] }}</p>
            </div>
        @endif

        <div class="flex gap-[1.875rem]">
            <x-secondary-button x-on:click="$dispatch('close-modal', 'change-oc-stage')" class="w-full">
                Cancelar
            </x-secondary-button>
            <x-primary-button
                x-on:click="$wire.moveTask($wire.currentTaskId, document.querySelector('select[name=etapa]').value); $dispatch('close-modal', 'change-oc-stage')"
                class="w-full">
                Continuar
            </x-primary-button>
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
