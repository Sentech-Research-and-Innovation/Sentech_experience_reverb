namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Fetch messages between the logged-in user and the receiver
    public function getMessages($receiverId)
    {
        // Get the logged-in user ID
        $userId = auth()->id();

        // Fetch messages between the logged-in user and the receiver
        $messages = Message::where(function ($query) use ($userId, $receiverId) {
                $query->where('user_id', $userId)
                      ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($query) use ($userId, $receiverId) {
                $query->where('user_id', $receiverId)
                      ->where('receiver_id', $userId);
            })
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

    // Send a new message and broadcast the event
    public function sendMessage(Request $request)
    {
        // Validate input data
        $request->validate([
            'message' => 'required|string|max:255',
            'receiver_id' => 'required|exists:users,id',  // Ensure receiver is valid
        ]);

        // Get the sender ID (logged-in user)
        $userId = auth()->id();

        // Create the new message
        $message = Message::create([
            'user_id' => $userId,  // The sender's user ID
            'receiver_id' => $request->receiver_id,  // The receiver's user ID
            'message' => $request->message,  // The message content
        ]);

        // Broadcast the event (sending message to others on the chat channel)
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'Message Sent!']);
    }
}
