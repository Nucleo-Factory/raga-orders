<div class="w-full px-0 mx-0">
    <div class="flex w-full gap-4 !max-w-full pb-4 overflow-x-auto kanban-container" wire:poll.10s>
        @if(!$board)
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">No hay tableros Kanban disponibles</h3>
                <p class="mt-2 text-gray-600">No se encontró ningún tablero Kanban para documentación de embarque. Contacta al administrador para crear uno.</p>
            </div>
        @else
            @foreach($columns as $column)
                <div class="flex-shrink-0 p-3 mx-2 bg-gray-100 rounded-lg w-80 kanban-column">
                    <h3 class="mb-3 text-lg font-bold" style="color: {{ $column['color'] }}">
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
                                        $wire.moveDocument(documentId, newColumn);
                                    }
                                }
                            })
                        "
                    >
                        @foreach($documentsByColumn[$column['id']] as $document)
                            <div
                                class="cursor-move document-card"
                                data-document-id="{{ $document['id'] }}"
                            >
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

    <style>
        .kanban-container {
            display: flex;
            overflow-x: auto;
            padding-bottom: 1rem;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
            scrollbar-width: thin;
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

        .document-card {
            transition: transform 0.2s ease;
            width: 100%;
        }

        .document-card:hover {
            transform: translateY(-2px);
        }
    </style>
</div>
