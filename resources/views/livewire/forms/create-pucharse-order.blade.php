<div>
    <div class="flex items-center justify-between">
        <x-view-title>
            <x-slot:title>
                {{ isset($id) ? 'Editar Orden de compra: ' . $order_number : 'Generar nueva orden de compra' }}
            </x-slot:title>

            <x-slot:content>
                {{ isset($id) ? 'Modifique los datos de la Orden de compra' : 'Ingrese los datos para cargar su Orden de compra' }}
            </x-slot:content>
        </x-view-title>

        <div class="space-x-4">
            <x-secondary-button class="w-[209px]">
                Cancelar
            </x-secondary-button>

            <x-primary-button wire:click="createPurchaseOrder" class="w-[209px]">
                {{ isset($id) ? 'Actualizar Orden' : 'Crear nueva Orden' }}
            </x-primary-button>
        </div>
    </div>

    <x-form>
        <div class="p-8 space-y-10 bg-white rounded-2xl">
            <div class="flex gap-4">
                <div class="w-full space-y-6">
                    <h3 class="text-lg font-bold text-[#7288FF]">Datos generales</h3>
                    <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                        <div class="relative">
                            <x-form-input>
                                <x-slot:label>
                                    Número PO
                                </x-slot:label>

                                <x-slot:input name="order_number" placeholder="Ingrese número PO" wire:model="order_number" class="pr-10"></x-slot:input>

                                <x-slot:icon>
                                    <button wire:click="generateUniqueOrderNumber"
                                        class="absolute text-gray-500 -translate-y-1/2 right-3 top-1/2 hover:text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot:icon>

                                <x-slot:error>
                                    {{ $errors->first('order_number') }}
                                </x-slot:error>
                            </x-form-input>
                        </div>
                        <x-form-input>
                            <x-slot:label>
                                Fecha de creación
                            </x-slot:label>
                            <x-slot:input name="order_date" type="date" placeholder="Ingrese número PO"
                                wire:model="order_date">
                            </x-slot:input>
                        </x-form-input>
                        <x-form-select label="Moneda" name="currency" wireModel="currency" :options="$currencyArray" />
                        <x-form-select label="Incoterms" name="incoterms" wireModel="incoterms" :options="$tiposIncotermArray" />
                        <x-form-select label="HUB Planificado" name="planned_hub_id" wireModel="planned_hub_id"
                            :options="$hubsArray" />
                        <x-form-select label="HUB Real" name="actual_hub_id" wireModel="actual_hub_id"
                            :options="$hubsArray" />
                    </div>
                </div>

                <x-form-input-file class="space-y-6">
                    <x-slot:label class="!text-lg !font-bold">
                        Adjunte la orden para autocompletar
                    </x-slot:label>
                    <x-slot:input name="autocomplete_product">
                    </x-slot:input>
                </x-form-input-file>
            </div>

            <div class="w-full space-y-6">
                <h3 class="text-lg font-bold text-[#7288FF]">Datos vendor</h3>

                <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                    <x-form-select label="Seleccionar Vendor" name="vendor_id" wireModel="vendor_id"
                        :options="$vendorArray" />
                </div>
            </div>

            <div class="w-full space-y-6">
                <h3 class="text-lg font-bold text-[#7288FF]">Datos Ship to</h3>

                <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                    <x-form-select label="Seleccionar Ship to" name="ship_to_id" wireModel="ship_to_id"
                        :options="$shipToArray" />
                </div>
            </div>

            <div class="w-full space-y-6">
                <h3 class="text-lg font-bold text-[#7288FF]">Datos de facturación</h3>
                <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                    <x-form-select label="Seleccionar Bill to" name="bill_to_id" wireModel="bill_to_id"
                        :options="$billToArray" />
                </div>
            </div>

            <div class="w-full space-y-6">
                <h3 class="text-lg font-bold text-[#7288FF]">Dimensiones en centímetros</h3>
                <div class="grid grid-cols-[1fr,1fr,1fr,1fr] gap-x-5 gap-y-6">
                    <x-form-input>
                        <x-slot:label>
                            Largo (cm)
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="largo_cm" placeholder="0.00">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Ancho (cm)
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="ancho_cm" placeholder="0.00">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Alto (cm)
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="alto_cm" placeholder="0.00">
                        </x-slot:input>
                    </x-form-input>
                </div>
            </div>

            <div class="w-full space-y-6">
                <h3 class="text-lg font-bold text-[#7288FF]">Dimensiones</h3>
                <div class="grid grid-cols-[1fr,1fr,1fr,1fr] gap-x-5 gap-y-6">
                    <x-form-input>
                        <x-slot:label>
                            Largo (in)
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="largo" placeholder="0.00"
                            wire:model="largo" disabled>
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Ancho (in)
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="ancho" placeholder="0.00"
                            wire:model="ancho" disabled>
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Alto (in)
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="alto" placeholder="0.00"
                            wire:model="alto" disabled>
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Volumen (ft³)
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="volumen" placeholder="0.00"
                            wire:model="volumen" disabled>
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Peso (kg)
                        </x-slot:label>
                        <x-slot:input type="number" step="1" min="0" name="peso_kg"
                            placeholder="0" wire:model="peso_kg">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Peso (lb)
                        </x-slot:label>
                        <x-slot:input type="number" step="1" min="0" name="peso_lb"
                            placeholder="0" wire:model="peso_lb">
                        </x-slot:input>
                    </x-form-input>
                </div>
            </div>

            <div class="w-full space-y-6">
                <h3 class="text-lg font-bold text-[#7288FF]">Fechas</h3>

                <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                    <x-form-input>
                        <x-slot:label>
                            Fecha requerida en destino
                        </x-slot:label>

                        <x-slot:input type="date" name="date_required_in_destination"
                            wire:model="date_required_in_destination">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Fecha pickup planificada
                        </x-slot:label>

                        <x-slot:input type="date" name="date_planned_pickup"
                            wire:model="date_planned_pickup">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Fecha pickup real
                        </x-slot:label>

                        <x-slot:input type="date" name="date_actual_pickup"
                            wire:model="date_actual_pickup">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Fecha estimada de llegada al hub
                        </x-slot:label>

                        <x-slot:input type="date" name="date_estimated_hub_arrival"
                            wire:model="date_estimated_hub_arrival">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Fecha de llegada real al hub
                        </x-slot:label>

                        <x-slot:input type="date" name="date_actual_hub_arrival"
                            wire:model="date_actual_hub_arrival">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Fecha ETD (Fecha estimada de salida)
                        </x-slot:label>

                        <x-slot:input type="date" name="date_etd" wire:model="date_etd">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Fecha ATD (Fecha real de salida)
                        </x-slot:label>

                        <x-slot:input type="date" name="date_atd" wire:model="date_atd">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Fecha ETA (Fecha estimada de llegada)
                        </x-slot:label>

                        <x-slot:input type="date" name="date_eta" wire:model="date_eta">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Fecha ATA (Fecha real de llegada)
                        </x-slot:label>

                        <x-slot:input type="date" name="date_ata" wire:model="date_ata">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Fecha de consolidado
                        </x-slot:label>

                        <x-slot:input type="date" name="date_consolidation"
                            wire:model="date_consolidation">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Fecha de release
                        </x-slot:label>

                        <x-slot:input type="date" name="release_date" wire:model="release_date">
                        </x-slot:input>
                    </x-form-input>
                </div>
            </div>

            <div class="w-full space-y-6">
                <h3 class="text-lg font-bold text-[#7288FF]">Información Adicional</h3>
                <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                    <x-form-select label="Tipo de Material" name="material_type" wire:model="material_type" :options="['dangerous' => 'Peligroso', 'general' => 'General', 'exclusive' => 'Exclusivo', 'estibable' => 'Estibable']" />
                    <x-form-select label="Seguro" name="ensurence_type" wire:model="ensurence_type" :options="['pending' => 'Pendiente', 'applied' => 'Aplicado']" />
                    <x-form-input>
                        <x-slot:label>
                            Modo
                        </x-slot:label>
                        <x-slot:input name="mode" placeholder="Ingrese modo" wire:model="mode">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            ID de Seguimiento
                        </x-slot:label>
                        <x-slot:input name="tracking_id" placeholder="Ingrese ID de seguimiento" wire:model="tracking_id">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Cantidad estimada de pallets
                        </x-slot:label>
                        <x-slot:input type="number" name="pallet_quantity" placeholder="0" wire:model="pallet_quantity">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Cantidad Real de Pallets
                        </x-slot:label>
                        <x-slot:input type="number" name="pallet_quantity_real" placeholder="0" wire:model="pallet_quantity_real">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Conocimiento de Embarque
                        </x-slot:label>
                        <x-slot:input type="number" name="bill_of_lading" placeholder="0" wire:model="bill_of_lading">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Costo Transporte Terrestre 1
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="ground_transport_cost_1" placeholder="0.00" wire:model="ground_transport_cost_1">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Costo Transporte Terrestre 2
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="ground_transport_cost_2" placeholder="0.00" wire:model="ground_transport_cost_2">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Costo de Nacionalización
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="cost_nationalization" placeholder="0.00" wire:model="cost_nationalization">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Costo OFR Estimado
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="cost_ofr_estimated" placeholder="0.00" wire:model="cost_ofr_estimated">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Costo OFR Real
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="cost_ofr_real" placeholder="0.00" wire:model="cost_ofr_real">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Costo Estimado de Pallets
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="estimated_pallet_cost" placeholder="0.00" wire:model="estimated_pallet_cost">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Costo Real Estimado PO
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="real_cost_estimated_po" placeholder="0.00" wire:model="real_cost_estimated_po">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Costo Real PO
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="real_cost_real_po" placeholder="0.00" wire:model="real_cost_real_po">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Otros Costos
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="other_costs" placeholder="0.00" wire:model="other_costs">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Otros Gastos
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="other_expenses" placeholder="0.00" wire:model="other_expenses">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Peso Variable Calculado
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="variable_calculare_weight" placeholder="0.00" wire:model="variable_calculare_weight">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Ahorros OFR FCL
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="savings_ofr_fcl" placeholder="0.00" wire:model="savings_ofr_fcl">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Ahorro en Recogida
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="saving_pickup" placeholder="0.00" wire:model="saving_pickup">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Ahorro Ejecutado
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="saving_executed" placeholder="0.00" wire:model="saving_executed">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Ahorro No Ejecutado
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="saving_not_executed" placeholder="0.00" wire:model="saving_not_executed">
                        </x-slot:input>
                    </x-form-input>
                </div>
            </div>
        </div>

        <div class="w-full p-8 space-y-6 bg-white rounded-2xl">
            <h3 class="w-fit border-b-2 border-[#190FDB] pb-2 text-lg font-bold text-[#190FDB]">Carga / Contenido</h3>

            <div class="flex flex-col space-y-4">
                <!-- Buscador de productos -->
                <div class="flex gap-4">
                    <div class="w-full max-w-md">
                        <div class="relative">
                            <x-form-input class="w-full">
                                <x-slot:label>
                                    Buscar producto
                                </x-slot:label>
                                <x-slot:input name="searchTerm" wire:model.live="searchTerm"
                                    wire:keyup="searchProducts" placeholder="Buscar por ID o descripción">
                                </x-slot:input>
                            </x-form-input>
                            @if (count($searchResults) > 0)
                                <div class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg">
                                    <ul class="py-1 overflow-auto text-base rounded-md max-h-60 sm:text-sm">
                                        @foreach ($searchResults as $product)
                                            <li class="relative py-2 pl-3 cursor-pointer select-none pr-9 hover:bg-gray-100"
                                                wire:click="selectProduct({{ $product->id }})">
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

                    <x-form-input class="w-24">
                        <x-slot:label>
                            Cantidad
                        </x-slot:label>
                        <x-slot:input type="number" min="1" name="quantity" wire:model="quantity">
                        </x-slot:input>
                    </x-form-input>

                    <div class="self-end h-fit" x-data="{ selectedProduct: @entangle('selectedProduct') }">
                        <x-primary-button class="border-[3px] border-[#565AFF] disabled:border-[#EDEDED]"
                             wire:click="addProduct">
                            Agregar
                        </x-primary-button>
                    </div>
                </div>

                <!-- Producto seleccionado -->
                @if ($selectedProduct)
                    <div class="p-3 mt-2 rounded-md bg-gray-50">
                        <div class="flex justify-between">
                            <div>
                                <p class="font-medium">{{ $selectedProduct->material_id }}</p>
                                <p class="text-sm text-gray-500">{{ $selectedProduct->description }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">Precio: {{ number_format($selectedProduct->price_per_unit, 2) }}
                                </p>
                                <p class="text-sm text-gray-500">Subtotal:
                                    {{ number_format($selectedProduct->price_per_unit * $quantity, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tabla de productos agregados -->
                <div class="mt-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="bg-[#E0E5FF] px-6 py-3 text-left text-lg font-bold text-[#171717]">
                                    ID</th>
                                <th scope="col"
                                    class="bg-[#E0E5FF] px-6 py-3 text-left text-lg font-bold text-[#171717]">
                                    Descripción</th>
                                <th scope="col"
                                    class="bg-[#E0E5FF] px-6 py-3 text-left text-lg font-bold text-[#171717]">
                                    Precio unitario</th>
                                <th scope="col"
                                    class="bg-[#E0E5FF] px-6 py-3 text-left text-lg font-bold text-[#171717]">
                                    Cantidad</th>
                                <th scope="col"
                                    class="bg-[#E0E5FF] px-6 py-3 text-left text-lg font-bold text-[#171717]">
                                    Subtotal</th>
                                <th scope="col"
                                    class="bg-[#E0E5FF] px-6 py-3 text-left text-lg font-bold text-[#171717]">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($orderProducts as $index => $product)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                        {{ $product['material_id'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $product['description'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ number_format($product['price_per_unit'], 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        <input type="number"
                                            wire:model.live="orderProducts.{{ $index }}.quantity"
                                            wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                            min="1"
                                            class="block w-20 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ number_format($product['subtotal'], 2) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        <button type="button" wire:click="removeProduct({{ $index }})"
                                            class="text-red-600 hover:text-red-900">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">No hay
                                        productos agregados</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-right text-gray-900">
                                    Total Neto:</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                    {{ number_format($net_total, 2) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-right text-gray-900">
                                    Costo Adicional:</td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                    <input type="number" wire:model.live="additional_cost" step="0.01"
                                        class="block w-32 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm font-medium text-right text-gray-900">
                                    Costo de Seguro:</td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                    <input type="number" wire:model.live="insurance_cost" step="0.01"
                                        class="block w-32 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm font-bold text-right text-gray-900">TOTAL:
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 whitespace-nowrap">
                                    {{ number_format($total, 2) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </x-form>
</div>

<script>
    // Ejecutar cuando el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        // Función para encontrar un campo por su nombre
        function findField(name) {
            return document.querySelector(`input[name="${name}"]`);
        }

        // Obtener referencias a los campos
        const lengthCmField = findField('largo_cm');
        const widthCmField = findField('ancho_cm');
        const heightCmField = findField('alto_cm');
        const lengthInField = findField('largo');
        const widthInField = findField('ancho');
        const heightInField = findField('alto');
        const volumeField = findField('volumen');
        const weightKgField = findField('peso_kg');
        const weightLbField = findField('peso_lb');

        console.log('Campos encontrados:', {
            lengthCm: lengthCmField,
            widthCm: widthCmField,
            heightCm: heightCmField,
            lengthIn: lengthInField,
            widthIn: widthInField,
            heightIn: heightInField,
            volume: volumeField,
            weightKg: weightKgField,
            weightLb: weightLbField
        });

        // Constante de conversión: 1 cm = 0.393701 pulgadas
        const CM_TO_INCH = 0.393701;

        // Función para convertir cm a pulgadas
        function convertCmToInch(value) {
            return value * CM_TO_INCH;
        }

        // Función para convertir cm a pulgadas y actualizar campos
        function updateDimensions() {
            console.log('Actualizando dimensiones');

            const lengthCm = parseFloat(lengthCmField.value) || 0;
            const widthCm = parseFloat(widthCmField.value) || 0;
            const heightCm = parseFloat(heightCmField.value) || 0;

            // Convertir a pulgadas
            const lengthIn = convertCmToInch(lengthCm);
            const widthIn = convertCmToInch(widthCm);
            const heightIn = convertCmToInch(heightCm);

            // Actualizar campos de pulgadas
            lengthInField.value = lengthIn.toFixed(2);
            widthInField.value = widthIn.toFixed(2);
            heightInField.value = heightIn.toFixed(2);

            // Disparar eventos para que Livewire detecte los cambios
            lengthInField.dispatchEvent(new Event('input', { bubbles: true }));
            widthInField.dispatchEvent(new Event('input', { bubbles: true }));
            heightInField.dispatchEvent(new Event('input', { bubbles: true }));

            console.log('Dimensiones convertidas a pulgadas:', {
                lengthIn: lengthIn.toFixed(2),
                widthIn: widthIn.toFixed(2),
                heightIn: heightIn.toFixed(2)
            });

            // Calcular volumen después de actualizar las dimensiones
            calculateVolume();
        }

        // Función para calcular el volumen en pies cúbicos
        function calculateVolume() {
            console.log('Calculando volumen');
            const lengthIn = parseFloat(lengthInField.value) || 0;
            const widthIn = parseFloat(widthInField.value) || 0;
            const heightIn = parseFloat(heightInField.value) || 0;

            if (lengthIn && widthIn && heightIn) {
                // Fórmula para convertir pulgadas cúbicas a pies cúbicos: (L × W × H) ÷ 1728
                const volume = (lengthIn * widthIn * heightIn) / 1728;
                volumeField.value = volume.toFixed(3);

                // Disparar evento de cambio para que Livewire detecte el cambio
                volumeField.dispatchEvent(new Event('input', { bubbles: true }));
                console.log('Volumen calculado en pies cúbicos:', volume.toFixed(3));
            }
        }

        // Función para convertir kg a lb
        function convertKgToLb() {
            console.log('Convirtiendo kg a lb');
            const kg = parseFloat(weightKgField.value) || 0;

            if (kg) {
                const lb = kg * 2.20462;
                weightLbField.value = Math.round(lb);

                // Disparar evento de cambio para que Livewire detecte el cambio
                weightLbField.dispatchEvent(new Event('input', { bubbles: true }));
                console.log('Peso convertido a lb:', Math.round(lb));
            }
        }

        // Función para convertir lb a kg
        function convertLbToKg() {
            console.log('Convirtiendo lb a kg');
            const lb = parseFloat(weightLbField.value) || 0;

            if (lb) {
                const kg = lb * 0.453592;
                weightKgField.value = Math.round(kg);

                // Disparar evento de cambio para que Livewire detecte el cambio
                weightKgField.dispatchEvent(new Event('input', { bubbles: true }));
                console.log('Peso convertido a kg:', Math.round(kg));
            }
        }

        // Agregar event listeners para los campos en cm
        if (lengthCmField) {
            lengthCmField.addEventListener('input', updateDimensions);
            console.log('Event listener agregado a lengthCm');
        }

        if (widthCmField) {
            widthCmField.addEventListener('input', updateDimensions);
            console.log('Event listener agregado a widthCm');
        }

        if (heightCmField) {
            heightCmField.addEventListener('input', updateDimensions);
            console.log('Event listener agregado a heightCm');
        }

        // Mantener los event listeners para peso
        if (weightKgField) {
            weightKgField.addEventListener('input', convertKgToLb);
            console.log('Event listener agregado a weightKg');
        }

        if (weightLbField) {
            weightLbField.addEventListener('input', convertLbToKg);
            console.log('Event listener agregado a weightLb');
        }

        // Calcular valores iniciales si ya hay datos
        updateDimensions();
        convertKgToLb();

        console.log('Script de cálculo inicializado');
    });
</script>
