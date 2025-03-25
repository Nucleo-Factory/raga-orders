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

    <x-form method="GET" action="">
        <div class="space-y-10 rounded-2xl bg-white p-8">
            <div class="flex gap-4">
                <div class="w-full space-y-6">
                    <h3 class="text-lg font-bold text-[#7288FF]">Datos generales</h3>
                    <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                        <div class="relative">
                            <x-form-input>
                                <x-slot:label>
                                    Número PO
                                </x-slot:label>
                                <x-slot:input name="order_number" placeholder="Ingrese número PO"
                                    wire:model="order_number" class="pr-10">
                                </x-slot:input>
                                <x-slot:icon>
                                    <button wire:click="generateUniqueOrderNumber"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot:icon>
                            </x-form-input>
                        </div>
                        <x-form-input>
                            <x-slot:label>
                                Fecha de creación
                            </x-slot:label>
                            <x-slot:input name="order_date" placeholder="Ingrese número PO"
                                wire:model="order_date">
                            </x-slot:input>
                        </x-form-input>
                        <x-form-select label="Moneda" name="currency" wireModel="currency" :options="$currencyArray" />
                        <x-form-select label="Incoterms" name="incoterms" wireModel="incoterms" :options="$tiposIncotermArray" />
                        <x-form-input>
                            <x-slot:label>
                                HUB
                            </x-slot:label>
                            <x-slot:input name="order_place" placeholder="Ingrese lugar de orden"
                                wire:model="order_place">
                            </x-slot:input>
                        </x-form-input>
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
                    <x-form-input>
                        <x-slot:label>
                            Nombre
                        </x-slot:label>
                        <x-slot:input name="bill_to_nombre" placeholder="Ingrese nombre"
                            wire:model="bill_to_nombre">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Dirección
                        </x-slot:label>
                        <x-slot:input name="bill_to_direccion" placeholder="Ingrese dirección"
                            wire:model="bill_to_direccion">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-select label="País" name="bill_to_pais" wireModel="bill_to_pais" :options="$paisArray"
                        optionPlaceholder="Elije país" />
                    <x-form-input>
                        <x-slot:label>
                            Teléfono
                        </x-slot:label>
                        <x-slot:input name="bill_to_telefono" placeholder="Ingrese dirección"
                            wire:model="bill_to_telefono">
                        </x-slot:input>
                    </x-form-input>
                </div>
            </div>

            <div class="w-full space-y-6">
                <h3 class="text-lg font-bold text-[#7288FF]">Costos</h3>
                <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                    <x-form-input>
                        <x-slot:label>
                            Total neto
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="net_total" placeholder="0.00"
                            wire:model="net_total">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Costo adicional
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="additional_cost" placeholder="0.00"
                            wire:model="additional_cost">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Total
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="total" placeholder="0.00"
                            wire:model="total">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Costo de seguro
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="insurance_cost" placeholder="0.00"
                            wire:model="insurance_cost">
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
                            wire:model="largo">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Ancho (in)
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="ancho" placeholder="0.00"
                            wire:model="ancho">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Alto (in)
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="alto" placeholder="0.00"
                            wire:model="alto">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Volumen (ft³)
                        </x-slot:label>
                        <x-slot:input type="number" step="0.01" name="volumen" placeholder="0.00"
                            wire:model="volumen">
                        </x-slot:input>
                    </x-form-input>
                    <x-form-input>
                        <x-slot:label>
                            Pallets
                        </x-slot:label>
                        <x-slot:input type="number" step="1" min="0" name="pallets"
                            placeholder="0.00" wire:model="pallets">
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
        </div>

        <div class="w-full space-y-6 rounded-2xl bg-white p-8">
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
                                <div class="absolute z-10 mt-1 w-full rounded-md bg-white shadow-lg">
                                    <ul class="max-h-60 overflow-auto rounded-md py-1 text-base sm:text-sm">
                                        @foreach ($searchResults as $product)
                                            <li class="relative cursor-pointer select-none py-2 pl-3 pr-9 hover:bg-gray-100"
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

                    <div class="h-fit self-end" x-data="{ selectedProduct: @entangle('selectedProduct') }">
                        <x-primary-button class="border-[3px] border-[#565AFF] disabled:border-[#EDEDED]"
                            x-bind:disabled="!selectedProduct" wire:click="addProduct">
                            Agregar
                        </x-primary-button>
                    </div>
                </div>

                <!-- Producto seleccionado -->
                @if ($selectedProduct)
                    <div class="mt-2 rounded-md bg-gray-50 p-3">
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
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($orderProducts as $index => $product)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $product['material_id'] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $product['description'] }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ number_format($product['price_per_unit'], 2) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <input type="number"
                                            wire:model.live="orderProducts.{{ $index }}.quantity"
                                            wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                            min="1"
                                            class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ number_format($product['subtotal'], 2) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <button type="button" wire:click="removeProduct({{ $index }})"
                                            class="text-red-600 hover:text-red-900">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No hay
                                        productos agregados</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    Total Neto:</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ number_format($net_total, 2) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    Costo Adicional:</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    <input type="number" wire:model.live="additional_cost" step="0.01"
                                        class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    Costo de Seguro:</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    <input type="number" wire:model.live="insurance_cost" step="0.01"
                                        class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">TOTAL:
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-gray-900">
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
        const lengthField = findField('largo');
        const widthField = findField('ancho');
        const heightField = findField('alto');
        const volumeField = findField('volumen');
        const weightKgField = findField('peso_kg');
        const weightLbField = findField('peso_lb');

        console.log('Campos encontrados:', {
            length: lengthField,
            width: widthField,
            height: heightField,
            volume: volumeField,
            weightKg: weightKgField,
            weightLb: weightLbField
        });

        // Función para calcular el volumen
        function calculateVolume() {
            console.log('Calculando volumen');
            const length = parseFloat(lengthField.value) || 0;
            const width = parseFloat(widthField.value) || 0;
            const height = parseFloat(heightField.value) || 0;

            if (length && width && height) {
                const volume = (length * width * height) / 1728;
                volumeField.value = volume.toFixed(3);

                // Disparar evento de cambio para que Livewire detecte el cambio
                volumeField.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
                console.log('Volumen calculado:', volume.toFixed(3));
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
                weightLbField.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
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
                weightKgField.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
                console.log('Peso convertido a kg:', Math.round(kg));
            }
        }

        // Agregar event listeners
        if (lengthField) {
            lengthField.addEventListener('input', calculateVolume);
            console.log('Event listener agregado a length');
        }

        if (widthField) {
            widthField.addEventListener('input', calculateVolume);
            console.log('Event listener agregado a width');
        }

        if (heightField) {
            heightField.addEventListener('input', calculateVolume);
            console.log('Event listener agregado a height');
        }

        if (weightKgField) {
            weightKgField.addEventListener('input', convertKgToLb);
            console.log('Event listener agregado a weightKg');
        }

        if (weightLbField) {
            weightLbField.addEventListener('input', convertLbToKg);
            console.log('Event listener agregado a weightLb');
        }

        // Calcular valores iniciales si ya hay datos
        calculateVolume();
        convertKgToLb();

        console.log('Script de cálculo inicializado');
    });
</script>
