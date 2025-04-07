<div>
    <div class="flex justify-between mb-4">
        <div class="flex items-center">
            <div class="relative">
                <input
                    type="text"
                    wire:model.debounce.300ms="search"
                    placeholder="Buscar documentos..."
                    class="w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <select
                wire:model="statusFilter"
                class="px-4 py-2 ml-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="">Todos los estados</option>
                <option value="draft">Borrador</option>
                <option value="pending">Pendiente</option>
                <option value="approved">Aprobado</option>
                <option value="in_transit">En Tránsito</option>
                <option value="delivered">Entregado</option>
            </select>
        </div>
        <div>
            <select
                wire:model="perPage"
                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
                <option value="100">100 por página</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#E0E5FF]">
                <tr>
                    <th scope="col" class="px-6 py-5 text-xs font-bold tracking-wider text-left text-black uppercase cursor-pointer" wire:click="sortBy('document_number')">
                        Documento
                        @if ($sortField === 'document_number')
                            @if ($sortDirection === 'asc')
                                <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-5 text-xs font-bold tracking-wider text-left text-black uppercase">
                        Órdenes de Compra
                    </th>
                    <th scope="col" class="px-6 py-5 text-xs font-bold tracking-wider text-left text-black uppercase cursor-pointer" wire:click="sortBy('weight_kg')">
                        Peso Total
                        @if ($sortField === 'weight_kg')
                            @if ($sortDirection === 'asc')
                                <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-5 text-xs font-bold tracking-wider text-left text-black uppercase cursor-pointer" wire:click="sortBy('creation_date')">
                        Fecha de Creación
                        @if ($sortField === 'creation_date')
                            @if ($sortDirection === 'asc')
                                <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-5 text-xs font-bold tracking-wider text-left text-black uppercase cursor-pointer" wire:click="sortBy('status')">
                        Estado
                        @if ($sortField === 'status')
                            @if ($sortDirection === 'asc')
                                <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-5 text-xs font-bold tracking-wider text-left text-black uppercase">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($shippingDocuments as $document)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $document->document_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @if(isset($groupedPurchaseOrders[$document->id]))
                                    @foreach($groupedPurchaseOrders[$document->id] as $order)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                            {{ $order->order_number }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ number_format($document->total_weight_kg, 0) }} kg
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($document->creation_date)
                                    {{ $document->creation_date->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($document->status === 'pending')
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                    Pendiente
                                </span>
                            @elseif($document->status === 'approved')
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                    Aprobado
                                </span>
                            @elseif($document->status === 'draft')
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 rounded-full">
                                    Borrador
                                </span>
                            @elseif($document->status === 'in_transit')
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                    En Tránsito
                                </span>
                            @elseif($document->status === 'delivered')
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-indigo-800 bg-indigo-100 rounded-full">
                                    Entregado
                                </span>
                            @else
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 rounded-full">
                                    Desconocido
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                            <a href="#" class="ml-2 text-blue-600 hover:text-blue-900">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No se encontraron documentos de embarque
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $shippingDocuments->links() }}
    </div>
</div>
