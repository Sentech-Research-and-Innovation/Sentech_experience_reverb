use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index', [
            'messages' => Message::with('user')->latest()->take(20)->get()
        ]);
    }

    public function sendMessage(Request $request)
  {
      // Validate input data
      $request->validate([
          'message' => 'required|string|max:255',
          'receiver_id' => 'required|exists:users,id',  // Ensure the receiver is a valid user
      ]);
  
      // Create the message, associating the sender and receiver
      $message = Message::create([
          'user_id' => auth()->id(),  // The sender's user ID
          'receiver_id' => $request->receiver_id,  // The receiver's user ID
          'message' => $request->message,  // The message content
      ]);
  
      // Broadcast the event, sending the message to the dynamic channel
      broadcast(new MessageSent($message))->toOthers();
  
      return ['status' => 'Message Sent!'];
  }

}
