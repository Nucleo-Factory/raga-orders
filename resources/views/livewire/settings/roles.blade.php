<div class="space-y-10">
    <div class="flex items-center justify-between">
        <x-view-title title="Lista de Roles" subtitle="Asigne roles y permisos a los diversos usuarios" />

        <div class="flex space-x-4">
            <a href="#">
                <x-black-btn>Crear nuevo rol</x-black-btn>
            </a>
        </div>
    </div>

    <div class="relative w-fit">
        <input type="text" placeholder="Buscar rol"
            class="w-[311px] rounded-lg border border-[#9D9D9D] bg-white py-1.5 pl-3 pr-9 placeholder:text-sm placeholder:text-[#A1A9B8]">
        <span class="absolute right-3 top-1/2 -translate-y-1/2">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M9.63581 10.834C8.64698 11.603 7.40442 12.0609 6.05495 12.0609C2.82997 12.0609 0.215607 9.44576 0.215607 6.21988C0.215607 2.994 2.82997 0.378906 6.05495 0.378906C9.27993 0.378906 11.8943 2.994 11.8943 6.21988C11.8943 7.5696 11.4366 8.8124 10.668 9.80146L13.6228 12.7535C13.9081 13.0385 13.9083 13.5008 13.6235 13.7861C13.3386 14.0714 12.8764 14.0717 12.5912 13.7867L9.63581 10.834ZM10.4345 6.21988C10.4345 8.63929 8.47369 10.6006 6.05495 10.6006C3.63622 10.6006 1.67544 8.63929 1.67544 6.21988C1.67544 3.80047 3.63622 1.83915 6.05495 1.83915C8.47369 1.83915 10.4345 3.80047 10.4345 6.21988Z"
                    fill="#868FA0" />
            </svg>
        </span>
    </div>

    {{-- Lista --}}
</div>
