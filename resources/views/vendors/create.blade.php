<x-app-layout>
<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Crear Nuevo Proveedor</h1>
                    <a href="{{ route('vendors.index') }}"
                        class="px-4 py-2 text-white bg-gray-500 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Volver a la lista
                    </a>
                </div>

                @livewire('forms.vendor-form')
            </div>
        </div>
    </div>
</div>
</x-app-layout>
