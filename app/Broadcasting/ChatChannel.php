<?php

namespace App\Broadcasting;

use App\Models\Chat;
use App\Models\User;

class ChatChannel
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
     * @param Chat $chat
     * @return User | void
     */
    public function join(User $user,Chat $chat)
    {
        if($chat->isAllowed($user))
            return $user;
    }
}
