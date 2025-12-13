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

        // Get selected website - prioritize websites with PaymentFunnelEvent data
        $selectedWebsiteId = $request->website_id;
        
        if (!$selectedWebsiteId) {
            // Find website with most PaymentFunnelEvent data
            $websiteWithData = PaymentFunnelEvent::select('website_id')
                ->groupBy('website_id')
                ->orderByRaw('COUNT(*) DESC')
                ->first();
                
            $selectedWebsiteId = $websiteWithData ? $websiteWithData->website_id : ($websites->first()->id ?? null);
        }
        
        // Get date range - expand default to 90 days to catch more data
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() 
                                        : now()->subDays(90)->startOfDay();
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
        
        // Debug: Log what we're looking for
        \Log::info("Analytics Stats Debug", [
            'website_id' => $websiteId,
            'start_date' => $startDate->toDateTimeString(),
            'end_date' => $endDate->toDateTimeString(),
            'payment_events_total' => PaymentFunnelEvent::count(),
            'payment_events_website' => PaymentFunnelEvent::where('website_id', $websiteId)->count(),
            'conversions_website' => PaymentFunnelEvent::where('website_id', $websiteId)->where('funnel_step', 'payment_completed')->count()
        ]);

        // Calculate Returning Customer Rate
        $returningCustomerRate = $this->getReturningCustomerRate($websiteId, $startDate, $endDate);

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

        // Calculate revenue values
        $todayRevenue = $this->getRevenue($websiteId, $startDate, $endDate);
        $monthRevenue = $this->getRevenue($websiteId, $lastMonth, now());
        $todayConversions = $this->getConversions($websiteId, $startDate, $endDate);
        
        return [
            'today' => [
                'pageViews' => $this->getPageViews($websiteId, $startDate, $endDate),
                'uniqueVisitors' => $this->getUniqueVisitors($websiteId, $startDate, $endDate),
                'conversions' => $todayConversions,
                'revenue' => $todayRevenue,
                'revenueFormatted' => '$' . number_format($todayRevenue, 2),
                'sessions' => $this->getUniqueVisitors($websiteId, $startDate, $endDate),
                // Shopify-style metrics
                'grossSales' => $todayRevenue, // Total revenue = gross sales
                'grossSalesFormatted' => '$' . number_format($todayRevenue, 2),
                'returningCustomerRate' => $returningCustomerRate,
                'returningCustomerRateFormatted' => number_format($returningCustomerRate, 2) . '%',
                'ordersFulfilled' => $todayConversions, // Completed payments = fulfilled orders
                'orders' => $todayConversions, // Total orders = conversions
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
                'revenue' => $monthRevenue,
                'revenueFormatted' => '$' . number_format($monthRevenue, 2),
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
        $lastWeek = now()->subDays(7); // Extended to 7 days to show recent activity
        
        // Get recent payment activity
        $paymentActivity = $this->getRecentPaymentActivity($lastWeek, $websiteId);
        
        // Get recent auction activity (bids, new auctions)
        $auctionActivity = $this->getRecentAuctionActivity($lastWeek, $websiteId);
        
        // Merge and sort all activities by time
        $allActivities = collect($paymentActivity)->merge($auctionActivity)
            ->sortByDesc('created_at')
            ->values()
            ->take(10);
        
        \Log::info('Real-time activity loaded', [
            'payment_count' => $paymentActivity->count(),
            'auction_count' => $auctionActivity->count(),
            'total_activities' => $allActivities->count()
        ]);
        
        return [
            'activeUsers' => $this->getActiveUsers($lastFiveMinutes, $websiteId),
            'recentPageViews' => $allActivities,
            'recentConversions' => $this->getRecentConversions($lastWeek, $websiteId),
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
        // Try visitor_id first, then fall back to session_id
        $visitorCount = \App\Models\PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('visitor_id')
            ->distinct('visitor_id')
            ->count('visitor_id');
            
        if ($visitorCount === 0) {
            $visitorCount = \App\Models\PaymentFunnelEvent::where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->distinct('session_id')
                ->count('session_id');
        }
        
        return $visitorCount;
    }

    protected function getCompletionFunnelStep()
    {
        // Dynamically detect the correct completion funnel step
        $possibleSteps = ['payment_completed', 'payment_complete', 'completed', 'payment_success', 'success'];
        
        foreach ($possibleSteps as $step) {
            $count = \App\Models\PaymentFunnelEvent::where('funnel_step', $step)->count();
            if ($count > 0) {
                \Log::info("Using funnel step: {$step} (found {$count} records)");
                return $step;
            }
        }
        
        // Fallback to payment_completed if nothing found
        \Log::warning("No completion funnel step found, defaulting to 'payment_completed'");
        return 'payment_completed';
    }

    protected function getConversions($websiteId, $startDate, $endDate)
    {
        // Get conversions from PaymentFunnelEvent using dynamic step detection
        $completionStep = $this->getCompletionFunnelStep();
        
        $count = \App\Models\PaymentFunnelEvent::where('funnel_step', $completionStep)
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        \Log::info("Conversions Debug", [
            'website_id' => $websiteId,
            'funnel_step' => $completionStep,
            'count' => $count,
            'date_range' => [$startDate, $endDate]
        ]);
            
        return $count;
    }

    protected function getRevenue($websiteId, $startDate, $endDate)
    {
        // Get revenue from PaymentFunnelEvent using dynamic step detection
        $completionStep = $this->getCompletionFunnelStep();
        
        $revenue = \App\Models\PaymentFunnelEvent::where('funnel_step', $completionStep)
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount') ?? 0;
            
        // Debug: Log revenue calculation
        \Log::info("Revenue Debug", [
            'website_id' => $websiteId,
            'funnel_step' => $completionStep,
            'revenue_raw' => $revenue,
            'revenue_formatted' => number_format($revenue, 2),
            'completed_payments' => \App\Models\PaymentFunnelEvent::where('funnel_step', $completionStep)
                ->where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get(['amount', 'form_type', 'created_at'])
                ->toArray()
        ]);
            
        return $revenue;
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
        // Get the actual completion step name
        $completionStep = $this->getCompletionFunnelStep();
        
        $query = PaymentFunnelEvent::whereIn('funnel_step', ['form_view', 'amount_entered', $completionStep])
            ->where('created_at', '>=', $since);
            
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }
        
        \Log::info('Recent Payment Activity Query', [
            'since' => $since,
            'website_id' => $websiteId,
            'completion_step' => $completionStep,
            'count' => $query->count()
        ]);
        
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
                    'url' => $event->url ?? 'payment',
                    'page_url' => $event->url ?? 'Payment Form',
                    'country' => $event->country,
                    'state' => $event->state,
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
     * Calculate returning customer rate
     * Returns percentage of customers who have made multiple purchases
     */
    protected function getReturningCustomerRate($websiteId, $startDate, $endDate)
    {
        $completionStep = $this->getCompletionFunnelStep();
        
        // Get all customers (users) who made purchases in the date range
        $customersInPeriod = PaymentFunnelEvent::where('funnel_step', $completionStep)
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('user_id') // Only count registered users
            ->distinct('user_id')
            ->pluck('user_id');
        
        if ($customersInPeriod->isEmpty()) {
            return 0;
        }
        
        // Count how many of these customers have made purchases BEFORE this period
        $returningCustomers = PaymentFunnelEvent::where('funnel_step', $completionStep)
            ->where('website_id', $websiteId)
            ->where('created_at', '<', $startDate) // Purchases before the period
            ->whereIn('user_id', $customersInPeriod)
            ->distinct('user_id')
            ->count();
        
        $totalCustomers = $customersInPeriod->count();
        
        return $totalCustomers > 0 ? ($returningCustomers / $totalCustomers) * 100 : 0;
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

    /**
     * Export analytics dashboard data as CSV or Excel
     */
    public function export(Request $request)
    {
        $websiteId = $request->website_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() 
                                        : now()->subDays(90)->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() 
                                    : now()->endOfDay();
        $format = $request->format ?? 'csv'; // csv or excel

        // Get analytics data
        $stats = $this->getAnalyticsStats($websiteId, $startDate, $endDate);
        
        // Get website name for file naming
        $websiteName = $websiteId ? \App\Models\Website::find($websiteId)?->name : 'All_Websites';
        $websiteName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $websiteName);

        if ($format === 'excel') {
            // Excel export
            $excelService = new \App\Services\ExcelExportService();
            $spreadsheet = $excelService->exportAnalyticsDashboard($stats, $websiteName, $startDate, $endDate);
            
            $filename = 'analytics_dashboard_' . $websiteName . '_' . now()->format('Y-m-d');
            return $excelService->generateAndDownload($spreadsheet, $filename);
        }

        // CSV export
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="analytics_dashboard_' . $websiteName . '_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($stats, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // Summary section
            fputcsv($file, ['Analytics Dashboard Export']);
            fputcsv($file, ['Date Range', $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')]);
            fputcsv($file, []);
            
            // Overview Stats
            fputcsv($file, ['Overview Statistics']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Page Views', number_format($stats['today']['pageViews'] ?? 0)]);
            fputcsv($file, ['Unique Visitors', number_format($stats['today']['uniqueVisitors'] ?? 0)]);
            fputcsv($file, ['Conversions', number_format($stats['today']['conversions'] ?? 0)]);
            fputcsv($file, ['Revenue', '$' . number_format($stats['today']['revenue'] ?? 0, 2)]);
            fputcsv($file, ['Gross Sales', '$' . number_format($stats['today']['grossSales'] ?? 0, 2)]);
            fputcsv($file, ['Returning Customer Rate', number_format($stats['today']['returningCustomerRate'] ?? 0, 2) . '%']);
            fputcsv($file, ['Orders Fulfilled', number_format($stats['today']['ordersFulfilled'] ?? 0)]);
            fputcsv($file, []);
            
            // Weekly Stats - Fixed key from 'weekly' to 'week'
            if (!empty($stats['week'])) {
                fputcsv($file, ['Weekly Performance']);
                fputcsv($file, ['Date', 'Page Views', 'Unique Visitors', 'Conversions', 'Revenue']);
                
                // Get dates and data arrays
                $dates = $stats['week']['dates'] ?? [];
                $pageViews = $stats['week']['pageViews'] ?? [];
                $uniqueVisitors = $stats['week']['uniqueVisitors'] ?? [];
                $conversions = $stats['week']['conversions'] ?? [];
                $revenue = $stats['week']['revenue'] ?? [];
                
                // Combine arrays into rows
                for ($i = 0; $i < count($dates); $i++) {
                    fputcsv($file, [
                        $dates[$i] ?? '',
                        number_format($pageViews[$i] ?? 0),
                        number_format($uniqueVisitors[$i] ?? 0),
                        number_format($conversions[$i] ?? 0),
                        '$' . number_format($revenue[$i] ?? 0, 2)
                    ]);
                }
                fputcsv($file, []);
            }
            
            // Top Pages
            if (!empty($stats['topPages'])) {
                fputcsv($file, ['Top Pages']);
                fputcsv($file, ['Page', 'Views']);
                foreach ($stats['topPages'] as $page) {
                    fputcsv($file, [
                        $page['page'] ?? 'Unknown',
                        number_format($page['views'] ?? 0)
                    ]);
                }
                fputcsv($file, []);
            }
            
            // Top Referrers
            if (!empty($stats['topReferrers'])) {
                fputcsv($file, ['Top Referrers']);
                fputcsv($file, ['Source', 'Visitors']);
                foreach ($stats['topReferrers'] as $referrer) {
                    fputcsv($file, [
                        $referrer['source'] ?? 'Unknown',
                        number_format($referrer['visitors'] ?? 0)
                    ]);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}