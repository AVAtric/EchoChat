<?php

namespace App\Http\Controllers;

use App\Events\NotificationMessage;
use App\Events\PersonalChatMessage;
use App\Models\Chat;
use App\Models\MainChatMessage;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getMessage($message){
        switch (explode(' ', $message)[0]){
            case '/quote':
                return \Illuminate\Foundation\Inspiring::quote();
            case '/now':
                return Carbon::now()->isoFormat('LLLL');
            default:
                return $message;
        }
    }

    public function sendMessageToMainChat(Request $request)
    {
        $request->validate([
            'message' => 'filled | string'
        ]);

        $msg = MainChatMessage::create([
            'user_id' => auth()->user()->id,
            'body' => $this->getMessage($request->input('message'))
        ]);

        event(new \App\Events\MainChatMessage(auth()->user(), $msg));

        return $msg->load('user');
    }

    public function sendMessageToPersonalChat(Request $request, \App\Models\Chat $chat)
    {
        if ($chat->isAllowed(auth()->user())) {
            $request->validate([
                'message' => 'filled | string'
            ]);

            $msg = $chat->messages()->create([
                'user_id' => auth()->user()->id,
                'body' => $this->getMessage($request->input('message'))
            ]);

            event(new PersonalChatMessage(auth()->user(), $chat, $msg));

            return $msg->load('user');
        }
    }

    public function getAllMainChatMessages()
    {
        return MainChatMessage::with('user')->get()->sortByDesc('created_at');
    }

    public function getAllChatMessages(\App\Models\Chat $chat)
    {
        if(!$chat->isAllowed(auth()->user()))
            return abort(403);

        return Message::with('user')->where('chat_id', $chat->id)->get();
    }

    public function getChatId(\App\Models\User $user1, \App\Models\User $user2)
    {
        if(auth()->user()->id != $user1->id && auth()->user()->id != $user2->id)
            return abort(403);

        if(auth()->user()->id == $user1->id && auth()->user()->id == $user2->id)
            return abort(422);

        $chat = Chat::chatExists($user1, $user2)->first();

        if (!$chat)
            return Chat::firstOrCreate([
                "user1_id" => $user1->id,
                "user2_id" => $user2->id
            ]);

        return $chat;
    }

    public function sendNotification(\App\Models\User $user)
    {
        event(new NotificationMessage($user->id));
    }

    public function getQuote()
    {
        return response(\Illuminate\Foundation\Inspiring::quote())
            ->header('Content-Type', 'text/plain');
    }
}
