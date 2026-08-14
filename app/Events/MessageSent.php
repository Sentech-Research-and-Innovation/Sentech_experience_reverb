<?php

namespace App\Events;

use App\Models\Message;
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

        return new PrivateChannel($channelName);
    }

    public function broadcastWith()
    {
        // Nested under "message" to match the shape ChatBox.vue expects from its listener
        return [
            'message' => [
                'id' => $this->message->id,
                'user_id' => $this->message->user_id,
                'sender_id' => $this->message->user_id,
                'receiver_id' => $this->message->receiver_id,
                'message' => $this->message->message,
                'created_at' => $this->message->created_at->toDateTimeString(),
            ],
        ];
    }
}
