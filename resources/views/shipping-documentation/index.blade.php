<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Documentación de Envío') }}
        </h2>
    </x-slot>

    <div>
        <div class="mx-auto">
            <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Órdenes de Compra</h1>
                    <a href="{{ route('new-purchase-order') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Nueva Orden
                    </a>
                </div>

                <!-- Pestañas para cambiar entre vista de tabla y tarjetas -->
                <div class="mb-6 border-b border-gray-200">
                    <div class="flex -mb-px">
                        <button x-data @click="$dispatch('change-view', {view: 'table'})" class="px-1 py-4 mr-8 text-sm font-medium leading-5 text-indigo-600 border-b-2 border-indigo-500 focus:outline-none focus:text-indigo-800 focus:border-indigo-700">
                            Vista de Tabla
                        </button>
                        <button x-data @click="$dispatch('change-view', {view: 'cards'})" class="px-1 py-4 mr-8 text-sm font-medium leading-5 text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
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
                            @foreach(\App\Models\PurchaseOrder::latest()->get() as $order)
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
