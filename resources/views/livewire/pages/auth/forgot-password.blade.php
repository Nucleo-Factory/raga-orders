<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state(['email' => '', 'showSuccessMessage' => false]);

rules(['email' => ['required', 'string', 'email']]);

$sendPasswordResetLink = function () {
    $this->validate([
        'email' => ['required', 'email'],
    ], [
        'email.required' => __('El correo ingresado no se encuentra registrado'),
        'email.email' => __('El campo email debe ser un email válido'),
    ]);

    // We will send the password reset link to this user. Once we have attempted
    // to send the link, we will examine the response then see the message we
    // need to show to the user. Finally, we'll send out a proper response.
    $status = Password::sendResetLink(
        $this->only('email')
    );

    if ($status != Password::RESET_LINK_SENT) {
        $this->addError('email', __($status));

        return;
    }

    $this->reset('email');

    $this->showSuccessMessage = true;

    Session::flash('status', __($status));
};

?>

<div class="relative">
    @if (!$showSuccessMessage)

    <h3 class="login-title mb-[10px]">¿Tienes problemas para iniciar sesión?</h3>
    <div class="mb-4 text-sm text-center text-gray-600">
        {{ __(' Ingresa tu correo electrónico y te enviaremos un enlace para crear una nueva contraseña. ') }}
    </div>

        <form wire:submit="sendPasswordResetLink" class="relative pb-[50px]">
            <!-- Email Address -->
            <div class="relative">
                <x-input-label for="email" :value="__('Email')"  class="{{ $errors->has('email') ? 'text-[#FF3459]' : '' }}"/>
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full border-2 {{ $errors->has('email') ? 'border-[#FF3459]' : '' }}" type="email" name="email" autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2 absolute -bottom-[22px] !text-[12px] text-[#FF3459] text-center w-full" />
            </div>

            <div class="flex items-center justify-center mt-10">
                <x-primary-button class="flex items-center gap-3" wire:loading.attr="disabled">
                    {{ __('Restablecer contraseña') }}

                    <div wire:loading>
                        <div role="status">
                            <svg aria-hidden="true" class="w-5 h-5 text-white animate-spin dark:text-gray-600 fill-red" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="white"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="#565AFF"/>
                            </svg>

                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </x-primary-button>
            </div>
        </form>
    @endif

    @if ($showSuccessMessage)
        <div class="pb-[60px]">
            <div class="flex flex-col items-center justify-center text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="104" height="104" viewBox="0 0 104 104" fill="none">
                    <path d="M93.1666 78L64.381 52M39.6191 52L10.8336 78M8.66675 30.3333L44.0481 55.1002C46.9131 57.1058 48.3457 58.1086 49.9039 58.497C51.2803 58.8401 52.7199 58.8401 54.0963 58.497C55.6545 58.1086 57.087 57.1058 59.9521 55.1002L95.3334 30.3333M29.4667 86.6666H74.5334C81.8141 86.6666 85.4544 86.6666 88.2353 85.2497C90.6814 84.0034 92.6701 82.0146 93.9165 79.5685C95.3334 76.7877 95.3334 73.1473 95.3334 65.8666V38.1333C95.3334 30.8526 95.3334 27.2123 93.9165 24.4314C92.6701 21.9853 90.6814 19.9966 88.2353 18.7502C85.4544 17.3333 81.8141 17.3333 74.5334 17.3333H29.4667C22.1861 17.3333 18.5457 17.3333 15.7649 18.7502C13.3188 19.9966 11.33 21.9853 10.0837 24.4314C8.66675 27.2123 8.66675 30.8526 8.66675 38.1333V65.8666C8.66675 73.1473 8.66675 76.7877 10.0837 79.5685C11.33 82.0146 13.3188 84.0034 15.7649 85.2497C18.5457 86.6666 22.1861 86.6666 29.4667 86.6666Z" stroke="#5DD595" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <h3 class="text-[#5DD595] text-[18px] font-bold mb-5">¡Correo envíado!</h3>

                <p class="mb-5">Revisa tu bandeja de entrada y sigue las instrucciones para recuperar tu contraseña.</p>

                <a href="/login" class="inline-flex items-center px-7 py-2 bg-[#565AFF] border border-transparent rounded-md font-semibold text-[18px] text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Iniciar sesión
                </a>
            </div>
        </div>
    @endif

    <a href="/login" class="absolute -bottom-5 left-[50%] -translate-x-[50%] text-[#565AFF] underline">
        Volver al inicio
    </a>
</div>
