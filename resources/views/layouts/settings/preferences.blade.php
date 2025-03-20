<x-app-layout>
    <x-view-title title="Configuraciones" subtitle="Visualiza y administra las configuraciones de la plataforma" />

    <nav class="rounded-2xl bg-white px-6 py-4">
        <ul class="flex items-center justify-between">
            <li>
                <livewire:settings.nav-link text="Generales" :route="'settings.index'" />
            </li>
            <li>
                <livewire:settings.nav-link text="Notificaciones" :route="'settings.notifications'" />
            </li>
            <li>
                <livewire:settings.nav-link text="Contraseña" :route="'settings.password'" />
            </li>
            <li>
                <a href="#">
                    Sesiones
                </a>
            </li>
            <li>
                <a href="#">
                    Etapas
                </a>
            </li>
        </ul>
    </nav>

    {{ $slot }}
</x-app-layout>
