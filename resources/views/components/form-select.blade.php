@props(["label" => "Label", "name" => "select", "options" => [], "optionPlaceholder" => "Elija opción"])

<div class="flex flex-col gap-2">
    <label for="{{ $name }}" class="text-[0.875rem] font-medium text-[#111928]">
        {{ $label }}
    </label>
    <select id="{{ $name }}" name="{{ $name }}"
        class="rounded-[0.5rem] border border-[#D1D5DB] px-3 py-2 text-[0.875rem] text-[#6B7280]">
        <option value="">{{ $optionPlaceholder }}</option>
        @foreach ($options as $key => $option)
            <option value="{{ $key }}">{{ $option }}</option>
        @endforeach
    </select>
</div>
