<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Crear una nueva notificación para un usuario
     *
     * @param User $user Usuario destinatario
     * @param string $type Tipo de notificación
     * @param string $title Título de la notificación
     * @param string $message Mensaje de la notificación
     * @param array $data Datos adicionales (opcional)
     * @return Notification
     */
    public function createForUser(User $user, string $type, string $title, string $message, array $data = [])
    {
        return Notification::create([
            'type' => $type,
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Notificar a todos los usuarios
     *
     * @param string $type Tipo de notificación
     * @param string $title Título de la notificación
     * @param string $message Mensaje de la notificación
     * @param array $data Datos adicionales (opcional)
     * @return array Arreglo de notificaciones creadas
     */
    public function notifyAll(string $type, string $title, string $message, array $data = [])
    {
        $users = User::all();
        $notifications = [];

        foreach ($users as $user) {
            try {
                $notifications[] = $this->createForUser($user, $type, $title, $message, $data);
            } catch (\Exception $e) {
                Log::error("Error creando notificación para el usuario {$user->id}: " . $e->getMessage());
            }
        }

        return $notifications;
    }

    /**
     * Notificar a usuarios específicos
     *
     * @param array $userIds IDs de usuarios
     * @param string $type Tipo de notificación
     * @param string $title Título de la notificación
     * @param string $message Mensaje de la notificación
     * @param array $data Datos adicionales (opcional)
     * @return array Arreglo de notificaciones creadas
     */
    public function notifyUsers(array $userIds, string $type, string $title, string $message, array $data = [])
    {
        $users = User::whereIn('id', $userIds)->get();
        $notifications = [];

        foreach ($users as $user) {
            try {
                $notifications[] = $this->createForUser($user, $type, $title, $message, $data);
            } catch (\Exception $e) {
                Log::error("Error creando notificación para el usuario {$user->id}: " . $e->getMessage());
            }
        }

        return $notifications;
    }
}
