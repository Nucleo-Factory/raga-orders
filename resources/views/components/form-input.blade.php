@props(['label' => null, 'input', 'icon' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col']) }}>
    @if ($label)
        <label @if ($input->attributes->has('name')) for="{{ $input->attributes->get('name') }}" @endif
            {{ $label->attributes->merge(['class' => 'ml-[1.125rem] text-sm font-medium text-[#565AFF]']) }}>
            {{ $label }}
        </label>
    @endif

    @if ($icon)
        <div class="relative">
            <input @if ($input->attributes->has('name')) id="{{ $input->attributes->get('name') }}" @endif
                {{ $input->attributes->merge(['class' => 'rounded-xl border-2 border-[#9AABFF] py-[0.625rem] px-3 text-lg text-[#2E2E2E] placeholder:text-[#AFAFAF] w-full leading-none', 'type' => 'text']) }}>

            {{ $icon }}
        </div>
    @else
        <input @if ($input->attributes->has('name')) id="{{ $input->attributes->get('name') }}" @endif
            {{ $input->attributes->merge(['class' => 'rounded-xl border-2 border-[#9AABFF] py-[0.625rem] px-3 text-lg text-[#2E2E2E] placeholder:text-[#AFAFAF] leading-none', 'type' => 'text']) }}>
    @endif
</div>
