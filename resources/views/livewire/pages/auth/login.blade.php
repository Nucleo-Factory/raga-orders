<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate([
        'form.email' => ['required', 'email'],
        'form.password' => ['required'],
    ], [
        'form.email.required' => __('El campo email es obligatorio'),
        'form.email.email' => __('El campo email debe ser un email válido'),
        'form.password.required' => __('El campo contraseña es obligatorio'),
    ]);

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
};
?>

<div>
    <!-- Session Status -->

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <div class="relative">
            <x-input-label for="email" :value="__('Email')" class="{{ $errors->has('form.email') ? 'text-[#FF3459]' : '' }}"/>
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full border-2 {{ $errors->has('form.email') ? 'border-[#FF3459]' : '' }}" type="email" name="email" autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 absolute -bottom-[19px] !text-[10px] text-[#FF3459] " />
        </div>

        <!-- Password -->
        <div class="mt-8 mb-2 relative">
            <x-input-label for="password" :value="__('Password')" class="{{ $errors->has('form.email') ? 'text-[#FF3459]' : '' }}"/>

            <div class="relative">
                <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full border-2 {{ $errors->has('form.password') ? 'border-[#FF3459]' : '' }}" type="password" name="password" autocomplete="current-password" />
                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <svg class="h-5 w-5 text-gray-500" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('form.password')" class="mt-2 absolute -bottom-[19px] !text-[10px] text-[#FF3459] " />
        </div>

        <div class="text-right mb-[50px] mt-2">
            @if (Route::has('password.request'))
                <a class="text-sm text-[#565AFF] hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('¿Has olvidado tu contraseña?') }}
                </a>
            @endif
        </div>

        <div class="flex items-center justify-center mt-4">
            <x-primary-button class="ms-3">
                {{ __('Iniciar sesión') }}
            </x-primary-button>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
    }
</script>
