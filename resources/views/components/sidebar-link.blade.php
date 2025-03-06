@props(["href" => "/"])

<a href="{{ $href }}" class="flex items-center gap-4 rounded-md px-2 py-1 hover:bg-stone-200 sm:px-4 sm:py-2">
    {{ $slot }}
</a>
