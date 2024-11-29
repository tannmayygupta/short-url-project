<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UrlController extends Controller
{
    /**
     * Show the form to input the URL.
     */
    public function index()
    {
        $urls = Url::where('user_id', Auth::id()) // Fetch only URLs for the logged-in user
                   ->orderBy('created_at', 'desc')
                   ->get();
    
        return view('Url.index', compact('urls'));
    }
    

    /**
     * Store the original URL and generate a short URL.
     */
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
        ]);
    
        $shortUrl = Str::random(6); // Generate a random 6-character string
    
        Url::create([
            'original_url' => $request->original_url,
            'short_url' => $shortUrl,
            'copy_count' => 0,
            'user_id' => Auth::id(), // Use the authenticated user's ID
        ]);
    
        return redirect()->route('Url.index')->with('success', 'Short URL created successfully!');
    }
    


    public function redirect($shortUrl)
    {
        // Find the URL record by the short URL and ensure it belongs to the authenticated user
        $url = Url::where('short_url', $shortUrl)
                  ->where('user_id', Auth::id()) // Ensure it belongs to the logged-in user
                  ->first();
    
        if ($url) {
            // Redirect to the original URL
            return redirect($url->original_url);
        }
    
        // Return a 404 error if the short URL is not found or does not belong to the user
        return abort(404, 'Short URL not found or access denied.');
    }
    

    /**
     * Increment the copy count for a short URL.
     */
    public function incrementCopyCount($id)
    {
        $url = Url::where('id', $id)
                  ->where('user_id', Auth::id()) // Ensure the URL belongs to the logged-in user
                  ->firstOrFail();
    
        $url->increment('copy_count');
    
        return response()->json([
            'success' => true,
            'copy_count' => $url->copy_count,
        ]);
    }
    

    public function copyShortUrl($id)
    {
        $url = Url::where('id', $id)
                  ->where('user_id', Auth::id()) // Ensure the URL belongs to the logged-in user
                  ->firstOrFail();
    
        $url->increment('copy_count'); // Increment the copy count
    
        return response()->json([
            'message' => 'Copy count updated successfully!',
            'copy_count' => $url->copy_count,
        ]);
    }

}