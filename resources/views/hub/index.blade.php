<x-app-layout>
    @php
        $etapaArray = ["e1" => "Etapa 1", "e2" => "Etapa 2"];
    @endphp

    <div class="flex items-center justify-between">
        <x-view-title>
            <x-slot:title>
                Gestión de Hubs
            </x-slot:title>

            <x-slot:content>
                Gestiona todos los hubs
            </x-slot:content>
        </x-view-title>

        <a href="{{ route('hub.create') }}" class="block w-fit rounded-[0.375rem] bg-[#0F172A] px-4 py-2 text-white">
            Nuevo Hub
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
                    'code' => 'Código',
                    'country' => 'País',
                    'documentary_cut' => 'Corte documental',
                    'zarpe' => 'Zarpe',
                    'actions' => 'Acciones',
                    'actions_html' => '',
                ];

                $sortable = ['name', 'code', 'country', 'documentary_cut', 'zarpe'];
                $searchable = ['name', 'code', 'country', 'documentary_cut', 'zarpe'];
                $filterable = ['name', 'code', 'country', 'documentary_cut', 'zarpe'];
                $filterOptions = ['name', 'code', 'country', 'documentary_cut', 'zarpe'];
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
                :baseRoute="'hub'"
                :model="\App\Models\Hub::class"
            />
        </div>
    </div>
</x-app-layout>
