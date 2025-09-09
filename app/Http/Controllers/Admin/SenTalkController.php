<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\SenTalk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;
use Illuminate\Support\Facades\Log;

class SenTalkController extends Controller
{
    public function index(Request $request)
    {
        Log::info('SenTalkController@index accessed', [
            'ip' => $request->ip(),
            'search' => $request->search ?? null,
            'timestamp' => now()
        ]);
    
        $query = SenTalk::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            Log::info('Applying search filter', ['search_term' => $request->search]);
    
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('creator', 'like', '%' . $request->search . '%');
        }
        
        // Get all editions ordered by newest
        $editions = $query->orderBy('created_at', 'desc')->get();
    
        Log::info('SenTalk fetched results', [
            'total' => $editions->count(),
        ]);
        
        return response()->json([
            'latest' => $editions->first(),
            'editions' => $editions->values(), // all except the latest
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
}
