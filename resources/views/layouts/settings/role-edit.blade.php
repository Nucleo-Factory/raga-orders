<x-app-layout>
    <div class="flex items-center justify-between">
        <x-view-title>
            <x-slot:title>
                Editar rol
            </x-slot:title>
            <x-slot:content>
                Asigne roles y permisos a los diversos usuarios
            </x-slot:content>
        </x-view-title>

        <div class="flex items-center gap-4">
            <x-primary-button>
                Guardar rol
            </x-primary-button>

            <x-secondary-button>
                Cancelar
            </x-secondary-button>
        </div>
    </div>

    <nav class="rounded-2xl bg-white px-6 py-4 text-lg">
        <ul class="flex items-center justify-between">
            <li>
                <livewire:settings.nav-link text="Permisos" :route="'settings.roles'" />
            </li>
            <li>
                <livewire:settings.nav-link text="Lista de miembros" :route="'settings.roles'" />
            </li>
        </ul>
    </nav>

    {{ $slot }}
</x-app-layout>
