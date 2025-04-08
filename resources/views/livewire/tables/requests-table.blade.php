<div>
    @php
        $headers = [
            'order_number' => 'Número de Orden',
            'document_type' => 'Tipo de Documento',
            'vendor_name' => 'Proveedor',
            'document_path' => 'Documento',
            'status' => 'Estado',
            'created_at' => 'Fecha de Solicitud',
            'actions' => 'Acciones',
            'actions_html' => '',
        ];

        $sortable = ['order_number', 'document_type', 'vendor_name', 'status', 'created_at'];
        $searchable = ['order_number', 'document_type', 'vendor_name', 'status'];
        $filterable = ['document_type', 'status'];
        $filterOptions = [
            'document_type' => [
                'invoice' => 'Factura',
                'packing_list' => 'Lista de Empaque',
                'bill_of_lading' => 'Conocimiento de Embarque',
                'other' => 'Otro'
            ],
            'status' => [
                'pending' => 'Pendiente',
                'approved' => 'Aprobado',
                'rejected' => 'Rechazado'
            ]
        ];

        $lastPo = App\Models\PurchaseOrder::orderBy('id', 'desc')->first() ?? null;
    @endphp

    <div class="mt-8 space-y-4">
        <div class="flex items-center justify-between mb-6">
            <x-search-input class="w-64" wire:model.debounce.300ms="search" placeholder="Buscar comentarios o archivos..." />
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#E0E5FF]">
                    <tr>
                        @foreach($headers as $key => $label)
                            @if($key != 'actions_html')
                                <th class="px-6 py-6 text-xs font-bold tracking-wider text-left text-black uppercase">
                                    {{ $label }}
                                </th>
                            @endif
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    <!-- Documento 3: Conocimiento de embarque pendiente -->
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                            <a href="{{ route('purchase-orders.detail', $lastPo->id ?? 0) }}" class="text-indigo-600 hover:text-indigo-900">PO-2023-0003</a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Conocimiento de Embarque</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Textiles Modernos S.A.</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">BillOfLading-TM-4532.pdf</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                Pendiente
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">2023-11-18</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            @if($actions)
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900">Aprobar</button>
                                    <button class="text-red-600 hover:text-red-900">Rechazar</button>
                                </div>
                            @else
                                <button class="text-blue-600 hover:text-blue-900">Ver detalles</button>
                            @endif
                        </td>
                    </tr>

                    <!-- Documento 4: Factura aprobada -->
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                            <a href="{{ route('purchase-orders.detail', $lastPo->id ?? 0) }}" class="text-indigo-600 hover:text-indigo-900">PO-2023-0004</a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Factura</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Químicos Especiales S.A.</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Factura-QE-8743.pdf</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                Aprobado
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">2023-11-10</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            @if($actions)
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900">Aprobar</button>
                                    <button class="text-red-600 hover:text-red-900">Rechazar</button>
                                </div>
                            @else
                                <button class="text-blue-600 hover:text-blue-900">Ver detalles</button>
                            @endif
                        </td>
                    </tr>

                    <!-- Documento 5: Lista de empaque rechazada -->
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                            <a href="{{ route('purchase-orders.detail', $lastPo->id ?? 0) }}" class="text-indigo-600 hover:text-indigo-900">PO-2023-0005</a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Lista de Empaque</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Maquinaria Industrial S.A.</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">PackingList-MI-9901.pdf</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                Rechazado
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">2023-11-08</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            @if($actions)
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900">Aprobar</button>
                                    <button class="text-red-600 hover:text-red-900">Rechazar</button>
                                </div>
                            @else
                                <button class="text-blue-600 hover:text-blue-900">Ver detalles</button>
                            @endif
                        </td>
                    </tr>

                    <!-- Documento 6: Otro tipo de documento pendiente -->
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                            <a href="{{ route('purchase-orders.detail', $lastPo->id ?? 0) }}" class="text-indigo-600 hover:text-indigo-900">PO-2023-0006</a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Otro</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Productos Médicos S.A.</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Certificado-PM-3401.pdf</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                Pendiente
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">2023-11-19</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            @if($actions)
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900">Aprobar</button>
                                    <button class="text-red-600 hover:text-red-900">Rechazar</button>
                                </div>
                            @else
                                <button class="text-blue-600 hover:text-blue-900">Ver detalles</button>
                            @endif
                        </td>
                    </tr>

                    <!-- Documento 7: Certificado de origen pendiente -->
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                            <a href="{{ route('purchase-orders.detail', $lastPo->id ?? 0) }}" class="text-indigo-600 hover:text-indigo-900">PO-2023-0007</a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Otro</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Equipos Agrícolas S.A.</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900">CertificadoOrigen-EA-5523.pdf</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                Pendiente
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">2023-11-20</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            @if($actions)
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900">Aprobar</button>
                                    <button class="text-red-600 hover:text-red-900">Rechazar</button>
                                </div>
                            @else
                                <button class="text-blue-600 hover:text-blue-900">Ver detalles</button>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
