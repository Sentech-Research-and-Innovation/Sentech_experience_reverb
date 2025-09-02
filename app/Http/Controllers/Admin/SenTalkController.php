<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SenTalk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

class SenTalkController extends Controller
{
    public function index(Request $request)
    {
        $query = SenTalk::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('creator', 'like', '%' . $request->search . '%');
        }
        
        // Paginate results
        $editions = $query->orderBy('created_at', 'desc')->paginate(1);
        
        return response()->json($editions);
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
    
            Log::info('File stored successfully.', [
                'path' => $path,
            ]);
    
            return response()->json([
                'success' => true,
                'pdf_path' => $path,
                'title' => $file->getClientOriginalName(),
            ]);
        } catch (\Exception $e) {
            Log::error('Upload failed.', [
                'error' => $e->getMessage(),
                // 'trace' => $e->getTraceAsString(),
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
