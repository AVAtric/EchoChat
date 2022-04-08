<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('main.chat', \App\Broadcasting\MainChatChannel::class);

Broadcast::channel('notifications.{userId}', \App\Broadcasting\NotificationsChannel::class);

Broadcast::channel('chat.{chat}', \App\Broadcasting\ChatChannel::class);
