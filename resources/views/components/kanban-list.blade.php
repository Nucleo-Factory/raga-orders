@props(["title" => "Título lista"])

<div class="p-4">
    <h3 class="mb-4 border-b border-black px-4 font-bold">{{ $title }}</h3>

    <ul>
        <x-kanban-card />
    </ul>
</div>
