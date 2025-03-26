<div class="space-y-10">
    <div class="flex items-center justify-between">
        <x-view-title>
            <x-slot:title>
                Lista de Roles
            </x-slot:title>

            <x-slot:content>
                Asigne roles y permisos a los diversos usuarios
            </x-slot:content>
        </x-view-title>

        <div class="flex space-x-4">
            <a href="{{ route('settings.roles.create') }}">
                <x-black-btn>Crear nuevo rol</x-black-btn>
            </a>
        </div>
    </div>

    {{-- Lista --}}

    @if (session('message'))
        <div class="px-4 py-2 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabs for switching between views -->
    <div x-data="{ activeTab: 'kanban' }" class="mb-6">
        <!-- Kanban View -->
        <div x-show="activeTab === 'kanban'" class="mt-4">
            @php
                $headers = [
                    'name' => 'Nombre del Rol',
                    'permissions_count' => 'Número de permisos',
                    'users_count' => 'Número de miembros',
                    'actions' => 'Acciones',
                ];

                $sortable = ['name', 'permissions_count', 'users_count'];
                $searchable = ['name'];
                $filterable = ['name'];
                $filterOptions = ['name'];

                // Datos de ejemplo hasta que se implemente el modelo de Roles
                $rolesData = [
                    [
                        'id' => 1,
                        'name' => 'Administrador',
                        'permissions_count' => 15,
                        'users_count' => 3
                    ],
                    [
                        'id' => 2,
                        'name' => 'Editor',
                        'permissions_count' => 8,
                        'users_count' => 5
                    ],
                    [
                        'id' => 3,
                        'name' => 'Usuario',
                        'permissions_count' => 4,
                        'users_count' => 12
                    ],
                    [
                        'id' => 4,
                        'name' => 'Invitado',
                        'permissions_count' => 2,
                        'users_count' => 7
                    ],
                ];
            @endphp


            <livewire:components.reusable-table
                :headers="$headers"
                :sortable="$sortable"
                :searchable="$searchable"
                :filterable="$filterable"
                :filterOptions="$filterOptions"
                :actions="true"
                :actionsView="true"
                :actionsEdit="true"
                :actionsDelete="true"
                :rows="$rolesData"
                :baseRoute="'settings.roles'"
            />
        </div>
    </div>
</div>
