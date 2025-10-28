<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // dd(\App\Models\AnalyticsEvent::where('website_id', $selectedWebsiteId)->whereBetween('created_at', [$startDate, $endDate])->latest()->limit(15)->get());

        return view('analytics.dashboard', compact('stats', 'websites', 'selectedWebsiteId', 'startDate', 'endDate'));
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

    protected function getRealTimeStats()
    {
        $lastFiveMinutes = now()->subMinutes(5);
        
        return [
            'activeUsers' => $this->getActiveUsers($lastFiveMinutes),
            'recentPageViews' => $this->getRecentPageViews($lastFiveMinutes),
            'recentConversions' => $this->getRecentConversions($lastFiveMinutes),
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
        return \App\Models\AnalyticsEvent::where('event_type', 'page_view')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('session_id')
            ->count('session_id');
    }

    protected function getConversions($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::where('event_type', 'conversion')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    protected function getRevenue($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::where('event_type', 'conversion')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('conversion_data->amount');
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
            ->groupBy('device_type')
            ->selectRaw('device_type, count(*) as count')
            ->get();
    }

    protected function getLocationData($websiteId, $startDate, $endDate)
    {
        return \App\Models\AnalyticsEvent::whereNotNull('country')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('country')
            ->selectRaw('country, count(*) as count')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
    }

    protected function getActiveUsers($since)
    {
        return \App\Models\UserSession::where('end_time', '>=', $since)
            ->count();
    }

    protected function getRecentPageViews($since)
    {
        return \App\Models\AnalyticsEvent::where('event_type', 'page_view')
            ->where('created_at', '>=', $since)
            ->with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    protected function getRecentConversions($since)
    {
        return \App\Models\AnalyticsEvent::where('event_type', 'conversion')
            ->where('created_at', '>=', $since)
            ->with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }
}