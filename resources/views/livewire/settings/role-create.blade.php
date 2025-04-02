<div>
<div class="space-y-8">
    <form id="roleForm" wire:submit.prevent="createRole" class="p-8 space-y-10 bg-white rounded-2xl">
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
                    <li class="flex items-start gap-4">
                        <button type="button" wire:click="togglePermission('{{ $permission }}')" class="toggle-button">
                            <div class="w-12 h-6 rounded-full transition-all {{ $selectedPermissions[$permission] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                                <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $selectedPermissions[$permission] ?? false ? 'right-1' : 'left-1' }}"></div>
                            </div>
                        </button>
                        <label class="ml-2">{{ $permissions[$permission] }}</label>
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
                        <li class="flex items-start gap-4">
                            <button type="button" wire:click="togglePermission('{{ $key }}')" class="toggle-button">
                                <div class="w-12 h-6 rounded-full transition-all {{ $selectedPermissions[$key] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                                    <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $selectedPermissions[$key] ?? false ? 'right-1' : 'left-1' }}"></div>
                                </div>
                            </button>
                            <label class="ml-2">{{ $label }}</label>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </form>
</div>

<style>
.toggle-button {
    display: inline-block;
    height: 24px;
    outline: none;
    border: none;
    background: transparent;
    cursor: pointer;
    padding: 0;
}
</style>
</div>
