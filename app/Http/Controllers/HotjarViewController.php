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
        $websites = Website::orderBy('name')->get();
        
        return view('hotjar.recordings.index', compact('websites'));
    }

    /**
     * Show recording replay
     */
    public function replay($recordingId)
    {
        $recording = SessionRecording::with('website')->findOrFail($recordingId);
        
        // Use hybrid replay: renders actual page in iframe + overlays interactions
        return view('hotjar.recordings.replay-hybrid', compact('recording'));
    }

    /**
     * Show heatmaps
     */
    public function heatmaps()
    {
        $websites = Website::orderBy('name')->get();
        
        return view('hotjar.heatmaps.index', compact('websites'));
    }

    /**
     * Get session recordings API
     */
    public function getRecordings(Request $request)
    {
        $query = SessionRecording::with('website');

        // Apply filters
        if ($request->website_id) {
            $query->where('website_id', $request->website_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->device_type) {
            $query->where('device_type', $request->device_type);
        }
        if ($request->min_duration) {
            $query->where('duration_ms', '>=', $request->min_duration);
        }
        if ($request->has_rage_clicks) {
            $query->where('has_rage_clicks', true);
        }
        if ($request->has_errors) {
            $query->where('has_errors', true);
        }
        if ($request->starred) {
            $query->where('is_starred', true);
        }

        $recordings = $query->orderBy('started_at', 'desc')
            ->paginate($request->per_page ?? 20);

        // Calculate stats
        $totalQuery = SessionRecording::query();
        if ($request->website_id) {
            $totalQuery->where('website_id', $request->website_id);
        }

        $stats = [
            'total' => $totalQuery->count(),
            'rage_clicks_count' => (clone $totalQuery)->where('has_rage_clicks', true)->count(),
            'errors_count' => (clone $totalQuery)->where('has_errors', true)->count(),
            'avg_duration' => $totalQuery->avg('duration_ms'),
        ];

        return response()->json([
            'data' => $recordings->items(),
            'current_page' => $recordings->currentPage(),
            'last_page' => $recordings->lastPage(),
            'total' => $recordings->total(),
            'per_page' => $recordings->perPage(),
            'meta' => $stats,
        ]);
    }

    /**
     * Get heatmap popular pages
     */
    public function getPopularPages(Request $request)
    {
        $websiteId = $request->website_id;
        if (!$websiteId) {
            return response()->json(['pages' => []]);
        }

        // Get pages with heatmap data
        $pages = \DB::table('heatmap_data')
            ->select('page_path', 'page_url', \DB::raw('COUNT(DISTINCT session_id) as visitors'))
            ->where('website_id', $websiteId)
            ->groupBy('page_path', 'page_url')
            ->orderBy('visitors', 'desc')
            ->limit(20)
            ->get();

        return response()->json(['pages' => $pages]);
    }

    /**
     * Get click heatmap data
     */
    public function getClickHeatmap(Request $request)
    {
        $websiteId = $request->website_id;
        $pagePath = $request->page_path;
        $days = $request->days ?? 30;

        $data = \DB::table('heatmap_data')
            ->select('x', 'y', 'viewport_width', 'viewport_height', \DB::raw('COUNT(*) as click_count'))
            ->where('website_id', $websiteId)
            ->where('page_path', $pagePath)
            ->where('event_type', 'click')
            ->where('created_at', '>=', now()->subDays($days))
            ->when($request->device_type, function($q) use ($request) {
                return $q->where('device_type', $request->device_type);
            })
            ->groupBy('x', 'y', 'viewport_width', 'viewport_height')
            ->get();

        return response()->json(['data' => $data]);
    }

    /**
     * Get move heatmap data
     */
    public function getMoveHeatmap(Request $request)
    {
        $websiteId = $request->website_id;
        $pagePath = $request->page_path;
        $days = $request->days ?? 30;

        $data = \DB::table('heatmap_data')
            ->select('x', 'y', 'viewport_width', 'viewport_height', \DB::raw('COUNT(*) as move_count'))
            ->where('website_id', $websiteId)
            ->where('page_path', $pagePath)
            ->where('event_type', 'move')
            ->where('created_at', '>=', now()->subDays($days))
            ->when($request->device_type, function($q) use ($request) {
                return $q->where('device_type', $request->device_type);
            })
            ->groupBy('x', 'y', 'viewport_width', 'viewport_height')
            ->get();

        return response()->json(['data' => $data]);
    }

    /**
     * Get scroll depth data
     */
    public function getScrollDepth(Request $request)
    {
        $websiteId = $request->website_id;
        $pagePath = $request->page_path;
        $days = $request->days ?? 30;

        // Calculate scroll percentages
        $scrollData = \DB::table('heatmap_data')
            ->where('website_id', $websiteId)
            ->where('page_path', $pagePath)
            ->where('event_type', 'scroll')
            ->where('created_at', '>=', now()->subDays($days))
            ->when($request->device_type, function($q) use ($request) {
                return $q->where('device_type', $request->device_type);
            })
            ->get();

        $totalUsers = $scrollData->unique('session_id')->count();
        $avgScroll = $scrollData->avg('scroll_depth') ?? 0;

        // Calculate percentage at each depth
        $percentages = [];
        foreach ([0, 25, 50, 75, 100] as $depth) {
            $count = $scrollData->where('scroll_depth', '>=', $depth)->unique('session_id')->count();
            $percentages[$depth] = $totalUsers > 0 ? round(($count / $totalUsers) * 100) : 0;
        }

        return response()->json([
            'data' => [
                'total_users' => $totalUsers,
                'average_scroll' => $avgScroll,
                'scroll_percentages' => $percentages,
            ]
        ]);
    }

    /**
     * Get element click statistics
     */
    public function getElementStats(Request $request)
    {
        $websiteId = $request->website_id;
        $pagePath = $request->page_path;

        $elements = \DB::table('heatmap_data')
            ->select('element_selector', 'element_text', \DB::raw('COUNT(*) as clicks'), \DB::raw('COUNT(DISTINCT session_id) as unique_users'))
            ->where('website_id', $websiteId)
            ->where('page_path', $pagePath)
            ->where('event_type', 'click')
            ->whereNotNull('element_selector')
            ->groupBy('element_selector', 'element_text')
            ->orderBy('clicks', 'desc')
            ->limit(20)
            ->get();

        return response()->json(['elements' => $elements]);
    }

    /**
     * Get screenshot URL
     */
    public function getScreenshot(Request $request)
    {
        $websiteId = $request->website_id;
        $pagePath = $request->page_path;

        // Get the latest screenshot for this page
        $screenshot = \DB::table('page_screenshots')
            ->where('website_id', $websiteId)
            ->where('page_path', $pagePath)
            ->orderBy('created_at', 'desc')
            ->first();

        // If no screenshot found, return 404 so JavaScript knows to capture one
        if (!$screenshot || !$screenshot->screenshot_url) {
            return response()->json([
                'message' => 'No screenshot found'
            ], 404);
        }

        return response()->json([
            'screenshot_url' => $screenshot->screenshot_url
        ]);
    }

    /**
     * Capture and save page screenshot
     */
    public function captureScreenshot(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|integer',
                'page_path' => 'required|string',
                'screenshot_data' => 'required|string',
                'viewport_width' => 'nullable|integer',
                'viewport_height' => 'nullable|integer'
            ]);

            // Extract base64 image data
            $screenshotData = $request->screenshot_data;
            
            if (preg_match('/^data:image\/(\w+);base64,/', $screenshotData, $type)) {
                $screenshotData = substr($screenshotData, strpos($screenshotData, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif

                $screenshotData = base64_decode($screenshotData);

                if ($screenshotData === false) {
                    throw new \Exception('Base64 decode failed');
                }
            } else {
                throw new \Exception('Invalid image data');
            }

            // Generate filename
            $filename = 'screenshot_' . $request->website_id . '_' . md5($request->page_path) . '_' . time() . '.png';
            $filepath = 'screenshots/' . $filename;
            
            // Ensure directory exists in storage/app/public
            $directory = storage_path('app/public/screenshots');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Save file to storage
            $fullPath = storage_path('app/public/' . $filepath);
            file_put_contents($fullPath, $screenshotData);

            // Save to database (URL should use storage link)
            $screenshotUrl = asset('storage/' . $filepath);
            
            \DB::table('page_screenshots')->insert([
                'website_id' => $request->website_id,
                'page_path' => $request->page_path,
                'screenshot_url' => $screenshotUrl,
                'viewport_width' => $request->viewport_width ?? 1920,
                'viewport_height' => $request->viewport_height ?? 1080,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            \Log::info('Screenshot captured successfully', [
                'website_id' => $request->website_id,
                'page_path' => $request->page_path,
                'screenshot_url' => $screenshotUrl
            ]);

            return response()->json([
                'success' => true,
                'screenshot_url' => $screenshotUrl,
                'message' => 'Screenshot captured successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Screenshot capture failed: ' . $e->getMessage(), [
                'website_id' => $request->website_id ?? null,
                'page_path' => $request->page_path ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to capture screenshot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start a new session recording
     */
    public function startRecording(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|string',
                'website_id' => 'required|integer',
                'page_url' => 'required|string',
                'user_agent' => 'nullable|string',
                'viewport_width' => 'nullable|integer',
                'viewport_height' => 'nullable|integer',
            ]);

            $recording = SessionRecording::create([
                'website_id' => $request->website_id,
                'session_id' => $request->session_id,
                'page_url' => $request->page_url,
                'user_agent' => $request->user_agent,
                'viewport_width' => $request->viewport_width ?? 1920,
                'viewport_height' => $request->viewport_height ?? 1080,
                'started_at' => now(),
                'events' => json_encode([]),
            ]);

            return response()->json([
                'success' => true,
                'recording_id' => $recording->id,
                'message' => 'Recording started'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to start recording: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to start recording'
            ], 500);
        }
    }

    /**
     * Save session recording events
     */
    public function saveEvents(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|string',
                'website_id' => 'required|integer',
                'events' => 'required|array',
            ]);

            $recording = SessionRecording::where('session_id', $request->session_id)
                ->where('website_id', $request->website_id)
                ->latest()
                ->first();

            if (!$recording) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recording not found'
                ], 404);
            }

            // Append new events to existing events
            $existingEvents = json_decode($recording->events, true) ?? [];
            $newEvents = array_merge($existingEvents, $request->events);
            
            $recording->update([
                'events' => json_encode($newEvents),
                'ended_at' => now(),
                'duration' => now()->diffInSeconds($recording->started_at),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Events saved'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to save events: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save events'
            ], 500);
        }
    }

    /**
     * Track click events for heatmap
     */
    public function trackClick(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|integer',
                'page_path' => 'required|string',
                'x' => 'required|numeric',
                'y' => 'required|numeric',
                'element' => 'nullable|string',
            ]);

            \DB::table('heatmap_clicks')->insert([
                'website_id' => $request->website_id,
                'page_path' => $request->page_path,
                'x' => $request->x,
                'y' => $request->y,
                'element' => $request->element,
                'viewport_width' => $request->viewport_width ?? 1920,
                'viewport_height' => $request->viewport_height ?? 1080,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Failed to track click: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Track mouse movement for heatmap
     */
    public function trackMouseMove(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|integer',
                'page_path' => 'required|string',
                'x' => 'required|numeric',
                'y' => 'required|numeric',
            ]);

            \DB::table('heatmap_moves')->insert([
                'website_id' => $request->website_id,
                'page_path' => $request->page_path,
                'x' => $request->x,
                'y' => $request->y,
                'viewport_width' => $request->viewport_width ?? 1920,
                'viewport_height' => $request->viewport_height ?? 1080,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Failed to track move: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Track scroll depth for heatmap
     */
    public function trackScroll(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|integer',
                'page_path' => 'required|string',
                'scroll_depth' => 'required|numeric',
                'max_scroll' => 'required|numeric',
            ]);

            \DB::table('heatmap_scrolls')->insert([
                'website_id' => $request->website_id,
                'page_path' => $request->page_path,
                'scroll_depth' => $request->scroll_depth,
                'max_scroll' => $request->max_scroll,
                'viewport_width' => $request->viewport_width ?? 1920,
                'viewport_height' => $request->viewport_height ?? 1080,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Failed to track scroll: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }
}
