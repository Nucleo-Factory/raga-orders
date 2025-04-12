<div class="p-8 space-y-10 bg-white rounded-2xl">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-[#7288FF]">Lista de usuarios</h2>

        <div class="flex space-x-4">
            <a href="{{ route('settings.users.create') }}">
                <x-primary-button class="flex items-center gap-[0.625rem]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M8 1V15M1 8H15" stroke="#F7F7F7" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span>Crear usuario</span>
                </x-primary-button>
            </a>
        </div>
    </div>

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-[#E0E5FF]">
            <tr>
                @foreach($headers as $key => $label)
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        {{ $label }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
            @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{ $user->roles->first()?->name ?? 'Sin rol' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        <div class="flex space-x-2">
                            <a href="{{ route('settings.users.edit', $user) }}"
                               class="text-blue-600 hover:text-blue-900">
                                Editar
                            </a>
                            <button wire:click="openModal({{ $user->id }})"
                                    class="text-red-600 hover:text-red-900">
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <x-modal-warning name="modal-delete-user">
        <x-slot:title>
            Estás seguro de querer eliminar el usuario {{ $user->name }}?
        </x-slot:title>

        <div class="flex space-x-2">
            <x-primary-button wire:click="closeModal" class="w-full">
                Cancelar
            </x-primary-button>

            <x-primary-button wire:click="deleteUser({{ $id }})" class="w-full bg-red-600 hover:bg-red-700">
                Eliminar
            </x-primary-button>
        </div>
    </x-modal-warning>
</div>
