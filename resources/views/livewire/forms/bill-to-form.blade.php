<div>
    <div class="flex max-w-[1254px] items-center justify-between mb-10">
        <x-view-title>
            <x-slot:title>
                {{ $title }}
            </x-slot:title>

            <x-slot:content>
                {{ $subtitle }}
            </x-slot:content>
        </x-view-title>
    </div>

    <form wire:submit.prevent="saveBillTo">
        <div class="w-full max-w-[1254px] space-y-6 bg-white rounded-2xl p-8">
            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <!-- Información básica -->
                <x-form-input>
                    <x-slot:label>
                        Nombre *
                    </x-slot:label>
                    <x-slot:input name="name" placeholder="Ingrese nombre" wire:model="name" required></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('name') }}
                    </x-slot:error>
                </x-form-input>

                <x-form-input>
                    <x-slot:label>
                        Email
                    </x-slot:label>
                    <x-slot:input type="email" name="email" placeholder="Ingrese email" wire:model="email"></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('email') }}
                    </x-slot:error>
                </x-form-input>

                <x-form-input>
                    <x-slot:label>
                        Persona de contacto
                    </x-slot:label>
                    <x-slot:input name="contact_person" placeholder="Ingrese persona de contacto" wire:model="contact_person"></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('contact_person') }}
                    </x-slot:error>
                </x-form-input>

                <!-- Dirección y contacto -->
                <x-form-input>
                    <x-slot:label>
                        Dirección
                    </x-slot:label>
                    <x-slot:input name="direccion" placeholder="Ingrese dirección" wire:model="direccion"></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('direccion') }}
                    </x-slot:error>
                </x-form-input>

                <x-form-input>
                    <x-slot:label>
                        Código Postal
                    </x-slot:label>
                    <x-slot:input name="codigo_postal" placeholder="Ingrese código postal" wire:model="codigo_postal"></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('codigo_postal') }}
                    </x-slot:error>
                </x-form-input>

                <x-form-input>
                    <x-slot:label>
                        País
                    </x-slot:label>
                    <x-slot:input name="pais" placeholder="Ingrese país" wire:model="pais"></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('pais') }}
                    </x-slot:error>
                </x-form-input>

                <x-form-input>
                    <x-slot:label>
                        Estado/Provincia
                    </x-slot:label>
                    <x-slot:input name="estado" placeholder="Ingrese estado o provincia" wire:model="estado"></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('estado') }}
                    </x-slot:error>
                </x-form-input>

                <x-form-input>
                    <x-slot:label>
                        Teléfono
                    </x-slot:label>
                    <x-slot:input name="telefono" placeholder="Ingrese teléfono" wire:model="telefono"></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('telefono') }}
                    </x-slot:error>
                </x-form-input>

                <x-form-select>
                    <x-slot:label>
                        Estado
                    </x-slot:label>
                    <x-slot:select name="status" wire:model="status">
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                    </x-slot:select>
                    <x-slot:error>
                        {{ $errors->first('status') }}
                    </x-slot:error>
                </x-form-select>
            </div>

            <div class="mt-6">
                <x-form-textarea>
                    <x-slot:label>
                        Notas
                    </x-slot:label>
                    <x-slot:textarea name="notes" placeholder="Ingrese notas adicionales" wire:model="notes" rows="4"></x-slot:textarea>
                    <x-slot:error>
                        {{ $errors->first('notes') }}
                    </x-slot:error>
                </x-form-textarea>
            </div>

            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ route('bill-to.index') }}" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-md hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    {{ $isEdit ? 'Actualizar' : 'Crear' }} Dirección de Facturación
                </button>
            </div>
        </div>
    </form>
</div>
