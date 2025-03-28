<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Documentación de Envío') }}
        </h2>
    </x-slot>

    <div>
        <div class="mx-auto">
            <div class="pb-6 overflow-hidden sm:rounded-lg">
                <div class="flex items-center justify-between mb-6">
                    <x-view-title>
                        <x-slot:title>
                            Consolidación de ordenes
                        </x-slot:title>
                    </x-view-title>

                    <a href="{{ route('purchase-orders.create') }}">
                        <x-primary-button>
                            Nueva orden
                        </x-primary-button>
                    </a>
                </div>

                <livewire:ui.counter-po />

                <!-- Pestañas para cambiar entre vista de tabla y tarjetas -->
                <div x-data="{
                    currentView: 'table'
                }" x-on:change-view.window="currentView = $event.detail.view"
                    class="flex items-center gap-6 mt-5 mb-[1.875rem]">
                    <button x-data @click="$dispatch('change-view', {view: 'table'})"
                        :class="currentView === 'table' ? 'border-dark-blue text-dark-blue' : 'border-transparent'"
                        class="border-b-2 py-[0.625rem]">
                        Vista de Tabla
                    </button>
                    <button x-data @click="$dispatch('change-view', {view: 'cards'})"
                        :class="currentView === 'cards' ? 'border-dark-blue text-dark-blue' : 'border-transparent'"
                        class="border-b-2 py-[0.625rem]">
                        Vista de Tarjetas
                    </button>
                </div>

                <!-- Contenedor para las vistas -->
                <div x-data="{ currentView: 'table' }" x-on:change-view.window="currentView = $event.detail.view">
                    <!-- Vista de tabla -->
                    <div x-show="currentView === 'table'">
                        <livewire:tables.custom-purchase-orders-table />
                    </div>

                    <!-- Vista de tarjetas -->
                    <div x-show="currentView === 'cards'" x-cloak>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                            @foreach (\App\Models\PurchaseOrder::latest()->get() as $order)
                                <livewire:ui.purchase-order-card :order="$order" :key="$order->id" />
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('viewToggle', () => ({
                    currentView: 'table',
                    changeView(view) {
                        this.currentView = view;
                    }
                }));
            });
        </script>
    @endpush
</x-app-layout>
