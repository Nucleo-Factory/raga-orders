@props([
    'id' => 'toggle-switch',
    'checked' => false,
    'label' => '',
])

<div class="flex items-center  {{$label ? 'gap-3' : ''}}">
    <label class="relative inline-block capitalize cursor-pointer">
        <input type="checkbox" class="sr-only peer" id="{{ $id }}" {{ $checked ? 'checked' : '' }}
            {{ $attributes }}>

        <div class="w-12 h-6 transition-colors duration-300 bg-gray-200 rounded-full peer peer-checked:bg-indigo-500">
        </div>

        <div
            class="absolute left-0 top-1/2 h-7 w-7 -translate-y-1/2 rounded-full border-[1.5px] border-[#D2D2D2] bg-white shadow-md transition-all duration-300 peer-checked:translate-x-5 peer-checked:border-[#565AFF]">
        </div>
    </label>
    @if ($label)
        <label for="{{ $id }}" class="cursor-pointer w-[700px] capitalize">
            {{ $label }}
        </label>
    @endif
</div>
