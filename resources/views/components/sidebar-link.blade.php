@props(['href' => '/'])

<a href="{{ $href }}" class="flex items-center hover:bg-stone-200 rounded-md gap-4 py-1 px-2 sm:py-2 sm:px-4">
    {{ $slot }}
</a>
