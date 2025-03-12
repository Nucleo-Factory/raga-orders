@props(["href" => "/"])

<a href="{{ $href }}"
    class="sidebar-link flex items-center rounded-md px-2 py-1 hover:bg-stone-200 sm:px-4 sm:py-2">
    {{ $slot }}
</a>
