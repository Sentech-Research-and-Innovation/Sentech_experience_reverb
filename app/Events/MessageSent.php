namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $message;
    public $sender_id;
    public $receiver_id;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->sender_id = $message->user_id;     // Sender's ID
        $this->receiver_id = $message->receiver_id; // Receiver's ID
    }

    // Broadcasting on a dynamic channel for sender and receiver
    public function broadcastOn()
    {
        // Channel format: 'chat.{sender_id}-{receiver_id}' or 'chat.{receiver_id}-{sender_id}'
        // Order doesn't matter, so we sort the user IDs to ensure the same channel every time
        $channelId = implode('-', sorted([$this->sender_id, $this->receiver_id]));

        return new Channel('chat.' . $channelId);  // Channel name will be chat.1-2 or chat.2-1
    }
}
