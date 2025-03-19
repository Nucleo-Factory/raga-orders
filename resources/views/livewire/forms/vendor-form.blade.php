<div>
    <form wire:submit.prevent="saveVendor">
        <div class="w-full max-w-[1254px] space-y-6">
            <h3 class="text-xl">Datos del Proveedor</h3>

            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <!-- Información básica -->
                <x-form-input
                    label="Nombre *"
                    type="text"
                    name="name"
                    wireModel="name"
                    placeholder="Ingrese nombre del proveedor"
                    required
                />
                <x-form-input
                    label="Email"
                    type="email"
                    name="email"
                    wireModel="email"
                    placeholder="Ingrese email del proveedor"
                />
                <x-form-input
                    label="Persona de contacto"
                    type="text"
                    name="contact_person"
                    wireModel="contact_person"
                    placeholder="Ingrese persona de contacto"
                />

                <!-- Dirección y contacto -->
                <x-form-input
                    label="Dirección"
                    type="text"
                    name="vendor_direccion"
                    wireModel="vendor_direccion"
                    placeholder="Ingrese dirección del proveedor"
                />
                <x-form-input
                    label="Código Postal"
                    type="text"
                    name="vendor_codigo_postal"
                    wireModel="vendor_codigo_postal"
                    placeholder="Ingrese código postal"
                />
                <x-form-input
                    label="País"
                    type="text"
                    name="vendor_pais"
                    wireModel="vendor_pais"
                    placeholder="Ingrese país"
                />
                <x-form-input
                    label="Estado/Provincia"
                    type="text"
                    name="vendor_estado"
                    wireModel="vendor_estado"
                    placeholder="Ingrese estado o provincia"
                />
                <x-form-input
                    label="Teléfono"
                    type="text"
                    name="vendor_telefono"
                    wireModel="vendor_telefono"
                    placeholder="Ingrese teléfono"
                />
                <x-form-select
                    label="Estado"
                    name="status"
                    wireModel="status"
                    :options="['active' => 'Activo', 'inactive' => 'Inactivo']"
                />
            </div>

            <div class="mt-6">
                <x-form-textarea
                    label="Notas"
                    name="notes"
                    wireModel="notes"
                    placeholder="Ingrese notas adicionales sobre el proveedor"
                    rows="4"
                />
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('vendors.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ $isEdit ? 'Actualizar' : 'Crear' }} Proveedor
                </button>
            </div>
        </div>
    </form>
</div>
