@props(["title" => "", "content" => "", "show" => false, "maxWidth" => "2xl"])

<x-modal name="success-modal" maxWidth="{{ $maxWidth }}" show="{{ $show }}">
    <div class="mb-8 space-y-8">
        <h3 class="flex items-center justify-between text-2xl font-bold">
            <span>{{ $title }}</span>

            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M11.9997 24C13.5756 24 15.136 23.6896 16.5919 23.0866C18.0478 22.4835 19.3707 21.5996 20.485 20.4853C21.5993 19.371 22.4832 18.0481 23.0863 16.5922C23.6893 15.1363 23.9997 13.5759 23.9997 12C23.9997 10.4241 23.6893 8.86371 23.0863 7.4078C22.4832 5.95189 21.5993 4.62902 20.485 3.51472C19.3707 2.40042 18.0478 1.5165 16.5919 0.913445C15.136 0.310389 13.5756 -2.34822e-08 11.9997 0C8.8171 4.74244e-08 5.76485 1.26428 3.51441 3.51472C1.26398 5.76515 -0.000305176 8.8174 -0.000305176 12C-0.000305176 15.1826 1.26398 18.2348 3.51441 20.4853C5.76485 22.7357 8.8171 24 11.9997 24ZM11.6904 16.8533L18.357 8.85333L16.309 7.14667L10.5757 14.0253L7.60903 11.0573L5.7237 12.9427L9.7237 16.9427L10.7557 17.9747L11.6904 16.8533Z"
                        fill="black" />
                </svg>
            </span>
        </h3>

        <p class="w-48 font-inter text-sm">
            {{ $content }}
        </p>
    </div>

    <x-black-btn x-data="" x-on:click="$dispatch('close-modal', 'success-modal')" class="w-full">
        Continuar
    </x-black-btn>
</x-modal>
