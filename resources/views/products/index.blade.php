<x-app-layout>
    <div class="flex items-center justify-between">
        <x-view-title title="Materiales" subtitle="Visualiza y administra los materiales" />

        <a href="{{ route('products.create') }}" class="block w-fit rounded-[0.375rem] bg-[#0F172A] px-4 py-2 text-white">
            Nuevo Material
        </a>
    </div>

    <ul class="grid grid-cols-3 gap-6">
        <li>
            <x-card class="space-y-4">
                <x-slot:title class="text-[1.375rem] font-medium">
                    Cant. de materiales
                </x-slot:title>

                <x-slot:content class="text-sm">
                    {{ count(\App\Models\Product::all()) }}
                </x-slot:content>
            </x-card>
        </li>
    </ul>

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
                    'material_id' => 'ID',
                    'description' => 'Descripción',
                    'qty_unit' => 'Cantidad',
                    'price_per_unit' => 'Precio Unitario',
                    'net_value' => 'Valor Neto',
                    'vat_value' => 'Valor IVA',
                    'actions' => 'Acciones',
                    'actions_html' => '',
                ];

                $sortable = ['material_id', 'description', 'qty_unit', 'price_per_unit', 'net_value', 'vat_value'];
                $searchable = ['material_id', 'description', 'qty_unit', 'price_per_unit', 'net_value', 'vat_value'];
                $filterable = ['material_id', 'description', 'qty_unit', 'price_per_unit', 'net_value', 'vat_value'];
                $filterOptions = ['material_id', 'description', 'qty_unit', 'price_per_unit', 'net_value', 'vat_value'];
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
                :baseRoute="'products'"
                :model="\App\Models\Product::class"
            />
        </div>
    </div>
</x-app-layout>
