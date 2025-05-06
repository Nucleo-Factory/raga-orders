<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleEdit extends Component {
    public $role;
    public $permissions;
    public $name;
    public $selectedPermissions = [];

    public function mount($roleId) {
        $this->role = Role::findOrFail($roleId);

        // Obtener todos los permisos de la base de datos
        $dbPermissions = Permission::all()->pluck('name', 'name')->toArray();

        // Definir los permisos básicos
        $basicPermissions = [
            'read' => 'Lectura',
            'export' => 'Exportar',
            'filter' => 'Filtrar',
        ];

        // Combinar los permisos básicos con los de la base de datos
        $this->permissions = array_merge($basicPermissions, $dbPermissions);

        $this->name = $this->role->name;
        $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();

        // Debug para ver qué permisos se están cargando
        \Log::info('Permissions:', [
            'all_permissions' => $this->permissions,
            'selected_permissions' => $this->selectedPermissions,
            'role_permissions' => $this->role->permissions->toArray()
        ]);
    }

    public function hasPermission($permission) {
        return in_array($permission, $this->selectedPermissions);
    }

    public function updateRole()
    {
        $this->role->syncPermissions($this->selectedPermissions);
        session()->flash('message', 'Rol actualizado exitosamente.');
    }

    public function render() {
        return view('livewire.settings.role-edit')
            ->layout('layouts.settings.role-edit', [
                'title' => 'Editar rol',
                'subtitle' => 'Edita el rol para tu equipo.',
            ]);
    }
}
