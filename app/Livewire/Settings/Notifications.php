<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\NotificationType;
use Illuminate\Support\Facades\Log;

class Notifications extends Component
{
    public array $preferences = [];
    public array $frequencies = [];

    // Añadimos listeners para los eventos de toggle
    protected $listeners = [
        'togglePreference' => 'togglePreference',
        'toggleFrequency' => 'toggleFrequency'
    ];

    public function mount()
    {
        Log::info('Mounting notifications component');

        // Inicializar todas las preferencias con valores por defecto
        $notificationTypes = NotificationType::all();

        Log::info('Notification types count: ' . $notificationTypes->count());

        // Si no hay tipos de notificaciones, inicializar manualmente
        if ($notificationTypes->isEmpty()) {
            Log::warning('No notification types found in database');
            $this->initDefaultPreferences();
            return;
        }

        // Obtener preferencias actuales del usuario
        $user = auth()->user();
        $userPreferences = $user->notificationPreferences()->get();

        Log::info('User preferences count: ' . $userPreferences->count());

        // Agrupar por tipo de notificación
        foreach ($notificationTypes as $type) {
            $userPref = $userPreferences->where('notification_type_id', $type->id)->first();

            $this->preferences[$type->key] = [
                'id' => $type->id,
                'enabled' => $userPref ? $userPref->enabled : false,
            ];
        }

        // Obtener las frecuencias seleccionadas por el usuario
        $userFrequencies = $user->frequencies()->get();

        Log::info('User frequencies count: ' . $userFrequencies->count());

        // Establecer las frecuencias predeterminadas
        $this->frequencies = [
            'immediate' => $userFrequencies->contains('frequency', 'immediate'),
            'daily' => $userFrequencies->contains('frequency', 'daily'),
            'weekly' => $userFrequencies->contains('frequency', 'weekly'),
        ];
    }

    // Método para inicializar preferencias por defecto
    private function initDefaultPreferences()
    {
        $defaultTypes = [
            'mobile_notifications', 'email_notifications', 'platform_notifications',
            'status_update', 'issues_detected', 'successful_deliveries',
            'pending_tasks', 'upcoming_deadlines', 'user_customization',
            'order_creation_changes', 'order_consolidation'
        ];

        foreach ($defaultTypes as $type) {
            $this->preferences[$type] = [
                'id' => 0,
                'enabled' => false,
            ];
        }

        $this->frequencies = [
            'immediate' => false,
            'daily' => false,
            'weekly' => false,
        ];
    }

    public function togglePreference($key, $field = 'enabled')
    {
        Log::info("Toggling preference: {$key}.{$field}");

        if (isset($this->preferences[$key])) {
            $oldValue = $this->preferences[$key][$field];
            $this->preferences[$key][$field] = !$oldValue;
            Log::info("Changed from {$oldValue} to {$this->preferences[$key][$field]}");
        } else {
            Log::warning("Key not found: {$key}");
        }
    }

    public function toggleFrequency($frequency)
    {
        Log::info("Toggling frequency: {$frequency}");

        if (isset($this->frequencies[$frequency])) {
            $oldValue = $this->frequencies[$frequency];
            $this->frequencies[$frequency] = !$oldValue;
            Log::info("Changed from {$oldValue} to {$this->frequencies[$frequency]}");
        } else {
            Log::warning("Frequency not found: {$frequency}");
        }
    }

    public function save()
    {
        Log::info('Saving preferences...');
        Log::info('Preferences: ' . json_encode($this->preferences));
        Log::info('Frequencies: ' . json_encode($this->frequencies));

        try {
            $user = auth()->user();

            // Guardar cada preferencia
            foreach ($this->preferences as $key => $values) {
                $notificationType = NotificationType::where('key', $key)->first();

                if (!$notificationType) {
                    Log::warning("Notification type not found for key: {$key}");
                    continue;
                }

                Log::info("Saving preference for type: {$key} with value: " . ($values['enabled'] ? 'true' : 'false'));

                $user->notificationPreferences()->updateOrCreate(
                    ['notification_type_id' => $notificationType->id],
                    ['enabled' => $values['enabled']]
                );
            }

            // Guardar frecuencias
            Log::info("Deleting existing frequencies for user: {$user->id}");
            $user->frequencies()->delete();

            // Luego crear nuevas frecuencias basadas en las selecciones del usuario
            foreach ($this->frequencies as $frequency => $enabled) {
                if ($enabled) {
                    Log::info("Creating frequency {$frequency} for user: {$user->id}");
                    $user->frequencies()->create(['frequency' => $frequency]);
                }
            }

            session()->flash('message', 'Preferencias de notificaciones guardadas correctamente.');
            Log::info('Preferences saved successfully');

            // Re-cargar las preferencias desde la base de datos para verificar que se guardaron correctamente
            $this->mount();
        } catch (\Exception $e) {
            Log::error('Error saving preferences: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            session()->flash('error', 'Error al guardar las preferencias: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.settings.notifications')
            ->layout('layouts.settings.preferences');
    }
}
