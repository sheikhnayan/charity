<?php

namespace App\Http\Controllers;

use App\Models\SessionRecording;
use App\Models\Website;
use Illuminate\Http\Request;

class HotjarViewController extends Controller
{
    /**
     * Show recordings list
     */
    public function recordings()
    {
        $websites = Website::orderBy('title')->get();
        
        return view('hotjar.recordings.index', compact('websites'));
    }

    /**
     * Show recording replay
     */
    public function replay($recordingId)
    {
        $recording = SessionRecording::with('website')->findOrFail($recordingId);
        
        return view('hotjar.recordings.replay', compact('recording'));
    }

    /**
     * Show heatmaps
     */
    public function heatmaps()
    {
        $websites = Website::orderBy('title')->get();
        
        return view('hotjar.heatmaps.index', compact('websites'));
    }
}
