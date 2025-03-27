@php
    $travelMethod = 'aéreo';
@endphp

<div class="space-y-8">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-[3.75rem]">
            <x-view-title>
                <x-slot:title>
                    {{ $purchaseOrder->order_number }}
                </x-slot:title>
            </x-view-title>

            <x-label class="bg-success">
                <span>En tránsito</span>

                <span>{{ $travelMethod }}</span>

                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path
                            d="M14.7873 2.34374C15.5913 1.51335 16.9196 1.5026 17.7369 2.31989C18.5318 3.11479 18.5466 4.39893 17.7703 5.21193L15.4547 7.63674C15.2732 7.8268 15.1825 7.92183 15.1265 8.03313C15.077 8.13166 15.0476 8.23903 15.0399 8.34903C15.0312 8.47328 15.0607 8.60131 15.1198 8.85738L16.5597 15.097C16.6204 15.3599 16.6507 15.4913 16.6409 15.6184C16.6323 15.7309 16.6008 15.8405 16.5485 15.9405C16.4895 16.0535 16.3941 16.1489 16.2033 16.3396L15.8944 16.6486C15.3892 17.1537 15.1367 17.4063 14.8782 17.452C14.6525 17.4919 14.4203 17.4371 14.2362 17.3005C14.0255 17.144 13.9125 16.8051 13.6866 16.1274L12.0118 11.103L9.22408 13.8908C9.05767 14.0572 8.97447 14.1404 8.91881 14.2384C8.86951 14.3252 8.8362 14.4202 8.82048 14.5187C8.80273 14.63 8.81572 14.747 8.84171 14.9809L8.9948 16.3587C9.02079 16.5926 9.03378 16.7095 9.01604 16.8208C9.00031 16.9194 8.967 17.0143 8.9177 17.1011C8.86204 17.1991 8.77884 17.2823 8.61243 17.4487L8.44785 17.6133C8.05363 18.0075 7.85652 18.2046 7.63748 18.2617C7.44536 18.3118 7.24167 18.2916 7.0631 18.2049C6.85951 18.1059 6.70488 17.874 6.39563 17.4101L5.0887 15.4497C5.03345 15.3668 5.00582 15.3254 4.97375 15.2878C4.94526 15.2544 4.91418 15.2233 4.8808 15.1949C4.84321 15.1628 4.80178 15.1352 4.7189 15.0799L2.7585 13.773C2.29462 13.4637 2.06269 13.3091 1.96375 13.1055C1.87698 12.9269 1.85681 12.7232 1.90688 12.5311C1.96396 12.3121 2.16107 12.115 2.55529 11.7208L2.71988 11.5562C2.88628 11.3898 2.96948 11.3066 3.06747 11.2509C3.15427 11.2016 3.24922 11.1683 3.34781 11.1526C3.45909 11.1348 3.57603 11.1478 3.80993 11.1738L5.18775 11.3269C5.42164 11.3529 5.53859 11.3659 5.64987 11.3481C5.74845 11.3324 5.84341 11.2991 5.93021 11.2498C6.02819 11.1941 6.1114 11.1109 6.2778 10.9445L9.06557 8.15676L4.04117 6.48196C3.36348 6.25606 3.02464 6.14311 2.86814 5.93236C2.73149 5.74832 2.67667 5.51613 2.7166 5.29041C2.76232 5.03192 3.01488 4.77936 3.52 4.27424L3.82897 3.96526C4.01972 3.77452 4.11509 3.67915 4.22809 3.62007C4.32809 3.56778 4.43768 3.53635 4.5502 3.52769C4.67733 3.5179 4.80875 3.54823 5.0716 3.60888L11.2875 5.04333C11.5458 5.10294 11.675 5.13274 11.7997 5.12387C11.9201 5.11531 12.0372 5.08071 12.1428 5.02244C12.2523 4.9621 12.3445 4.86687 12.5289 4.67643L14.7873 2.34374Z"
                            stroke="#F7F7F7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </x-slot:icon>
            </x-label>
        </div>

        <div class="flex space-x-4">
            <a href="{{ route('purchase-orders.edit', $purchaseOrder->id) }}" class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none"
                    class="absolute -translate-y-1/2 left-4 top-1/2">
                    <path
                        d="M1.87604 17.1159C1.92198 16.7024 1.94496 16.4957 2.00751 16.3025C2.06301 16.131 2.14143 15.9679 2.24064 15.8174C2.35246 15.6478 2.49955 15.5008 2.79373 15.2066L16 2.0003C17.1046 0.895732 18.8955 0.895734 20 2.0003C21.1046 3.10487 21.1046 4.89573 20 6.0003L6.79373 19.2066C6.49955 19.5008 6.35245 19.6479 6.18289 19.7597C6.03245 19.8589 5.86929 19.9373 5.69785 19.9928C5.5046 20.0553 5.29786 20.0783 4.88437 20.1243L1.5 20.5003L1.87604 17.1159Z"
                        stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <x-primary-button class="pl-12">Editar</x-primary-button>
            </a>
        </div>
    </div>

    <div class="flex max-w-[600px] justify-between gap-5 rounded-[0.625rem] bg-white p-4 text-xs">
        <div class="flex flex-col justify-between space-y-[0.875rem]">
            <x-label class="bg-danger">
                <span>Producto peligroso</span>

                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 20 18"
                        fill="none">
                        <path
                            d="M9.99979 6.50019V9.83353M9.99979 13.1669H10.0081M8.84588 2.24329L1.99181 14.0821C1.61164 14.7388 1.42156 15.0671 1.44965 15.3366C1.47416 15.5716 1.5973 15.7852 1.78843 15.9242C2.00756 16.0835 2.38695 16.0835 3.14572 16.0835H16.8539C17.6126 16.0835 17.992 16.0835 18.2111 15.9242C18.4023 15.7852 18.5254 15.5716 18.5499 15.3366C18.578 15.0671 18.3879 14.7388 18.0078 14.0821L11.1537 2.24329C10.7749 1.58899 10.5855 1.26184 10.3384 1.15196C10.1228 1.05612 9.87675 1.05612 9.6612 1.15196C9.4141 1.26184 9.22469 1.58899 8.84588 2.24329Z"
                            stroke="#F7F7F7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </x-slot:icon>
            </x-label>

            <x-label class="bg-[#E0E5FF] py-[0.625rem] text-neutral-blue">
                <p class="text-base">HUB: <span>{{ $purchaseOrder->actualHub->name }}</span></p>

                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19"
                        fill="none">
                        <path
                            d="M5.66667 14.1663H12.3333M8.18141 2.30297L2.52949 6.6989C2.15168 6.99275 1.96278 7.13968 1.82669 7.32368C1.70614 7.48667 1.61633 7.67029 1.56169 7.86551C1.5 8.0859 1.5 8.32521 1.5 8.80384V14.833C1.5 15.7664 1.5 16.2331 1.68166 16.5896C1.84144 16.9032 2.09641 17.1582 2.41002 17.318C2.76654 17.4996 3.23325 17.4996 4.16667 17.4996H13.8333C14.7668 17.4996 15.2335 17.4996 15.59 17.318C15.9036 17.1582 16.1586 16.9032 16.3183 16.5896C16.5 16.2331 16.5 15.7664 16.5 14.833V8.80384C16.5 8.32521 16.5 8.0859 16.4383 7.86551C16.3837 7.67029 16.2939 7.48667 16.1733 7.32368C16.0372 7.13968 15.8483 6.99275 15.4705 6.69891L9.81859 2.30297C9.52582 2.07526 9.37943 1.9614 9.21779 1.91763C9.07516 1.87902 8.92484 1.87902 8.78221 1.91763C8.62057 1.9614 8.47418 2.07526 8.18141 2.30297Z"
                            stroke="#7288FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </x-slot:icon>
            </x-label>
        </div>

        <x-weight-card>
            <x-slot:weight>
                {{ $purchaseOrderDetails->weight_kg }}
            </x-slot:weight>
            <x-slot:height>
                {{ intval($purchaseOrderDetails->height) }}
            </x-slot:height>
            <x-slot:width>
                {{ intval($purchaseOrderDetails->width) }}
            </x-slot:width>
            <x-slot:length>
                {{ intval($purchaseOrderDetails->length) }}
            </x-slot:length>
        </x-weight-card>

        <x-date-card>
            <x-slot:recolectaTime>
                1/1/2014
            </x-slot:recolectaTime>
            <x-slot:leadTime>
                1/1/2014
            </x-slot:leadTime>
            <x-slot:pickupTime>
                1/1/2014
            </x-slot:pickupTime>
        </x-date-card>
    </div>

    <div class="space-y-[1.875rem]" x-data="{
        activeTab: 'tab1'
    }">
        <!-- Selector de pestañas -->
        <div class="flex items-center gap-6 text-lg font-bold">
            <button @click="activeTab = 'tab1'"
                :class="activeTab === 'tab1' ? 'border-dark-blue text-dark-blue' : 'border-transparent'"
                class="border-b-2 py-[0.625rem]">
                Información general
            </button>
            <button @click="activeTab = 'tab2'"
                :class="activeTab === 'tab2' ? 'border-dark-blue text-dark-blue' : 'border-transparent'"
                class="border-b-2 py-[0.625rem]">
                Comparación de costos
            </button>
            <button @click="activeTab = 'tab3'"
                :class="activeTab === 'tab3' ? 'border-dark-blue text-dark-blue' : 'border-transparent'"
                class="border-b-2 py-[0.625rem]">
                Costos y ahorros
            </button>
            <button @click="activeTab = 'tab4'"
                :class="activeTab === 'tab4' ? 'border-dark-blue text-dark-blue' : 'border-transparent'"
                class="border-b-2 py-[0.625rem]">
                Histórico
            </button>

            <x-dropdown alignmentClasses="rounded-[1.25rem]"
                contentClasses="rounded-[1.25rem] shadow-lg px-[1.125rem] py-[0.625rem] bg-white">
                <x-slot:trigger>
                    <button class="rounded-[0.375rem] px-2 py-4 transition-colors duration-500 hover:bg-[#DDDDDD]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4"
                            fill="none">
                            <path
                                d="M4 2C4 2.53043 3.78929 3.03914 3.41421 3.41421C3.03914 3.78929 2.53043 4 2 4C1.46957 4 0.960859 3.78929 0.585786 3.41421C0.210714 3.03914 0 2.53043 0 2C0 1.46957 0.210714 0.96086 0.585786 0.585787C0.960859 0.210714 1.46957 0 2 0C2.53043 0 3.03914 0.210714 3.41421 0.585787C3.78929 0.96086 4 1.46957 4 2ZM11 2C11 2.53043 10.7893 3.03914 10.4142 3.41421C10.0391 3.78929 9.53043 4 9 4C8.46957 4 7.96086 3.78929 7.58579 3.41421C7.21071 3.03914 7 2.53043 7 2C7 1.46957 7.21071 0.96086 7.58579 0.585787C7.96086 0.210714 8.46957 0 9 0C9.53043 0 10.0391 0.210714 10.4142 0.585787C10.7893 0.96086 11 1.46957 11 2ZM18 2C18 2.53043 17.7893 3.03914 17.4142 3.41421C17.0391 3.78929 16.5304 4 16 4C15.4696 4 14.9609 3.78929 14.5858 3.41421C14.2107 3.03914 14 2.53043 14 2C14 1.46957 14.2107 0.96086 14.5858 0.585787C14.9609 0.210714 15.4696 0 16 0C16.5304 0 17.0391 0.210714 17.4142 0.585787C17.7893 0.96086 18 1.46957 18 2Z"
                                class="fill-dark-blue" />
                        </svg>
                    </button>
                </x-slot:trigger>

                <x-slot:content>
                    <ul class="space-y-2 text-base font-normal text-[#2e2e2e]">
                        <li class="rounded-[0.25rem] px-2 py-1 hover:bg-[#EEF0FF]">
                            <button>Ocultar celdas</button>
                        </li>
                        <li class="rounded-[0.25rem] px-2 py-1 hover:bg-[#EEF0FF]">
                            <button>Mostrar celdas</button>
                        </li>
                    </ul>
                </x-slot:content>
            </x-dropdown>
        </div>

        <!-- Contenido de las pestañas -->
        <div>
            <div x-show="activeTab === 'tab1'" x-transition class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer"
                                wire:click="sortBy('material_id')">
                                Material ID
                                @if ($sortField === 'material_id')
                                    @if ($sortDirection === 'asc')
                                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer"
                                wire:click="sortBy('description')">
                                Descripción
                                @if ($sortField === 'description')
                                    @if ($sortDirection === 'asc')
                                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer"
                                wire:click="sortBy('quantity')">
                                Cantidad
                                @if ($sortField === 'quantity')
                                    @if ($sortDirection === 'asc')
                                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer"
                                wire:click="sortBy('price_per_unit')">
                                Precio unitario
                                @if ($sortField === 'price_per_unit')
                                    @if ($sortDirection === 'asc')
                                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer"
                                wire:click="sortBy('subtotal')">
                                Subtotal
                                @if ($sortField === 'subtotal')
                                    @if ($sortDirection === 'asc')
                                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orderProducts as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $product['material_id'] }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $product['description'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product['quantity'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ number_format($product['price_per_unit'], 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ number_format($product['subtotal'], 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No se encontraron materiales para esta orden de compra
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="4" class="px-6 py-4 text-sm font-medium text-right text-gray-900">
                                Subtotal:
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ number_format($net_total, 2) }}
                            </td>
                            <td></td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td colspan="4" class="px-6 py-4 text-sm font-medium text-right text-gray-900">
                                Costos adicionales:
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ number_format($additional_cost, 2) }}
                            </td>
                            <td></td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td colspan="4" class="px-6 py-4 text-sm font-medium text-right text-gray-900">
                                Seguro:
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ number_format($insurance_cost, 2) }}
                            </td>
                            <td></td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td colspan="4" class="px-6 py-4 text-sm font-bold text-right text-gray-900">
                                Total:
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                {{ number_format($total, 2) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div x-show="activeTab === 'tab2'" x-transition class="space-y-[1.875rem]">
                <div class="flex justify-between items-centers">
                    <x-search-input class="w-64" />

                    <div class="flex gap-4">
                        <x-primary-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 20 20" fill="none">
                                <path
                                    d="M18.453 10.8927C18.1752 13.5026 16.6964 15.9483 14.2494 17.3611C10.1839 19.7083 4.98539 18.3153 2.63818 14.2499L2.38818 13.8168M1.54613 9.10664C1.82393 6.49674 3.30272 4.05102 5.74971 2.63825C9.8152 0.29104 15.0137 1.68398 17.3609 5.74947L17.6109 6.18248M1.49316 16.0657L2.22521 13.3336L4.95727 14.0657M15.0424 5.93364L17.7744 6.66569L18.5065 3.93364"
                                    stroke="#F7F7F7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="disabled:stroke-[#C2C2C2]" />
                            </svg>
                        </x-primary-button>

                        <x-secondary-button class="flex items-center gap-[0.625rem]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="22"
                                viewBox="0 0 21 22" fill="none">
                                <path
                                    d="M19.1527 9.89994L10.1371 18.9156C8.08686 20.9658 4.76275 20.9658 2.71249 18.9156C0.662241 16.8653 0.662242 13.5412 2.71249 11.4909L11.7281 2.47532C13.0949 1.10849 15.311 1.10849 16.6779 2.47532C18.0447 3.84216 18.0447 6.05823 16.6779 7.42507L8.01579 16.0871C7.33238 16.7705 6.22434 16.7705 5.54092 16.0871C4.8575 15.4037 4.8575 14.2957 5.54092 13.6123L13.1423 6.01086"
                                    stroke="#565AFF" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                            <span>Adjuntar costos</span>
                        </x-secondary-button>
                    </div>
                </div>

                {{-- Añadir tabla --}}
            </div>

            <div x-show="activeTab === 'tab3'" x-transition>
                {{-- Añadir tabla --}}
            </div>

            <div x-show="activeTab === 'tab4'" x-transition>
                <div class="flex justify-between items-centers">
                    <x-search-input class="w-64" />

                    <div class="flex gap-4">
                        <x-primary-button class="group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 20 20" fill="none">
                                <path
                                    d="M18.453 10.8927C18.1752 13.5026 16.6964 15.9483 14.2494 17.3611C10.1839 19.7083 4.98539 18.3153 2.63818 14.2499L2.38818 13.8168M1.54613 9.10664C1.82393 6.49674 3.30272 4.05102 5.74971 2.63825C9.8152 0.29104 15.0137 1.68398 17.3609 5.74947L17.6109 6.18248M1.49316 16.0657L2.22521 13.3336L4.95727 14.0657M15.0424 5.93364L17.7744 6.66569L18.5065 3.93364"
                                    stroke="#F7F7F7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="transition-colors duration-500 group-disabled:stroke-[#C2C2C2]" />
                            </svg>
                        </x-primary-button>

                        <x-secondary-button class="group flex items-center gap-[0.625rem]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="22"
                                viewBox="0 0 21 22" fill="none">
                                <path
                                    d="M19.1527 9.89994L10.1371 18.9156C8.08686 20.9658 4.76275 20.9658 2.71249 18.9156C0.662241 16.8653 0.662242 13.5412 2.71249 11.4909L11.7281 2.47532C13.0949 1.10849 15.311 1.10849 16.6779 2.47532C18.0447 3.84216 18.0447 6.05823 16.6779 7.42507L8.01579 16.0871C7.33238 16.7705 6.22434 16.7705 5.54092 16.0871C4.8575 15.4037 4.8575 14.2957 5.54092 13.6123L13.1423 6.01086"
                                    stroke="#565AFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="transition-colors duration-500 group-hover:stroke-dark-blue group-active:stroke-neutral-blue group-disabled:stroke-[#C2C2C2]" />
                            </svg>

                            <span>Adjuntar documentación</span>
                        </x-secondary-button>
                    </div>
                </div>

                {{-- Añadir tabla --}}
            </div>
        </div>
    </div>
</div>
