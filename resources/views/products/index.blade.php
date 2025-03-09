@php
    $etapaArray = ["e1" => "Etapa 1", "e2" => "Etapa 2"];
@endphp

<x-app-layout>
    <div class="flex items-center justify-between">
        <x-view-title title="Lista de productos" subtitle="Cree y monitoree sus productos" />

        <a href="{{ route("products.create") }}" class="block w-fit rounded-[0.375rem] bg-[#0F172A] px-4 py-2 text-white">
            Cargar nuevo producto
        </a>
    </div>

    <div x-data="{ open: false }" class="relative">
        <button x-on:click="open = ! open" class="ml-auto block rounded-[0.375rem] bg-[#DDDDDD] px-2 py-4">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4" fill="none">
                    <path
                        d="M4 2C4 2.53043 3.78929 3.03914 3.41421 3.41421C3.03914 3.78929 2.53043 4 2 4C1.46957 4 0.960859 3.78929 0.585786 3.41421C0.210714 3.03914 0 2.53043 0 2C0 1.46957 0.210714 0.96086 0.585786 0.585787C0.960859 0.210714 1.46957 0 2 0C2.53043 0 3.03914 0.210714 3.41421 0.585787C3.78929 0.96086 4 1.46957 4 2ZM11 2C11 2.53043 10.7893 3.03914 10.4142 3.41421C10.0391 3.78929 9.53043 4 9 4C8.46957 4 7.96086 3.78929 7.58579 3.41421C7.21071 3.03914 7 2.53043 7 2C7 1.46957 7.21071 0.96086 7.58579 0.585787C7.96086 0.210714 8.46957 0 9 0C9.53043 0 10.0391 0.210714 10.4142 0.585787C10.7893 0.96086 11 1.46957 11 2ZM18 2C18 2.53043 17.7893 3.03914 17.4142 3.41421C17.0391 3.78929 16.5304 4 16 4C15.4696 4 14.9609 3.78929 14.5858 3.41421C14.2107 3.03914 14 2.53043 14 2C14 1.46957 14.2107 0.96086 14.5858 0.585787C14.9609 0.210714 15.4696 0 16 0C16.5304 0 17.0391 0.210714 17.4142 0.585787C17.7893 0.96086 18 1.46957 18 2Z"
                        fill="black" />
                </svg>
            </span>
        </button>

        <ul x-show="open"
            class="absolute right-0 top-full mt-[0.375rem] space-y-1 bg-white p-2 font-inter font-medium shadow-[0_4px_4px_0_rgba(0,0,0,0.25)]">
            <li>
                <button x-data="" x-on:click="$dispatch('open-modal', 'change-oc-stage'); open = false"
                    class="w-full rounded-md px-2 py-1 text-left hover:bg-[#E9E9E9]">
                    Cambiar etapa
                </button>
            </li>
            <li>
                <button class="w-full rounded-md px-2 py-1 text-left hover:bg-[#E9E9E9]"
                    x-on:click="$dispatch('open-modal', 'modify-oc'); open = false">
                    Editar producto
                </button>
            </li>
            <li>
                <button class="w-full rounded-md px-2 py-1 text-left hover:bg-[#E9E9E9]"
                    x-on:click="$dispatch('open-modal', 'delete-oc'); open = false">
                    Eliminar producto
                </button>
            </li>
        </ul>
    </div>

    <div class="flex max-h-[587px] w-full gap-x-10 overflow-auto">
        <livewire:kanban.kanban-board />
    </div>
</x-app-layout>
