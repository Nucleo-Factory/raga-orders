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
                        x-data="{ isModalOpen: false }"
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
                                    if (isModalOpen) return;

                                    const documentId = evt.item.getAttribute('data-document-id');
                                    const newColumn = evt.to.getAttribute('data-column-id');

                                    if (evt.from.getAttribute('data-column-id') !== newColumn) {
                                        isModalOpen = true;
                                        $wire.setCurrentDocument(documentId, newColumn)
                                            .then(() => {
                                                $dispatch('open-modal', 'modal-document-move');
                                            });

                                        setTimeout(() => {
                                            isModalOpen = false;
                                        }, 1000);
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
                wire:model.live="newColumnId" />

            <div class="mt-4">
                <!-- Campo para la columna 1 -->
                <div class="{{ $newColumnId == $columns[0]['id'] ? '' : 'hidden' }}">
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

                <!-- Campos para la columna 2 -->
                <div class="{{ $newColumnId == $columns[1]['id'] ? '' : 'hidden' }}">
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

                <!-- Campo para la columna "Digitaciones" (ID 14) -->
                <div class="{{ $newColumnId == 14 ? '' : 'hidden' }}">
                    <x-form-input class="mb-4">
                        <x-slot:label>
                            Ingrese fecha de instrucción
                        </x-slot:label>

                        <x-slot:input name="instruction_date" type="date" placeholder="Ingrese fecha de instrucción" wire:model="instruction_date" class="pr-10"></x-slot:input>

                        <x-slot:error>
                            {{ $errors->first('instruction_date') }}
                        </x-slot:error>
                    </x-form-input>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <x-form-textarea label="" name="comentario_documento" wireModel="comment" placeholder="Comentarios" />
        </div>


        <div class="mb-12 space-y-2">
            <label class="ml-[1.125rem] text-sm font-medium text-[#565AFF]">
                Adjuntar documentación (opcional)
            </label>

            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="mb-1 text-sm text-gray-500">
                            <span class="font-semibold">Haz clic para subir</span> o arrastra y suelta
                        </p>
                        <p class="text-xs text-gray-500">XLS, XLSX, PDF (máx. 5MB)</p>
                    </div>
                    <input id="dropzone-file" wire:model="file" type="file" class="hidden" />
                </label>
            </div>

            @error('file')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if($file)
                <div class="flex items-center justify-between p-2 mt-2 bg-gray-100 rounded-md">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-700 truncate">{{ $file->getClientOriginalName() }}</span>
                    </div>
                    <button type="button" wire:click="$set('file', null)" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        <div class="flex gap-[1.875rem]">
            <x-secondary-button x-on:click="$dispatch('close-modal', 'modal-document-move')" class="w-full">
                Cancelar
            </x-secondary-button>

            <x-primary-button
                wire:click="saveAndMoveDocument"
                x-on:click="$dispatch('close-modal', 'modal-document-move')"
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
