<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
        @livewireStyles
    </head>
    <body x-data="{ isCollapsed: false }">
            <div class="relative z-50 hidden" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-900/80" aria-hidden="true"></div>
                <div class="fixed inset-0 flex">
                    <div class="relative mr-16 flex w-full max-w-xs flex-1">
                        <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                            <button type="button" class="-m-2.5 p-2.5">
                                <span class="sr-only">Cerrar sidebar</span>
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Sidebar component, swap this element with another sidebar if you like -->
                        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
                            <div class="flex h-16 shrink-0 items-center">
                                <img class="h-8 w-auto" src="{{ asset('img/dashboard_logo.png') }}" alt="Raga AI">
                            </div>
                            <nav class="flex flex-1 flex-col">
                                <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                    <li>
                                        <ul role="list" class="-mx-2 space-y-1">
                                            <li>
                                                <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                                                <a href="#" class="group flex gap-x-3 rounded-md bg-gray-50 p-2 text-sm font-semibold leading-6 text-indigo-600">
                                                    <svg class="h-6 w-6 shrink-0 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                                    </svg>
                                                    Dashboard
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                                    <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                                    </svg>
                                                    Team
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                                    <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                                                    </svg>
                                                    Projects
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                                    <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                    </svg>
                                                    Calendar
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                                    <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                                                    </svg>
                                                    Documents
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                                    <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                                                    </svg>
                                                    Reports
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="mt-auto">
                                        <a href="#" class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                            <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Settings
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Static sidebar for desktop -->
            <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col sidebar transition-all duration-500" :class="{ 'collapse-sidebar w-98': isCollapsed }" >
                <!-- Sidebar component, swap this element with another sidebar if you like -->
                <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-[#F7F7F7] px-6 pb-4">
                    <div class="flex h-[100px] shrink-0 items-center justify-center">
                        <a href="/" x-show="!isCollapsed">
                            <svg xmlns="http://www.w3.org/2000/svg" width="157" height="53" viewBox="0 0 157 53" fill="none">
                                <g clip-path="url(#clip0_2001_7199)">
                                    <path d="M61.386 40.1843H64.6212L56.5549 28.9998L57.5325 28.7766C60.832 28.0261 62.8812 25.418 62.8812 21.9699C62.8812 20.5463 62.513 17.9066 60.0436 16.2282C58.5137 15.1843 56.6625 14.7783 53.4394 14.7783H49.3828V40.1843H52.1075V29.2405H53.8198L61.3843 40.1843H61.386ZM52.1075 17.2159H54.1514C59.2448 17.2159 60.3145 19.8223 60.3145 22.0103C60.3145 25.1632 57.9667 27.0455 54.0316 27.0455H52.1075V17.2159Z" fill="#0C0C0C"/>
                                    <path d="M84.1283 33.731L86.9017 40.1844H89.8121L78.2969 14.1458L66.1982 40.1844H69.0948L71.9862 33.731H84.1301H84.1283ZM78.165 19.8575L83.0795 31.2125H73.0508L78.165 19.8575Z" fill="#0C0C0C"/>
                                    <path d="M103.178 27.6062V30.0438H109.826L109.749 30.7784C109.55 32.6976 108.76 34.3127 107.336 35.7152C105.573 37.4568 103.552 38.2688 100.976 38.2688C98.0101 38.2688 95.6901 37.3215 93.8823 35.3724C92.0311 33.3724 90.9718 30.5623 90.9718 27.6607C90.9718 24.5763 92.0954 21.5992 93.9761 19.6941C95.3098 18.3426 97.6837 16.7327 101.412 16.7327C103.378 16.7327 105.017 17.144 106.422 17.9893C107.474 18.5886 108.586 19.5781 109.473 20.6941L111.634 19.1563C110.92 18.1668 109.758 16.9313 107.94 15.8944C106.115 14.8645 103.781 14.2969 101.37 14.2969C97.7549 14.2969 94.214 15.7204 91.8974 18.1071C89.6086 20.4234 88.2437 23.9788 88.2437 27.622C88.2437 31.2652 89.5704 34.7468 91.979 37.26C94.2904 39.6747 96.8379 40.7064 100.499 40.7064C104.159 40.7064 106.827 39.6536 109.032 37.4867C111.38 35.1826 112.66 31.7731 112.748 27.6062H103.176H103.178Z" fill="#0C0C0C"/>
                                    <path d="M128.775 33.731L131.549 40.1844H134.459L122.944 14.1458L110.845 40.1844H113.742L116.633 33.731H128.777H128.775ZM122.81 19.8575L127.725 31.2125H117.696L122.81 19.8575Z" fill="#0C0C0C"/>
                                    <path d="M40.7608 44.4919C41.551 43.6272 42.2821 42.7063 42.9524 41.7415C41.2644 36.3091 38.3574 31.3127 34.3581 27.1035C38.4373 22.5394 41.3773 17.1826 43.0479 11.3935C42.3706 10.4093 41.6326 9.47259 40.8338 8.5921C39.4358 14.6642 36.5601 20.2863 32.3836 25.0121C26.3039 18.1299 22.9748 9.34254 22.9644 0.0384982C22.1187 -0.00719604 21.2643 -0.0142259 20.4012 0.0226809C20.2901 0.0279533 20.1824 0.0402556 20.0713 0.045528C20.0591 9.34605 16.7319 18.1299 10.6521 25.0086C6.48262 20.2916 3.61033 14.68 2.21066 8.62022C1.41183 9.50423 0.675526 10.4445 0 11.4304C1.67232 17.2055 4.60713 22.5482 8.67765 27.1018C4.68875 31.3039 1.7852 36.2863 0.097248 41.7046C0.767565 42.6712 1.49866 43.5903 2.28707 44.455C3.71279 38.7661 6.5573 33.5288 10.6521 29.1896C16.7249 35.6255 20.0609 44.0315 20.0713 52.9594C20.9292 53.0069 21.7975 53.0139 22.6744 52.9752C22.7717 52.9717 22.8672 52.9612 22.9644 52.9559C22.9766 44.0297 26.3125 35.6237 32.3836 29.1896C36.4871 33.5394 39.3368 38.7907 40.7591 44.4937L40.7608 44.4919ZM21.5179 42.7239C19.8855 36.912 16.8656 31.5657 12.6266 27.1035C16.8691 22.3566 19.8872 16.7555 21.5179 10.6993C23.1485 16.7555 26.1667 22.3566 30.4091 27.1035C26.1701 31.5657 23.1485 36.912 21.5179 42.7239Z" fill="url(#paint0_linear_2001_7199)"/>
                                    <path d="M142.763 31.9263H135.513V34.6222H142.763V31.9263Z" fill="#0C0C0C"/>
                                    <path d="M154.006 40.0703H157L151.304 32.7663L156.161 26.645H153.11L149.805 31.0211L146.535 26.645H143.602L148.31 32.7751L148.267 32.8278L142.428 40.0703H145.478L149.812 34.5607L154.006 40.0703Z" fill="#0C0C0C"/>
                                </g>
                                <defs>
                                    <linearGradient id="paint0_linear_2001_7199" x1="-5.75153" y1="54.1229" x2="44.1623" y2="4.80253" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="white"/>
                                        <stop offset="0.01" stop-color="#F2FDFE"/>
                                        <stop offset="0.09" stop-color="#A0F1F8"/>
                                        <stop offset="0.15" stop-color="#6DE9F5"/>
                                        <stop offset="0.17" stop-color="#5AE7F4"/>
                                        <stop offset="0.2" stop-color="#5AE1F4"/>
                                        <stop offset="0.23" stop-color="#5BD0F5"/>
                                        <stop offset="0.27" stop-color="#5DB5F8"/>
                                        <stop offset="0.31" stop-color="#6090FB"/>
                                        <stop offset="0.35" stop-color="#6367FF"/>
                                        <stop offset="0.4" stop-color="#5556F8"/>
                                        <stop offset="0.49" stop-color="#312BE6"/>
                                        <stop offset="0.55" stop-color="#190FDB"/>
                                        <stop offset="0.62" stop-color="#4922B9"/>
                                        <stop offset="0.72" stop-color="#853B90"/>
                                        <stop offset="0.81" stop-color="#B54E6F"/>
                                        <stop offset="0.89" stop-color="#D75C57"/>
                                        <stop offset="0.95" stop-color="#EC6449"/>
                                        <stop offset="1" stop-color="#F46844"/>
                                    </linearGradient>
                                    <clipPath id="clip0_2001_7199">
                                        <rect width="157" height="53" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>

                        <a href="/" x-show="isCollapsed" x-cloak>
                            <svg xmlns="http://www.w3.org/2000/svg" width="44" height="53" viewBox="0 0 44 53" fill="none">
                                <path d="M41.2757 44.0533C42.0671 43.1958 42.7995 42.2861 43.4727 41.3308C41.7828 35.9519 38.8694 31.0051 34.8619 26.8361C38.9512 22.3169 41.8943 17.013 43.5682 11.2816C42.8905 10.3059 42.149 9.37791 41.3507 8.50682C39.9497 14.518 37.068 20.0857 32.8831 24.7642C26.7901 17.9501 23.4535 9.25054 23.4444 0.0392554C22.5984 -0.0062324 21.7409 -0.0130556 20.8766 0.0233347C20.7652 0.0278835 20.656 0.0392554 20.5468 0.0460786C20.5355 9.25509 17.2012 17.9524 11.1081 24.7642C6.92779 20.0948 4.04841 14.5385 2.64739 8.53866C1.8468 9.4143 1.1099 10.3445 0.432129 11.3202C2.10836 17.0381 5.04914 22.3283 9.12713 26.8361C5.12875 30.9937 2.22208 35.9292 0.529928 41.2922C1.20087 42.2497 1.93323 43.1594 2.72472 44.0169C4.15303 38.3855 7.00512 33.1976 11.1081 28.9013C17.1921 35.2741 20.5378 43.5961 20.5468 52.4367C21.4066 52.4845 22.2777 52.4913 23.1556 52.4526C23.2534 52.4481 23.3489 52.439 23.4467 52.4321C23.4581 43.5939 26.8014 35.2719 32.8854 28.899C36.9975 33.2044 39.8519 38.406 41.2779 44.051L41.2757 44.0533ZM21.9934 42.302C20.3581 36.5478 17.3309 31.2553 13.0846 26.8361C17.3354 22.1372 20.3603 16.59 21.9934 10.5947C23.6264 16.59 26.6513 22.1372 30.9044 26.8361C26.6559 31.2553 23.6309 36.5478 21.9934 42.302Z" fill="url(#paint0_linear_822_2322)"/>
                                <defs>
                                    <linearGradient id="paint0_linear_822_2322" x1="-5.33118" y1="53.5898" x2="44.0914" y2="4.16501" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="white"/>
                                        <stop offset="0.01" stop-color="#F2FDFE"/>
                                        <stop offset="0.09" stop-color="#A0F1F8"/>
                                        <stop offset="0.15" stop-color="#6DE9F5"/>
                                        <stop offset="0.17" stop-color="#5AE7F4"/>
                                        <stop offset="0.2" stop-color="#5AE1F4"/>
                                        <stop offset="0.23" stop-color="#5BD0F5"/>
                                        <stop offset="0.27" stop-color="#5DB5F8"/>
                                        <stop offset="0.31" stop-color="#6090FB"/>
                                        <stop offset="0.35" stop-color="#6367FF"/>
                                        <stop offset="0.4" stop-color="#5556F8"/>
                                        <stop offset="0.49" stop-color="#312BE6"/>
                                        <stop offset="0.55" stop-color="#190FDB"/>
                                        <stop offset="0.62" stop-color="#4922B9"/>
                                        <stop offset="0.72" stop-color="#853B90"/>
                                        <stop offset="0.81" stop-color="#B54E6F"/>
                                        <stop offset="0.89" stop-color="#D75C57"/>
                                        <stop offset="0.95" stop-color="#EC6449"/>
                                        <stop offset="1" stop-color="#F46844"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </a>
                    </div>

                    <nav class="flex flex-1 flex-col">
                        <ul role="list" class="flex flex-1 flex-col justify-between gap-y-7">
                            <li>
                                <ul role="list" class="-mx-2">
                                    <li class="mb-5">
                                        <a href="" class="group flex gap-x-3 rounded-md p-2 text-[16px]  leading-6 text-[#7288FF] hover:bg-gray-50 hover:text-indigo-600 items-center">
                                            <div class="flex border-2 border-[#7288FF] rounded-full p-[10px]">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M12 3H16.2C17.8802 3 18.7202 3 19.362 3.32698C19.9265 3.6146 20.3854 4.07354 20.673 4.63803C21 5.27976 21 6.11984 21 7.8V16.2C21 17.8802 21 18.7202 20.673 19.362C20.3854 19.9265 19.9265 20.3854 19.362 20.673C18.7202 21 17.8802 21 16.2 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V12M8 13V17M16 11V17M12 7V17M2 5L5 2M5 2L8 5M5 2L5 8" stroke="#7288FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>

                                            <span x-show="!isCollapsed">
                                                Lista de  margenes
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <ul>
                            <div class="flex flex-col items-start justify-start">
                                <li class="mt-auto">
                                    <a href="#" class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm  leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                    <a href="#" class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm  leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shrink-0 text-gray-400 group-hover:text-indigo-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                        </svg>

                                        <span x-show="!isCollapsed">
                                            Prueba botón
                                        </span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="lg:pl-72 transition-all duration-500 h-full" :class="{ 'w-6rem': isCollapsed }">
                <livewire:layout.navigation />

                <main class="pt-10 pb-[150px] flex justify-between w-full relative ">
                    <div class="w-full">
                        {{ $slot }}
                    </div>
                </main>

                <div class="fixed bottom-0 left-0 w-full lg:pl-72 transition-all duration-500" :class="{ 'w-6rem': isCollapsed }">
                    <div class=" w-full  py-5 bg-gradient-to-r from-[#e6fcfe] via-[#e3ecff] to-[#c4d0ff] px-5 flex justify-between items-center">
                        <div class="max-w-[100px]">
                            <img src="{{ asset('img/dashboard_logo.png') }}" alt="" class="h-auto w-[40px]">
                        </div>

                        <p> Powered by Linktech</p>

                        <span></span>
                    </div>
                </div>
            </div>

            @livewireScripts
    </body>

    @stack('scripts')
</html>
