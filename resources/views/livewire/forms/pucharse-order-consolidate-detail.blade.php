<div>

    <div class="flex items-center justify-between mb-8">
        <div class="flex items-start gap-[3.75rem]">
            <x-view-title :title="$shippingDocument->document_number ?? 'Documento de embarque'" titleClass="font-dm-sans text-[2.5rem] font-medium leading-none pb-1"
                :subtitle="'Órdenes consolidadas: ' . $poCount" subtitleClass="font-dm-sans font-medium" />
            <div class="flex items-center gap-[0.375rem] rounded-md bg-[#D8D8D8] px-2 py-[0.375rem]">
                <span>Estado: </span>
                <span>{{ $shippingDocument->status ?? 'Pendiente' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 20 18" fill="none">
                    <path
                        d="M13.3334 7.33333H16.6667C17.1087 7.33333 17.5326 7.50893 17.8452 7.82149C18.1578 8.13405 18.3334 8.55797 18.3334 9C18.3334 9.44203 18.1578 9.86595 17.8452 10.1785C17.5326 10.4911 17.1087 10.6667 16.6667 10.6667H13.3334L10 16.5H7.50002L9.16669 10.6667H5.83335L4.16669 12.3333H1.66669L3.33335 9L1.66669 5.66667H4.16669L5.83335 7.33333H9.16669L7.50002 1.5H10L13.3334 7.33333Z"
                        stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>

        <div class="flex space-x-4">
            <a href="#" class="cursor-pointer">
                <x-black-btn>Editar Documento</x-black-btn>
            </a>
        </div>
    </div>

    <div class="flex max-w-[600px] justify-between gap-5 rounded-[0.625rem] bg-white p-4 text-xs mb-8">
        <div class="flex flex-col justify-between space-y-[0.875rem]">
            <div class="flex items-center justify-between gap-2 rounded-[0.375rem] bg-[#FFE5D3] p-2">
                <span>Documento de embarque</span>

                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16"
                    fill="none">
                    <path d="M16 2H11.82C11.4 0.84 10.3 0 9 0C7.7 0 6.6 0.84 6.18 2H2C0.9 2 0 2.9 0 4V14C0 15.1 0.9 16 2 16H16C17.1 16 18 15.1 18 14V4C18 2.9 17.1 2 16 2ZM9 2C9.55 2 10 2.45 10 3C10 3.55 9.55 4 9 4C8.45 4 8 3.55 8 3C8 2.45 8.45 2 9 2ZM11 12H4V10H11V12ZM14 9H4V7H14V9Z" fill="#0369A1"/>
                </svg>
            </div>

            <div class="mb-4 flex items-center justify-between gap-2 rounded-[0.375rem] bg-[#E9E9E9] p-2">
                <p>HUB: <span>{{ $shippingDocument->hub_location ?? 'No especificado' }}</span></p>

                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16"
                    fill="none">
                    <path
                        d="M0.666992 13.8333V4.95833C0.666992 4.61111 0.760881 4.29861 0.948659 4.02083C1.13644 3.74306 1.38977 3.54167 1.70866 3.41667L8.37533 0.75C8.56977 0.666667 8.7781 0.625 9.00033 0.625C9.22255 0.625 9.43088 0.666667 9.62533 0.75L16.292 3.41667C16.6114 3.54167 16.865 3.74306 17.0528 4.02083C17.2406 4.29861 17.3342 4.61111 17.3337 4.95833V13.8333C17.3337 14.2917 17.1706 14.6842 16.8445 15.0108C16.5184 15.3375 16.1259 15.5006 15.667 15.5H12.3337V8.83333H5.66699V15.5H2.33366C1.87533 15.5 1.4831 15.3369 1.15699 15.0108C0.830881 14.6847 0.667548 14.2922 0.666992 13.8333ZM6.50033 15.5V13.8333H8.16699V15.5H6.50033ZM8.16699 13V11.3333H9.83366V13H8.16699ZM9.83366 15.5V13.8333H11.5003V15.5H9.83366Z"
                        fill="black" />
                </svg>
            </div>
        </div>

        <div class="flex gap-2 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 18 20" fill="none">
                <path
                    d="M5.625 10.25C5.32663 10.25 5.04048 10.3685 4.8295 10.5795C4.61853 10.7905 4.5 11.0766 4.5 11.375C4.5 11.6734 4.61853 11.9595 4.8295 12.1705C5.04048 12.3815 5.32663 12.5 5.625 12.5C5.92337 12.5 6.20952 12.3815 6.4205 12.1705C6.63147 11.9595 6.75 11.6734 6.75 11.375C6.75 11.0766 6.63147 10.7905 6.4205 10.5795C6.20952 10.3685 5.92337 10.25 5.625 10.25ZM7.875 11.375C7.875 11.0766 7.99353 10.7905 8.2045 10.5795C8.41548 10.3685 8.70163 10.25 9 10.25H12.375C12.6734 10.25 12.9595 10.3685 13.1705 10.5795C13.3815 10.7905 13.5 11.0766 13.5 11.375C13.5 11.6734 13.3815 11.9595 13.1705 12.1705C12.9595 12.3815 12.6734 12.5 12.375 12.5H9C8.70163 12.5 8.41548 12.3815 8.2045 12.1705C7.99353 11.9595 7.875 11.6734 7.875 11.375ZM5.625 13.25C5.32663 13.25 5.04048 13.3685 4.8295 13.5795C4.61853 13.7905 4.5 14.0766 4.5 14.375C4.5 14.6734 4.61853 14.9595 4.8295 15.1705C5.04048 15.3815 5.32663 15.5 5.625 15.5H9C9.29837 15.5 9.58452 15.3815 9.79549 15.1705C10.0065 14.9595 10.125 14.6734 10.125 14.375C10.125 14.0766 10.0065 13.7905 9.79549 13.5795C9.58452 13.3685 9.29837 13.25 9 13.25H5.625Z"
                    fill="black" />
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M4.125 0.5C3.82663 0.5 3.54048 0.618526 3.3295 0.829505C3.11853 1.04048 3 1.32663 3 1.625V3.5C2.20435 3.5 1.44129 3.81607 0.87868 4.37868C0.31607 4.94129 0 5.70435 0 6.5V17C0 17.7956 0.31607 18.5587 0.87868 19.1213C1.44129 19.6839 2.20435 20 3 20H15C15.7956 20 16.5587 19.6839 17.1213 19.1213C17.6839 18.5587 18 17.7956 18 17V6.5C18 5.70435 17.6839 4.94129 17.1213 4.37868C16.5587 3.81607 15.7956 3.5 15 3.5V1.625C15 1.32663 14.8815 1.04048 14.6705 0.829505C14.4595 0.618526 14.1734 0.5 13.875 0.5C13.5766 0.5 13.2905 0.618526 13.0795 0.829505C12.8685 1.04048 12.75 1.32663 12.75 1.625V3.5H5.25V1.625C5.25 1.32663 5.13147 1.04048 4.9205 0.829505C4.70952 0.618526 4.42337 0.5 4.125 0.5ZM2.25 9.5C2.25 9.10218 2.40804 8.72064 2.68934 8.43934C2.97064 8.15804 3.35218 8 3.75 8H14.25C14.6478 8 15.0294 8.15804 15.3107 8.43934C15.592 8.72064 15.75 9.10218 15.75 9.5V16.25C15.75 16.6478 15.592 17.0294 15.3107 17.3107C15.0294 17.592 14.6478 17.75 14.25 17.75H3.75C3.35218 17.75 2.97064 17.592 2.68934 17.3107C2.40804 17.0294 2.25 16.6478 2.25 16.25V9.5Z"
                    fill="black" />
            </svg>

            <div class="space-y-1">
                <p>Creación: <span>{{ $shippingDocument->creation_date ? $shippingDocument->creation_date->format('d/m/Y') : 'N/A' }}</span></p>
                <p>Salida estimada: <span>{{ $shippingDocument->estimated_departure_date ? $shippingDocument->estimated_departure_date->format('d/m/Y') : 'N/A' }}</span></p>
                <p>Llegada estimada: <span>{{ $shippingDocument->estimated_arrival_date ? $shippingDocument->estimated_arrival_date->format('d/m/Y') : 'N/A' }}</span></p>
            </div>
        </div>

        <div class="flex gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 16 18" fill="none">
                <path
                    d="M2.00016 16H14.0002L12.5752 6H3.42517L2.00016 16ZM8.00016 4C8.2835 4 8.52116 3.904 8.71317 3.712C8.90517 3.52 9.00083 3.28267 9.00016 3C8.9995 2.71733 8.9035 2.48 8.71216 2.288C8.52083 2.096 8.2835 2 8.00016 2C7.71683 2 7.4795 2.096 7.28817 2.288C7.09683 2.48 7.00083 2.71733 7.00016 3C6.9995 3.28267 7.0955 3.52033 7.28817 3.713C7.48083 3.90567 7.71816 4.00133 8.00016 4ZM10.8252 4H12.5752C13.0752 4 13.5085 4.16667 13.8752 4.5C14.2418 4.83333 14.4668 5.24167 14.5502 5.725L15.9752 15.725C16.0585 16.325 15.9045 16.8543 15.5132 17.313C15.1218 17.7717 14.6175 18.0007 14.0002 18H2.00016C1.3835 18 0.879165 17.771 0.487165 17.313C0.0951649 16.855 -0.058835 16.3257 0.025165 15.725L1.45016 5.725C1.5335 5.24167 1.7585 4.83333 2.12516 4.5C2.49183 4.16667 2.92517 4 3.42517 4H5.17517C5.12516 3.83333 5.0835 3.671 5.05017 3.513C5.01683 3.355 5.00016 3.184 5.00016 3C5.00016 2.16667 5.29183 1.45833 5.87516 0.875C6.4585 0.291667 7.16683 0 8.00016 0C8.8335 0 9.54183 0.291667 10.1252 0.875C10.7085 1.45833 11.0002 2.16667 11.0002 3C11.0002 3.18333 10.9835 3.35433 10.9502 3.513C10.9168 3.67167 10.8752 3.834 10.8252 4Z"
                    fill="black" />
            </svg>

            <div>
                <p>Carga total: <span>{{ number_format($totalWeight, 0) }} kg</span></p>
                <p>Compañía: <span>{{ $shippingDocument->company->name ?? 'N/A' }}</span></p>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-10 text-sm w-fit">
        <ul class="flex">
            <li>
                <button class="px-3 py-[0.375rem]">Información General</button>
            </li>
            <li>
                <button class="px-3 py-[0.375rem]">Órdenes relacionadas</button>
            </li>
            <li>
                <button class="rounded-[0.188rem] bg-white px-3 py-[0.375rem]">Histórico</button>
            </li>
        </ul>

        <button class="ml-auto block rounded-[0.375rem] bg-[#DDDDDD] px-2 py-4">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4" fill="none">
                    <path
                        d="M4 2C4 2.53043 3.78929 3.03914 3.41421 3.41421C3.03914 3.78929 2.53043 4 2 4C1.46957 4 0.960859 3.78929 0.585786 3.41421C0.210714 3.03914 0 2.53043 0 2C0 1.46957 0.210714 0.96086 0.585786 0.585787C0.960859 0.210714 1.46957 0 2 0C2.53043 0 3.03914 0.210714 3.41421 0.585787C3.78929 0.96086 4 1.46957 4 2ZM11 2C11 2.53043 10.7893 3.03914 10.4142 3.41421C10.0391 3.78929 9.53043 4 9 4C8.46957 4 7.96086 3.78929 7.58579 3.41421C7.21071 3.03914 7 2.53043 7 2C7 1.46957 7.21071 0.96086 7.58579 0.585787C7.96086 0.210714 8.46957 0 9 0C9.53043 0 10.0391 0.210714 10.4142 0.585787C10.7893 0.96086 11 1.46957 11 2ZM18 2C18 2.53043 17.7893 3.03914 17.4142 3.41421C17.0391 3.78929 16.5304 4 16 4C15.4696 4 14.9609 3.78929 14.5858 3.41421C14.2107 3.03914 14 2.53043 14 2C14 1.46957 14.2107 0.96086 14.5858 0.585787C14.9609 0.210714 15.4696 0 16 0C16.5304 0 17.0391 0.210714 17.4142 0.585787C17.7893 0.96086 18 1.46957 18 2Z"
                        fill="black" />
                </svg>
            </span>
        </button>
    </div>


    {{-- Lista --}}
    <div class="mt-8">
        @php
            // Default values for sorting to avoid undefined variable errors
            $sortField = $sortField ?? 'po_number';
            $sortDirection = $sortDirection ?? 'asc';
        @endphp

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer" wire:click="sortBy('po_number')">
                            Número de PO
                            @if ($sortField === 'po_number')
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
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer" wire:click="sortBy('supplier')">
                            Proveedor
                            @if ($sortField === 'supplier')
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
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer" wire:click="sortBy('items_count')">
                            Cant. Items
                            @if ($sortField === 'items_count')
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
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer" wire:click="sortBy('total_amount')">
                            Total
                            @if ($sortField === 'total_amount')
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
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer" wire:click="sortBy('status')">
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
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($relatedPurchaseOrders ?? [] as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $order['po_number'] }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $order['supplier'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order['items_count'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ number_format($order['total_amount'], 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-{{ $order['status_color'] }}-800 bg-{{ $order['status_color'] }}-100 rounded-full">
                                    {{ $order['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                <a href="{{ route('purchase-orders.show', $order['id']) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No se encontraron órdenes de compra relacionadas con este documento de embarque
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <td colspan="3" class="px-6 py-4 text-sm font-bold text-right text-gray-900">
                            Total consolidado:
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">
                            {{ number_format($totalConsolidated ?? 0, 2) }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
