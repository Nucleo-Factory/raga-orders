<x-app-layout>
    <x-view-title>
        <x-slot:title>
            Órdenes de Compra
        </x-slot:title>

        <x-slot:content>
            Visualiza y administra las órdenes de compra
        </x-slot:content>
    </x-view-title>

    <livewire:ui.counter-po />

    {{-- Lista --}}
    <div class="space-y-[1.875rem]" x-data="{
        activeTab: 'tab1'
    }">
        <!-- Selector de pestañas -->
        <div class="flex items-center justify-between gap-6 text-lg font-bold">
            <div class="flex items-center gap-6">
                <button @click="activeTab = 'tab1'"
                    :class="activeTab === 'tab1' ? 'border-dark-blue text-dark-blue' : 'border-transparent'"
                    class="border-b-2 py-[0.625rem]">
                    Órdenes de compra
                </button>
                <button @click="activeTab = 'tab2'"
                    :class="activeTab === 'tab2' ? 'border-dark-blue text-dark-blue' : 'border-transparent'"
                    class="border-b-2 py-[0.625rem]">
                    Órdenes consolidadas
                </button>

                <x-dropdown alignmentClasses="rounded-[1.25rem]"
                    contentClasses="rounded-[1.25rem] shadow-lg px-[1.125rem] py-[0.625rem] bg-white">
                    <x-slot:trigger>
                        <button class="rounded-[0.375rem] px-2 py-4 transition-colors duration-500 hover:bg-[#DDDDDD]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4"
                                fill="none">
                                <path
                                    d="M4 2C4 2.53043 3.78929 3.03914 3.41421 3.41421C3.03914 3.78929 2.53043 4 2 4C1.46957 4 0.960859 3.78929 0.585786 3.41421C0.210714 3.03914 0 2.53043 0 2C0 1.46957 0.210714 0.96086 0.585786 0.585787C0.960859 0.210714 1.46957 0 2 0C2.53043 0 3.03914 0.210714 3.41421 0.585787C3.78929 0.96086 4 1.46957 4 2ZM11 2C11 2.53043 10.7893 3.03914 10.4142 3.41421C10.0391 3.78929 9.53043 4 9 4C8.46957 4 7.96086 3.78929 7.58579 3.41421C7.21071 3.03914 7 2.53043 7 2C7 1.46957 7.21071 0.96086 7.58579 0.585787C7.96086 0.210714 8.46957 0 9 0C9.53043 0 10.0391 0.210714 10.4142 0.585787C10.7893 0.96086 11 1.46957 11 2ZM18 2C18 2.53043 17.7893 3.03914 17.4142 3.41421C17.0391 3.78929 16.5304 4 16 4C15.4696 4 14.9609 3.78929 14.5858 3.41421C14.2107 3.03914 14 2.53043 14 2C14 1.46957 14.2107 0.96086 14.5858 0.585787C14.9609 0.210714 15.4696 0 16 0C16.5304 0 17.0391 0.210714 17.4142 0.585787C17.7893 0.96086 18 1.46957 18 2Z"
                                    class="fill-dark-blue" />
                            </svg>
                        </button>
                    </x-slot:trigger>
                    <x-slot:content>
                        <ul class="space-y-2 text-base font-normal text-[#2e2e2e]">
                            <li>
                                <button class="w-full rounded-[0.25rem] px-2 py-1 text-left hover:bg-[#EEF0FF]">Ocultar
                                    celdas</button>
                            </li>
                            <li>
                                <button class="w-full rounded-[0.25rem] px-2 py-1 text-left hover:bg-[#EEF0FF]">Mostrar
                                    celdas</button>
                            </li>
                        </ul>
                    </x-slot:content>
                </x-dropdown>
            </div>
        </div>

        <!-- Contenido de las pestañas -->
        <div>
            <div x-show="activeTab === 'tab1'" x-transition class="">
                <livewire:kanban.kanban-board />
            </div>

            <div x-show="activeTab === 'tab2'" x-transition class="space-y-[1.875rem]">
                {{-- Añadir tabla --}}
            </div>
        </div>
    </div>
</x-app-layout>
