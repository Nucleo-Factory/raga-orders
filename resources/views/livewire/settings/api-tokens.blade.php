<div>
    <div class="flex items-center justify-between">
        <x-view-title>
            <x-slot:title>
                Gestión de Tokens API
            </x-slot:title>

            <x-slot:content>
                Gestiona todos los tokens API para acceso a la API del sistema
            </x-slot:content>
        </x-view-title>

        <button wire:click="$set('showCreateForm', true)" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
            Crear Nuevo Token
        </button>
    </div>

    @if (session('message'))
        <div class="px-4 py-2 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Formulario para crear token -->
    @if($showCreateForm ?? false)
        <div class="p-4 mb-6 rounded-lg bg-gray-50">
            <h3 class="mb-4 text-lg font-medium">Crear Nuevo Token API</h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="tokenName" class="block text-sm font-medium text-gray-700">Nombre del Token</label>
                    <input type="text" wire:model="tokenName" id="tokenName"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Ej: API Mobile App">
                    @error('tokenName') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="expiresAt" class="block text-sm font-medium text-gray-700">Fecha de Expiración (Opcional)</label>
                    <input type="datetime-local" wire:model="expiresAt" id="expiresAt"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('expiresAt') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex mt-4 space-x-2">
                <button wire:click="createToken" class="px-4 py-2 font-bold text-white bg-green-500 rounded hover:bg-green-700">
                    Crear Token
                </button>
                <button wire:click="$set('showCreateForm', false)" class="px-4 py-2 font-bold text-white bg-gray-500 rounded hover:bg-gray-700">
                    Cancelar
                </button>
            </div>
        </div>
    @endif

    <!-- Modal para mostrar nuevo token -->
    @if($showNewToken)
        <div class="fixed inset-0 z-50 w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50">
            <div class="relative p-5 mx-auto bg-white border rounded-md shadow-lg top-20 w-96">
                <div class="mt-3">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Token Creado Exitosamente</h3>
                    <p class="mb-4 text-sm text-gray-600">
                        Copia este token ahora. No podrás verlo nuevamente por seguridad.
                    </p>
                    <div class="p-3 bg-gray-100 border rounded">
                        <code class="text-sm break-all">{{ $newTokenValue }}</code>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button wire:click="closeNewTokenModal" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Lista de tokens existentes -->
    <div class="mt-6">
        <h3 class="mb-4 text-lg font-medium">Tokens Existentes</h3>

        @if($tokens->count() > 0)
            <div class="overflow-hidden bg-white shadow sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($tokens as $token)
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $token->name }}
                                        </p>
                                        <div class="flex flex-shrink-0 ml-2">
                                            @if($token->expires_at)
                                                @if($token->expires_at->isPast())
                                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                                        Expirado
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                        Activo
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                    Sin Expiración
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                Creado: {{ $token->created_at->format('d/m/Y H:i') }}
                                            </p>
                                            @if($token->last_used_at)
                                                <p class="flex items-center mt-2 text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                    Último uso: {{ $token->last_used_at->format('d/m/Y H:i') }}
                                                </p>
                                            @else
                                                <p class="flex items-center mt-2 text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                    Nunca usado
                                                </p>
                                            @endif
                                        </div>
                                        @if($token->expires_at)
                                            <div class="flex items-center mt-2 text-sm text-gray-500 sm:mt-0">
                                                Expira: {{ $token->expires_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <button wire:click="deleteToken({{ $token->id }})"
                                            wire:confirm="¿Estás seguro de que quieres eliminar este token?"
                                            class="px-3 py-1 text-sm font-bold text-white bg-red-500 rounded hover:bg-red-700">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="py-8 text-center">
                <p class="text-gray-500">No hay tokens creados aún.</p>
                <button wire:click="$set('showCreateForm', true)" class="px-4 py-2 mt-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                    Crear tu primer token
                </button>
            </div>
        @endif
    </div>
</div>
