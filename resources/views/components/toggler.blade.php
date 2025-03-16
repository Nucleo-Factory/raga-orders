@props([
    'id' => 'toggle-switch',
    'checked' => false,
    'label' => '',
])

<div class="flex items-center  {{$label ? 'gap-3' : ''}}">
    <label class="relative inline-block cursor-pointer">
        <input type="checkbox" class="peer sr-only" id="{{ $id }}" {{ $checked ? 'checked' : '' }}
            {{ $attributes }}>

        <div class="peer h-6 w-12 rounded-full bg-gray-200 transition-colors duration-300 peer-checked:bg-indigo-500">
        </div>

        <div
            class="absolute left-0 top-1/2 h-7 w-7 -translate-y-1/2 rounded-full border-[1.5px] border-[#D2D2D2] bg-white shadow-md transition-all duration-300 peer-checked:translate-x-5 peer-checked:border-[#565AFF]">
        </div>
    </label>
    @if ($label)
        <label for="{{ $id }}" class="cursor-pointer w-[700px]">
            {{ $label }}
        </label>
    @endif
</div>
