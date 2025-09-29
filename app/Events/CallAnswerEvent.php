<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CallAnswerEvent implements ShouldBroadcastNow
{
    use InteractsWithSockets;

    public $payload; // ['from'=>id, 'to'=>id, 'answer'=>sdp]

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('calls.' . $this->payload['to']);
    }

    public function broadcastWith()
    {
        return $this->payload;
    }
}

