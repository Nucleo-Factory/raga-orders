<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    public $id;
    public $user;
    public $headers = [
        'name' => 'Nombre',
        'email' => 'Email',
        'role' => 'Rol',
        'created_at' => 'Fecha de registro',
        'actions' => 'Acciones'
    ];

    public function deleteUser($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->delete();
            $this->dispatch('close-modal', 'modal-delete-user');
        }
    }

    public function openModal($id) {
        $this->id = $id;
        $this->user = User::find($id);
        $this->dispatch('open-modal', 'modal-delete-user');
    }

    public function render()
    {
        $users = User::with('roles')->get();

        return view('livewire.settings.users', [
            'users' => $users
        ])->layout('layouts.settings.user-management');
    }
}
