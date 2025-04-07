<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Users extends Component
{
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
            session()->flash('message', 'Usuario eliminado correctamente.');
        }
    }

    public function render()
    {
        $users = User::with('roles')->get();

        return view('livewire.settings.users', [
            'users' => $users
        ])->layout('layouts.settings.user-management');
    }
}
