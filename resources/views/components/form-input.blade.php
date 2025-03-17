@props([
    "label" => "Label",
    "type" => "text",
    "placeholder" => "Input placeholder",
    "name" => "input",
    "value" => "",
    "wireModel" => "",
])

<div class="flex flex-col gap-2">
    <label for="{{ $name }}" class="text-[0.875rem] font-medium text-[#111928]">
        {{ $label }}
    </label>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"
        class="rounded-[0.5rem] border border-[#D1D5DB] px-3 py-2 text-[0.875rem] placeholder:text-[#6B7280]"
        placeholder="{{ $placeholder }}"
        @if($wireModel) wire:model="{{ $wireModel }}" @endif
        {{ $attributes }}>
</div>
