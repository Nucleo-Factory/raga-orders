<button
    {{ $attributes->merge(['class' => 'rounded-md border-[3px] border-[#565AFF] px-4 py-[0.438rem] text-lg font-black leading-[1.625rem] text-[#565AFF] transition-colors duration-500 hover:border-[#1108BC] active:border-[#7288FF] disabled:border-[#C2C2C2] disabled:text-[#C2C2C2] disabled:cursor-not-allowed', 'type' => 'button']) }}>
    {{ $slot }}
</button>
