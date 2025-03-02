<x-app-layout>
    <a href="/new-purchase-order"
        class="change-oc-stage block w-fit rounded-[0.375rem] bg-[#0F172A] px-4 py-2 text-white">
        Cargar nueva orden de compra
    </a>

    <div class="flex max-h-[587px] max-w-[1184px] gap-x-10 overflow-auto">
        <x-kanban-list title="Pick Up" />
        <x-kanban-list title="En transito terrestre" />
        <x-kanban-list title="Validación operativa" />
        <x-kanban-list title="Validación operativa" />
        <x-kanban-list title="Validación operativa" />
        <x-kanban-list title="Validación operativa" />
        <x-kanban-list title="Validación operativa" />
    </div>
</x-app-layout>
