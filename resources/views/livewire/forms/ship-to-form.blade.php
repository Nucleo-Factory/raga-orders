<div>
    <div class="flex max-w-[1254px] items-center justify-between mb-10">
        <x-view-title title="{{ $title }}" subtitle="{{ $subtitle }}" />
    </div>

    <form wire:submit.prevent="saveShipTo">
        <div class="w-full max-w-[1254px] space-y-6">
            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <!-- Información básica -->
                <x-form-input
                    label="Nombre *"
                    type="text"
                    name="name"
                    wireModel="name"
                    placeholder="Ingrese nombre"
                    required
                />
                <x-form-input
                    label="Email"
                    type="email"
                    name="email"
                    wireModel="email"
                    placeholder="Ingrese email"
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
                    name="ship_to_direccion"
                    wireModel="ship_to_direccion"
                    placeholder="Ingrese dirección"
                />
                <x-form-input
                    label="Código Postal"
                    type="text"
                    name="ship_to_codigo_postal"
                    wireModel="ship_to_codigo_postal"
                    placeholder="Ingrese código postal"
                />
                <x-form-input
                    label="País"
                    type="text"
                    name="ship_to_pais"
                    wireModel="ship_to_pais"
                    placeholder="Ingrese país"
                />
                <x-form-input
                    label="Estado/Provincia"
                    type="text"
                    name="ship_to_estado"
                    wireModel="ship_to_estado"
                    placeholder="Ingrese estado o provincia"
                />
                <x-form-input
                    label="Teléfono"
                    type="text"
                    name="ship_to_telefono"
                    wireModel="ship_to_telefono"
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
                    placeholder="Ingrese notas adicionales"
                    rows="4"
                />
            </div>

            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ route('ship-to.index') }}" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-md hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    {{ $isEdit ? 'Actualizar' : 'Crear' }} Dirección de Envío
                </button>
            </div>
        </div>
    </form>
</div>
