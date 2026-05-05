<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Cria uma notificação para um usuário específico.
     */
    public static function notify(int $userId, string $title, string $message, ?string $link = null): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
        ]);
    }

    /**
     * Notifica todos os admins do sistema.
     */
    public static function notifyAdmins(string $title, string $message, ?string $link = null): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            self::notify($admin->id, $title, $message, $link);
        }
    }

    /**
     * Notifica o funcionário (via user_id do employee).
     */
    public static function notifyEmployee(int $employeeUserId, string $title, string $message, ?string $link = null): void
    {
        self::notify($employeeUserId, $title, $message, $link);
    }
}
