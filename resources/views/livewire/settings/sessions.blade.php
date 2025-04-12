<div class="p-8 space-y-10 bg-white rounded-2xl">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-[#7288FF]">Lista de sesiones</h2>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-[#E0E5FF]">
            <tr>
                <th class="w-8 px-6 py-3">
                    <input type="checkbox" class="rounded">
                </th>
                @foreach($headers as $label)
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        {{ $label }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
            @foreach($sessions as $key => $session)
                <tr>
                    <td class="px-6 py-4">
                        <input type="checkbox" class="rounded">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $session->user_name ?? 'Usuario desconocido' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                Sin rol
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{ $this->getDeviceType($session->user_agent) }} - {{ $this->getBrowserType($session->user_agent) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        <span class="px-2 py-1 text-sm text-green-800 bg-green-100 rounded-full">
                            Activa
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        País desconocido
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        <button
                            wire:click="closeSession('{{ $session->id }}')"
                            class="inline-flex items-center text-gray-500 hover:text-gray-700"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

<x-modal-success name="modal-session-closed">
    <x-slot:title>
        Sesión cerrada correctamente
    </x-slot:title>

    <x-slot:description>
        La sesión ha sido cerrada correctamente
    </x-slot:description>

    <x-primary-button wire:click="closeModal" class="w-full">
        Cerrar
    </x-primary-button>
</x-modal-success>
</div>
