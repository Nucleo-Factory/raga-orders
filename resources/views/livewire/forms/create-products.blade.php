<div>
    <div class="flex items-center justify-between" wire:ignore>
        <x-view-title>
            <x-slot:title>
                {{ $title }}
            </x-slot:title>

            <x-slot:content>
                {{ $subtitle }}
            </x-slot:content>
        </x-view-title>

        <div class="flex gap-4">
            <x-primary-button wire:click="createProduct" class="w-[209px]">
                Crear producto
            </x-primary-button>
        </div>
    </div>

    <x-form wire:submit.prevent="createProduct">
        <div class="p-8 space-y-10 bg-white rounded-2xl">
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
                        </div>
                        <x-form-input class="grow">
                            <x-slot:label>
                                Short Text
                            </x-slot:label>
                            <x-slot:input name="short_text" placeholder="Ingrese short text"
                                wire:model="short_text">
                            </x-slot:input>
                        </x-form-input>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <x-form-input>
                    <x-slot:label>
                        Supplying Plant
                    </x-slot:label>
                    <x-slot:input name="supplying_plant" placeholder="Ingrese supplying plant" wire:model="supplying_plant">
                    </x-slot:input>
                </x-form-input>

                <x-form-input>
                    <x-slot:label>
                        Unit of Measure
                    </x-slot:label>
                    <x-slot:input name="unit_of_measure" placeholder="Ingrese unidad de medida" wire:model="unit_of_measure">
                    </x-slot:input>
                </x-form-input>
                <x-form-select label="QTY Unit" name="qty_unit" :options="$qtyUnitOptions" wire:model="qty_unit" />
                <x-form-input>
                    <x-slot:label>
                        Plant
                    </x-slot:label>
                    <x-slot:input name="plant" placeholder="Ingrese planta" wire:model="plant">
                    </x-slot:input>
                </x-form-input>
                <x-form-input>
                    <x-slot:label>
                        Vendor Name
                    </x-slot:label>
                    <x-slot:input name="vendor_name" placeholder="Ingrese nombre del proveedor" wire:model="vendor_name">
                    </x-slot:input>
                </x-form-input>
                <x-form-input>
                    <x-slot:label>
                        Vendor Code
                    </x-slot:label>
                    <x-slot:input name="vendor_code" placeholder="Ingrese código del proveedor" wire:model="vendor_code">
                    </x-slot:input>
                </x-form-input>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 mt-4 text-green-700 bg-green-100 rounded-md">
                {{ session('message') }}
            </div>
        @endif
    </x-form>

    <x-modal-success name="modal-product-created">
        <x-slot:title>
            Producto creado correctamente
        </x-slot:title>

        <x-slot:description>
            El producto ha sido creado correctamente
        </x-slot:description>

        <x-primary-button wire:click="$dispatch('close-modal', 'modal-product-created')" class="w-full">
            Cerrar
        </x-primary-button>
    </x-modal-success>
</div>
