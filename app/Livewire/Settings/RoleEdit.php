<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class RoleEdit extends Component
{
    public function render()
    {
        return view('livewire.settings.role-edit')
            ->layout('layouts.settings.role-edit', [
                'title' => 'Editar rol',
                'subtitle' => 'Edita el rol para tu equipo.',
            ]);
    }
}
