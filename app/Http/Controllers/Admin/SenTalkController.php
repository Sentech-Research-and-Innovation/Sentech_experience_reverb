<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\SenTalk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

class SenTalkController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = SenTalk::query();
        
    //     // Search functionality
    //     if ($request->has('search') && !empty($request->search)) {
    //         $query->where('title', 'like', '%' . $request->search . '%')
    //               ->orWhere('creator', 'like', '%' . $request->search . '%');
    //     }
        
    //     // Paginate results
    //     $editions = $query->orderBy('created_at', 'desc')->paginate(1);
        
    //     return response()->json($editions);
    // }
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
            'editions' => $editions->skip(1)->values(), // all except the latest
        ]);
    }


    public function upload(Request $request)
    {
        Log::info('SenTalk upload request received.', [
            'hasFile' => $request->hasFile('pdf'),
            'all' => $request->all(),
        ]);
    
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:10000',
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
    
            // Save record in DB
            $sentalk = new SenTalk();
            $sentalk->pdf_path = $path;
            $sentalk->title = $file->getClientOriginalName();
            $sentalk->creator = auth()->user()->name ?? 'Unknown'; // or set manually
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



    public function download($id)
    {
        $sentalk = SenTalk::findOrFail($id);
        
        // Increment download count
        $sentalk->increment('number_downloads');
        
        $filePath = storage_path('app/public/' . $sentalk->pdf_path);
        
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
