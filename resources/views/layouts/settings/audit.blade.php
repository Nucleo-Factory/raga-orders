<x-app-layout>
    {{ $header }}

    <nav class="rounded-2xl bg-white px-6 py-4">
        <ul class="flex items-center justify-between max-w-screen-md mx-auto">
            <li class="border-b-2 border-[#190FDB] text-[#190FDB]">
                Histórico Operaciones
            </li>
            <li>
                Histórico Usuarios
            </li>
        </ul>
    </nav>

    {{ $slot }}
</x-app-layout>
