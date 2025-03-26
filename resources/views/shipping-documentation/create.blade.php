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
                    <x-view-title>
                        <x-slot:title>
                            Órdenes de compra
                        </x-slot:title>
                    </x-view-title>

                    <a href="{{ route('purchase-orders.create') }}">
                        <x-primary-button>
                            Nueva orden
                        </x-primary-button>
                    </a>
                </div>

                <ul class="grid grid-cols-3 gap-4">
                    <li>
                        <x-card-icon>
                            <x-slot:icon class="rounded-full bg-[#E0E5FF] p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22"
                                    fill="none">
                                    <path
                                        d="M10.5 4H10.9344C13.9816 4 15.5053 4 16.0836 4.54729C16.5836 5.02037 16.8051 5.71728 16.6702 6.39221C16.514 7.17302 15.2701 8.05285 12.7823 9.81253L8.71772 12.6875C6.2299 14.4471 4.98599 15.327 4.82984 16.1078C4.69486 16.7827 4.91642 17.4796 5.41636 17.9527C5.99474 18.5 7.51836 18.5 10.5656 18.5H11.5M7 4C7 5.65685 5.65685 7 4 7C2.34315 7 1 5.65685 1 4C1 2.34315 2.34315 1 4 1C5.65685 1 7 2.34315 7 4ZM21 18C21 19.6569 19.6569 21 18 21C16.3431 21 15 19.6569 15 18C15 16.3431 16.3431 15 18 15C19.6569 15 21 16.3431 21 18Z"
                                        stroke="#565AFF" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </x-slot:icon>

                            <x-slot:title class="text-lg font-bold text-[#898989]">
                                Cant. de PO en transito
                            </x-slot:title>

                            <x-slot:content class="text-4xl font-bold text-[#2e2e2e]">
                                35
                            </x-slot:content>
                        </x-card-icon>
                    </li>

                    <li>
                        <x-card-icon>
                            <x-slot:icon class="rounded-full bg-[#E0E5FF] p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="22"
                                    viewBox="0 0 19 22" fill="none">
                                    <path
                                        d="M13.3333 3C14.2633 3 14.7283 3 15.1098 3.10222C16.1451 3.37962 16.9537 4.18827 17.2311 5.22354C17.3333 5.60504 17.3333 6.07003 17.3333 7V16.2C17.3333 17.8802 17.3333 18.7202 17.0064 19.362C16.7187 19.9265 16.2598 20.3854 15.6953 20.673C15.0536 21 14.2135 21 12.5333 21H6.13334C4.45319 21 3.61311 21 2.97137 20.673C2.40689 20.3854 1.94794 19.9265 1.66032 19.362C1.33334 18.7202 1.33334 17.8802 1.33334 16.2V7C1.33334 6.07003 1.33334 5.60504 1.43557 5.22354C1.71297 4.18827 2.52161 3.37962 3.55689 3.10222C3.93839 3 4.40337 3 5.33334 3M6.33334 14L8.33334 16L12.8333 11.5M6.93334 5H11.7333C12.2934 5 12.5734 5 12.7873 4.89101C12.9755 4.79513 13.1285 4.64215 13.2243 4.45399C13.3333 4.24008 13.3333 3.96005 13.3333 3.4V2.6C13.3333 2.03995 13.3333 1.75992 13.2243 1.54601C13.1285 1.35785 12.9755 1.20487 12.7873 1.10899C12.5734 1 12.2934 1 11.7333 1H6.93334C6.37329 1 6.09326 1 5.87935 1.10899C5.69119 1.20487 5.53821 1.35785 5.44234 1.54601C5.33334 1.75992 5.33334 2.03995 5.33334 2.6V3.4C5.33334 3.96005 5.33334 4.24008 5.44234 4.45399C5.53821 4.64215 5.69119 4.79513 5.87935 4.89101C6.09326 5 6.37329 5 6.93334 5Z"
                                        stroke="#565AFF" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </x-slot:icon>

                            <x-slot:title class="text-lg font-bold text-[#898989]">
                                Cant. de PO en consolidables
                            </x-slot:title>

                            <x-slot:content class="text-4xl font-bold text-[#2e2e2e]">
                                35
                            </x-slot:content>
                        </x-card-icon>
                    </li>

                    <li>
                        <x-card-icon>
                            <x-slot:icon class="rounded-full bg-[#E0E5FF] p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="22"
                                    viewBox="0 0 23 22" fill="none">
                                    <path
                                        d="M21.6666 10.0857V11.0057C21.6654 13.1621 20.9671 15.2604 19.676 16.9875C18.3848 18.7147 16.5699 19.9782 14.502 20.5896C12.4341 21.201 10.2239 21.1276 8.2011 20.3803C6.17831 19.633 4.45128 18.2518 3.27759 16.4428C2.10389 14.6338 1.54642 12.4938 1.6883 10.342C1.83019 8.19029 2.66383 6.14205 4.06491 4.5028C5.46598 2.86354 7.35941 1.72111 9.46282 1.24587C11.5662 0.770634 13.7669 0.988061 15.7366 1.86572M21.6666 3L11.6666 13.01L8.66663 10.01"
                                        stroke="#565AFF" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </x-slot:icon>

                            <x-slot:title class="text-lg font-bold text-[#898989]">
                                Cant. PO entregadas
                            </x-slot:title>

                            <x-slot:content class="text-4xl font-bold text-[#2e2e2e]">
                                35
                            </x-slot:content>
                        </x-card-icon>
                    </li>
                </ul>

                <!-- Pestañas para cambiar entre vista de tabla y tarjetas -->
                <div x-data="{
                    currentView: 'table'
                }" x-on:change-view.window="currentView = $event.detail.view"
                    class="flex items-center gap-6 mt-5 mb-6">
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
