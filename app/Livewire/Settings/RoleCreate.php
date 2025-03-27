<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class RoleCreate extends Component
{
    public function render()
    {
        return view('livewire.settings.role-create')
            ->layout('layouts.settings.role-edit', [
                'title' => 'Crear rol',
                'subtitle' => 'Crea un nuevo rol para tu equipo.',
            ]);
    }
}
