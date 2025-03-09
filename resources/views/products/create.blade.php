{{-- TODO: Modificar con datos dinámicos --}}
<x-app-layout>
    <div class="flex max-w-[1254px] items-center justify-between">
        <x-view-title title="Crear nuevo producto" subtitle="Ingrese los datos para cargar su producto" />

        <x-black-btn>Crear producto</x-black-btn>
    </div>

    <x-form>
        <div class="w-full max-w-[1254px] space-y-6">
            <h3 class="text-xl">Datos generales</h3>

            <div class="grid grid-cols-3 gap-x-5 gap-y-6">
                <x-form-input label="Item" type="number" name="item" placeholder="Item" />
                <x-form-input label="Material" type="text" name="material" placeholder="Material" />
                <x-form-input label="Description" type="text" name="description" placeholder="Description" />
                <x-form-input label="Lgeacy material" type="text" name="legacy_material"
                    placeholder="Lgeacy material" />
                <x-form-input label="Contract" type="text" name="contract" placeholder="Contract" />
                <x-form-input label="Order Quantity" type="bumber" name="order_quantity"
                    placeholder="Order Quantity" />
                <x-form-input label="Quantity Unit" type="number" name="quantity_unit" placeholder="Quantity unit" />
                <x-form-input label="Price Per Unit" type="text" name="price_per_unit"
                    placeholder="Price Per Unit" />
                <x-form-input label="Pricing Per UOM" type="text" name="pricing_per_uom"
                    placeholder="Pricing Per UOM" />
                <x-form-input label="Contract" type="text" name="contract" placeholder="Contract" />
                <x-form-input label="Net Value" type="number" name="net_value" placeholder="Net Value" />
                <x-form-input label="VAT Rate" type="number" name="vat_rate" placeholder="VAT Rate" />
                <x-form-input label="VAT Value" type="number" name="vat_value" placeholder="VAT Value" />
            </div>
        </div>
    </x-form>
</x-app-layout>
