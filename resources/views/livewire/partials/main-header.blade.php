<header x-data="{ open: false }">
    <div class="flex justify-between items-center py-6 px-2 sm:pt-[2.625rem] sm:pb-5 sm:px-10">
        <div class="text-left grow">
            <x-breadcrumb />
        </div>

        <!-- Settings Dropdown -->
        <div class="hidden space-x-4 sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out border border-transparent rounded-md hover:bg-white focus:out line-none">
                        <div class="w-8 h-8 bg-gray-400 rounded-full"></div>
                        <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                            x-on:profile-updated.window="name = $event.detail.name"></div>
                        <div class="ms-1">
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-responsive-nav-link :href="route('profile')" wire:navigate>
                        {{ __('Perfil') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </x-slot>
            </x-dropdown>

            <div class="h-10 bg-[#B9B9B9] w-[1px]"></div>

            <div class="flex gap-2 px-3 py-2">
                <button
                    class="h-6 w-6 bg-white border border-[#E4E7EC] rounded-[0.25rem] flex items-center justify-center hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="13" viewBox="0 0 14 13"
                        fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0 7.23997C0 8.22297 0.713 9.06497 1.69 9.18297C2.59467 9.29164 3.507 9.37264 4.427 9.42597C4.79 9.44597 5.115 9.65697 5.277 9.98197L6.329 12.085C6.39125 12.2096 6.48701 12.3145 6.60553 12.3878C6.72405 12.4611 6.86065 12.4999 7 12.4999C7.13935 12.4999 7.27595 12.4611 7.39447 12.3878C7.51299 12.3145 7.60875 12.2096 7.671 12.085L8.723 9.98197C8.885 9.65697 9.21 9.44697 9.573 9.42597C10.493 9.37264 11.4057 9.29164 12.311 9.18297C13.287 9.06497 14 8.22297 14 7.23997V2.75897C14 1.77697 13.287 0.934969 12.31 0.816969C8.78269 0.393041 5.21731 0.393041 1.69 0.816969C0.712 0.934969 0 1.77697 0 2.75997V7.23997ZM3 3.74997C3 3.55106 3.07902 3.36029 3.21967 3.21964C3.36032 3.07899 3.55109 2.99997 3.75 2.99997H10.25C10.4489 2.99997 10.6397 3.07899 10.7803 3.21964C10.921 3.36029 11 3.55106 11 3.74997C11 3.94888 10.921 4.13965 10.7803 4.2803C10.6397 4.42095 10.4489 4.49997 10.25 4.49997H3.75C3.55109 4.49997 3.36032 4.42095 3.21967 4.2803C3.07902 4.13965 3 3.94888 3 3.74997ZM3.75 5.49997C3.55109 5.49997 3.36032 5.57899 3.21967 5.71964C3.07902 5.86029 3 6.05106 3 6.24997C3 6.44888 3.07902 6.63965 3.21967 6.7803C3.36032 6.92095 3.55109 6.99997 3.75 6.99997H6.25C6.44891 6.99997 6.63968 6.92095 6.78033 6.7803C6.92098 6.63965 7 6.44888 7 6.24997C7 6.05106 6.92098 5.86029 6.78033 5.71964C6.63968 5.57899 6.44891 5.49997 6.25 5.49997H3.75Z"
                            fill="black" />
                    </svg>
                </button>
                <button
                    class="h-6 w-6 bg-white border border-[#E4E7EC] rounded-[0.25rem] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="15" viewBox="0 0 12 15"
                        fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M9.99998 4.5C9.99998 3.43913 9.57855 2.42172 8.8284 1.67157C8.07826 0.921427 7.06084 0.5 5.99998 0.5C4.93911 0.5 3.92169 0.921427 3.17155 1.67157C2.4214 2.42172 1.99998 3.43913 1.99998 4.5V6.879C1.99963 7.27669 1.84136 7.65797 1.55998 7.939L0.293977 9.207C0.106427 9.39449 0.0010332 9.6488 0.000976562 9.914V10.5C0.000976562 10.7652 0.106334 11.0196 0.29387 11.2071C0.481406 11.3946 0.73576 11.5 1.00098 11.5H3.00098C3.00098 12.2956 3.31705 13.0587 3.87966 13.6213C4.44227 14.1839 5.20533 14.5 6.00098 14.5C6.79663 14.5 7.55969 14.1839 8.1223 13.6213C8.68491 13.0587 9.00098 12.2956 9.00098 11.5H11.001C11.2662 11.5 11.5205 11.3946 11.7081 11.2071C11.8956 11.0196 12.001 10.7652 12.001 10.5V9.914C12.0009 9.6488 11.8955 9.39449 11.708 9.207L10.44 7.94C10.1586 7.65897 10.0003 7.27769 9.99998 6.88V4.5ZM4.49998 11.5C4.49998 11.8978 4.65801 12.2794 4.93932 12.5607C5.22062 12.842 5.60215 13 5.99998 13C6.3978 13 6.77933 12.842 7.06064 12.5607C7.34194 12.2794 7.49998 11.8978 7.49998 11.5H4.49998Z"
                            fill="black" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Hamburger -->
        <div class="flex items-center sm:hidden">
            <button @click="open = ! open"
                class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</header>
