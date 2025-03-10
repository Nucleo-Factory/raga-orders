<div>
    <div class="flex max-w-[1254px] items-center justify-between">
        <x-view-title title="Crear nueva Orden de compra" subtitle="Ingrese los datos para cargar su Orden de compra" />

        <x-black-btn wire:click="createPurchaseOrder">Crear nueva Orden</x-black-btn>
    </div>

    <x-form>
        <div class="flex gap-[3.5rem]">
            <div class="w-full max-w-[1254px] space-y-6">
                <h3 class="text-xl">Datos generales</h3>

                <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                    <x-form-input label="Número PO" type="text" name="order_number" wire:model="order_number" placeholder="Ingrese número PO" />
                    <x-form-input label="Fecha de orden" type="date" name="order_date" wire:model="order_date" />
                    <x-form-select label="Moneda" name="currency" wire:model="currency" :options="$currencyArray" />
                    <x-form-select label="Incoterms" name="incoterms" wire:model="incoterms" :options="$tiposIncotermArray" />
                    <x-form-select label="Términos de pago" name="payment_terms" wire:model="payment_terms" :options="$paymentTermsArray" />
                    <x-form-input label="Lugar de orden" type="text" name="order_place" wire:model="order_place" placeholder="Ingrese lugar de orden" />
                    <x-form-input label="Email del agente" type="email" name="email_agent" wire:model="email_agent" placeholder="Ingrese email del agente" />
                </div>
            </div>

            {{-- TODO: Añadir funcionalidad input type=file --}}
            <div class="w-full max-w-[449px] space-y-6 hidden">
                <h3 class="text-xl">Adjunte la Orden para autocompletar</h3>

                <div
                    class="flex flex-col items-center justify-center space-y-4 rounded-[0.5rem] border border-dashed border-[#E5E7EB] bg-white py-[0.688rem] text-[#6B7280]">
                    <div class="flex flex-col items-center justify-center space-y-[0.438rem]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 35 35"
                            fill="none">
                            <path
                                d="M25.3452 8.17252L18.6785 1.33519C18.5237 1.176 18.3398 1.04971 18.1373 0.963537C17.9348 0.877364 17.7177 0.833008 17.4985 0.833008C17.2793 0.833008 17.0622 0.877364 16.8597 0.963537C16.6572 1.04971 16.4733 1.176 16.3185 1.33519L9.65183 8.17252C9.33932 8.49349 9.16392 8.92863 9.16424 9.38223C9.16455 9.83582 9.34054 10.2707 9.6535 10.5912C9.96645 10.9117 10.3907 11.0916 10.833 11.0913C11.2753 11.091 11.6993 10.9105 12.0118 10.5895L15.8335 6.67002V21.3463C15.8335 21.7997 16.0091 22.2345 16.3217 22.555C16.6342 22.8756 17.0581 23.0557 17.5002 23.0557C17.9422 23.0557 18.3661 22.8756 18.6787 22.555C18.9912 22.2345 19.1668 21.7997 19.1668 21.3463V6.67002L22.9885 10.5895C23.3028 10.9009 23.7238 11.0732 24.1608 11.0693C24.5978 11.0654 25.0158 10.8856 25.3249 10.5687C25.6339 10.2518 25.8092 9.82305 25.813 9.37487C25.8168 8.92669 25.6488 8.4949 25.3452 8.17252Z"
                                fill="#9CA3AF" />
                            <path
                                d="M30.8335 20.4917H22.5002V21.3463C22.5002 22.7064 21.9734 24.0107 21.0357 24.9724C20.098 25.9341 18.8262 26.4743 17.5002 26.4743C16.1741 26.4743 14.9023 25.9341 13.9646 24.9724C13.0269 24.0107 12.5002 22.7064 12.5002 21.3463V20.4917H4.16683C3.28277 20.4917 2.43493 20.8519 1.80981 21.493C1.18469 22.1341 0.833496 23.0037 0.833496 23.9103V30.7477C0.833496 31.6544 1.18469 32.5239 1.80981 33.165C2.43493 33.8062 3.28277 34.1663 4.16683 34.1663H30.8335C31.7176 34.1663 32.5654 33.8062 33.1905 33.165C33.8156 32.5239 34.1668 31.6544 34.1668 30.7477V23.9103C34.1668 23.0037 33.8156 22.1341 33.1905 21.493C32.5654 20.8519 31.7176 20.4917 30.8335 20.4917ZM26.6668 30.7477C26.1724 30.7477 25.689 30.5973 25.2779 30.3156C24.8668 30.0338 24.5464 29.6334 24.3571 29.1649C24.1679 28.6964 24.1184 28.1808 24.2149 27.6835C24.3113 27.1861 24.5494 26.7292 24.8991 26.3707C25.2487 26.0121 25.6942 25.7679 26.1791 25.6689C26.6641 25.57 27.1667 25.6208 27.6235 25.8149C28.0804 26.0089 28.4708 26.3376 28.7455 26.7592C29.0202 27.1808 29.1668 27.6766 29.1668 28.1837C29.1668 28.8637 28.9034 29.5159 28.4346 29.9967C27.9658 30.4775 27.3299 30.7477 26.6668 30.7477Z"
                                fill="#9CA3AF" />
                        </svg>

                        <input type="file" class="hidden">

                        <span class="text-sm font-semibold">Adjuntar archivo</span>
                        <span class="text-xs font-semibold">Max. File Size: 30MB</span>
                    </div>

                    <button class="flex items-center gap-2 rounded-[0.5rem] bg-[#64748B] px-4 py-2 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13"
                            fill="none">
                            <path
                                d="M5.30006 10.1C4.3507 10.1 3.42266 9.81848 2.63329 9.29105C1.84392 8.76362 1.22869 8.01396 0.865385 7.13688C0.50208 6.25979 0.407023 5.29467 0.592234 4.36357C0.777445 3.43246 1.23461 2.57718 1.90591 1.90589C2.57721 1.2346 3.43249 0.777442 4.36361 0.592233C5.29473 0.407024 6.25986 0.50208 7.13696 0.86538C8.01406 1.22868 8.76372 1.84391 9.29116 2.63326C9.8186 3.42262 10.1001 4.35065 10.1001 5.3C10.0987 6.5726 9.59251 7.79267 8.69263 8.69253C7.79276 9.5924 6.57267 10.0986 5.30006 10.1ZM5.30006 1.7C4.58804 1.7 3.89201 1.91114 3.29998 2.30671C2.70796 2.70228 2.24653 3.26453 1.97405 3.92234C1.70157 4.58015 1.63028 5.30399 1.76919 6.00232C1.9081 6.70066 2.25097 7.34211 2.75444 7.84558C3.25792 8.34905 3.89939 8.69192 4.59772 8.83082C5.29606 8.96973 6.01991 8.89844 6.67773 8.62596C7.33556 8.35349 7.89781 7.89207 8.29338 7.30005C8.68896 6.70803 8.9001 6.01201 8.9001 5.3C8.89915 4.34551 8.51955 3.43039 7.84462 2.75547C7.16969 2.08055 6.25456 1.70095 5.30006 1.7Z"
                                fill="white" />
                            <path
                                d="M11.9001 12.5C11.741 12.5 11.5884 12.4367 11.4759 12.3242L9.07589 9.9242C8.96659 9.81104 8.90611 9.65948 8.90748 9.50216C8.90885 9.34484 8.97195 9.19436 9.0832 9.08311C9.19444 8.97187 9.34493 8.90876 9.50225 8.9074C9.65957 8.90603 9.81114 8.96651 9.9243 9.0758L12.3243 11.4758C12.4082 11.5597 12.4653 11.6666 12.4885 11.783C12.5116 11.8994 12.4997 12.02 12.4543 12.1296C12.4089 12.2392 12.332 12.3329 12.2334 12.3988C12.1348 12.4648 12.0188 12.5 11.9001 12.5Z"
                                fill="white" />
                        </svg>
                        <span class="text-sm font-semibold">Browse File</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="w-full max-w-[1254px] space-y-6">
            <h3 class="text-xl">Datos vendor</h3>

            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <x-form-input label="Código Vendor" name="vendor_id" wire:model="vendor_id" placeholder="Ingrese código" />
                <x-form-input label="Dirección" name="vendor_direccion" wire:model="vendor_direccion" placeholder="Ingrese dirección" />
                <x-form-input label="Código postal" name="vendor_codigo_postal" wire:model="vendor_codigo_postal" placeholder="Ingrese código postal" />
                <x-form-select label="País" name="vendor_pais" wire:model="vendor_pais" :options="$paisArray" optionPlaceholder="Elije país" />
                <x-form-select label="Estado" name="vendor_estado" wire:model="vendor_estado" :options="$estadoArray" />
                <x-form-input label="Teléfono" name="vendor_telefono" wire:model="vendor_telefono" placeholder="Ingrese teléfono" />
            </div>
        </div>

        <div class="w-full max-w-[1254px] space-y-6">
            <h3 class="text-xl">Datos Ship to</h3>

            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <x-form-input label="Dirección" name="ship_to_direccion" wire:model="ship_to_direccion" placeholder="Ingrese dirección" />
                <x-form-input label="Código postal" name="ship_to_codigo_postal" wire:model="ship_to_codigo_postal" placeholder="Ingrese código postal" />
                <x-form-select label="País" name="ship_to_pais" wire:model="ship_to_pais" :options="$paisArray" optionPlaceholder="Elije país" />
                <x-form-select label="Estado" name="ship_to_estado" wire:model="ship_to_estado" :options="$estadoArray" />
                <x-form-input label="Teléfono" name="ship_to_telefono" wire:model="ship_to_telefono" placeholder="Ingrese teléfono" />
            </div>
        </div>

        <div class="w-full max-w-[1254px] space-y-6">
            <h3 class="text-xl">Datos de facturación</h3>

            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <x-form-input label="Dirección" name="bill_to_direccion" wire:model="bill_to_direccion" placeholder="Ingrese dirección" />
                <x-form-input label="Código postal" name="bill_to_codigo_postal" wire:model="bill_to_codigo_postal" placeholder="Ingrese código postal" />
                <x-form-select label="País" name="bill_to_pais" wire:model="bill_to_pais" :options="$paisArray" optionPlaceholder="Elije país" />
                <x-form-select label="Estado" name="bill_to_estado" wire:model="bill_to_estado" :options="$estadoArray" />
                <x-form-input label="Teléfono" name="bill_to_telefono" wire:model="bill_to_telefono" placeholder="Ingrese teléfono" />
            </div>
        </div>

        <div class="w-full max-w-[1254px] space-y-6">
            <h3 class="text-xl">Totales</h3>

            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <x-form-input label="Total neto" type="number" step="0.01" name="net_total" wire:model="net_total" placeholder="0.00" />
                <x-form-input label="Costo adicional" type="number" step="0.01" name="additional_cost" wire:model="additional_cost" placeholder="0.00" />
                <x-form-input label="Total" type="number" step="0.01" name="total" wire:model="total" placeholder="0.00" />
                <x-form-input label="Costo de seguro" type="number" step="0.01" name="insurance_cost" wire:model="insurance_cost" placeholder="0.00" />
            </div>
        </div>

        <div class="w-full max-w-[1254px] space-y-6">
            <h3 class="text-xl">Dimensiones</h3>

            <div class="grid grid-cols-[1fr,1fr,1fr,1fr] gap-x-5 gap-y-6">
                <x-form-input label="Alto (cm)" type="number" step="0.01" name="height_cm" wire:model="height_cm" placeholder="0.00" />
                <x-form-input label="Ancho (cm)" type="number" step="0.01" name="width_cm" wire:model="width_cm" placeholder="0.00" />
                <x-form-input label="Largo (cm)" type="number" step="0.01" name="length_cm" wire:model="length_cm" placeholder="0.00" />
                <x-form-input label="Volumen (m³)" type="number" step="0.001" name="volume_m3" wire:model="volume_m3" placeholder="0.000" />
            </div>
        </div>

        <div class="w-full max-w-[1254px] space-y-6">
            <h3 class="text-xl">Fechas</h3>

            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <x-form-input label="Fecha de entrega solicitada" type="datetime-local" name="requested_delivery_date" wire:model="requested_delivery_date" />
                <x-form-input label="Fecha estimada de recogida" type="datetime-local" name="estimated_pickup_date" wire:model="estimated_pickup_date" />
                <x-form-input label="Fecha real de recogida" type="datetime-local" name="actual_pickup_date" wire:model="actual_pickup_date" />
                <x-form-input label="Llegada estimada al hub" type="datetime-local" name="estimated_hub_arrival" wire:model="estimated_hub_arrival" />
                <x-form-input label="Llegada real al hub" type="datetime-local" name="actual_hub_arrival" wire:model="actual_hub_arrival" />
                <x-form-input label="ETD (Fecha estimada de salida)" type="datetime-local" name="etd_date" wire:model="etd_date" />
                <x-form-input label="ATD (Fecha real de salida)" type="datetime-local" name="atd_date" wire:model="atd_date" />
                <x-form-input label="ETA (Fecha estimada de llegada)" type="datetime-local" name="eta_date" wire:model="eta_date" />
                <x-form-input label="ATA (Fecha real de llegada)" type="datetime-local" name="ata_date" wire:model="ata_date" />
            </div>
        </div>

        <div class="w-full max-w-[1254px] space-y-6">
            <h3 class="text-xl">Carga / Contenido</h3>

            <div class="flex flex-col space-y-4">
                <!-- Buscador de productos -->
                <div class="flex items-end gap-4">
                    <div class="w-full max-w-md">
                        <label for="searchTerm" class="block text-sm font-medium text-gray-700">Buscar producto</label>
                        <div class="relative">
                            <input
                                type="text"
                                id="searchTerm"
                                wire:model.live="searchTerm"
                                wire:keyup="searchProducts"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Buscar por ID o descripción"
                            >
                            @if(count($searchResults) > 0)
                                <div class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg">
                                    <ul class="py-1 overflow-auto text-base rounded-md max-h-60 sm:text-sm">
                                        @foreach($searchResults as $product)
                                            <li
                                                class="relative py-2 pl-3 cursor-pointer select-none pr-9 hover:bg-gray-100"
                                                wire:click="selectProduct({{ $product->id }})"
                                            >
                                                <div class="flex items-center">
                                                    <span class="font-medium">{{ $product->material_id }}</span>
                                                    <span class="ml-2 text-gray-500">{{ $product->description }}</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="w-24">
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input
                            type="number"
                            id="quantity"
                            wire:model="quantity"
                            min="1"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                    </div>

                    <button
                        type="button"
                        wire:click="addProduct"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        @if(!$selectedProduct) disabled @endif
                    >
                        Agregar
                    </button>
                </div>

                <!-- Producto seleccionado -->
                @if($selectedProduct)
                    <div class="p-3 mt-2 rounded-md bg-gray-50">
                        <div class="flex justify-between">
                            <div>
                                <p class="font-medium">{{ $selectedProduct->material_id }}</p>
                                <p class="text-sm text-gray-500">{{ $selectedProduct->description }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">Precio: {{ number_format($selectedProduct->price_per_unit, 2) }}</p>
                                <p class="text-sm text-gray-500">Subtotal: {{ number_format($selectedProduct->price_per_unit * $quantity, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tabla de productos agregados -->
                <div class="mt-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Descripción</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Precio unitario</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Cantidad</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Subtotal</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($orderProducts as $index => $product)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $product['material_id'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $product['description'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ number_format($product['price_per_unit'], 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        <input
                                            type="number"
                                            wire:model.live="orderProducts.{{ $index }}.quantity"
                                            wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                            min="1"
                                            class="block w-20 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        >
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ number_format($product['subtotal'], 2) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        <button
                                            type="button"
                                            wire:click="removeProduct({{ $index }})"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">No hay productos agregados</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-right text-gray-900">Total Neto:</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ number_format($net_total, 2) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-right text-gray-900">Costo Adicional:</td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                    <input
                                        type="number"
                                        wire:model.live="additional_cost"
                                        step="0.01"
                                        class="block w-32 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    >
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-right text-gray-900">Costo de Seguro:</td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                    <input
                                        type="number"
                                        wire:model.live="insurance_cost"
                                        step="0.01"
                                        class="block w-32 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    >
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm font-bold text-right text-gray-900">TOTAL:</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 whitespace-nowrap">{{ number_format($total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </x-form>
</div>
