<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Documentación de Envío') }}
        </h2>
    </x-slot>

    <div>
        <div class="mx-auto">
            <div class="overflow-hidden pb-6 sm:rounded-lg">
                <div class="mb-6 flex items-center justify-between">
                    <x-view-title title="Órdenes de compra" subtitle="" />
                    <a href="{{ route('new-purchase-order') }}"
                        class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900">
                        Nueva Orden
                    </a>
                </div>

                <ul class="grid grid-cols-3 gap-6 mb-4">
                    <li>
                        <x-card class="space-y-4">
                            <x-slot:title class="text-[1.375rem] font-medium">
                                Cant. de PO en transito
                            </x-slot:title>

                            <x-slot:content class="text-sm">
                                35
                            </x-slot:content>
                        </x-card>
                    </li>

                    <li>
                        <x-card class="space-y-4">
                            <x-slot:title class="text-[1.375rem] font-medium">
                                Cant. de PO en consolidables
                            </x-slot:title>

                            <x-slot:content class="text-sm">
                                35
                            </x-slot:content>
                        </x-card>
                    </li>

                    <li>
                        <x-card class="space-y-4">
                            <x-slot:title class="text-[1.375rem] font-medium">
                                Cant. PO entregadas
                            </x-slot:title>

                            <x-slot:content class="text-sm">
                                35
                            </x-slot:content>
                        </x-card>
                    </li>
                </ul>

                <!-- Pestañas para cambiar entre vista de tabla y tarjetas -->
                <div class="mb-6 border-b border-gray-200">
                    <div class="-mb-px flex">
                        <button x-data @click="$dispatch('change-view', {view: 'table'})"
                            class="mr-8 border-b-2 border-indigo-500 px-1 py-4 text-sm font-medium leading-5 text-indigo-600 focus:border-indigo-700 focus:text-indigo-800 focus:outline-none">
                            Vista de Tabla
                        </button>
                        <button x-data @click="$dispatch('change-view', {view: 'cards'})"
                            class="mr-8 border-b-2 border-transparent px-1 py-4 text-sm font-medium leading-5 text-gray-500 hover:border-gray-300 hover:text-gray-700 focus:border-gray-300 focus:text-gray-700 focus:outline-none">
                            Vista de Tarjetas
                        </button>
                    </div>
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
