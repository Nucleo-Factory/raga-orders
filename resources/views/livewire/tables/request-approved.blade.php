<div>
    @php
        $headers = [
            'created_at' => 'Fecha y hora',
            'operation_id' => 'ID Operación',
            'authorizable_id' => 'ID',
            'requester_id' => 'Usuario',
            'operation_type' => 'Operación',
            'status' => 'Estado',
            'actions' => 'Acciones',
        ];

        $statusClasses = [
            'pending' => 'inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full',
            'approved' => 'inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full',
            'rejected' => 'inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full',
        ];

        $statusLabels = [
            'pending' => 'Pendiente',
            'approved' => 'Aprobado',
            'rejected' => 'Rechazado',
        ];
    @endphp

    <div class="mt-8 space-y-4">
        <div class="flex items-center justify-between mb-6">
            <x-search-input class="w-64" wire:model.debounce.300ms="search" placeholder="Buscar solicitudes..." />

            <div class="flex space-x-4">
                <select wire:model.live="filters.status" class="border-gray-300 rounded-md">
                    <option value="">Todos los estados</option>
                    <option value="pending">Pendientes</option>
                    <option value="approved">Aprobados</option>
                    <option value="rejected">Rechazados</option>
                </select>

                <select wire:model.live="filters.operation" class="border-gray-300 rounded-md">
                    <option value="">Todas las operaciones</option>
                    @foreach($requests->pluck('operation_type')->unique() as $operation)
                        <option value="{{ $operation }}">{{ $operation }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#E0E5FF]">
                    <tr>
                        @foreach($headers as $key => $label)
                            <th class="px-6 py-6 text-xs font-bold tracking-wider text-left text-black uppercase">
                                {{ $label }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                {{ $request->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $request->operation_id }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $request->authorizable_id }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $request->requester->name ?? 'Usuario desconocido' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $request->operation_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="{{ $statusClasses[$request->status] }}">
                                    {{ $statusLabels[$request->status] }}
                                </span>
                            </td>
                            <td class="flex justify-center px-6 py-4 whitespace-nowrap">
                                <div class="cursor-pointer" wire:click="openModal({{ $request->id }})">
                                    <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_8_110743)">
                                            <path d="M2.11342 8.47415C2.02262 8.33039 1.97723 8.25851 1.95182 8.14764C1.93273 8.06436 1.93273 7.93303 1.95182 7.84975C1.97723 7.73889 2.02262 7.66701 2.11341 7.52325C2.86369 6.33526 5.09693 3.33203 8.50027 3.33203C11.9036 3.33203 14.1369 6.33526 14.8871 7.52325C14.9779 7.66701 15.0233 7.73889 15.0487 7.84975C15.0678 7.93303 15.0678 8.06436 15.0487 8.14764C15.0233 8.25851 14.9779 8.33039 14.8871 8.47415C14.1369 9.66214 11.9036 12.6654 8.50027 12.6654C5.09693 12.6654 2.86369 9.66214 2.11342 8.47415Z" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8.50027 9.9987C9.60484 9.9987 10.5003 9.10327 10.5003 7.9987C10.5003 6.89413 9.60484 5.9987 8.50027 5.9987C7.3957 5.9987 6.50027 6.89413 6.50027 7.9987C6.50027 9.10327 7.3957 9.9987 8.50027 9.9987Z" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_8_110743">
                                                <rect width="16" height="16" fill="white" transform="translate(0.5)"/>
                                            </clipPath>
                                        </defs>
                                    </svg>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($headers) }}" class="px-6 py-4 text-center text-gray-500">
                                No hay solicitudes de autorización que mostrar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between mt-4">
            <div class="flex justify-between flex-1 sm:hidden">
                <button wire:click="previousPage" @if($requests->onFirstPage()) disabled @endif class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ $requests->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    Anterior
                </button>
                <button wire:click="nextPage" @if(!$requests->hasMorePages()) disabled @endif class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ !$requests->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    Siguiente
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ $requests->firstItem() ?? 0 }}</span>
                        a
                        <span class="font-medium">{{ $requests->lastItem() ?? 0 }}</span>
                        de
                        <span class="font-medium">{{ $requests->total() }}</span>
                        resultados
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <!-- Botón Anterior -->
                        <button wire:click="previousPage" @if($requests->onFirstPage()) disabled @endif class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ $requests->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <span class="sr-only">Anterior</span>
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Números de página -->
                        @for ($i = 1; $i <= $requests->lastPage(); $i++)
                            <button wire:click="gotoPage({{ $i }})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium {{ $requests->currentPage() === $i ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                {{ $i }}
                            </button>
                        @endfor

                        <!-- Botón Siguiente -->
                        <button wire:click="nextPage" @if(!$requests->hasMorePages()) disabled @endif class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ !$requests->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <span class="sr-only">Siguiente</span>
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <x-modal-requests name="modal-requests" show="{{ $showModal ?? false }}">
        <x-slot name="title">
            Detalles de la Solicitud de Autorización
        </x-slot>

        <x-slot name="operationId">
            {{ $selectedRequest->operation_id ?? '' }}
        </x-slot>

        <x-slot name="requester">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-8 h-8 text-sm font-medium text-white bg-blue-600 rounded-full">
                    @if(isset($selectedRequest->requester))
                        {{ substr($selectedRequest->requester->name ?? 'UN', 0, 2) }}
                    @else
                        {{ 'UN' }}
                    @endif
                </div>
                <span class="ml-2 text-gray-700">
                    @if(isset($selectedRequest->requester))
                        {{ $selectedRequest->requester->name }}
                    @else
                        Usuario ID: {{ $selectedRequest->requester_id ?? 'Desconocido' }}
                    @endif
                </span>
            </div>
        </x-slot>

        <x-slot name="date">
            <p class="text-gray-700">
                @if(isset($selectedRequest->created_at))
                    {{ \Carbon\Carbon::parse($selectedRequest->created_at)->format('d/m/Y') }}
                @else
                    --/--/----
                @endif
            </p>
        </x-slot>

        <x-slot name="time">
            <p class="text-gray-700">
                @if(isset($selectedRequest->created_at))
                    {{ \Carbon\Carbon::parse($selectedRequest->created_at)->format('H:i:s') }}
                @else
                    --:--:--
                @endif
            </p>
        </x-slot>

        <x-slot name="operationType">
            <p class="text-gray-700">{{ $selectedRequest->operation_type ?? '' }}</p>
        </x-slot>

        <x-slot name="authorizableInfo">
            <p class="text-gray-700">
                <span class="font-medium">Tipo:</span> {{ $selectedRequest->authorizable_type ?? '' }}<br>
                <span class="font-medium">ID:</span> {{ $selectedRequest->authorizable_id ?? '' }}
            </p>
        </x-slot>

        <x-slot name="status">
            @if(isset($selectedRequest->status))
                <span class="{{ $statusClasses[$selectedRequest->status] ?? '' }}">
                    {{ $statusLabels[$selectedRequest->status] ?? $selectedRequest->status }}
                </span>
                @if(isset($selectedRequest->authorized_at))
                    <p class="mt-1 text-sm text-gray-500">
                        Autorizado: {{ \Carbon\Carbon::parse($selectedRequest->authorized_at)->format('d/m/Y H:i:s') }}
                    </p>
                @endif
            @else
                <p class="text-gray-500">No definido</p>
            @endif
        </x-slot>

        <x-slot name="dataContent">
            @if(isset($selectedRequest->data))
                <div class="p-3 overflow-auto text-sm text-gray-600 bg-gray-100 rounded-md max-h-40">
                    @php
                        $data = is_array($selectedRequest->data)
                            ? $selectedRequest->data
                            : json_decode($selectedRequest->data, true);
                    @endphp

                    @if(is_array($data))
                        @foreach($data as $key => $value)
                            <div class="mb-1">
                                <span class="font-semibold">{{ ucfirst($key) }}:</span>
                                @if(is_bool($value))
                                    {{ $value ? 'Sí' : 'No' }}
                                @elseif(is_array($value) || is_object($value))
                                    <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @else
                                    {{ $value }}
                                @endif
                            </div>
                        @endforeach
                    @else
                        {{ $selectedRequest->data }}
                    @endif
                </div>
            @else
                <p class="text-gray-500">Sin datos adicionales</p>
            @endif
        </x-slot>

        <x-slot name="notes">
            <p class="text-sm text-gray-500">
                {{ $selectedRequest->notes ?? 'Sin notas adicionales' }}
            </p>
        </x-slot>

        <x-slot name="actions">
            @if($selectedRequest && $selectedRequest->status === 'pending')
                <button class="w-1/2 py-3 font-medium text-white transition duration-200 bg-green-600 rounded-lg hover:bg-green-700" wire:click="approveRequest({{ $selectedRequest->id ?? 0 }})">
                    Aprobar
                </button>
                <button class="w-1/2 py-3 font-medium text-white transition duration-200 bg-red-600 rounded-lg hover:bg-red-700" wire:click="rejectRequest({{ $selectedRequest->id ?? 0 }})">
                    Rechazar
                </button>
            @else
                <button class="w-full py-3 font-medium text-white transition duration-200 bg-indigo-600 rounded-lg hover:bg-indigo-700" wire:click="closeModal">
                    Aceptar
                </button>
            @endif
        </x-slot>
    </x-modal-requests>
</div>
