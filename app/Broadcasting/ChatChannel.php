<?php

namespace App\Broadcasting;

use App\Models\Chat;
use App\Models\User;

class ChatChannel
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
    public function join(User $user, string $chatId): array|bool
    {
        return (int) $user->id === (int) Chat::find($chatId)->user_1_id
        || (int) $user->id === (int) Chat::find($chatId)->user_2_id;
    }
}
