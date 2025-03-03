@props(["title" => "Título lista"])

<div>
    <h3 class="mb-4 border-b border-black px-4 py-1 font-bold">{{ $title }}</h3>

    <ul class="space-y-4">
        <x-kanban-card />
        <x-kanban-card />
        <x-kanban-card />
        <x-kanban-card />
        <x-kanban-card />
    </ul>
</div>
