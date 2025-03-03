<?php

namespace App\Livewire\Kanban;

use Livewire\Component;
use Livewire\Attributes\On;

class KanbanBoard extends Component {
    public $columns = [];
    public $tasks = [];

    public function mount() {
        $this->loadColumns();
        $this->loadTasks();
    }

    public function loadColumns() {
        $this->columns = [
            ['id' => 'pending', 'name' => 'Pendientes'],
            ['id' => 'in_progress', 'name' => 'En Progreso'],
            ['id' => 'review', 'name' => 'En Revisión'],
            ['id' => 'completed', 'name' => 'Completadas'],
        ];
    }

    public function loadTasks() {
        $this->tasks = [
            [
                'id' => 1,
                'po' => '12345a',
                'trackingId' => '11111',
                'hubLocation' => 'New Jersey',
                'leadTime' => '2024-01-01',
                'recolectaTime' => '2024-11-11',
                'pickupTime' => '2024-11-11',
                'totalWeight' => '10 tons',
                'status' => 'pending',
            ],
            [
                'id' => 2,
                'po' => '12345b',
                'trackingId' => '22222',
                'hubLocation' => 'California',
                'leadTime' => '2024-02-01',
                'recolectaTime' => '2024-12-11',
                'pickupTime' => '2024-12-11',
                'totalWeight' => '15 tons',
                'status' => 'in_progress',
            ],
            [
                'id' => 3,
                'po' => '12345c',
                'trackingId' => '33333',
                'hubLocation' => 'Texas',
                'leadTime' => '2024-03-01',
                'recolectaTime' => '2024-13-11',
                'pickupTime' => '2024-13-11',
                'totalWeight' => '20 tons',
                'status' => 'review',
            ],
            [
                'id' => 4,
                'po' => '12345d',
                'trackingId' => '44444',
                'hubLocation' => 'Florida',
                'leadTime' => '2024-04-01',
                'recolectaTime' => '2024-14-11',
                'pickupTime' => '2024-14-11',
                'totalWeight' => '25 tons',
                'status' => 'completed',
            ],
        ];
    }

    #[On('task-moved')]
    public function moveTask($taskId, $newStatus) {
        foreach ($this->tasks as $key => $task) {
            if ($task['id'] == $taskId) {
                // Update the status
                $this->tasks[$key]['status'] = $newStatus;
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.kanban.kanban-board');
    }
}
