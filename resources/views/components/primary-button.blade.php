<button
    {{ $attributes->merge(['class' => 'rounded-md bg-[#565AFF] px-4 py-[0.625rem] text-lg font-black leading-[1.625rem] text-white transition-colors duration-500 hover:bg-[#1108BC] active:bg-[#7288FF] disabled:bg-[#EDEDED] disabled:text-[#C2C2C2] disabled:cursor-not-allowed', 'type' => 'button']) }}>
    {{ $slot }}
</button>
