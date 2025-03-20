<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class History extends Component
{
    public function render()
    {
        return view('livewire.settings.history')
            ->layout('layouts.settings.audit');
    }
}
