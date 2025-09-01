namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SenTalk;
use Illuminate\Support\Facades\Storage;

class SenTalkController extends Controller
{
    public function index()
    {
        return SenTalk::orderBy('created_at', 'desc')->paginate(10);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:10000',
            'title' => 'required|string',
            'creator' => 'required|string',
        ]);

        $path = $request->file('pdf')->store('sentalk_pdfs', 'public');

        $sentalk = SenTalk::create([
            'title' => $request->title,
            'creator' => $request->creator,
            'pdf_path' => $path,
        ]);

        return response()->json($sentalk, 201);
    }

    public function show($id)
    {
        $sentalk = SenTalk::findOrFail($id);
        $sentalk->increment('number_views');
        return $sentalk;
    }

    public function download($id)
    {
        $sentalk = SenTalk::findOrFail($id);
        $sentalk->increment('number_downloads');

        return Storage::disk('public')->download($sentalk->pdf_path);
    }

    public function stats()
    {
        return [
            'total' => SenTalk::count(),
            'views' => SenTalk::sum('number_views'),
            'downloads' => SenTalk::sum('number_downloads'),
            'likes' => SenTalk::sum('number_likes'),
        ];
    }
}

