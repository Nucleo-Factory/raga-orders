<div class="flex w-full p-4 space-x-4 overflow-x-auto">
    @foreach($columns as $column)
        <div class="flex-shrink-0 p-3 bg-gray-100 rounded-lg">
            <h3 class="mb-3 text-lg font-bold">{{ $column['name'] }}</h3>

            <div
                id="column-{{ $column['id'] }}"
                data-column-id="{{ $column['id'] }}"
                class="space-y-3 min-h-40"
                x-data
                x-init="
                    new Sortable($el, {
                        group: 'tasks',
                        animation: 150,
                        onEnd: function(evt) {
                            const taskId = evt.item.getAttribute('data-task-id');
                            const newColumn = evt.to.getAttribute('data-column-id');

                            if (evt.from.getAttribute('data-column-id') !== newColumn) {
                                $wire.dispatch('task-moved', {
                                    taskId: taskId,
                                    newStatus: newColumn
                                });
                            }
                        }
                    })
                "
            >
                @foreach(collect($tasks)->where('status', $column['id']) as $task)
                    <div
                        class="p-3 bg-white rounded shadow cursor-move task-card"
                        data-task-id="{{ $task['id'] }}"
                    >
                        <x-kanban-card
                            :po="$task['po']"
                            :trackingId="$task['trackingId']"
                            :hubLocation="$task['hubLocation']"
                            :leadTime="$task['leadTime']"
                            :recolectaTime="$task['recolectaTime']"
                            :pickupTime="$task['pickupTime']"
                            :totalWeight="$task['totalWeight']"
                        />
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
