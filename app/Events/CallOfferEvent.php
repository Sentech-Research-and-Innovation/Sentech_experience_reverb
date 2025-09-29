<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CallOfferEvent implements ShouldBroadcastNow
{
    use InteractsWithSockets;

    public $payload; // array: ['from'=>id, 'to'=>id, 'offer'=>sdp]

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
