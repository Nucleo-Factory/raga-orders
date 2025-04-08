    <!-- Contenido Principal (sin sidebar) -->
    <div class="p-6">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-[44px] font-bold">¡Hola {{ $user->name }}!</h1>
            <p class="text-lg text-gray-600">Visualiza y administra tu perfil</p>
        </div>

        <!-- Contenido del Perfil -->
        <div class="flex flex-col gap-6 lg:flex-row">
            <!-- Columna Izquierda (40%) -->
            <div class="lg:w-[35%] space-y-6 flex flex-col">
                <!-- Tarjeta de Perfil -->
                <div class="flex-1 p-8 bg-white rounded-lg">
                    <div class="relative w-[206px] h-[206px] mx-auto mb-4">
                        <div class="w-full h-full overflow-hidden rounded-full">
                            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Captura%20de%20pantalla%202025-04-07%20a%20la%28s%29%2010.57.21%E2%80%AFp.%C2%A0m.-nA7CeLiGVWDs4fuuPVs2ZpyFP5lzwD.png" alt="Profile" class="object-cover w-full h-full">
                        </div>
                        <button class="absolute bottom-0 right-0 flex items-center justify-center w-10 h-10 text-white bg-indigo-500 rounded-lg">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    </div>
                    <div class="text-center">
                        <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                        <p class="text-lg text-gray-500">{{ $user->roles->first()->name }}</p>
                    </div>
                </div>

                <!-- Sección de Plan -->
                <div class="flex-1 p-6 bg-white rounded-lg">
                    <!-- Tu plan -->
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-indigo-500">Tu plan</h3>
                    </div>

                    <!-- Plan Básico Mensual -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-lg font-bold">Plan Básico Mensual</h4>
                            <a href="#" class="text-sm text-indigo-500">Cambiar plan</a>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">Ciclo de facturación: Mensual</p>
                            <p class="text-sm text-gray-500">Vecimiento: 12/09/32</p>
                            <p class="text-sm text-gray-500">Total: $50 usd/mes</p>
                        </div>
                    </div>

                    <!-- Facturación y pago -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-lg font-bold">Facturación y pago</h4>
                            <a href="#" class="text-sm text-indigo-500">Editar método de pago</a>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">Método de pago: Tarjeta de credito</p>
                            <p class="text-sm text-gray-500">Visa terminada en: *****2354</p>
                        </div>
                    </div>

                    <!-- Historial de facturación -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-lg font-bold">Historial de facturación</h4>
                            <a href="#" class="text-sm text-indigo-500">Ver historial</a>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">Ultima facturación: 1/03/25 - 08:00am</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha (55%) -->
            <div class="lg:w-[60%] flex flex-col">
                <div class="flex-1 p-6 bg-white rounded-lg">
                    <!-- Información de usuario -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-sm font-medium text-indigo-500">Informacion de ususario</h3>
                            <button class="flex items-center justify-center w-10 h-10 text-white bg-indigo-500 rounded-lg">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-1 text-xs text-gray-300">Nombre de usuario</label>
                                <input type="text" wire:model="name" class="w-full px-3 py-2 border border-gray-200 rounded-lg" value="mj_cardi97">
                            </div>
                            <div>
                                <label class="block mb-1 text-xs text-gray-300">Correo electrónico</label>
                                <input type="email" wire:model="email" class="w-full px-3 py-2 border border-gray-200 rounded-lg" value="mariacardva@gmail.com">
                            </div>
                        </div>
                    </div>

                    <!-- Información de contacto -->
                    <div class="mb-8">
                        <h3 class="mb-6 text-sm font-medium text-indigo-500">Informacion de contacto</h3>
                        <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-1 text-xs text-gray-300">Empresa</label>
                                <input type="text" wire:model="company" class="w-full px-3 py-2 border border-gray-200 rounded-lg" value="Grupo Linc">
                            </div>
                            <div>
                                <label class="block mb-1 text-xs text-gray-300">Teléfono</label>
                                <input type="tel" wire:model="phone" class="w-full px-3 py-2 border border-gray-200 rounded-lg" value="+506 8753-0522">
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block mb-1 text-xs text-gray-300">País</label>
                                <input type="text" wire:model="country" class="w-full px-3 py-2 border border-gray-200 rounded-lg" value="Costa Rica">
                            </div>
                            <div>
                                <label class="block mb-1 text-xs text-gray-300">Ciudad</label>
                                <input type="text" wire:model="city" class="w-full px-3 py-2 border border-gray-200 rounded-lg" value="San Ramón">
                            </div>
                            <div>
                                <label class="block mb-1 text-xs text-gray-300">Código Postal</label>
                                <input type="text" wire:model="zip" class="w-full px-3 py-2 border border-gray-200 rounded-lg" value="20201">
                            </div>
                        </div>
                    </div>

                    <!-- Sobre mí -->
                    <div>
                        <h3 class="mb-6 text-sm font-medium text-indigo-500">Sobre mí</h3>
                        <div>
                            <label class="block mb-1 text-xs text-gray-300">Escribe</label>
                            <textarea wire:model="description" class="w-full h-32 px-3 py-2 border border-gray-200 rounded-lg" placeholder="texto personal si se desea colocar"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
