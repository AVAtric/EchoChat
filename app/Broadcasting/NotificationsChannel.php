<?php

namespace App\Broadcasting;

use App\Models\User;

class NotificationsChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param User $user
     * @param $userId
     * @return User
     */
    public function join(User $user)
    {
        return $user;
    }
}
