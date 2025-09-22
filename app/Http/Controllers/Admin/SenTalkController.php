<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\SenTalk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
class SenTalkController extends Controller
{
    public function index(Request $request)
    {
        Log::info('SenTalkController@index accessed', [
            'ip'       => $request->ip(),
            'search'   => $request->search ?? null,
            'timestamp'=> now()
        ]);
    
        $query = SenTalk::query();
    
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            Log::info('Applying search filter', ['search_term' => $request->search]);
    
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('creator', 'like', '%' . $request->search . '%');
            });
        }
    
        // Get all editions ordered by newest
        $editions = $query->orderBy('created_at', 'desc')->get();
    
        $user = auth()->user();
    
        // Add liked flag for each edition
        if ($user) {
            foreach ($editions as $edition) {
                $edition->liked = \DB::table('sentalk_likes')
                    ->where('edition_id', $edition->id)
                    ->where('user_id', $user->id)
                    ->exists();
            }
        } else {
            foreach ($editions as $edition) {
                $edition->liked = false;
            }
        }
    
        Log::info('SenTalk fetched results', [
            'total' => $editions->count(),
        ]);
        return response()->json([
            'latest'   => $editions->first(),
            'editions' => $editions->values(),
        ]);
    }



    public function upload(Request $request)
    {
        Log::info('SenTalk upload request received.', [
            'hasFile' => $request->hasFile('pdf'),
            'all' => $request->all(),
        ]);

        if ($request->hasFile('pdf')) {
            Log::info('Uploaded file details', [
                'extension' => $request->file('pdf')->getClientOriginalExtension(),
                'mime' => $request->file('pdf')->getMimeType(),
            ]);
        }

    
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:51200',
        ]);

    
        try {
            $file = $request->file('pdf');
    
            Log::info('Uploading file...', [
                'originalName' => $file->getClientOriginalName(),
                'mimeType' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
    
            // Store PDF in storage/app/public/sentalk_pdfs
            $path = $file->store('sentalk_pdfs', 'public');

            // Generate thumbnail (first page)
            $pdfPath = storage_path('app/public/' . $path);
            $thumbnailPath = 'sentalk_thumbs/' . pathinfo($file->hashName(), PATHINFO_FILENAME) . '.jpg';
    
            // Save first page as JPG
            $pdf = new Pdf($pdfPath);
            $pdf->setPage(1)->saveImage(storage_path('app/public/' . $thumbnailPath));

    
            // Save record in DB
            $now = now(); // Gets current date and time as a Carbon instance

            $sentalk = new SenTalk();
            $sentalk->pdf_path = $path;
             $sentalk->thumbnail_path = $thumbnailPath;
            $sentalk->title = $file->getClientOriginalName();
            $sentalk->creator = 'MachabaL';
            $sentalk->created_date = $now->format('d M Y'); // Format: 06 Sept 2025
            $sentalk->created_time = $now->format('h:i A'); // Format: 02:35 PM
            $sentalk->save();
    
            Log::info('File stored and DB record created.', [
                'id' => $sentalk->id,
                'path' => $path,
            ]);
    
            return response()->json([
                'success' => true,
                'latest' => $sentalk,
            ]);
        } catch (\Exception $e) {
            Log::error('Upload failed.', [
                'error' => $e->getMessage(),
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Upload failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $sentalk = SenTalk::findOrFail($id);
    
            // Delete PDF from storage
            if ($sentalk->pdf_path && \Storage::disk('public')->exists($sentalk->pdf_path)) {
                \Storage::disk('public')->delete($sentalk->pdf_path);
            }

            // Delete Thumbnail
            if ($sentalk->thumbnail_path && \Storage::disk('public')->exists($sentalk->thumbnail_path)) {
                \Storage::disk('public')->delete($sentalk->thumbnail_path);
            }
    
            // Delete record from DB
            $sentalk->delete();
    
            // Get the next latest edition
            $latest = SenTalk::orderBy('created_at', 'desc')->first();
            $editions = SenTalk::orderBy('created_at', 'desc')->skip(1)->get();
    
            return response()->json([
                'success' => true,
                'message' => 'Edition deleted successfully',
                'latest' => $latest,
                'editions' => $editions,
            ]);
        } catch (\Exception $e) {
            \Log::error('Delete failed.', [
                'error' => $e->getMessage(),
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Delete failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        Log::info('SenTalk update request received.', [
            'id' => $id,
            'payload' => $request->all(),
        ]);
    
        $request->validate([
            'title' => 'required|string|max:255',
            'creator' => 'required|string|max:255',
            'created_date' => 'required|string',
            'created_time' => 'required|string',
        ]);
    
        try {
            $sentalk = SenTalk::findOrFail($id);
    
            $sentalk->title = $request->title;
            $sentalk->creator = $request->creator;
            $sentalk->created_date = $request->created_date;
            $sentalk->created_time = $request->created_time;
            $sentalk->save();
    
            Log::info('SenTalk record updated successfully.', [
                'id' => $sentalk->id,
                'title' => $sentalk->title,
            ]);
    
            return response()->json([
                'success' => true,
                'latest' => $sentalk,
            ]);
        } catch (\Exception $e) {
            Log::error('Update failed.', [
                'error' => $e->getMessage(),
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Update failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function display()
    {
        $filePath = storage_path('app/public/sentalk_pdfs/current.pdf');
    
        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'No PDF uploaded yet.',
            ], 404);
        }
    
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="SenTalk.pdf"'
        ]);
    }



    public function download(Request $request, $id)
    {
        $sentalk = SenTalk::findOrFail($id);
    
        // Log download attempt
        \Log::info('Download requested', [
            'id'       => $sentalk->id,
            'title'    => $sentalk->title,
            'ip'       => $request->ip(),
            'user_id'  => $request->user()->id ?? null,
            'time'     => now()
        ]);
    
        // Increment download count
        $sentalk->increment('number_downloads');
    
        $filePath = storage_path('app/public/' . $sentalk->pdf_path);
    
        if (!file_exists($filePath)) {
            \Log::error('Download failed - file not found', [
                'id'       => $sentalk->id,
                'filePath' => $filePath
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'File not found on server'
            ], 404);
        }
    
        \Log::info('Download successful', [
            'id'       => $sentalk->id,
            'filePath' => $filePath
        ]);
    
        return response()->download($filePath, $sentalk->title . '.pdf');
    }

    public function feedback(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string|max:1000',
            'edition_id' => 'required|integer', // make sure frontend passes the edition ID
        ]);
    
        // Get edition title
        $edition = Sentalk::find($data['edition_id']);
        $editionTitle = str_replace('.pdf', '', $edition->title); 
        $editionTitle = $edition ? $edition->title : 'Unknown Edition';
    
        // Build email body
        $body = <<<EOT
    Good day,
    
    You just received a feedback on the TX platform based on the SenTalk edition: **{$editionTitle}**.
    
    From: {$data['name']}  
    Email: {$data['email']}
    
    Message:
    {$data['message']}
    
    Kind regards,  
    TX Platform
    EOT;
    
        // Log the feedback
        Log::info('Feedback received', $data);
    
        // Send the email
        Mail::raw($body, function ($msg) use ($data) {
            $msg->to('u20507934@tuks.co.za')
                ->subject("New Feedback on SenTalk Edition");
        });
    
        Log::info("Feedback email sent successfully to u20507934@tuks.co.za for edition {$editionTitle}");
    
        return response()->json(['success' => true]);
    }



    public function stats()
    {
        $stats = SenTalk::selectRaw(
            'COUNT(*) as total_editions, 
            SUM(number_views) as total_views, 
            SUM(number_downloads) as total_downloads, 
            SUM(number_likes) as total_likes'
        )->first();
        
        return response()->json($stats);
    }


    public function like($id)
    {
        Log::info('Like method called', ['edition_id' => $id]);
    
        $edition = SenTalk::findOrFail($id);
        Log::info('Edition found', ['edition' => $edition]);
    
        $user = auth()->user(); // get the logged-in user
    
        if (!$user) {
            Log::warning('Unauthenticated user tried to like edition', ['edition_id' => $id]);
    
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
    
        Log::info('Authenticated user', ['user_id' => $user->id]);
    
        // Check if user already liked
        $existing = \DB::table('sentalk_likes')
            ->where('edition_id', $edition->id)
            ->where('user_id', $user->id)
            ->first();
    
        if ($existing) {
            Log::info('User already liked edition, proceeding to unlike', [
                'edition_id' => $edition->id,
                'user_id' => $user->id
            ]);
    
            // Unlike (remove record)
            \DB::table('sentalk_likes')
                ->where('edition_id', $edition->id)
                ->where('user_id', $user->id)
                ->delete();
    
            $liked = false;
        } else {
            Log::info('User has not liked edition yet, proceeding to like', [
                'edition_id' => $edition->id,
                'user_id' => $user->id
            ]);
    
            // Like (add record with correct user fields)
            \DB::table('sentalk_likes')->insert([
                'edition_id' => $edition->id,
                'user_id'    => $user->id,
                'name'       => $user->first_name,
                'surname'    => $user->last_name,
                'email'      => $user->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            $liked = true;
        }
    
        // Count total likes
        $totalLikes = \DB::table('sentalk_likes')
            ->where('edition_id', $edition->id)
            ->count();
    
        Log::info('Total likes counted', ['edition_id' => $edition->id, 'total_likes' => $totalLikes]);
    
        // Sync edition like count
        $edition->number_likes = $totalLikes;
        $edition->save();
    
        Log::info('Edition like count updated', [
            'edition_id' => $edition->id,
            'new_like_count' => $edition->number_likes,
            'user_id' => $user->id,
            'liked' => $liked
        ]);
    
        return response()->json([
            'success' => true,
            'liked' => $liked,
            'total_likes' => $totalLikes,
        ]);
    }

    public function view($id)
    {
        Log::info("SenTalk view requested for edition ID: {$id}");
    
        $edition = SenTalk::findOrFail($id);
        $user = auth()->user();
    
        if (!$user) {
            Log::warning("Unauthenticated access attempt to SenTalk edition ID: {$id}");
    
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
    
        Log::info("User authenticated", [
            'user_id' => $user->id,
            'email'   => $user->email
        ]);
    
        // Check if user already viewed
        $existing = DB::table('sentalk_views')
            ->where('edition_id', $edition->id)
            ->where('user_id', $user->id)
            ->first();
    
        if (!$existing) {
            Log::info("New view recorded for edition ID: {$edition->id}", [
                'user_id'   => $user->id,
                'first_name'=> $user->first_name,
                'last_name' => $user->last_name,
                'email'     => $user->email
            ]);
    
            DB::table('sentalk_views')->insert([
                'edition_id' => $edition->id,
                'user_id'    => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            // Increment edition view count
            $edition->increment('number_views');
    
            Log::info("Incremented number_views for edition ID: {$edition->id}");
        } else {
            Log::info("View already exists. No update required.", [
                'edition_id' => $edition->id,
                'user_id'    => $user->id
            ]);
        }
    
        return response()->json([
            'success' => true,
            'views'   => $edition->number_views,
        ]);
    }
    
    public function show($id, Request $request)
    {
        $edition = SenTalk::findOrFail($id);
        $user = auth()->user();
    
        // Add liked flag
        if ($user) {
            $edition->liked = \DB::table('sentalk_likes')
                ->where('edition_id', $edition->id)
                ->where('user_id', $user->id)
                ->exists();
        } else {
            $edition->liked = false;
        }
    
        // Add like count
        $edition->number_likes = \DB::table('sentalk_likes')
            ->where('edition_id', $edition->id)
            ->count();
    
        return response()->json([
            'edition' => $edition  
        ]);
    }
 



}
