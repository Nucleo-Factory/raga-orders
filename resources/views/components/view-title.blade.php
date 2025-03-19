@props([
    "title" => "Título",
    "subtitle" => "Subtítulo",
    "titleClass" => "font-dm-sans text-[2.5rem] font-medium",
    "subtitleClass" => "font-dm-sans text-xl text-[#7B7A7A]",
])

<div>
    <h1 class="{{ $titleClass }}">
        {{ $title }}
    </h1>
    <p class="{{ $subtitleClass }}">
        {{ $subtitle }}
    </p>
</div>
