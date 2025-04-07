<?php

namespace App\Livewire\Settings;

use App\Models\Session;
use App\Models\User;
use Livewire\Component;

class Sessions extends Component {

    public $sessions;
    public $headers = [
        'Usuario',
        'IP',
        'Navegador',
        'Última actividad',
    ];

    public $users;

    public function mount()
    {
        $this->sessions = Session::all();
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.settings.sessions', [
            'sessions' => $this->sessions,
            'users' => $this->users,
        ])->layout('layouts.settings.user-management');
    }
}
