<?php

namespace App\Livewire\Tables;

use Livewire\Component;

class RequestsTable extends Component {

    public $actions = false;

    public function mount($actions = false) {
        $this->actions = $actions;
    }

    public function render() {
        return view('livewire.tables.requests-table');
    }
}
