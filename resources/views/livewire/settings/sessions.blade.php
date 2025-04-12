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
                @foreach($headers as $key => $label)
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        {{ $label }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">
            @foreach($sessions as $session)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                        {{ $session->user->name ?? 'Usuario desconocido' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{ $session->ip_address ?? 'IP desconocida' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{ $session->user_agent ?? 'Agente de usuario desconocido' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{ $session->last_activity ?? 'Última actividad desconocida' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
