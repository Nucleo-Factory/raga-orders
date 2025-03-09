@props(["label" => "Label", "name" => "textarea", "placeholder" => "Ingrese su texto...", "class" => ""])

<div class="{{ $class }} flex flex-col gap-2">
    <label for="{{ $name }}" class="text-[0.875rem] font-medium text-[#111928]">
        {{ $label }}
    </label>
    <textarea id="{{ $name }}" name="{{ $name }}"
        class="w-full rounded-[0.5rem] border border-[#D1D5DB] px-3 py-2 text-[0.875rem] placeholder:text-[#6B7280]"
        placeholder="{{ $placeholder }}"></textarea>
</div>
