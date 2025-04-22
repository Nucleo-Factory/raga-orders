<x-app-layout>

    <div class="flex items-center justify-between">
        <x-view-title>
            <x-slot:title>
                Órdenes de Compra
            </x-slot:title>

            <x-slot:content>
                Visualiza y administra las órdenes de compra
            </x-slot:content>
        </x-view-title>


        <div class="flex items-center gap-2">

            <a href="{{ route('shipping-documentation.create') }}">
                <x-secondary-button>
                    Crear nuevo consolidado
                </x-secondary-button>
            </a>

            <a href="{{ route('purchase-orders.create') }}">
                <x-primary-button>
                    Nueva orden de compra
                </x-primary-button>
            </a>
        </div>
    </div>

    <div class="flex items-center justify-between hidden mb-6">
        <x-search-input class="w-64" wire:model.debounce.300ms="search" placeholder="Buscar comentarios o archivos..." />
    </div>

    <div class="" x-data="{activeTab: 'tab1'}">
        <!-- Selector de pestañas -->
        <div class="flex items-center justify-start gap-6 mb-3 text-lg font-bold">
            <div class="flex items-center gap-6">
                <button @click="activeTab = 'tab1'"
                    :class="activeTab === 'tab1' ? 'border-dark-blue text-dark-blue' : 'border-transparent'"
                    class="border-b-2 py-[0.625rem]">
                    Por Etapas
                </button>
                <button @click="activeTab = 'tab2'"
                    :class="activeTab === 'tab2' ? 'border-dark-blue text-dark-blue' : 'border-transparent'"
                    class="border-b-2 py-[0.625rem]">
                    Reporte
                </button>
            </div>

            <div class="flex gap-4">
                <livewire:kanban.kanban-filters />
            </div>
        </div>

        <!-- Contenido de las pestañas -->
        <div>
            <div x-show="activeTab === 'tab1'" x-transition class="">
                <livewire:kanban.kanban-board />
            </div>

            <div x-show="activeTab === 'tab2'" x-transition class="space-y-[1.875rem]">
                <livewire:tables.list-purchase-orders />
            </div>
        </div>
    </div>
</x-app-layout>
