<div class="space-y-8">
    <form id="roleForm" wire:submit.prevent="updateRole" class="p-8 space-y-10 bg-white rounded-2xl">
        @if (session()->has('message'))
            <div class="p-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <x-form-input class="w-1/4">
            <x-slot:label>
                Nombre de rol
            </x-slot:label>
            <x-slot:input name="name" placeholder="Nombre de rol" wire:model="name">
            </x-slot:input>
        </x-form-input>

        <!-- Permisos básicos -->
        <div class="space-y-4 text-lg text-[#231F20]">
            <h2>Tipo de permisos</h2>
            <ul class="space-y-4 text-sm text-[#2B3674]">
                @foreach(['read', 'export', 'filter'] as $permission)
                    <li class="flex items-center gap-4">
                        <x-toggler
                            :id="'permission-'.$permission"
                            :label="$permissions[$permission] ?? $permission"
                            wire:model.live="selectedPermissions"
                            :value="$permission"
                            :checked="in_array($permission, $selectedPermissions)"
                        />
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Permisos de operaciones -->
        <div class="space-y-4 text-lg text-[#231F20]">
            <h2>Tareas y operaciones relevantes</h2>
            <ul class="space-y-4 text-sm text-[#2B3674]">
                @foreach($permissions as $key => $label)
                    @if (!in_array($key, ['read', 'export', 'filter']))
                        <li class="flex items-center gap-4">
                            <x-toggler
                                :id="'permission-'.$key"
                                :label="$label"
                                wire:model="selectedPermissions.{{ $key }}"
                            />
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </form>
</div>
