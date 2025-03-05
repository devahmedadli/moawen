<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Models\Notification;
class NotificationChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, string $notificationId): array|bool
    {
        return (int) $user->id === (int) Notification::find($notificationId)->user_id;
    }
}
