<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleCreate extends Component
{
    public $name;
    public $selectedPermissions = [];
    public $permissions = [];
    public $id;

    public function mount($id = null)
    {
        $this->id = $id;

        // Inicializamos los permisos basados en tu plantilla
        $this->permissions = [
            'read' => 'Permiso de lectura',
            'export' => 'Exportar datos',
            'filter' => 'Filtrar',
            'metrics.view' => 'Consultar métricas generales de operaciones',
            'orders.comment' => 'Agregar comentarios específicos a las órdenes',
            'tasks.attach_documents' => 'Adjuntar documentos relacionados con las tareas',
            'reports.costs' => 'Generar reportes de ahorro y costos operativos',
            'orders.deviations' => 'Descargar datos sobre desviaciones en órdenes',
            'users.monitor' => 'Monitorear perfiles de usuarios',
            'notifications.access' => 'Acceder a registros de notificaciones',
            'kpis.view' => 'Visualizar análisis y KPIs relevantes',
            'events.history' => 'Visualizar Historial de eventos y acciones'
        ];
    }

    public function createRole()
    {
        $this->validate([
            'name' => 'required|min:3|unique:roles,name',
            'selectedPermissions' => 'required|array|min:1',
        ]);

        // Crear el rol
        $role = Role::create(['name' => $this->name]);

        // Crear y asignar permisos
        foreach ($this->selectedPermissions as $permission => $value) {
            if ($value) {
                // Crear el permiso si no existe
                Permission::firstOrCreate(['name' => $permission]);
                $role->givePermissionTo($permission);
            }
        }

        $this->reset(['name', 'selectedPermissions']);
        $this->dispatch('open-modal', 'modal-role-created');
    }

    public function togglePermission($permission)
    {
        if (!isset($this->selectedPermissions[$permission])) {
            $this->selectedPermissions[$permission] = true;
        } else {
            $this->selectedPermissions[$permission] = !$this->selectedPermissions[$permission];
        }
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', 'modal-role-created');
    }

    public function render()
    {
        return view('livewire.settings.role-create')
            ->layout('layouts.settings.role-edit', [
                'title' => 'Crear rol',
                'subtitle' => 'Crea un nuevo rol para tu equipo.',
            ]);
    }
}
