<div>
    <div class="p-8 space-y-10 bg-white rounded-2xl">
        <div class="flex gap-4">
            <div class="w-full space-y-6">
                <h3 class="text-lg font-bold text-neutral-blue">{{ $title }}</h3>
                <p class="text-sm text-gray-600">{{ $subtitle }}</p>

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-2 gap-4">
                        <x-form-input>
                            <x-slot:label>
                                Nombre
                            </x-slot:label>
                            <x-slot:input
                                name="name"
                                placeholder="Ingrese nombre del usuario"
                                wire:model="name">
                            </x-slot:input>
                            @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </x-form-input>

                        <x-form-input>
                            <x-slot:label>
                                Correo Electrónico
                            </x-slot:label>
                            <x-slot:input
                                type="email"
                                name="email"
                                placeholder="Ingrese correo electrónico"
                                wire:model="email">
                            </x-slot:input>
                            @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </x-form-input>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <x-form-input>
                            <x-slot:label>
                                Contraseña {{ $id ? '(dejar en blanco para mantener)' : '' }}
                            </x-slot:label>
                            <x-slot:input
                                type="password"
                                name="password"
                                placeholder="Ingrese contraseña"
                                wire:model="password">
                            </x-slot:input>
                            @error('password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </x-form-input>

                        <x-form-select
                            label="Rol"
                            name="role_id"
                            :options="$roles->pluck('name', 'id')"
                            wire:model="role_id"
                        />
                        @error('role_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-4 mt-6">
                        <x-secondary-button type="button" wire:click="$navigate('{{ route('settings.users') }}')">
                            Cancelar
                        </x-secondary-button>

                        <x-primary-button type="submit">
                            {{ $id ? 'Actualizar Usuario' : 'Crear Usuario' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mt-4 text-green-700 bg-green-100 rounded-md">
            {{ session('message') }}
        </div>
    @endif

    <x-modal-success name="modal-user-created">
        <x-slot:title>
            Usuario creado correctamente
        </x-slot:title>

        <x-slot:description>
            El usuario ha sido creado correctamente
        </x-slot:description>

        <x-primary-button wire:click="$dispatch('close-modal', 'modal-user-created')" class="w-full">
            Cerrar
        </x-primary-button>
    </x-modal-success>
</div>
