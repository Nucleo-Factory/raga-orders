@props([
    "title" => "Título",
    "subtitle" => "Subtítulo",
    "titleClass" => "text-[2.5rem] font-medium",
    "subtitleClass" => "text-xl text-[#7B7A7A]",
])

<div>
    <h1 class="{{ $titleClass }}">
        {{ $title }}
    </h1>
    <p class="{{ $subtitleClass }}">
        {{ $subtitle }}
    </p>
</div>
