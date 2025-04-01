<?php

namespace App\Services;

use App\Models\User;
use App\Models\NotificationType;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function notify(string $type, $notifiable, array $data = [])
    {
        $notificationType = NotificationType::where('key', $type)->firstOrFail();

        // Obtener preferencias del usuario
        $preference = $notifiable->notificationPreferences()
            ->where('notification_type_id', $notificationType->id)
            ->first();

        if (!$preference || !$preference->enabled) {
            return;
        }

        // Determinar si enviar ahora o programar según la frecuencia
        if ($preference->frequency === 'immediate') {
            $this->sendNotification($notifiable, $type, $data);
        } else {
            $this->queueNotification($notifiable, $type, $data, $preference->frequency);
        }
    }

    protected function sendNotification($notifiable, $type, $data)
    {
        // Aquí implementarías la lógica para enviar la notificación
        // según el tipo de notificación que se está enviando
        Notification::send($notifiable, new \App\Notifications\GeneralNotification(
            $type,
            $data
        ));
    }

    protected function queueNotification($notifiable, $type, $data, $frequency)
    {
        // Implementar lógica para cola de notificaciones programadas
        // Esto podría usar un job para procesamiento posterior
    }
}
