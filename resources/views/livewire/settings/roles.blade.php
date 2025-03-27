<div class="space-y-10 rounded-2xl bg-white p-8">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-[#7288FF]">Lista de roles</h2>

        <div class="flex space-x-4">
            <a href="{{ route('settings.roles.create') }}">
                <x-primary-button class="flex items-center gap-[0.625rem]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M8 1V15M1 8H15" stroke="#F7F7F7" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span>Crear nuevo rol</span>
                </x-primary-button>
            </a>
        </div>
    </div>

    {{-- Lista --}}
    @if (session('message'))
        <div class="mb-4 rounded border border-green-400 bg-green-100 px-4 py-2 text-green-700">
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
                        'users_count' => 3,
                    ],
                    [
                        'id' => 2,
                        'name' => 'Editor',
                        'permissions_count' => 8,
                        'users_count' => 5,
                    ],
                    [
                        'id' => 3,
                        'name' => 'Usuario',
                        'permissions_count' => 4,
                        'users_count' => 12,
                    ],
                    [
                        'id' => 4,
                        'name' => 'Invitado',
                        'permissions_count' => 2,
                        'users_count' => 7,
                    ],
                ];
            @endphp

            <livewire:components.reusable-table :headers="$headers" :sortable="$sortable" :searchable="$searchable" :filterable="$filterable"
                :filterOptions="$filterOptions" :actions="true" :actionsView="true" :actionsEdit="true" :actionsDelete="true"
                :rows="$rolesData" :baseRoute="'settings.roles'" />
        </div>
    </div>
</div>
