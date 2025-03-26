<div>
    <div class="flex max-w-[1254px] items-center justify-between">
        <x-view-title>
            <x-slot:title>
                Órdenes de Compra
            </x-slot:title>

            <x-slot:content>
                Gestiona todas tus órdenes de compra
            </x-slot:content>
        </x-view-title>

        <div class="flex space-x-4">
            <a href="{{ route('purchase-orders.kanban-boards') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Ver Tableros Kanban
            </a>
            <a href="{{ route('new-purchase-order') }}">
                <x-black-btn>Nueva Orden</x-black-btn>
            </a>
        </div>
    </div>

    <div class="mt-8 space-y-4">
        <!-- Filtros -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div>
                    <label for="search" class="sr-only">Buscar</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" id="search" class="block w-full pl-10 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Buscar órdenes...">
                    </div>
                </div>

                <div>
                    <label for="statusFilter" class="sr-only">Filtrar por estado</label>
                    <select wire:model.live="statusFilter" id="statusFilter" class="block w-full border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todos los estados</option>
                        <option value="draft">Borrador</option>
                        <option value="pending">Pendiente</option>
                        <option value="approved">Aprobada</option>
                        <option value="shipped">Enviada</option>
                        <option value="delivered">Entregada</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="perPage" class="sr-only">Por página</label>
                <select wire:model.live="perPage" id="perPage" class="block w-full border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="10">10 por página</option>
                    <option value="25">25 por página</option>
                    <option value="50">50 por página</option>
                    <option value="100">100 por página</option>
                </select>
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            <div class="flex items-center space-x-1 cursor-pointer" wire:click="sortBy('order_number')">
                                <span>Número de Orden</span>
                                @if ($sortField === 'order_number')
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if ($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            <div class="flex items-center space-x-1 cursor-pointer" wire:click="sortBy('vendor_id')">
                                <span>Vendor</span>
                                @if ($sortField === 'vendor_id')
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if ($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            <div class="flex items-center space-x-1 cursor-pointer" wire:click="sortBy('status')">
                                <span>Estado</span>
                                @if ($sortField === 'status')
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if ($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            <div class="flex items-center space-x-1 cursor-pointer" wire:click="sortBy('order_date')">
                                <span>Fecha de Orden</span>
                                @if ($sortField === 'order_date')
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if ($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            <div class="flex items-center space-x-1 cursor-pointer" wire:click="sortBy('total')">
                                <span>Total</span>
                                @if ($sortField === 'total')
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if ($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchaseOrders as $order)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                {{ $order->order_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $order->vendor_id ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5
                                    {{ $order->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'delivered' ? 'bg-purple-100 text-purple-800' : '' }}
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $order->order_date ? $order->order_date->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $order->total ? number_format($order->total, 2) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                <a href="{{ route('purchase-orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                <a href="{{ route('purchase-orders.edit', $order->id) }}" class="ml-4 text-indigo-600 hover:text-indigo-900">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">
                                No se encontraron órdenes de compra
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div>
            {{ $purchaseOrders->links() }}
        </div>
    </div>
</div>
