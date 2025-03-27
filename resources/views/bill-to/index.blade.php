<x-app-layout>
    @php
        $etapaArray = ["e1" => "Etapa 1", "e2" => "Etapa 2"];
    @endphp

    <div class="flex items-center justify-between">
        <x-view-title>
            <x-slot:title>
                Gestión de Direcciones de facturación
            </x-slot:title>

            <x-slot:content>
                Gestiona todas las direcciones de facturación
            </x-slot:content>
        </x-view-title>

        <a href="{{ route('bill-to.create') }}" class="block w-fit rounded-[0.375rem] bg-[#0F172A] px-4 py-2 text-white">
            Nueva Dirección de facturación
        </a>
    </div>

    @if (session('message'))
        <div class="px-4 py-2 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabs for switching between views -->
    <div x-data="{ activeTab: 'kanban' }" class="mb-6">
        <!-- Kanban View -->
        <div x-show="activeTab === 'kanban'" class="mt-4">
            @php
                $headers = [
                    'name' => 'Nombre',
                    'email' => 'Email',
                    'contact_person' => 'Persona de contacto',
                    'address' => 'Dirección',
                    'country' => 'País',
                    'phone' => 'Teléfono',
                    'status' => 'Estado',
                    'actions' => 'Acciones',
                    'actions_html' => '',
                ];

                $sortable = ['name', 'email', 'phone', 'status'];
                $searchable = ['name', 'email', 'phone', 'status'];
                $filterable = ['name', 'email', 'phone', 'status'];
                $filterOptions = ['name', 'email', 'phone', 'status'];
            @endphp


            <livewire:components.reusable-table
                :headers="$headers"
                :sortable="$sortable"
                :searchable="$searchable"
                :filterable="$filterable"
                :filterOptions="$filterOptions"
                :actions="true"
                :actionsView="false"
                :actionsEdit="true"
                :actionsDelete="true"
                :baseRoute="'bill-to'"
                :model="\App\Models\BillTo::class"
            />
        </div>
    </div>
</x-app-layout>
