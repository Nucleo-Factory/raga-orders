@props([
    'label' => '',
    'name' => 'select',
    'options' => [],
    'optionPlaceholder' => 'Elija opción',
    'selectClasses' => 'rounded-[0.5rem] border border-[#D1D5DB] px-3 py-2 text-[0.875rem] text-[#6B7280]',
])

<div class="flex flex-col gap-2">
    @if ($label)
        <label for="{{ $name }}" class="text-[0.875rem] font-medium text-[#111928]">
            {{ $label }}
        </label>
    @endif
    <select id="{{ $name }}" name="{{ $name }}" class="{{ $selectClasses }}">
        <option value="">{{ $optionPlaceholder }}</option>
        @foreach ($options as $key => $option)
            <option value="{{ $key }}">{{ $option }}</option>
        @endforeach
    </select>
</div>
