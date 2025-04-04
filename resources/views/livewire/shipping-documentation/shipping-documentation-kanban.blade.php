<div class="w-full px-0 mx-0">
    <div class="flex w-full gap-4 pb-4 overflow-x-auto kanban-container" wire:poll.10s>
        @if(!$board)
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">No hay tableros Kanban disponibles</h3>
                <p class="mt-2 text-gray-600">No se encontró ningún tablero Kanban para documentación de embarque. Contacta al administrador para crear uno.</p>
            </div>
        @else
            @foreach($columns as $column)
                <div class="flex-shrink-0 p-3 mx-2 rounded-lg kanban-column w-80">
                    <h3 class="mb-4 border-b-2 border-[#2E2E2E] px-2 text-lg font-bold text-[#2E2E2E]">
                        {{ $column['name'] }}
                        <span class="ml-2 text-sm font-normal text-gray-600">
                            ({{ count($documentsByColumn[$column['id']]) }})
                        </span>
                    </h3>

                    <div
                        id="column-{{ $column['id'] }}"
                        data-column-id="{{ $column['id'] }}"
                         class="space-y-3 min-h-40"
                        x-data
                        x-init="
                            new Sortable($el, {
                                group: 'documents',
                                animation: 150,
                                ghostClass: 'bg-gray-100',
                                chosenClass: 'bg-gray-200',
                                dragClass: 'cursor-grabbing',
                                forceFallback: true,
                                fallbackClass: 'sortable-fallback',
                                fallbackOnBody: true,
                                onEnd: function(evt) {
                                    const documentId = evt.item.getAttribute('data-document-id');
                                    const newColumn = evt.to.getAttribute('data-column-id');

                                    if (evt.from.getAttribute('data-column-id') !== newColumn) {
                                        $dispatch('open-modal', 'modal-document-move');
                                        $wire.setCurrentDocument(documentId, newColumn);
                                        window.dispatchEvent(new CustomEvent('column-changed', { detail: newColumn }));
                                    }
                                }
                            })
                        "
                    >
                        @foreach($documentsByColumn[$column['id']] as $document)
                            <div
                                class="cursor-move document-card"
                                data-document-id="{{ $document['id'] }}">
                                <x-shipping-documentation-card
                                    :documentId="$document['document_number']"
                                    :trackingId="$document['document_id']"
                                    :hubLocation="$document['hub_location']"
                                    :creationDate="$document['creation_date']"
                                    :estimatedDepartureDate="$document['estimated_departure_date']"
                                    :estimatedArrivalDate="$document['estimated_arrival_date']"
                                    :totalWeight="number_format($document['weight_kg'], 0) . ' kg'"
                                    :poCount="$document['po_count']"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <x-modal name="modal-document-move" maxWidth="lg">
        <h3 class="mb-2 text-lg font-bold text-center text-light-blue">
            ¿Cambiar el documento de etapa?
        </h3>

        @if ($currentDocument)
            <div class="mb-5 text-center">
                <p class="text-[#171717] underline underline-offset-4">Documento: {{ $currentDocument['document_number'] }}</p>
            </div>
        @endif

        <div class="mb-4">
            <x-form-select
                label=""
                name="etapa_documento"
                :options="collect($columns)->pluck('name', 'id')->toArray()"
                optionPlaceholder="Seleccionar etapa"
                :value="$newColumnId"
                wire:model="newColumnId" />

            <div class="mt-4">
                <!-- Campo para la primera columna (Coordinación de salida - zarpe) -->
                <div class="{{ $newColumnId == $columns[1]['id'] ? '' : 'hidden' }}">
                    <x-form-input class="mb-4">
                        <x-slot:label>
                            Ingrese fecha de release
                        </x-slot:label>

                        <x-slot:input name="release_date" type="date" placeholder="Ingrese fecha de release" wire:model="release_date" class="pr-10"></x-slot:input>

                        <x-slot:error>
                            {{ $errors->first('release_date') }}
                        </x-slot:error>
                    </x-form-input>
                </div>

                <!-- Campos para la segunda columna (En tránsito - seguimiento) -->
                <div class="{{ $newColumnId == $columns[2]['id'] ? '' : 'hidden' }}">
                    <x-form-input class="mb-4">
                        <x-slot:label>
                            Ingrese ID tracking
                        </x-slot:label>

                        <x-slot:input name="tracking_id" placeholder="Ingrese ID tracking" wire:model="tracking_id" class="pr-10"></x-slot:input>

                        <x-slot:error>
                            {{ $errors->first('tracking_id') }}
                        </x-slot:error>
                    </x-form-input>

                    <x-form-input class="mb-4">
                        <x-slot:label>
                            Ingrese código booking
                        </x-slot:label>

                        <x-slot:input name="booking_code" placeholder="Ingrese código booking" wire:model="booking_code" class="pr-10"></x-slot:input>

                        <x-slot:error>
                            {{ $errors->first('booking_code') }}
                        </x-slot:error>
                    </x-form-input>

                    <x-form-input class="mb-4">
                        <x-slot:label>
                            Ingrese Contenedor
                        </x-slot:label>

                        <x-slot:input name="container_number" placeholder="Ingrese Contenedor" wire:model="container_number" class="pr-10"></x-slot:input>

                        <x-slot:error>
                            {{ $errors->first('container_number') }}
                        </x-slot:error>
                    </x-form-input>


                    <x-form-input>
                        <x-slot:label>
                            Ingrese MBL
                        </x-slot:label>

                        <x-slot:input name="mbl_number" placeholder="Ingrese MBL" wire:model="mbl_number" class="pr-10"></x-slot:input>

                        <x-slot:error>
                            {{ $errors->first('mbl_number') }}
                        </x-slot:error>
                    </x-form-input>
                </div>

                <div class="{{ $newColumnId == $columns[4]['id'] ? '' : 'hidden' }}">
                    <x-form-input class="mb-4">
                        <x-slot:label>
                            Ingrese fecha de instrucción
                        </x-slot:label>

                        <x-slot:input name="instruction_date" type="date" placeholder="Ingrese fecha de instrucción" wire:model="instruction_date" class="pr-10"></x-slot:input>

                        <x-slot:error>
                            {{ $errors->first('release_date') }}
                        </x-slot:error>
                    </x-form-input>
                </div>

            </div>
        </div>

        <div class="mb-8">
            <x-form-textarea label="" name="comentario_documento" wireModel="comment" placeholder="Comentarios" />
        </div>

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
            <x-secondary-button x-on:click="$dispatch('close-modal', 'modal-document-move')" class="w-full">
                Cancelar
            </x-secondary-button>

            <x-primary-button
                x-on:click="
                    const commentText = document.querySelector('textarea[name=comentario_documento]').value || '';
                    const columnId = document.querySelector('select[name=etapa_documento]').value;
                    console.log('Moving to column:', columnId); // Para depuración
                    if (columnId) {
                        $wire.setComments($wire.currentDocumentId, commentText);
                        $wire.moveDocument($wire.currentDocumentId, columnId);
                        $dispatch('close-modal', 'modal-document-move');
                    } else {
                        alert('Por favor selecciona una etapa.');
                    }"
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
        }

        .sortable-fallback {
            opacity: 0.8;
            transform: rotate(2deg);
            min-height: 180px !important;
            width: 320px !important;
        }

        .document-card {
            transition: transform 0.2s ease;
            width: 100%;
        }

        .document-card:hover {
            transform: translateY(-2px);
        }
    </style>
</div>
