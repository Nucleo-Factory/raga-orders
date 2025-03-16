<form class="space-y-6 rounded-2xl bg-white px-6 py-4">
    <div>
        <h4 class="mb-4 text-lg font-bold text-[#7288FF]">Tipo de notificaciones</h4>

        <ul class="space-y-4">
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications1"
                label="Notificaciones móviles: Activa/desactiva alertas en la app móvil sobre actualizaciones importantes (como cambios en órdenes o estados de carga)."
                :checked="false" />
            </li>
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications2"
                label="Notificaciones por correo electrónico: Selecciona qué eventos deben enviarse por email (órdenes
                    confirmadas, entregas, problemas detectados)."
                :checked="false" />
            </li>
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications3"
                label="Notificaciones en la plataforma (desktop): Activa pop-ups o banners dentro del dashboard para
                    tareas urgentes o recordatorios."
                :checked="false" />
            </li>
        </ul>
    </div>

    <div>
        <h4 class="mb-4 text-lg font-bold text-[#7288FF]">Frecuencia</h4>

        <ul class="space-y-4">
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications4"
                label="Inmediato: Notificaciones enviadas al instante."
                :checked="false" />
            </li>
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications5"
                label="Diario: Resumen de actividad al final del día."
                :checked="false" />
            </li>
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications6"
                label="Semanal: Resumen consolidado de la semana."
                :checked="false" />
            </li>
        </ul>
    </div>

    <div>
        <h4 class="mb-4 text-lg font-bold text-[#7288FF]">Cargas y envios</h4>

        <ul class="space-y-4">
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications7"
                label="Actualización de estado."
                :checked="false" />
            </li>
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications8"
                label="Problemas detectados."
                :checked="false" />
            </li>
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications9"
                label="Entregas exitosas."
                :checked="false" />
            </li>
        </ul>
    </div>

    <div>
        <h4 class="mb-4 text-lg font-bold text-[#7288FF]">Recordatorios</h4>

        <ul class="space-y-4">
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications10"
                label="Tareas pendientes."
                :checked="false" />
            </li>
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications11"
                label="Vencimientos próximos."
                :checked="false" />
            </li>
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications12"
                label="Personalización por usuario."
                :checked="false" />
            </li>
        </ul>
    </div>

    <div class="mb-8">
        <h4 class="mb-4 text-lg font-bold text-[#7288FF]">Órdenes</h4>

        <ul class="space-y-4">
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications13"
                label="Creación o cambios en PO's."
                :checked="false" />
            </li>
            <li class="flex items-center gap-4">
                <x-toggler id="mobile-notifications14"
                label="Al consolidar una orden."
                :checked="false" />
            </li>
        </ul>
    </div>

    <span class="block text-sm text-[#171717]">Cada usuario puede activar/desactivar notificaciones según su rol en
        la plataforma (e.g., operador, administrador).</span>
</form>
