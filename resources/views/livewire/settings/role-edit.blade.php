<div class="pb-10 space-y-10">
    <div class="flex items-center justify-between">
        <x-view-title>
            <x-slot:title>
                Editar Rol: Administrador
            </x-slot:title>
        </x-view-title>

        <div class="flex space-x-4">
            <x-black-btn>Guardar Cambios</x-black-btn>

            <x-black-btn class="bg-[#B7B7B7]">Cancelar</x-black-btn>
        </div>
    </div>

    <div class="space-y-8">
        <nav class="px-6 py-4 bg-white rounded-2xl">
            <ul class="flex gap-4">
                <li class="grow">
                    <button class="w-full rounded-lg bg-[#D8D8D8] py-2.5 text-center">Lista de miembros</button>
                </li>
                <li class="grow">
                    <button class="w-full rounded-lg py-2.5 text-center">Permisos</button>
                </li>
            </ul>
        </nav>

        <form class="px-4 space-y-6">
            <div class="space-y-4 text-lg text-[#231F20]">
                <h2>Tipo de permisos</h2>

                <ul class="space-y-4 text-sm text-[#2B3674]">
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-1" label="Permiso de lectura" :checked="false" />
                    </li>
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-2" label="Exportar datos" :checked="false" />
                    </li>
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-3" label="Filtrar" :checked="false" />
                    </li>
                </ul>
            </div>

            <div class="space-y-4 text-lg text-[#231F20]">
                <h2>Tareas y operaciones relevantes</h2>

                <ul class="space-y-4 text-sm text-[#2B3674]">
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-4" label="Consultar métricas generales de operaciones"
                            :checked="false" />
                    </li>
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-5" label="Agregar comentarios específicos a las órdenes (PO y CRM)"
                            :checked="false" />
                    </li>
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-6" label="Adjuntar documentos relacionados con las tareas asignadas"
                            :checked="false" />
                    </li>
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-7" label="Generar reportes de ahorro y costos operativos"
                            :checked="false" />
                    </li>
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-8" label="Descargar datos sobre desviaciones en órdenes"
                            :checked="false" />
                    </li>
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-9"
                            label="Monitorear perfiles de usuarios para análisis de actividades" :checked="false" />
                    </li>
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-10" label="Acceder a registros de notificaciones" :checked="false" />
                    </li>
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-11" label="Visualizar análisis y KPIs relevantes"
                            :checked="false" />
                    </li>
                    <li class="flex items-center gap-4">
                        <x-toggler id="permission-12" label="Visualizar Historial de eventos y acciones del usuario"
                            :checked="false" />
                    </li>
                </ul>
            </div>
        </form>
    </div>
</div>
