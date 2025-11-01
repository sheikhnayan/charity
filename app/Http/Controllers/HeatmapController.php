<?php

namespace App\Http\Controllers;

use App\Services\HeatmapService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HeatmapController extends Controller
{
    protected HeatmapService $service;

    public function __construct(HeatmapService $service)
    {
        $this->service = $service;
    }

    /**
     * Store heatmap event
     */
    public function trackEvent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_url' => 'required|url',
            'event_type' => 'required|in:click,move,scroll,attention',
            'x' => 'integer|nullable',
            'y' => 'integer|nullable',
            'viewport_width' => 'required|integer',
            'viewport_height' => 'required|integer',
            'element_selector' => 'string|nullable',
            'element_text' => 'string|nullable',
            'element_class' => 'string|nullable',
            'element_id' => 'string|nullable',
            'scroll_depth' => 'integer|nullable',
            'max_scroll' => 'integer|nullable',
            'duration_ms' => 'integer|nullable',
            'device_type' => 'string|nullable',
            'session_id' => 'string|nullable',
            'visitor_id' => 'string|nullable',
        ]);

        $this->service->storeEvent($validated);

        return response()->json(['success' => true]);
    }

    /**
     * Store batch heatmap events
     */
    public function trackBatch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'events' => 'required|array',
            'events.*.website_id' => 'required|integer',
            'events.*.page_url' => 'required|url',
            'events.*.event_type' => 'required|in:click,move,scroll,attention',
        ]);

        $count = $this->service->storeBatchEvents($validated['events']);

        return response()->json([
            'success' => true,
            'stored_count' => $count,
        ]);
    }

    /**
     * Get click heatmap data
     */
    public function getClickHeatmap(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_path' => 'required|string',
            'date_from' => 'date|nullable',
            'date_to' => 'date|nullable',
            'device_type' => 'string|nullable',
            'days' => 'integer|nullable',
        ]);

        $filters = $request->only(['date_from', 'date_to', 'device_type', 'days']);
        $data = $this->service->getClickHeatmap(
            $validated['website_id'],
            $validated['page_path'],
            $filters
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get move heatmap data
     */
    public function getMoveHeatmap(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_path' => 'required|string',
            'date_from' => 'date|nullable',
            'date_to' => 'date|nullable',
            'device_type' => 'string|nullable',
            'days' => 'integer|nullable',
        ]);

        $filters = $request->only(['date_from', 'date_to', 'device_type', 'days']);
        $data = $this->service->getMoveHeatmap(
            $validated['website_id'],
            $validated['page_path'],
            $filters
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get scroll depth data
     */
    public function getScrollHeatmap(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_path' => 'required|string',
            'date_from' => 'date|nullable',
            'date_to' => 'date|nullable',
            'device_type' => 'string|nullable',
            'days' => 'integer|nullable',
        ]);

        $filters = $request->only(['date_from', 'date_to', 'device_type', 'days']);
        $data = $this->service->getScrollHeatmap(
            $validated['website_id'],
            $validated['page_path'],
            $filters
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get aggregated normalized heatmap
     */
    public function getAggregatedHeatmap(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_path' => 'required|string',
            'type' => 'required|in:click,move',
            'target_width' => 'integer|nullable',
            'target_height' => 'integer|nullable',
        ]);

        $data = $this->service->getAggregatedHeatmap(
            $validated['website_id'],
            $validated['page_path'],
            $validated['type'],
            $validated['target_width'] ?? 1440,
            $validated['target_height'] ?? 2400
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get popular pages
     */
    public function getPopularPages(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'limit' => 'integer|nullable',
        ]);

        $pages = $this->service->getPopularPages(
            $validated['website_id'],
            $validated['limit'] ?? 20
        );

        return response()->json([
            'success' => true,
            'pages' => $pages,
        ]);
    }

    /**
     * Get element click statistics
     */
    public function getElementStats(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_path' => 'required|string',
        ]);

        $stats = $this->service->getElementClickStats(
            $validated['website_id'],
            $validated['page_path']
        );

        return response()->json([
            'success' => true,
            'elements' => $stats,
        ]);
    }
}
