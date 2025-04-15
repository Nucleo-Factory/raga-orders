<div>
    @php
        $headers = [
            'created_at' => 'Fecha y hora',
            'operation_id' => 'ID Operación',
            'authorizable_id' => 'Número de PO',
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
                                {{ $request->order_number ?? $request->authorizable_id }}
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
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                @if($actions && $request->status === 'pending')
                                    <div class="flex space-x-2">
                                        <button wire:click="approve('{{ $request->id }}')" class="text-green-600 hover:text-green-900">
                                            Aprobar
                                        </button>
                                        <button wire:click="reject('{{ $request->id }}')" class="text-red-600 hover:text-red-900">
                                            Rechazar
                                        </button>
                                    </div>
                                @else
                                @endif
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
</div>
