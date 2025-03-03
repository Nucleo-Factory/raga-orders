@props([
    "po" => "12345a",
    "trackingId" => "11111",
    "hubLocation" => "New Jersey",
    "leadTime" => "01/01/2024",
    "recolectaTime" => "11/11/2024",
    "pickupTime" => "11/11/24",
    "totalWeight" => "10 Ton",
])

<li class="flex w-full gap-5 rounded-[0.625rem] border border-[#6D6D6D] bg-white px-4 py-2 text-xs">
    <div class="space-y-2">
        <div class="flex gap-4">
            <input type="checkbox" name="" id="">

            <div class="space-y-1 font-bold">
                <p>PO: {{ $po }}</p>
                <p>ID Tracking: {{ $trackingId }}</p>
            </div>
        </div>

        <p class="flex items-center gap-2 rounded-[0.375rem] bg-[#FFE5D3] p-2">
            <span>Producto peligroso</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M3.42622 6.96833C5.85788 2.65583 7.07372 0.5 8.99955 0.5C10.9254 0.5 12.1412 2.65583 14.5729 6.96833L14.8762 7.505C16.8971 11.0883 17.9079 12.88 16.9946 14.19C16.0812 15.5 13.8212 15.5 9.30288 15.5H8.69622C4.17788 15.5 1.91788 15.5 1.00455 14.19C0.091216 12.88 1.10205 11.0883 3.12288 7.505L3.42622 6.96833ZM8.99955 4.04167C9.16531 4.04167 9.32428 4.10751 9.44149 4.22472C9.5587 4.34193 9.62455 4.50091 9.62455 4.66667V8.83333C9.62455 8.99909 9.5587 9.15806 9.44149 9.27527C9.32428 9.39248 9.16531 9.45833 8.99955 9.45833C8.83379 9.45833 8.67482 9.39248 8.55761 9.27527C8.4404 9.15806 8.37455 8.99909 8.37455 8.83333V4.66667C8.37455 4.50091 8.4404 4.34193 8.55761 4.22472C8.67482 4.10751 8.83379 4.04167 8.99955 4.04167ZM8.99955 12.1667C9.22056 12.1667 9.43252 12.0789 9.58881 11.9226C9.74509 11.7663 9.83288 11.5543 9.83288 11.3333C9.83288 11.1123 9.74509 10.9004 9.58881 10.7441C9.43252 10.5878 9.22056 10.5 8.99955 10.5C8.77854 10.5 8.56657 10.5878 8.41029 10.7441C8.25401 10.9004 8.16622 11.1123 8.16622 11.3333C8.16622 11.5543 8.25401 11.7663 8.41029 11.9226C8.56657 12.0789 8.77854 12.1667 8.99955 12.1667Z"
                    fill="black" />
            </svg>
        </p>
    </div>

    <div>
        <div class="mb-4 flex items-center gap-2 rounded-[0.375rem] bg-[#E9E9E9] p-2">
            <p>HUB: <span>{{ $hubLocation }}</span></p>

            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none">
                <path
                    d="M0.666992 13.8333V4.95833C0.666992 4.61111 0.760881 4.29861 0.948659 4.02083C1.13644 3.74306 1.38977 3.54167 1.70866 3.41667L8.37533 0.75C8.56977 0.666667 8.7781 0.625 9.00033 0.625C9.22255 0.625 9.43088 0.666667 9.62533 0.75L16.292 3.41667C16.6114 3.54167 16.865 3.74306 17.0528 4.02083C17.2406 4.29861 17.3342 4.61111 17.3337 4.95833V13.8333C17.3337 14.2917 17.1706 14.6842 16.8445 15.0108C16.5184 15.3375 16.1259 15.5006 15.667 15.5H12.3337V8.83333H5.66699V15.5H2.33366C1.87533 15.5 1.4831 15.3369 1.15699 15.0108C0.830881 14.6847 0.667548 14.2922 0.666992 13.8333ZM6.50033 15.5V13.8333H8.16699V15.5H6.50033ZM8.16699 13V11.3333H9.83366V13H8.16699ZM9.83366 15.5V13.8333H11.5003V15.5H9.83366Z"
                    fill="black" />
            </svg>
        </div>

        <div class="flex gap-2 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 18 20" fill="none">
                <path
                    d="M5.625 10.25C5.32663 10.25 5.04048 10.3685 4.8295 10.5795C4.61853 10.7905 4.5 11.0766 4.5 11.375C4.5 11.6734 4.61853 11.9595 4.8295 12.1705C5.04048 12.3815 5.32663 12.5 5.625 12.5C5.92337 12.5 6.20952 12.3815 6.4205 12.1705C6.63147 11.9595 6.75 11.6734 6.75 11.375C6.75 11.0766 6.63147 10.7905 6.4205 10.5795C6.20952 10.3685 5.92337 10.25 5.625 10.25ZM7.875 11.375C7.875 11.0766 7.99353 10.7905 8.2045 10.5795C8.41548 10.3685 8.70163 10.25 9 10.25H12.375C12.6734 10.25 12.9595 10.3685 13.1705 10.5795C13.3815 10.7905 13.5 11.0766 13.5 11.375C13.5 11.6734 13.3815 11.9595 13.1705 12.1705C12.9595 12.3815 12.6734 12.5 12.375 12.5H9C8.70163 12.5 8.41548 12.3815 8.2045 12.1705C7.99353 11.9595 7.875 11.6734 7.875 11.375ZM5.625 13.25C5.32663 13.25 5.04048 13.3685 4.8295 13.5795C4.61853 13.7905 4.5 14.0766 4.5 14.375C4.5 14.6734 4.61853 14.9595 4.8295 15.1705C5.04048 15.3815 5.32663 15.5 5.625 15.5H9C9.29837 15.5 9.58452 15.3815 9.79549 15.1705C10.0065 14.9595 10.125 14.6734 10.125 14.375C10.125 14.0766 10.0065 13.7905 9.79549 13.5795C9.58452 13.3685 9.29837 13.25 9 13.25H5.625Z"
                    fill="black" />
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M4.125 0.5C3.82663 0.5 3.54048 0.618526 3.3295 0.829505C3.11853 1.04048 3 1.32663 3 1.625V3.5C2.20435 3.5 1.44129 3.81607 0.87868 4.37868C0.31607 4.94129 0 5.70435 0 6.5V17C0 17.7956 0.31607 18.5587 0.87868 19.1213C1.44129 19.6839 2.20435 20 3 20H15C15.7956 20 16.5587 19.6839 17.1213 19.1213C17.6839 18.5587 18 17.7956 18 17V6.5C18 5.70435 17.6839 4.94129 17.1213 4.37868C16.5587 3.81607 15.7956 3.5 15 3.5V1.625C15 1.32663 14.8815 1.04048 14.6705 0.829505C14.4595 0.618526 14.1734 0.5 13.875 0.5C13.5766 0.5 13.2905 0.618526 13.0795 0.829505C12.8685 1.04048 12.75 1.32663 12.75 1.625V3.5H5.25V1.625C5.25 1.32663 5.13147 1.04048 4.9205 0.829505C4.70952 0.618526 4.42337 0.5 4.125 0.5ZM2.25 9.5C2.25 9.10218 2.40804 8.72064 2.68934 8.43934C2.97064 8.15804 3.35218 8 3.75 8H14.25C14.6478 8 15.0294 8.15804 15.3107 8.43934C15.592 8.72064 15.75 9.10218 15.75 9.5V16.25C15.75 16.6478 15.592 17.0294 15.3107 17.3107C15.0294 17.592 14.6478 17.75 14.25 17.75H3.75C3.35218 17.75 2.97064 17.592 2.68934 17.3107C2.40804 17.0294 2.25 16.6478 2.25 16.25V9.5Z"
                    fill="black" />
            </svg>

            <div class="space-y-1">
                <p>Lead time: <span>{{ $leadTime }}</span></p>
                <p>Recolecta: <span>{{ $recolectaTime }}</span></p>
                <p>Pickup: <span>{{ $pickupTime }}</span></p>
            </div>
        </div>

        <div class="flex gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 16 18" fill="none">
                <path
                    d="M2.00016 16H14.0002L12.5752 6H3.42517L2.00016 16ZM8.00016 4C8.2835 4 8.52116 3.904 8.71317 3.712C8.90517 3.52 9.00083 3.28267 9.00016 3C8.9995 2.71733 8.9035 2.48 8.71216 2.288C8.52083 2.096 8.2835 2 8.00016 2C7.71683 2 7.4795 2.096 7.28817 2.288C7.09683 2.48 7.00083 2.71733 7.00016 3C6.9995 3.28267 7.0955 3.52033 7.28817 3.713C7.48083 3.90567 7.71816 4.00133 8.00016 4ZM10.8252 4H12.5752C13.0752 4 13.5085 4.16667 13.8752 4.5C14.2418 4.83333 14.4668 5.24167 14.5502 5.725L15.9752 15.725C16.0585 16.325 15.9045 16.8543 15.5132 17.313C15.1218 17.7717 14.6175 18.0007 14.0002 18H2.00016C1.3835 18 0.879165 17.771 0.487165 17.313C0.0951649 16.855 -0.058835 16.3257 0.025165 15.725L1.45016 5.725C1.5335 5.24167 1.7585 4.83333 2.12516 4.5C2.49183 4.16667 2.92517 4 3.42517 4H5.17517C5.12516 3.83333 5.0835 3.671 5.05017 3.513C5.01683 3.355 5.00016 3.184 5.00016 3C5.00016 2.16667 5.29183 1.45833 5.87516 0.875C6.4585 0.291667 7.16683 0 8.00016 0C8.8335 0 9.54183 0.291667 10.1252 0.875C10.7085 1.45833 11.0002 2.16667 11.0002 3C11.0002 3.18333 10.9835 3.35433 10.9502 3.513C10.9168 3.67167 10.8752 3.834 10.8252 4Z"
                    fill="black" />
            </svg>

            <div>
                <p>Carga total: <span>{{ $totalWeight }}</span></p>
            </div>
        </div>
    </div>
</li>
