<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    /**
     * Only allow marking as read if user belongs to the target division.
     */
    public function markAsRead(User $user, Message $message): bool
    {
        return $user->division_id === $message->target_division_id;
    }
}
