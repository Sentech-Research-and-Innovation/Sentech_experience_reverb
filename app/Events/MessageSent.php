<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        $senderId = $this->message->user_id;
        $receiverId = $this->message->receiver_id;

        $channelName = 'chat.' . collect([$senderId, $receiverId])->sort()->join('-');

        return new Channel($channelName);
    }

    public function broadcastWith()
    {
        // Customize what gets sent to the frontend
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->user_id,
            'receiver_id' => $this->message->receiver_id,
            'content' => $this->message->content,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
