<div class="bg-white shadow rounded-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Módulo de Confirmación de POs</h2>
        <div class="flex items-center space-x-3">
            <span class="text-sm text-gray-600">Estado:</span>
            <button wire:click="toggleModule"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $isEnabled ? 'bg-blue-600' : 'bg-gray-200' }}">
                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $isEnabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
            </button>
            <span class="text-sm font-medium {{ $isEnabled ? 'text-blue-600' : 'text-gray-500' }}">
                {{ $isEnabled ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
    </div>

    @if($message)
        <div class="mb-6 p-4 rounded-md {{ $messageType === 'success' ? 'bg-green-50 border border-green-200' : ($messageType === 'error' ? 'bg-red-50 border border-red-200' : 'bg-yellow-50 border border-yellow-200') }}">
            <div class="flex">
                <div class="flex-shrink-0">
                    @if($messageType === 'success')
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    @elseif($messageType === 'error')
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium {{ $messageType === 'success' ? 'text-green-800' : ($messageType === 'error' ? 'text-red-800' : 'text-yellow-800') }}">
                        {{ $message }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Pendientes</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $statistics['pending_confirmation'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Emails Enviados</p>
                    <p class="text-2xl font-bold text-green-900">{{ $statistics['emails_sent'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-purple-600">Confirmadas</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $statistics['confirmed'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-600">Hash Válido</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $statistics['with_valid_hash'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-600">Hash Expirado</p>
                    <p class="text-2xl font-bold text-red-900">{{ $statistics['with_expired_hash'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-4">
        <button wire:click="processPendingPOs"
                wire:loading.attr="disabled"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
            <svg wire:loading wire:target="processPendingPOs" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ $processing ? 'Procesando...' : 'Procesar POs Pendientes' }}
        </button>

        <button wire:click="cleanExpiredHashes"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Limpiar Hashes Expirados
        </button>
    </div>

    <!-- Configuration Info -->
    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
        <h3 class="text-lg font-medium text-gray-900 mb-3">Configuración del Módulo</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">Expiración de Hash:</span>
                <span class="text-gray-600">{{ config('po-confirmation.hash_expiry_hours', 72) }} horas</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Envío Automático:</span>
                <span class="text-gray-600">{{ config('po-confirmation.auto_send', true) ? 'Activado' : 'Desactivado' }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Intervalo de Verificación:</span>
                <span class="text-gray-600">{{ config('po-confirmation.check_interval', 'hourly') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Notificar Admin:</span>
                <span class="text-gray-600">{{ config('po-confirmation.notify_admin_on_confirmation', true) ? 'Activado' : 'Desactivado' }}</span>
            </div>
        </div>
    </div>
</div>
