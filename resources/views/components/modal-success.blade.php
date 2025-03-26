@props(['title' => '', 'show' => false, 'maxWidth' => 'lg'])

<x-modal name="success-modal" maxWidth="{{ $maxWidth }}" show="{{ $show }}" {{ $attributes }}>
    <div class="mb-4 flex">
        <h3 class="ml-auto text-lg font-bold text-success">
            Operación Exitosa
        </h3>

        <button type="button" aria-label="Cerrar" x-data=""
            x-on:click="$dispatch('close-modal', 'success-modal')" class="ml-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="#A5A3A3">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div class="mb-8 space-y-2">
        {{ $slot }}

        <span class="text-center text-sm text-[#AFAFAF] block">
            La operación se encuentra pendiente de aprobación por parte de su supervisor
        </span>
    </div>

    <x-primary-button class="w-full" x-data=""
    x-on:click="$dispatch('close-modal', 'success-modal')">
        Aceptar
    </x-primary-button>
</x-modal>
