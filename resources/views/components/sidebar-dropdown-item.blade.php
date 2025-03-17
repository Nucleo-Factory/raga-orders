@props(['active' => false])

<a
    {{ $attributes->merge([
        'class' => 'cursor-pointer text-xs px-3 py-2 w-full block' . ($active ? '' : ' hover:text-black'),
    ]) }}>
    <span
        class="{{ $active ? 'after:content-[""] after:w-2 after:h-2 after:rounded-full after:bg-purple-500 after:block flex items-center justify-between' : '' }}">{{ $slot }}</span>
</a>
