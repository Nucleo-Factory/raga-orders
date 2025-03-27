<x-app-layout>
    <div class="flex items-center justify-between">
        <x-view-title>
            <x-slot:title>
                {{ $title }}
            </x-slot:title>
            <x-slot:content>
                {{ $subtitle }}
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

    <nav class="px-6 py-4 text-lg bg-white rounded-2xl">
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
