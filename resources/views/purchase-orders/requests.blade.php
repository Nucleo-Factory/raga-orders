@php
    $etapaArray = ["e1" => "Etapa 1", "e2" => "Etapa 2"];
@endphp

<x-app-layout>
    <div class="flex items-center justify-between">
        <x-view-title>
            <x-slot:title>
                Solicitudes y aprobaciones
            </x-slot:title>

            <x-slot:content>
                Gestiona todas las solicitudes y aprobaciones
            </x-slot:content>
        </x-view-title>
    </div>

    @if (session('message'))
        <div class="px-4 py-2 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabs for switching between views -->
    <div x-data="{ activeTab: 'approval_requests' }" class="mb-6">
        <div class="border-b border-gray-200">
            <ul class="flex -mb-px">
                <li class="mr-2">
                    <button
                        @click="activeTab = 'approval_requests'"
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'approval_requests', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'approval_requests' }"
                        class="inline-block p-4 font-medium border-b-2"
                    >
                        Solicitudes de Aprobación
                    </button>
                </li>
                <li class="mr-2">
                    <button
                        @click="activeTab = 'document_history'"
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'document_history', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'document_history' }"
                        class="inline-block p-4 font-medium border-b-2"
                    >
                        Historial de aprobaciones
                    </button>
                </li>
            </ul>
        </div>

        <!-- Approval Requests View -->
        <div x-show="activeTab === 'approval_requests'" class="mt-4">
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
            @endphp

                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach($headers as $key => $label)
                                @if($key != 'actions_html')
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        {{ $label }}
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- Documento 1: Factura pendiente -->
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">PO-2023-0001</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Factura</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Electrónicos del Norte S.A.</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Factura-ELN-12345.pdf</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                    Pendiente
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">2023-11-15</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900">Aprobar</button>
                                    <button class="text-red-600 hover:text-red-900">Rechazar</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Documento 2: Lista de empaque pendiente -->
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">PO-2023-0002</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Lista de Empaque</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">Aceros Industriales S.A.</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">PackingList-AISA-7789.pdf</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                    Pendiente
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">2023-11-17</td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900">Aprobar</button>
                                    <button class="text-red-600 hover:text-red-900">Rechazar</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Documento 3: Conocimiento de embarque pendiente -->
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">PO-2023-0003</td>
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
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900">Aprobar</button>
                                    <button class="text-red-600 hover:text-red-900">Rechazar</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Documento 4: Factura aprobada -->
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">PO-2023-0004</td>
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
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900">Ver detalles</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Documento 5: Lista de empaque rechazada -->
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">PO-2023-0005</td>
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
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900">Ver detalles</button>
                                    <button class="text-orange-600 hover:text-orange-900">Solicitar de nuevo</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Documento 6: Otro tipo de documento pendiente -->
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">PO-2023-0006</td>
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
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900">Aprobar</button>
                                    <button class="text-red-600 hover:text-red-900">Rechazar</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Documento 7: Certificado de origen pendiente -->
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">PO-2023-0007</td>
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
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900">Aprobar</button>
                                    <button class="text-red-600 hover:text-red-900">Rechazar</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </div>

        <!-- Document History View -->
        <div x-show="activeTab === 'document_history'" class="mt-4">
            <div class="p-4 bg-white rounded-lg shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-700">Historial de Documentos</h3>
                <p class="text-gray-500">Aquí se mostrará el historial de documentos aprobados y rechazados.</p>
                <!-- Placeholder for document history -->
            </div>
        </div>
    </div>

</x-app-layout>
