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
            'pdf' => 'required|mimes:pdf|max:10000',
        ]);
    
        $path = $request->file('pdf')->store('sentalk_pdfs', 'public');
    
        return response()->json([
            'pdf_path' => $path,
            'title' => $request->file('pdf')->getClientOriginalName(),
        ]);
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
