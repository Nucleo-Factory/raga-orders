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

    <form wire:submit.prevent="saveHub">
        <div class="w-full max-w-[1254px] space-y-6">
            <div class="grid grid-cols-[1fr,1fr,1fr] gap-x-5 gap-y-6">
                <!-- Información básica -->
                <x-form-input>
                    <x-slot:label>
                        Nombre *
                    </x-slot:label>
                    <x-slot:input name="name" placeholder="Ingrese nombre del hub" wire:model="name" required></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('name') }}
                    </x-slot:error>
                </x-form-input>

                <x-form-input>
                    <x-slot:label>
                        Código de Hub
                    </x-slot:label>
                    <x-slot:input name="code" placeholder="Ingrese código del hub" wire:model="code"></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('code') }}
                    </x-slot:error>
                </x-form-input>

                <x-form-input>
                    <x-slot:label>
                        País
                    </x-slot:label>
                    <x-slot:input name="country" placeholder="Ingrese país del hub" wire:model="country"></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('country') }}
                    </x-slot:error>
                </x-form-input>

                <x-form-input>
                    <x-slot:label>
                        Corte documental
                    </x-slot:label>
                    <x-slot:input name="documentary_cut" placeholder="Ingrese corte documental" wire:model="documentary_cut"></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('contact_person') }}
                    </x-slot:error>
                </x-form-input>

                <!-- Dirección y contacto -->
                <x-form-input>
                    <x-slot:label>
                        Zarpe
                    </x-slot:label>
                    <x-slot:input name="zarpe" placeholder="Ingrese zarpe" wire:model="zarpe"></x-slot:input>
                    <x-slot:error>
                        {{ $errors->first('zarpe') }}
                    </x-slot:error>
                </x-form-input>
            </div>

            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ route('vendors.index') }}" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-md hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    {{ $isEdit ? 'Actualizar' : 'Crear' }} Hub
                </button>
            </div>
        </div>
    </form>
</div>
