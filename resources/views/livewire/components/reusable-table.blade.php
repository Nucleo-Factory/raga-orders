<div>
    {{-- In work, do what you enjoy. --}}
    <div class="mb-4 flex justify-between">
        @if ($showSearch && !empty($searchable))
            <div class="flex items-center">
                <x-search-input class="w-64" wire:model.debounce.300ms="search" />

                @if (!empty($filterable) && !empty($filterOptions))
                    @foreach ($filterable as $filter)
                        @if (isset($filterOptions[$filter]))
                            <select wire:model="filters.{{ $filter }}"
                                class="ml-4 rounded-md border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos</option>
                                @foreach ($filterOptions[$filter] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        @endif
                    @endforeach
                @endif
            </div>
        @endif

        @if ($showPerPage)
            <div>
                <select wire:model="perPage"
                    class="rounded-md border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="10">10 por página</option>
                    <option value="25">25 por página</option>
                    <option value="50">50 por página</option>
                    <option value="100">100 por página</option>
                </select>
            </div>
        @endif
    </div>

    <div class="overflow-x-auto rounded-t-xl">
        <table class="min-w-full divide-y divide-[#E0E5FF]">
            <thead class="bg-[#E0E5FF]">
                <tr>
                    @foreach ($headers as $key => $header)
                        <th scope="col"
                            class="{{ in_array($key, $sortable) ? 'cursor-pointer' : '' }} px-6 py-3 text-left text-lg font-bold text-[#121619]"
                            @if (in_array($key, $sortable)) wire:click="sortBy('{{ $key }}')" @endif>
                            {{ $header }}
                            @if (in_array($key, $sortable) && $sortField === $key)
                                @if ($sortDirection === 'asc')
                                    <svg class="ml-1 inline-block h-4 w-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="ml-1 inline-block h-4 w-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E0E5FF] bg-white">
                @forelse($processedRows as $row)
                    <tr>
                        @foreach ($headers as $key => $header)
                            <td
                                class="{{ $key === 'actions' ? 'whitespace-nowrap text-sm font-medium' : '' }} px-6 py-4">
                                @if ($key === 'actions')
                                    @if ($showActions)
                                        <div class="flex items-center space-x-2">
                                            @if ($actionsEdit)
                                                <a href="{{ $this->getRouteFor('edit', $row) }}"
                                                    class="group">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M1.91732 12.0786C1.94795 11.8029 1.96326 11.6651 2.00497 11.5363C2.04197 11.422 2.09425 11.3132 2.16038 11.2129C2.23493 11.0999 2.33299 11.0018 2.52911 10.8057L11.3333 2.0015C12.0697 1.26512 13.2636 1.26512 14 2.0015C14.7364 2.73788 14.7364 3.93179 14 4.66817L5.19578 13.4724C4.99966 13.6685 4.9016 13.7665 4.78855 13.8411C4.68826 13.9072 4.57949 13.9595 4.46519 13.9965C4.33636 14.0382 4.19853 14.0535 3.92287 14.0841L1.66663 14.3348L1.91732 12.0786Z" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="group-hover:stroke-blue-900" />
                                                    </svg>
                                                </a>
                                            @endif
                                            @if ($actionsView)
                                                <a href="{{ $this->getRouteFor('view', $row) }}"
                                                    class="text-[#666666] hover:text-indigo-900">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @endif
                                            @if ($actionsDelete)
                                                <button type="button"
                                                    wire:click="confirmDelete('{{ $useModel ? $row->{$routeKeyName} : $row[$routeKeyName] ?? '' }}')"
                                                    class="group">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                                        <path d="M5 1H9M1 3H13M11.6667 3L11.1991 10.0129C11.129 11.065 11.0939 11.5911 10.8667 11.99C10.6666 12.3412 10.3648 12.6235 10.0011 12.7998C9.58798 13 9.06073 13 8.00623 13H5.99377C4.93927 13 4.41202 13 3.99889 12.7998C3.63517 12.6235 3.33339 12.3412 3.13332 11.99C2.90607 11.5911 2.871 11.065 2.80086 10.0129L2.33333 3" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="group-hover:stroke-red-900"
                                                        />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    @elseif($useModel && method_exists($row, 'getActionButtons'))
                                        {!! $row->getActionButtons() !!}
                                    @elseif(isset($row->{$key . '_html'}) || isset($row[$key . '_html']))
                                        {!! $useModel ? $row->{$key . '_html'} : $row[$key . '_html'] !!}
                                    @elseif($useModel && isset($row->$key))
                                        @if (is_array($row->$key))
                                            @foreach ($row->$key as $action => $url)
                                                <a href="{{ $url }}"
                                                    class="{{ $loop->first ? 'text-indigo-600 hover:text-indigo-900' : 'ml-2 text-blue-600 hover:text-blue-900' }}">{{ $action }}</a>
                                            @endforeach
                                        @else
                                            @php
                                                $actions = explode(',', $row->$key);
                                            @endphp
                                            @foreach ($actions as $index => $action)
                                                <span
                                                    class="{{ $index > 0 ? 'ml-2' : '' }} {{ $index === 0 ? 'text-indigo-600 hover:text-indigo-900' : 'text-blue-600 hover:text-blue-900' }}">{{ trim($action) }}</span>
                                            @endforeach
                                        @endif
                                    @elseif(!$useModel && isset($row[$key]))
                                        @if (is_array($row[$key]))
                                            @foreach ($row[$key] as $action => $url)
                                                <a href="{{ $url }}"
                                                    class="{{ $loop->first ? 'text-indigo-600 hover:text-indigo-900' : 'ml-2 text-blue-600 hover:text-blue-900' }}">{{ $action }}</a>
                                            @endforeach
                                        @else
                                            @php
                                                $actions = explode(',', $row[$key]);
                                            @endphp
                                            @foreach ($actions as $index => $action)
                                                <span
                                                    class="{{ $index > 0 ? 'ml-2' : '' }} {{ $index === 0 ? 'text-indigo-600 hover:text-indigo-900' : 'text-blue-600 hover:text-blue-900' }}">{{ trim($action) }}</span>
                                            @endforeach
                                        @endif
                                    @endif
                                @else
                                    <div class="text-sm text-[#2E2E2E] font-dm-sans">
                                        @if ($useModel)
                                            @if (isset($row->{$key . '_formatted'}))
                                                {!! $row->{$key . '_formatted'} !!}
                                            @elseif(isset($row->$key))
                                                @if (is_array($row->$key))
                                                    @foreach ($row->$key as $item)
                                                        <span
                                                            class="mr-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                            {{ $item }}
                                                        </span>
                                                    @endforeach
                                                @elseif($row->$key instanceof \Illuminate\Support\Collection)
                                                    @foreach ($row->$key as $item)
                                                        <span
                                                            class="mr-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                            {{ is_object($item) ? (method_exists($item, '__toString') ? $item : $item->id) : $item }}
                                                        </span>
                                                    @endforeach
                                                @elseif($row->$key instanceof \Carbon\Carbon)
                                                    {{ $row->$key->format('d/m/Y') }}
                                                @elseif(strpos($key, '.') !== false && str_contains($key, '_count'))
                                                    {{ $row->$key }}
                                                @elseif(is_object($row->$key))
                                                    {{ method_exists($row->$key, '__toString') ? $row->$key : $row->$key->id }}
                                                @else
                                                    {{ $row->$key }}
                                                @endif
                                            @elseif(strpos($key, '.') !== false)
                                                @php
                                                    $parts = explode('.', $key);
                                                    $value = $row;
                                                    foreach ($parts as $part) {
                                                        if (is_object($value) && isset($value->$part)) {
                                                            $value = $value->$part;
                                                        } elseif (is_array($value) && isset($value[$part])) {
                                                            $value = $value[$part];
                                                        } else {
                                                            $value = null;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                @if ($value !== null)
                                                    @if (is_object($value) && method_exists($value, '__toString'))
                                                        {{ $value }}
                                                    @elseif(!is_object($value) && !is_array($value))
                                                        {{ $value }}
                                                    @endif
                                                @endif
                                            @endif
                                        @else
                                            @if (isset($row[$key]))
                                                @if (is_array($row[$key]))
                                                    @foreach ($row[$key] as $item)
                                                        <span
                                                            class="mr-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                                            {{ $item }}
                                                        </span>
                                                    @endforeach
                                                @elseif(isset($row[$key . '_formatted']))
                                                    {!! $row[$key . '_formatted'] !!}
                                                @else
                                                    {{ $row[$key] }}
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-6 py-4 text-center text-gray-500">
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showPagination)
        <div class="mt-4">
            {{ $processedRows->links() }}
        </div>
    @endif

    <!-- Modal de confirmación de eliminación -->
    @if ($confirmingDelete)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    Confirmar eliminación
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        ¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede
                                        deshacer.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button wire:click="delete" type="button"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                            Eliminar
                        </button>
                        <button wire:click="cancelDelete" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
