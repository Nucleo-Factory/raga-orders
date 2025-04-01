@props([
    'id' => 'toggle-switch',
    'checked' => false,
    'label' => '',
    'disabled' => false,
])

<div class="flex items-center  {{$label ? 'gap-3' : ''}}">
    <div class="inline-block">
        <label for="{{ $id }}" class="flex items-center cursor-pointer">
            <div class="relative">
                <input type="checkbox" id="{{ $id }}" class="sr-only"
                    {{ $checked ? 'checked' : '' }}
                    {{ $disabled ? 'disabled' : '' }}
                >
                <div class="w-10 h-4 bg-gray-300 rounded-full shadow-inner transition-all {{ $checked ? 'bg-[#7288FF]' : '' }}"></div>
                <div class="absolute w-6 h-6 bg-white rounded-full shadow -top-1 left-0 transition-all {{ $checked ? 'transform translate-x-full bg-[#7288FF] border-[#7288FF]' : 'border-gray-300' }} border-2"></div>
            </div>
        </label>
    </div>
    @if ($label)
        <label for="{{ $id }}" class="cursor-pointer w-[700px] capitalize">
            {{ $label }}
        </label>
    @endif
</div>
