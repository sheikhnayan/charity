<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PaymentFunnelEvent;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get websites based on user role
        if (auth()->user()->role === 'admin') {
            $websites = \App\Models\Website::all();
        } else {
            $websites = \App\Models\Website::where('user_id', auth()->id())->get();
        }

        // Get selected website
        $selectedWebsiteId = $request->website_id ?? ($websites->first()->id ?? null);
        
        // Get date range
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() 
                                        : now()->subDays(30)->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() 
                                    : now()->endOfDay();

        $stats = $this->getAnalyticsStats($selectedWebsiteId, $startDate, $endDate);
        
        // Get selected website for type checking
        $selectedWebsite = $websites->find($selectedWebsiteId);

        return view('analytics.enhanced_dashboard', compact('stats', 'websites', 'selectedWebsiteId', 'selectedWebsite', 'startDate', 'endDate'));
    }

    public function realTime(Request $request)
    {
        $websiteId = $request->website_id;
        $realTimeStats = $this->getRealTimeStats($websiteId);
        return response()->json($realTimeStats);
    }

    protected function getAnalyticsStats($websiteId, $startDate, $endDate)
    {
        $today = now()->startOfDay();
        $lastWeek = now()->subWeek();
        $lastMonth = now()->subMonth();

        // Get daily stats for the past week
        $weeklyStats = collect(range(6, 0))->map(function ($daysAgo) use ($websiteId) {
            $date = now()->subDays($daysAgo)->startOfDay();
            return [
                'date' => $date->format('D'),
                'pageViews' => $this->getPageViews($websiteId, $date, $date->copy()->endOfDay()),
                'uniqueVisitors' => $this->getUniqueVisitors($websiteId, $date, $date->copy()->endOfDay()),
                'conversions' => $this->getConversions($websiteId, $date, $date->copy()->endOfDay()),
                'revenue' => $this->getRevenue($websiteId, $date, $date->copy()->endOfDay()),
            ];
        });

        return [
            'today' => [
                'pageViews' => $this->getPageViews($websiteId, $today, now()),
                'uniqueVisitors' => $this->getUniqueVisitors($websiteId, $today, now()),
                'conversions' => $this->getConversions($websiteId, $today, now()),
                'revenue' => $this->getRevenue($websiteId, $today, now()),
            ],
            'week' => [
                'dates' => $weeklyStats->pluck('date')->toArray(),
                'pageViews' => $weeklyStats->pluck('pageViews')->toArray(),
                'uniqueVisitors' => $weeklyStats->pluck('uniqueVisitors')->toArray(),
                'conversions' => $weeklyStats->pluck('conversions')->toArray(),
                'revenue' => $weeklyStats->pluck('revenue')->toArray(),
            ],
            'month' => [
                'pageViews' => $this->getPageViews($websiteId, $lastMonth, now()),
                'uniqueVisitors' => $this->getUniqueVisitors($websiteId, $lastMonth, now()),
                'conversions' => $this->getConversions($websiteId, $lastMonth, now()),
                'revenue' => $this->getRevenue($websiteId, $lastMonth, now()),
            ],
            'topPages' => $this->getTopPages($websiteId, $startDate, $endDate),
            'topReferrers' => $this->getTopReferrers($websiteId, $startDate, $endDate),
            'deviceBreakdown' => $this->getDeviceBreakdown($websiteId, $startDate, $endDate),
            'locationData' => $this->getLocationData($websiteId, $startDate, $endDate),
        ];
    }

    protected function getRealTimeStats($websiteId = null)
    {
        $lastFiveMinutes = now()->subMinutes(5);
        $lastDay = now()->subDay(); // Extended time for testing - show last 24 hours of activity
        
        // Get recent payment activity
        $paymentActivity = $this->getRecentPaymentActivity($lastDay, $websiteId);
        
        // Get recent auction activity (bids, new auctions)
        $auctionActivity = $this->getRecentAuctionActivity($lastDay, $websiteId);
        
        // Merge and sort all activities by time
        $allActivities = collect($paymentActivity)->merge($auctionActivity)
            ->sortByDesc('created_at')
            ->values()
            ->take(10);
        
        return [
            'activeUsers' => $this->getActiveUsers($lastFiveMinutes, $websiteId),
            'recentPageViews' => $allActivities,
            'recentConversions' => $this->getRecentConversions($lastDay, $websiteId),
        ];
    }

    protected function getPageViews($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::where('event_type', 'page_view')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    protected function getUniqueVisitors($websiteId, $startDate, $endDate)
    {
        return \App\Models\PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('session_id')
            ->count('session_id');
    }

    protected function getConversions($websiteId, $startDate, $endDate)
    {
        // Get conversions from PaymentFunnelEvent
        return \App\Models\PaymentFunnelEvent::where('funnel_step', 'payment_completed')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    protected function getRevenue($websiteId, $startDate, $endDate)
    {
        // Get revenue from PaymentFunnelEvent
        return \App\Models\PaymentFunnelEvent::where('funnel_step', 'payment_completed')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount') ?? 0;
    }

    protected function getTopPages($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::where('event_type', 'page_view')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('url')
            ->selectRaw('url, count(*) as views')
            ->orderByDesc('views')
            ->limit(10)
            ->get();
    }

    protected function getTopReferrers($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::whereNotNull('referrer_url')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('referrer_url')
            ->selectRaw('referrer_url, count(*) as count')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
    }

    protected function getDeviceBreakdown($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->selectRaw('device_type, count(*) as count')
            ->orderByDesc('count')
            ->get();
    }

    protected function getLocationData($websiteId, $startDate, $endDate)
    {
        // First try to get data with country info
        $withCountry = \App\Models\AnalyticsEvent::whereNotNull('country')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('country')
            ->selectRaw('country, count(*) as count')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
            
        // If no country data, return IP-based data as fallback
        if ($withCountry->isEmpty()) {
            return \App\Models\AnalyticsEvent::whereNotNull('ip_address')
                ->where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('ip_address')
                ->selectRaw('ip_address as country, count(*) as count')
                ->orderByDesc('count')
                ->limit(10)
                ->get();
        }
        
        return $withCountry;
    }

    protected function getActiveUsers($since, $websiteId = null)
    {
        // Count unique sessions from PaymentFunnelEvent in the time period
        $query = PaymentFunnelEvent::where('created_at', '>=', $since);
        
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        return $query->distinct('session_id')->count('session_id');
    }

    protected function getRecentPageViews($since, $websiteId = null)
    {
        $query = \App\Models\AnalyticsEvent::where('event_type', 'page_view')
            ->where('created_at', '>=', $since);
            
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        return $query->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    protected function getRecentConversions($since, $websiteId = null)
    {
        $query = PaymentFunnelEvent::where('funnel_step', 'payment_completed')
            ->where('created_at', '>=', $since);
            
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        return $query->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }

    protected function getRecentPaymentActivity($since, $websiteId = null)
    {
        $query = PaymentFunnelEvent::whereIn('funnel_step', ['form_view', 'amount_entered', 'payment_completed'])
            ->where('created_at', '>=', $since);
            
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        return $query->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($event) {
                return [
                    'created_at' => $event->created_at,
                    'event_type' => $event->funnel_step,
                    'form_type' => $event->form_type,
                    'amount' => $event->amount,
                    'session_id' => $event->session_id,
                    'user_id' => $event->user_id,
                    'url' => $event->form_type . ' form',
                    'page_url' => ucfirst($event->form_type) . ' Page'
                ];
            });
    }

    protected function getRecentAuctionActivity($since, $websiteId = null)
    {
        $query = \App\Models\Auction::where('created_at', '>=', $since);
            
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        return $query->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($auction) {
                return [
                    'created_at' => $auction->updated_at ?? $auction->created_at, // Use updated_at for bid activity
                    'event_type' => 'auction_activity',
                    'form_type' => 'auction',
                    'amount' => $auction->current_bid ?? $auction->starting_bid ?? null,
                    'session_id' => 'auction_' . $auction->id,
                    'user_id' => null,
                    'url' => 'auction',
                    'page_url' => 'Auction: ' . ($auction->name ?? 'Item #' . $auction->id),
                    'auction_name' => $auction->name ?? 'Auction Item'
                ];
            });
    }

    /**
     * Chart Data API Endpoints
     */
    public function getTimeBasedConversions(Request $request)
    {
        $websiteId = $request->website_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
        $groupBy = $request->group_by ?? 'day';
        
        $chartService = new \App\Services\AnalyticsChartService();
        return response()->json($chartService->getTimeBasedConversions($websiteId, $startDate, $endDate, $groupBy));
    }

    public function getTimeBasedSessions(Request $request)
    {
        $websiteId = $request->website_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
        $groupBy = $request->group_by ?? 'day';
        
        $chartService = new \App\Services\AnalyticsChartService();
        return response()->json($chartService->getTimeBasedSessions($websiteId, $startDate, $endDate, $groupBy));
    }

    public function getConversionFunnel(Request $request)
    {
        try {
            $websiteId = $request->website_id;
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
            
            $chartService = new \App\Services\AnalyticsChartService();
            $data = $chartService->getConversionFunnelData($websiteId, $startDate, $endDate);
            
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Funnel data error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load funnel data', 'message' => $e->getMessage()], 500);
        }
    }

    public function getDeviceData(Request $request)
    {
        try {
            $websiteId = $request->website_id;
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
            
            $chartService = new \App\Services\AnalyticsChartService();
            $data = $chartService->getDeviceBreakdown($websiteId, $startDate, $endDate);
            
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Device data error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load device data', 'message' => $e->getMessage()], 500);
        }
    }

    public function getLocationChartData(Request $request)
    {
        try {
            $websiteId = $request->website_id;
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
            
            $chartService = new \App\Services\AnalyticsChartService();
            $data = $chartService->getLocationBreakdown($websiteId, $startDate, $endDate);
            
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Location data error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load location data', 'message' => $e->getMessage()], 500);
        }
    }

    public function getProductData(Request $request)
    {
        $websiteId = $request->website_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
        
        $chartService = new \App\Services\AnalyticsChartService();
        return response()->json($chartService->getProductSellThroughRates($websiteId, $startDate, $endDate));
    }

    public function getGeoMapData(Request $request)
    {
        try {
            $websiteId = $request->website_id;
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
            
            $chartService = new \App\Services\AnalyticsChartService();
            $data = $chartService->getGeoMapData($websiteId, $startDate, $endDate);
            
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('GeoMap data error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load geomap data', 'message' => $e->getMessage()], 500);
        }
    }
}