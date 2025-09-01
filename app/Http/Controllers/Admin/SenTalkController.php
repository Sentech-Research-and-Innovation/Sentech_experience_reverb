<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SenTalk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([
            'title' => 'required|string|max:255',
            'creator' => 'required|string|max:255',
            'pdf' => 'required|file|mimes:pdf|max:10240' // Max 10MB
        ]);

        // Store the PDF file
        $file = $request->file('pdf');
        $path = $file->store('sentalk_pdfs', 'public');

        // Create database record
        $sentalk = SenTalk::create([
            'title' => $request->title,
            'creator' => $request->creator,
            'pdf_path' => $path,
            'number_views' => 0,
            'number_likes' => 0,
            'number_downloads' => 0
        ]);

        return response()->json([
            'message' => 'SenTalk edition uploaded successfully',
            'data' => $sentalk
        ], 201);
    }

    public function show($id)
    {
        $sentalk = SenTalk::findOrFail($id);
        
        // Increment view count
        $sentalk->increment('number_views');
        
        return response()->json($sentalk);
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
