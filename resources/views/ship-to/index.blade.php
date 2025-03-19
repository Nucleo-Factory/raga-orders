<x-app-layout>
<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Gestión de Direcciones de Envío</h1>
                    <a href="{{ route('ship-to.create') }}"
                        class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Nueva Dirección de Envío
                    </a>
                </div>

                @if (session('message'))
                    <div class="px-4 py-2 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
                        {{ session('message') }}
                    </div>
                @endif

                @livewire('tables.ship-to-table')
            </div>
        </div>
    </div>
</div>
</x-app-layout>
