<div>
    <div class="flex max-w-[1254px] items-center justify-between">
        <x-view-title title="Crear nuevo Producto" subtitle="Ingrese los datos para crear un nuevo producto" />

        <x-black-btn wire:click="createProduct">Crear Producto</x-black-btn>
    </div>

    <x-form wire:submit.prevent="createProduct">
        <div class="w-full max-w-[1254px] space-y-6">
            <h3 class="text-xl">Datos del Producto</h3>

            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <div>
                    <x-form-input label="Material ID" name="material_id" placeholder="Ingrese ID del material" wire:model="material_id" />
                    <p class="mt-1 text-xs text-gray-500">Item ID será generado automáticamente</p>
                </div>

                <x-form-input label="Legacy Material" name="legacy_material" placeholder="Ingrese legacy material" wire:model="legacy_material" />
                <x-form-input label="Contract" name="contract" placeholder="Ingrese contrato" wire:model="contract" />

                <x-form-input label="Order Quantity" type="number" step="0.01" name="order_quantity" placeholder="Ingrese cantidad" wire:model="order_quantity" />
                <x-form-select label="QTY Unit" name="qty_unit" :options="$qtyUnitOptions" wire:model="qty_unit" />
                <x-form-input label="Price per Unit" type="number" step="0.01" name="price_per_unit" placeholder="Ingrese precio por unidad" wire:model="price_per_unit" />

                <x-form-input label="Price per UON" type="number" step="0.01" name="price_per_uon" placeholder="Ingrese precio por UON" wire:model="price_per_uon" />
                <x-form-input label="Net Value" type="number" step="0.01" name="net_value" placeholder="Valor neto" wire:model="net_value" readonly />
                <x-form-select label="VAT Rate" name="vat_rate" :options="$vatRateOptions" wire:model="vat_rate" />

                <x-form-input label="VAT Value" type="number" step="0.01" name="vat_value" placeholder="Valor de IVA" wire:model="vat_value" readonly />
                <x-form-input label="Delivery Date" type="date" name="delivery_date" wire:model="delivery_date" />
            </div>

            <div class="mt-6">
                <label class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                <textarea
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    rows="4"
                    wire:model="description"
                    placeholder="Ingrese descripción detallada del producto"></textarea>
            </div>
        </div>

        @if(session()->has('message'))
        <div class="p-4 mt-4 text-green-700 bg-green-100 rounded-md">
            {{ session('message') }}
        </div>
        @endif
    </x-form>
</div>
