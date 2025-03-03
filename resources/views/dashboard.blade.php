<x-app-layout>
    <a href="/new-purchase-order" class="block w-fit rounded-[0.375rem] bg-[#0F172A] px-4 py-2 text-white">
        Cargar nueva orden de compra
    </a>

    <div class="flex max-h-[587px] w-full gap-x-10 overflow-auto">
        <livewire:kanban.kanban-board />
    </div>
</x-app-layout>
