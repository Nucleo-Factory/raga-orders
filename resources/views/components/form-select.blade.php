@props([
    'label' => false,
    'name' => 'select',
    'options' => [],
    'optionPlaceholder' => 'Elija opción',
    'selectClasses' => 'rounded-xl border-2 border-[#9AABFF] py-[0.625rem] px-3 text-lg text-[#2E2E2E]',
    'wireModel' => '',
])

<div class="flex flex-col">
    @if ($label)
        <label for="{{ $name }}" class="ml-[1.125rem] text-sm font-medium text-[#565AFF]">
            {{ $label }}
        </label>
    @endif
    <select id="{{ $name }}" name="{{ $name }}" class="{{ $selectClasses }}"
        @if ($wireModel) wire:model="{{ $wireModel }}" @endif {{ $attributes }}>
        <option value="">{{ $optionPlaceholder }}</option>
        @foreach ($options as $key => $option)
            <option value="{{ $key }}">{{ $option }}</option>
        @endforeach
    </select>
</div>
