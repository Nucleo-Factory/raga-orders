<x-app-layout>
    {{ $header }}

    <nav class="rounded-2xl bg-white px-6 py-4 text-lg">
        <ul class="mx-auto flex max-w-screen-md items-center justify-between">
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
