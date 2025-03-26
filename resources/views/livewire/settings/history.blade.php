<div>
    <x-slot:header>
        <x-view-title>
            <x-slot:title>
                Histórico
            </x-slot:title>

            <x-slot:content>
                Visualiza y administra las operaciones generales
            </x-slot:content>
        </x-view-title>
    </x-slot:header>

    {{-- Lista --}}
    <div class="mt-6">
        @php
        // Definir los encabezados para la tabla de histórico
        $headers = [
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'usuario' => 'Usuario',
            'accion' => 'Acción',
            'modulo' => 'Módulo',
            'descripcion' => 'Descripción',
            'ip' => 'IP'
        ];

        // Campos ordenables
        $sortable = ['id', 'fecha', 'hora', 'usuario', 'accion', 'modulo'];

        // Campos buscables
        $searchable = ['usuario', 'accion', 'modulo', 'descripcion'];

        // Campos filtrables
        $filterable = ['accion', 'modulo'];

        // Opciones de filtro
        $filterOptions = [
            'accion' => [
                'crear' => 'Crear',
                'actualizar' => 'Actualizar',
                'eliminar' => 'Eliminar',
                'login' => 'Inicio de sesión',
                'logout' => 'Cierre de sesión'
            ],
            'modulo' => [
                'usuarios' => 'Usuarios',
                'proveedores' => 'Proveedores',
                'productos' => 'Productos',
                'ordenes' => 'Órdenes',
                'embarques' => 'Embarques',
                'config' => 'Configuración'
            ]
        ];

        // Datos de ejemplo para la tabla
        $rows = [
            [
                'fecha' => '2023-11-15',
                'hora' => '08:45:23',
                'usuario' => 'admin@example.com',
                'accion' => 'crear',
                'modulo' => 'proveedores',
                'descripcion' => 'Creación de proveedor "Distribuidora XYZ"',
                'ip' => '192.168.1.100'
            ],
            [
                'fecha' => '2023-11-15',
                'hora' => '09:12:05',
                'usuario' => 'juan.perez@example.com',
                'accion' => 'actualizar',
                'modulo' => 'productos',
                'descripcion' => 'Actualización de precio del producto ID-4589',
                'ip' => '192.168.1.105'
            ],
            [
                'id' => '1003',
                'fecha' => '2023-11-15',
                'hora' => '10:30:18',
                'usuario' => 'maria.lopez@example.com',
                'accion' => 'eliminar',
                'modulo' => 'ordenes',
                'descripcion' => 'Eliminación de orden #ORD-7823 por duplicidad',
                'ip' => '192.168.1.110'
            ],
            [
                'id' => '1004',
                'fecha' => '2023-11-16',
                'hora' => '08:05:42',
                'usuario' => 'admin@example.com',
                'accion' => 'login',
                'modulo' => 'usuarios',
                'descripcion' => 'Inicio de sesión exitoso',
                'ip' => '192.168.1.100'
            ],
            [
                'id' => '1005',
                'fecha' => '2023-11-16',
                'hora' => '09:25:11',
                'usuario' => 'carlos.rodriguez@example.com',
                'accion' => 'crear',
                'modulo' => 'embarques',
                'descripcion' => 'Creación de embarque #SHP-2345',
                'ip' => '192.168.1.115'
            ],
            [
                'id' => '1006',
                'fecha' => '2023-11-16',
                'hora' => '11:45:33',
                'usuario' => 'juan.perez@example.com',
                'accion' => 'actualizar',
                'modulo' => 'proveedores',
                'descripcion' => 'Actualización de información de contacto de proveedor ID-234',
                'ip' => '192.168.1.105'
            ],
            [
                'id' => '1007',
                'fecha' => '2023-11-16',
                'hora' => '14:30:27',
                'usuario' => 'maria.lopez@example.com',
                'accion' => 'logout',
                'modulo' => 'usuarios',
                'descripcion' => 'Cierre de sesión',
                'ip' => '192.168.1.110'
            ],
            [
                'id' => '1008',
                'fecha' => '2023-11-17',
                'hora' => '08:15:52',
                'usuario' => 'admin@example.com',
                'accion' => 'actualizar',
                'modulo' => 'config',
                'descripcion' => 'Modificación de parámetros de sistema',
                'ip' => '192.168.1.100'
            ],
            [
                'id' => '1009',
                'fecha' => '2023-11-17',
                'hora' => '10:05:19',
                'usuario' => 'carlos.rodriguez@example.com',
                'accion' => 'crear',
                'modulo' => 'productos',
                'descripcion' => 'Adición de producto nuevo SKU-78954',
                'ip' => '192.168.1.115'
            ],
            [
                'id' => '1010',
                'fecha' => '2023-11-17',
                'hora' => '11:42:08',
                'usuario' => 'juan.perez@example.com',
                'accion' => 'eliminar',
                'modulo' => 'productos',
                'descripcion' => 'Eliminación de producto obsoleto ID-3245',
                'ip' => '192.168.1.105'
            ]
        ];
        @endphp

        <livewire:components.reusable-table
            :headers="$headers"
            :rows="$rows"
            :sortable="$sortable"
            :searchable="$searchable"
            :filterable="$filterable"
            :filterOptions="$filterOptions"
            :actions="true"
            :actionsView="true"
            :actionsEdit="false"
            :actionsDelete="false"
        />
    </div>

</div>
