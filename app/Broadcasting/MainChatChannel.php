<?php

namespace App\Broadcasting;

use App\Models\User;

class MainChatChannel
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
     * @param  User  $user
     * @return User
     */
    public function join(User $user)
    {
        return $user;
    }
}
