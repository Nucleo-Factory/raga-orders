<div>
<div class="p-6 bg-white rounded-2xl">
    <h2 class="mb-6 text-2xl font-bold">Notificaciones</h2>

    <form wire:submit.prevent="save">
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tipo de notificaciones -->
        <div class="mb-8">
            <h4 class="text-lg font-bold text-[#7288FF] mb-4">Tipo de notificaciones</h4>

            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <button type="button" wire:click="togglePreference('mobile_notifications')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $preferences['mobile_notifications']['enabled'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $preferences['mobile_notifications']['enabled'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <div>
                        <p class="text-gray-800">Notificaciones móviles: Activa/desactiva alertas en la app móvil sobre actualizaciones importantes (como cambios en órdenes o estados de carga).</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <button type="button" wire:click="togglePreference('email_notifications')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $preferences['email_notifications']['enabled'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $preferences['email_notifications']['enabled'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <div>
                        <p class="text-gray-800">Notificaciones por correo electrónico: Selecciona qué eventos deben enviarse por email (órdenes confirmadas, entregas, problemas detectados)</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <button type="button" wire:click="togglePreference('platform_notifications')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $preferences['platform_notifications']['enabled'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $preferences['platform_notifications']['enabled'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <div>
                        <p class="text-gray-800">Notificaciones en la plataforma (desktop): Activa pop-ups o banners dentro del dashboard para tareas urgentes o recordatorios.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Frecuencia -->
        <div class="mb-8">
            <h4 class="text-lg font-bold text-[#7288FF] mb-4">Frecuencia</h4>

            <div class="space-y-6">
                <div class="flex items-center">
                    <button type="button" wire:click="toggleFrequency('immediate')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $frequencies['immediate'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $frequencies['immediate'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <label class="ml-2">Inmediato: Notificaciones enviadas al instante.</label>
                </div>

                <div class="flex items-center">
                    <button type="button" wire:click="toggleFrequency('daily')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $frequencies['daily'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $frequencies['daily'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <label class="ml-2">Diario: Resumen de actividad al final del día.</label>
                </div>

                <div class="flex items-center">
                    <button type="button" wire:click="toggleFrequency('weekly')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $frequencies['weekly'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $frequencies['weekly'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <label class="ml-2">Semanal: Resumen consolidado de la semana.</label>
                </div>
            </div>
        </div>

        <!-- Cargas y envíos -->
        <div class="mb-8">
            <h4 class="text-lg font-bold text-[#7288FF] mb-4">Cargas y envíos</h4>

            <div class="space-y-6">
                <div class="flex items-center">
                    <button type="button" wire:click="togglePreference('status_update')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $preferences['status_update']['enabled'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $preferences['status_update']['enabled'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <label class="ml-2">Actualización de estado.</label>
                </div>

                <div class="flex items-center">
                    <button type="button" wire:click="togglePreference('issues_detected')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $preferences['issues_detected']['enabled'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $preferences['issues_detected']['enabled'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <label class="ml-2">Problemas detectados.</label>
                </div>

                <div class="flex items-center">
                    <button type="button" wire:click="togglePreference('successful_deliveries')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $preferences['successful_deliveries']['enabled'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $preferences['successful_deliveries']['enabled'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <label class="ml-2">Entregas exitosas.</label>
                </div>
            </div>
        </div>

        <!-- Recordatorios -->
        <div class="mb-8">
            <h4 class="text-lg font-bold text-[#7288FF] mb-4">Recordatorios:</h4>

            <div class="space-y-6">
                <div class="flex items-center">
                    <button type="button" wire:click="togglePreference('pending_tasks')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $preferences['pending_tasks']['enabled'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $preferences['pending_tasks']['enabled'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <label class="ml-2">Tareas pendientes.</label>
                </div>

                <div class="flex items-center">
                    <button type="button" wire:click="togglePreference('upcoming_deadlines')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $preferences['upcoming_deadlines']['enabled'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $preferences['upcoming_deadlines']['enabled'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <label class="ml-2">Vencimientos próximos</label>
                </div>

                <div class="flex items-center">
                    <button type="button" wire:click="togglePreference('user_customization')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $preferences['user_customization']['enabled'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $preferences['user_customization']['enabled'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <label class="ml-2">Personalización por usuario:</label>
                </div>
            </div>
        </div>

        <!-- Órdenes -->
        <div class="mb-8">
            <h4 class="text-lg font-bold text-[#7288FF] mb-4">Órdenes:</h4>

            <div class="space-y-6">
                <div class="flex items-center">
                    <button type="button" wire:click="togglePreference('order_creation_changes')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $preferences['order_creation_changes']['enabled'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $preferences['order_creation_changes']['enabled'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <label class="ml-2">Creación o cambios en PO'S.</label>
                </div>

                <div class="flex items-center">
                    <button type="button" wire:click="togglePreference('order_consolidation')" class="toggle-button">
                        <div class="w-12 h-6 rounded-full transition-all {{ $preferences['order_consolidation']['enabled'] ?? false ? 'bg-[#7288FF]' : 'bg-gray-300' }} relative">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all {{ $preferences['order_consolidation']['enabled'] ?? false ? 'right-1' : 'left-1' }}"></div>
                        </div>
                    </button>
                    <label class="ml-2">Al consolidar una orden.</label>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button
                type="submit"
                class="w-full primary-btn h-[46px] bg-[#565AFF] rounded-[6px] text-white hover:bg-[#565AFF]/80 transition-colors duration-300"
            >
                Guardar preferencias
            </button>
        </div>
    </form>
</div>

<style>
/* Estilos para los botones de alternancia */
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
