<div>
    {{-- In work, do what you enjoy. --}}
    <div class="flex justify-between mb-4">
        @if($showSearch && !empty($searchable))
        <div class="flex items-center">
            <div class="relative">
                <input
                    type="text"
                    wire:model.debounce.300ms="search"
                    placeholder="Buscar..."
                    class="w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            @if(!empty($filterable) && !empty($filterOptions))
                @foreach($filterable as $filter)
                    @if(isset($filterOptions[$filter]))
                        <select
                            wire:model="filters.{{ $filter }}"
                            class="px-4 py-2 ml-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Todos</option>
                            @foreach($filterOptions[$filter] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    @endif
                @endforeach
            @endif
        </div>
        @endif

        @if($showPerPage)
        <div>
            <select
                wire:model="perPage"
                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
                <option value="100">100 por página</option>
            </select>
        </div>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $key => $header)
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase {{ in_array($key, $sortable) ? 'cursor-pointer' : '' }}"
                            @if(in_array($key, $sortable)) wire:click="sortBy('{{ $key }}')" @endif>
                            {{ $header }}
                            @if(in_array($key, $sortable) && $sortField === $key)
                                @if($sortDirection === 'asc')
                                    <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="inline-block w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($processedRows as $row)
                    <tr>
                        @foreach($headers as $key => $header)
                            <td class="px-6 py-4 {{ $key === 'actions' ? 'whitespace-nowrap text-sm font-medium' : 'whitespace-nowrap' }}">
                                @if($key === 'actions')
                                    @if($showActions)
                                        <div class="flex items-center space-x-2">
                                            @if($actionsEdit)
                                                <a href="{{ $this->getRouteFor('edit', $row) }}" class="text-blue-600 hover:text-blue-900">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                            @if($actionsView)
                                                <a href="{{ $this->getRouteFor('view', $row) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                            @if($actionsDelete)
                                                <button type="button" wire:click="confirmDelete('{{ $useModel ? $row->{$routeKeyName} : $row[$routeKeyName] ?? '' }}')" class="text-red-600 hover:text-red-900">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    @elseif($useModel && method_exists($row, 'getActionButtons'))
                                        {!! $row->getActionButtons() !!}
                                    @elseif(isset($row->{$key.'_html'}) || isset($row[$key.'_html']))
                                        {!! $useModel ? $row->{$key.'_html'} : $row[$key.'_html'] !!}
                                    @elseif($useModel && isset($row->$key))
                                        @if(is_array($row->$key))
                                            @foreach($row->$key as $action => $url)
                                                <a href="{{ $url }}" class="{{ $loop->first ? 'text-indigo-600 hover:text-indigo-900' : 'ml-2 text-blue-600 hover:text-blue-900' }}">{{ $action }}</a>
                                            @endforeach
                                        @else
                                            @php
                                                $actions = explode(',', $row->$key);
                                            @endphp
                                            @foreach($actions as $index => $action)
                                                <span class="{{ $index > 0 ? 'ml-2' : '' }} {{ $index === 0 ? 'text-indigo-600 hover:text-indigo-900' : 'text-blue-600 hover:text-blue-900' }}">{{ trim($action) }}</span>
                                            @endforeach
                                        @endif
                                    @elseif(!$useModel && isset($row[$key]))
                                        @if(is_array($row[$key]))
                                            @foreach($row[$key] as $action => $url)
                                                <a href="{{ $url }}" class="{{ $loop->first ? 'text-indigo-600 hover:text-indigo-900' : 'ml-2 text-blue-600 hover:text-blue-900' }}">{{ $action }}</a>
                                            @endforeach
                                        @else
                                            @php
                                                $actions = explode(',', $row[$key]);
                                            @endphp
                                            @foreach($actions as $index => $action)
                                                <span class="{{ $index > 0 ? 'ml-2' : '' }} {{ $index === 0 ? 'text-indigo-600 hover:text-indigo-900' : 'text-blue-600 hover:text-blue-900' }}">{{ trim($action) }}</span>
                                            @endforeach
                                        @endif
                                    @endif
                                @else
                                    <div class="text-sm text-gray-900">
                                        @if($useModel)
                                            @if(isset($row->{$key.'_formatted'}))
                                                {!! $row->{$key.'_formatted'} !!}
                                            @elseif(isset($row->$key))
                                                @if(is_array($row->$key))
                                                    @foreach($row->$key as $item)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                                            {{ $item }}
                                                        </span>
                                                    @endforeach
                                                @elseif($row->$key instanceof \Illuminate\Support\Collection)
                                                    @foreach($row->$key as $item)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
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
                                                    foreach($parts as $part) {
                                                        if(is_object($value) && isset($value->$part)) {
                                                            $value = $value->$part;
                                                        } elseif(is_array($value) && isset($value[$part])) {
                                                            $value = $value[$part];
                                                        } else {
                                                            $value = null;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                @if($value !== null)
                                                    @if(is_object($value) && method_exists($value, '__toString'))
                                                        {{ $value }}
                                                    @elseif(!is_object($value) && !is_array($value))
                                                        {{ $value }}
                                                    @endif
                                                @endif
                                            @endif
                                        @else
                                            @if(isset($row[$key]))
                                                @if(is_array($row[$key]))
                                                    @foreach($row[$key] as $item)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
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

    @if($showPagination)
    <div class="mt-4">
        {{ $processedRows->links() }}
    </div>
    @endif

    <!-- Modal de confirmación de eliminación -->
    @if($confirmingDelete)
    <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Confirmar eliminación
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    ¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="delete" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Eliminar
                    </button>
                    <button wire:click="cancelDelete" type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
