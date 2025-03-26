<div>
    <div class="flex items-center justify-between">
        <x-view-title>
            <x-slot:title>
                {{ $title }}
            </x-slot:title>

            <x-slot:content>
                {{ $subtitle }}
            </x-slot:content>
        </x-view-title>

        <div class="flex gap-4">
            <x-secondary-button class="w-[209px]">
                Cancelar
            </x-secondary-button>

            <x-primary-button wire:click="createProduct" class="w-[209px]">
                Crear nueva orden
            </x-primary-button>
        </div>
    </div>

    <x-form wire:submit.prevent="createProduct">
        <div class="space-y-10 rounded-2xl bg-white p-8">
            <div class="flex gap-4">
                <div class="w-full space-y-6">
                    <h3 class="text-lg font-bold text-neutral-blue">Datos del Producto</h3>

                    <div class="flex gap-4">
                        <div class="grow">
                            <x-form-input>
                                <x-slot:label>
                                    Material ID
                                </x-slot:label>
                                <x-slot:input name="material_id" placeholder="Ingrese ID del material"
                                    wire:model="material_id">
                                </x-slot:input>
                            </x-form-input>
                            <p class="mt-1 text-xs text-gray-500">Item ID será generado automáticamente</p>
                        </div>
                        <x-form-input class="grow">
                            <x-slot:label>
                                Legacy Material
                            </x-slot:label>
                            <x-slot:input name="legacy_material" placeholder="Ingrese legacy material"
                                wire:model="legacy_material">
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

            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <x-form-input>
                    <x-slot:label>
                        Contract
                    </x-slot:label>
                    <x-slot:input name="contract" placeholder="Ingrese contrato" wire:model="contract">
                    </x-slot:input>
                </x-form-input>

                <x-form-input>
                    <x-slot:label>
                        Order Quantity
                    </x-slot:label>
                    <x-slot:input type="number" step="0.01" name="order_quantity"
                        placeholder="Ingrese cantidad" wire:model="order_quantity">
                    </x-slot:input>
                </x-form-input>
                <x-form-select label="QTY Unit" name="qty_unit" :options="$qtyUnitOptions" wire:model="qty_unit" />
                <x-form-input>
                    <x-slot:label>
                        Price per Unit
                    </x-slot:label>
                    <x-slot:input type="number" step="0.01" name="price_per_unit"
                        placeholder="Ingrese precio por unidad" wire:model="price_per_unit">
                    </x-slot:input>
                </x-form-input>
                <x-form-input>
                    <x-slot:label>
                        Price per UON
                    </x-slot:label>
                    <x-slot:input type="number" step="0.01" name="price_per_uon"
                        placeholder="Ingrese precio por UON" wire:model="price_per_uon">
                    </x-slot:input>
                </x-form-input>
                <x-form-input>
                    <x-slot:label>
                        Net Value
                    </x-slot:label>
                    <x-slot:input type="number" step="0.01" name="net_value" placeholder="Valor neto"
                        wire:model="net_value" readonly>
                    </x-slot:input>
                </x-form-input>
                <x-form-select label="VAT Rate" name="vat_rate" :options="$vatRateOptions" wire:model="vat_rate" />

                <x-form-input>
                    <x-slot:label>
                        VAT Value
                    </x-slot:label>
                    <x-slot:input type="number" step="0.01" name="vat_value" placeholder="Valor de IVA"
                        wire:model="vat_value" readonly>
                    </x-slot:input>
                </x-form-input>
                <x-form-input>
                    <x-slot:label>
                        Price per UON
                    </x-slot:label>
                    <x-slot:input type="date" name="delivery_date" wire:model="delivery_date">
                    </x-slot:input>
                </x-form-input>
            </div>

            <div class="mt-6">
                <x-form-textarea rows="4" wire:model="description"
                    placeholder="Ingrese descripción detallada del producto" />
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mt-4 rounded-md bg-green-100 p-4 text-green-700">
                {{ session('message') }}
            </div>
        @endif
    </x-form>
</div>
