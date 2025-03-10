<sidebar
    class="main-sidebar fixed left-0 top-0 z-10 flex h-full max-h-[1000px] flex-col gap-8 rounded-br-[1.25rem] rounded-tr-[1.25rem] bg-white px-2 py-6 text-[0.875rem] transition-all duration-500 sm:static sm:px-3 sm:py-[2.625rem]">
    <div class="mx-auto">
        <a href="/">
            <img src="{{ asset("img/logo-negro.png") }}" alt="Raga Logo">
        </a>
    </div>

    <div class="h-[1px] w-full bg-[#B9B9B9]"></div>

    <nav>
        <ul class="space-y-2">
            <li>
                <x-sidebar-link href="{{ route('dashboard') }}">
                    <div class="flex items-center justify-center w-6 h-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="23" viewBox="0 0 17 23"
                            fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M16.5833 21.5808C16.5833 22.2612 16.0645 22.8134 15.4226 22.8134C14.7807 22.8134 14.2619 22.2612 14.2619 21.5808C14.2619 18.1825 11.6584 15.4178 8.45833 15.4178C5.25824 15.4178 2.65476 18.1825 2.65476 21.5808C2.65476 22.2612 2.13592 22.8134 1.49404 22.8134C0.852168 22.8134 0.333328 22.2612 0.333328 21.5808C0.333328 16.823 3.97913 12.9526 8.45833 12.9526C12.9375 12.9526 16.5833 16.823 16.5833 21.5808ZM8.45833 3.09189C9.7386 3.09189 10.7798 4.19753 10.7798 5.55708C10.7798 6.91663 9.7386 8.02226 8.45833 8.02226C7.17806 8.02226 6.1369 6.91663 6.1369 5.55708C6.1369 4.19753 7.17806 3.09189 8.45833 3.09189ZM8.45833 10.4874C11.0189 10.4874 13.1012 8.27618 13.1012 5.55708C13.1012 2.83798 11.0189 0.626709 8.45833 0.626709C5.89779 0.626709 3.81547 2.83798 3.81547 5.55708C3.81547 8.27618 5.89779 10.4874 8.45833 10.4874Z"
                                fill="black" />
                        </svg>
                    </div>

                    <span class="link-text">Ordenes de compra</span>
                </x-sidebar-link>
            </li>

            <li>
                <x-sidebar-link href="{{ route('purchase-orders.kanban') }}">
                    <div class="flex items-center justify-center w-6 h-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                    </div>

                    <span class="link-text">Kanban</span>
                </x-sidebar-link>
            </li>

            <li>
                <x-sidebar-link href="{{ route('products.create') }}">
                    <div class="flex items-center justify-center w-6 h-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="23" viewBox="0 0 22 23"
                            fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M2.76971 11.4288C3.97871 11.8536 4.94179 12.8897 5.34479 14.2032L5.38812 14.3363C5.84962 15.7296 5.66221 17.2294 4.88871 18.3543C4.74787 18.5584 4.77929 18.8025 4.92771 18.9179L7.17237 20.6639C7.25146 20.725 7.32837 20.725 7.37604 20.7194C7.43021 20.7105 7.50929 20.6806 7.57321 20.5874L7.82346 20.2235C8.56987 19.1397 9.77237 18.4708 11.042 18.4331C12.4677 18.4031 13.7482 19.072 14.5455 20.2513L14.6734 20.441C14.7373 20.5341 14.8153 20.5652 14.8705 20.5741C14.9182 20.5841 14.9962 20.5807 15.0742 20.5186L17.307 18.7936C17.463 18.6738 17.4976 18.4175 17.3817 18.2456L17.1 17.8296C16.3742 16.7558 16.1586 15.3624 16.5237 14.1034C16.9202 12.7322 17.9288 11.6473 19.2234 11.2036L19.4411 11.1281C19.6155 11.0693 19.7098 10.8475 19.648 10.6433L18.7955 7.84672C18.7554 7.71582 18.673 7.65702 18.6275 7.63262C18.5625 7.59823 18.4921 7.59268 18.425 7.61598L18.0566 7.74133C16.7967 8.17064 15.4068 7.93768 14.3397 7.11567L14.2227 7.02581C13.2087 6.24484 12.6053 4.98575 12.6096 3.65899L12.6118 3.34838C12.6118 3.20084 12.5435 3.10876 12.5024 3.06661C12.4634 3.02556 12.3973 2.97675 12.3041 2.97675L9.54487 2.97342C9.37587 2.97342 9.23829 3.13871 9.23721 3.34283L9.23612 3.61129C9.23071 4.95913 8.61429 6.24152 7.58729 7.04356L7.44754 7.15228C6.31762 8.03198 4.84429 8.28047 3.50962 7.81455C3.45871 7.7968 3.41104 7.80013 3.36554 7.82453C3.33087 7.84228 3.26804 7.88776 3.23771 7.98871L2.35262 10.8685C2.28871 11.0782 2.39379 11.2967 2.59204 11.3666L2.76971 11.4288ZM7.33162 22.9414C6.80512 22.9414 6.29379 22.7661 5.86262 22.43L3.61796 20.685C2.54546 19.853 2.30821 18.2523 3.08821 17.1174C3.49337 16.5295 3.57679 15.774 3.33846 15.0574L3.27887 14.871C3.08062 14.2254 2.62779 13.724 2.06771 13.5276H2.06662L1.89004 13.4644C0.570541 13.0018 -0.133626 11.5696 0.285624 10.2029L1.16962 7.32422C1.37004 6.67305 1.80229 6.14722 2.38729 5.84437C2.95929 5.54929 3.60604 5.50381 4.21054 5.71569C4.85946 5.942 5.57987 5.81886 6.13562 5.38622L6.27537 5.27751C6.76937 4.89146 7.06729 4.26469 7.06946 3.60242L7.07054 3.33507C7.07596 1.91068 8.18637 0.754761 9.54379 0.754761H9.54813L12.3074 0.758089C12.9595 0.759198 13.5749 1.02322 14.0385 1.50134C14.5185 1.99499 14.7806 2.65505 14.7785 3.35947L14.7763 3.66898C14.7741 4.29686 15.0547 4.89035 15.5281 5.25421L15.644 5.34407C16.1413 5.72679 16.7891 5.83661 17.3709 5.63693L17.7381 5.51158C18.3632 5.29858 19.0284 5.35072 19.6145 5.65801C20.2157 5.97306 20.6599 6.51552 20.8635 7.18667L21.7161 9.98329C22.1278 11.3345 21.415 12.7921 20.129 13.2325L19.9113 13.3068C19.2873 13.5221 18.7965 14.0545 18.6005 14.7334C18.4206 15.3558 18.5246 16.0402 18.881 16.5661L19.1627 16.9821C19.9362 18.1269 19.6892 19.7332 18.6124 20.5641L16.3796 22.2902C15.8434 22.7051 15.1858 22.8726 14.526 22.765C13.8609 22.6552 13.2802 22.278 12.8913 21.7034L12.7635 21.5126C12.3843 20.9535 11.7776 20.614 11.1417 20.6506C10.5047 20.6684 9.95437 20.9779 9.59471 21.5015L9.34446 21.8653C8.95229 22.4344 8.37054 22.8061 7.70862 22.9126C7.58187 22.9325 7.45621 22.9414 7.33162 22.9414ZM11 10.184C10.1041 10.184 9.37501 10.9305 9.37501 11.848C9.37501 12.7654 10.1041 13.512 11 13.512C11.8959 13.512 12.625 12.7654 12.625 11.848C12.625 10.9305 11.8959 10.184 11 10.184ZM11 15.7306C8.90917 15.7306 7.20834 13.989 7.20834 11.848C7.20834 9.70696 8.90917 7.96531 11 7.96531C13.0908 7.96531 14.7917 9.70696 14.7917 11.848C14.7917 13.989 13.0908 15.7306 11 15.7306Z"
                                fill="black" />
                        </svg>
                    </div>

                    <span class="link-text">Productos</span>
                </x-sidebar-link>
            </li>
        </ul>
    </nav>
</sidebar>
