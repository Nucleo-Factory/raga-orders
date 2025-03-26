<div>
    <form class="space-y-10 rounded-2xl bg-white p-8">
        <x-form-input class="w-1/4">
            <x-slot:label>
                Nombre de rol
            </x-slot:label>
            <x-slot:input name="" placeholder="Nombre de rol" wire:model="">
            </x-slot:input>
        </x-form-input>

        <div class="space-y-4 text-lg text-[#231F20]">
            <h2 class="text-lg font-bold text-[#7288FF]">Tipo de permisos</h2>

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
            <h2 class="text-lg font-bold text-[#7288FF]">Tareas y operaciones relevantes</h2>

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
                    <x-toggler id="permission-9" label="Monitorear perfiles de usuarios para análisis de actividades"
                        :checked="false" />
                </li>
                <li class="flex items-center gap-4">
                    <x-toggler id="permission-10" label="Acceder a registros de notificaciones" :checked="false" />
                </li>
                <li class="flex items-center gap-4">
                    <x-toggler id="permission-11" label="Visualizar análisis y KPIs relevantes" :checked="false" />
                </li>
                <li class="flex items-center gap-4">
                    <x-toggler id="permission-12" label="Visualizar Historial de eventos y acciones del usuario"
                        :checked="false" />
                </li>
            </ul>
        </div>
    </form>
</div>
