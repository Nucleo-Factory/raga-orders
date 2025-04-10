<div class="p-[32px] bg-white rounded-lg">
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
                class="hidden px-4 py-2 ml-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-5 text-xs font-bold tracking-wider text-left text-black uppercase cursor-pointer" wire:click="sortBy('creation_date')">
                        Modalidad
                    </th>
                    <th scope="col" class="px-6 py-5 text-xs font-bold tracking-wider text-left text-black uppercase cursor-pointer" wire:click="sortBy('status')">
                        Intercom
                    </th>

                    <th scope="col" class="px-6 py-5 text-xs font-bold tracking-wider text-left text-black uppercase cursor-pointer" wire:click="sortBy('status')">
                        Vendedor
                    </th>

                    <th scope="col" class="px-6 py-5 text-xs font-bold tracking-wider text-left text-black uppercase cursor-pointer" wire:click="sortBy('status')">
                        Hub
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
                            <div class="text-sm font-medium text-gray-900">
                                <a href="{{ route('purchase-orders.consolidated-order-detail', $document->id) }}" class="text-blue-600 underline hover:text-blue-900">
                                    {{ $document->document_number }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @if(isset($groupedPurchaseOrders[$document->id]))
                                    @foreach($groupedPurchaseOrders[$document->id] as $order)
                                        <a href="{{ route('purchase-orders.consolidated-order-detail', $order->id) }}" class="text-blue-600 underline hover:text-blue-900">
                                            {{ $order->order_number }}
                                        </a>  / <br/>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
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
                                    <div class="flex items-center justify-between bg-[#FEAE33] text-white px-3 py-1.5 rounded-md">
                                        <span class="font-medium">En proceso</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <circle cx="12" cy="12" r="10" stroke-width="2" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
                                        </svg>
                                    </div>
                                @elseif($document->status === 'delivered')
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-indigo-800 bg-indigo-100 rounded-full">
                                        Entregado
                                    </span>
                                @else
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 rounded-full">
                                        Desconocido
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <!-- Terrestre -->
                            <div class="flex items-center bg-[#565AFF] text-white px-3 py-1.5 rounded-md gap-2">
                                <span class="font-medium">Terrestre</span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.6667 5.83398H13.6145C13.8183 5.83398 13.9202 5.83398 14.0161 5.85701C14.1011 5.87742 14.1824 5.91109 14.257 5.95678C14.3411 6.00832 14.4131 6.08038 14.5573 6.22451L17.9429 9.61013C18.087 9.75425 18.1591 9.82632 18.2106 9.91041C18.2563 9.98497 18.29 10.0663 18.3104 10.1513C18.3334 10.2472 18.3334 10.3491 18.3334 10.5529V12.9173C18.3334 13.3056 18.3334 13.4997 18.27 13.6529C18.1854 13.8571 18.0232 14.0193 17.819 14.1039C17.6658 14.1673 17.4717 14.1673 17.0834 14.1673M12.9167 14.1673H11.6667M11.6667 14.1673V6.00065C11.6667 5.06723 11.6667 4.60052 11.4851 4.244C11.3253 3.9304 11.0703 3.67543 10.7567 3.51564C10.4002 3.33398 9.9335 3.33398 9.00008 3.33398H4.33342C3.39999 3.33398 2.93328 3.33398 2.57676 3.51564C2.26316 3.67543 2.00819 3.9304 1.8484 4.244C1.66675 4.60052 1.66675 5.06723 1.66675 6.00065V12.5007C1.66675 13.4211 2.41294 14.1673 3.33341 14.1673M11.6667 14.1673H8.33342M8.33342 14.1673C8.33342 15.548 7.21413 16.6673 5.83341 16.6673C4.4527 16.6673 3.33341 15.548 3.33341 14.1673M8.33342 14.1673C8.33342 12.7866 7.21413 11.6673 5.83341 11.6673C4.4527 11.6673 3.33341 12.7866 3.33341 14.1673M17.0834 14.584C17.0834 15.7346 16.1507 16.6673 15.0001 16.6673C13.8495 16.6673 12.9167 15.7346 12.9167 14.584C12.9167 13.4334 13.8495 12.5007 15.0001 12.5007C16.1507 12.5007 17.0834 13.4334 17.0834 14.584Z" stroke="#F7F7F7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $groupedPurchaseOrders[$document->id]->first()->incoterms }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $groupedPurchaseOrders[$document->id]->first()->vendor->name }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $groupedPurchaseOrders[$document->id]->first()->shipTo->name }}
                        </td>

                        <td class="text-sm font-medium whitespace-nowrap">
                            <a href="{{ route('purchase-orders.consolidated-order-detail', $document->id) }}" class="flex items-center justify-center text-indigo-600 hover:text-indigo-900">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1.61342 8.4761C1.52262 8.33234 1.47723 8.26046 1.45182 8.1496C1.43273 8.06632 1.43273 7.93498 1.45182 7.85171C1.47723 7.74084 1.52262 7.66896 1.61341 7.5252C2.36369 6.33721 4.59693 3.33398 8.00027 3.33398C11.4036 3.33398 13.6369 6.33721 14.3871 7.5252C14.4779 7.66896 14.5233 7.74084 14.5487 7.85171C14.5678 7.93498 14.5678 8.06632 14.5487 8.1496C14.5233 8.26046 14.4779 8.33234 14.3871 8.4761C13.6369 9.66409 11.4036 12.6673 8.00027 12.6673C4.59693 12.6673 2.36369 9.66409 1.61342 8.4761Z" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.00027 10.0007C9.10484 10.0007 10.0003 9.10522 10.0003 8.00065C10.0003 6.89608 9.10484 6.00065 8.00027 6.00065C6.8957 6.00065 6.00027 6.89608 6.00027 8.00065C6.00027 9.10522 6.8957 10.0007 8.00027 10.0007Z" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
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
