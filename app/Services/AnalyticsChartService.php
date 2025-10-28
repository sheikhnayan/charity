<?php

namespace App\Services;

use App\Models\PaymentFunnelEvent;
use App\Models\UniqueVisitor;
use App\Models\PageView;
use App\Models\Ticket;
use App\Models\Investment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsChartService
{
    /**
     * Get time-based conversion data for charts
     */
    public function getTimeBasedConversions($websiteId, $startDate, $endDate, $groupBy = 'day')
    {
        $dateFormat = $this->getDateFormat($groupBy);
        $selectFormat = $this->getSelectFormat($groupBy);
        
        return PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('funnel_step', 'payment_completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("$selectFormat as period, COUNT(*) as conversions, SUM(amount) as revenue")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(function ($item) use ($groupBy) {
                return [
                    'period' => $this->formatPeriodLabel($item->period, $groupBy),
                    'conversions' => (int) $item->conversions,
                    'revenue' => (float) $item->revenue / 100 // Convert cents to dollars
                ];
            });
    }

    /**
     * Get time-based sessions data
     */
    public function getTimeBasedSessions($websiteId, $startDate, $endDate, $groupBy = 'day')
    {
        $selectFormat = $this->getSelectFormat($groupBy);
        
        return PageView::where('website_id', $websiteId)
            ->whereBetween('viewed_at', [$startDate, $endDate])
            ->selectRaw("$selectFormat as period, COUNT(DISTINCT session_id) as sessions, COUNT(*) as page_views, COUNT(DISTINCT visitor_id) as unique_visitors")
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(function ($item) use ($groupBy) {
                return [
                    'period' => $this->formatPeriodLabel($item->period, $groupBy),
                    'sessions' => (int) $item->sessions,
                    'page_views' => (int) $item->page_views,
                    'unique_visitors' => (int) $item->unique_visitors
                ];
            });
    }

    /**
     * Get conversion funnel breakdown
     */
    public function getConversionFunnelData($websiteId, $startDate, $endDate)
    {
        $totalSessions = PageView::where('website_id', $websiteId)
            ->whereBetween('viewed_at', [$startDate, $endDate])
            ->distinct('session_id')
            ->count();

        $funnelSteps = [
            'Sessions' => $totalSessions,
            'Form Views' => PaymentFunnelEvent::where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('funnel_step', 'form_view')
                ->distinct('session_id')
                ->count(),
            'Amount Entered' => PaymentFunnelEvent::where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('funnel_step', 'amount_entered')
                ->distinct('session_id')
                ->count(),
            'Personal Info Started' => PaymentFunnelEvent::where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('funnel_step', 'personal_info_started')
                ->distinct('session_id')
                ->count(),
            'Personal Info Completed' => PaymentFunnelEvent::where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('funnel_step', 'personal_info_completed')
                ->distinct('session_id')
                ->count(),
            'Payment Page' => PaymentFunnelEvent::where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('funnel_step', 'payment_initiated')
                ->distinct('session_id')
                ->count(),
            'Completed Conversions' => PaymentFunnelEvent::where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('funnel_step', 'payment_completed')
                ->distinct('session_id')
                ->count()
        ];

        // Calculate conversion rates
        $funnelData = [];
        $previousStep = $totalSessions;
        
        foreach ($funnelSteps as $stepName => $count) {
            $conversionRate = $totalSessions > 0 ? ($count / $totalSessions) * 100 : 0;
            $dropoffRate = $previousStep > 0 ? (($previousStep - $count) / $previousStep) * 100 : 0;
            
            $funnelData[] = [
                'step' => $stepName,
                'count' => $count,
                'conversion_rate' => round($conversionRate, 2),
                'dropoff_rate' => round($dropoffRate, 2)
            ];
            
            $previousStep = $count;
        }

        return $funnelData;
    }

    /**
     * Get device type breakdown
     */
    public function getDeviceBreakdown($websiteId, $startDate, $endDate)
    {
        $deviceData = UniqueVisitor::where('website_id', $websiteId)
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->selectRaw('device_type, COUNT(*) as visitors')
            ->groupBy('device_type')
            ->get();

        $deviceConversions = PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('funnel_step', 'payment_completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('device_type, COUNT(*) as conversions, SUM(amount) as revenue')
            ->groupBy('device_type')
            ->get()
            ->keyBy('device_type');

        return $deviceData->map(function ($item) use ($deviceConversions) {
            $conversions = $deviceConversions->get($item->device_type);
            $conversionRate = $item->visitors > 0 ? (($conversions->conversions ?? 0) / $item->visitors) * 100 : 0;
            
            return [
                'device_type' => $item->device_type ?: 'Unknown',
                'visitors' => (int) $item->visitors,
                'conversions' => (int) ($conversions->conversions ?? 0),
                'revenue' => (float) (($conversions->revenue ?? 0) / 100),
                'conversion_rate' => round($conversionRate, 2)
            ];
        });
    }

    /**
     * Get location breakdown
     */
    public function getLocationBreakdown($websiteId, $startDate, $endDate)
    {
        $locationData = UniqueVisitor::where('website_id', $websiteId)
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->selectRaw('country, COUNT(*) as visitors')
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('visitors')
            ->get();

        $locationConversions = PaymentFunnelEvent::join('unique_visitors', 'payment_funnel_events.visitor_id', '=', 'unique_visitors.visitor_id')
            ->where('payment_funnel_events.website_id', $websiteId)
            ->where('payment_funnel_events.funnel_step', 'payment_completed')
            ->whereBetween('payment_funnel_events.created_at', [$startDate, $endDate])
            ->selectRaw('unique_visitors.country, COUNT(*) as conversions, SUM(payment_funnel_events.amount) as revenue')
            ->whereNotNull('unique_visitors.country')
            ->groupBy('unique_visitors.country')
            ->get()
            ->keyBy('country');

        return $locationData->map(function ($item) use ($locationConversions) {
            $conversions = $locationConversions->get($item->country);
            $conversionRate = $item->visitors > 0 ? (($conversions->conversions ?? 0) / $item->visitors) * 100 : 0;
            
            return [
                'country' => $item->country,
                'country_name' => $this->getCountryName($item->country),
                'visitors' => (int) $item->visitors,
                'conversions' => (int) ($conversions->conversions ?? 0),
                'revenue' => (float) (($conversions->revenue ?? 0) / 100),
                'conversion_rate' => round($conversionRate, 2)
            ];
        });
    }

    /**
     * Get product sell-through rates
     */
    public function getProductSellThroughRates($websiteId, $startDate, $endDate)
    {
        // Get ticket sell-through rates
        $ticketData = Ticket::where('website_id', $websiteId)
            ->selectRaw('
                id,
                name as title,
                price,
                quantity,
                (SELECT COUNT(*) FROM payment_funnel_events 
                 WHERE form_type = "ticket" 
                 AND funnel_step = "payment_completed" 
                 AND JSON_EXTRACT(form_data, "$.ticket_id") = tickets.id 
                 AND created_at BETWEEN ? AND ?) as sold,
                (SELECT SUM(amount) FROM payment_funnel_events 
                 WHERE form_type = "ticket" 
                 AND funnel_step = "payment_completed" 
                 AND JSON_EXTRACT(form_data, "$.ticket_id") = tickets.id 
                 AND created_at BETWEEN ? AND ?) as revenue
            ', [$startDate, $endDate, $startDate, $endDate])
            ->get()
            ->map(function ($ticket) {
                $sellThroughRate = $ticket->quantity > 0 ? ($ticket->sold / $ticket->quantity) * 100 : 0;
                return [
                    'type' => 'ticket',
                    'id' => $ticket->id,
                    'name' => $ticket->title,
                    'price' => (float) $ticket->price,
                    'available' => (int) $ticket->quantity,
                    'sold' => (int) $ticket->sold,
                    'remaining' => (int) ($ticket->quantity - $ticket->sold),
                    'sell_through_rate' => round($sellThroughRate, 2),
                    'revenue' => (float) ($ticket->revenue ?? 0) / 100
                ];
            });

        // Get investment completion rates
        $investmentData = collect([]);
        if (class_exists('App\Models\Investment')) {
            $investmentData = Investment::where('website_id', $websiteId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('
                    "investment" as type,
                    0 as id,
                    "Investment Opportunities" as name,
                    AVG(investment_amount) as avg_amount,
                    COUNT(*) as completed,
                    SUM(investment_amount) as total_revenue
                ')
                ->first();
            
            if ($investmentData) {
                $investmentViews = PaymentFunnelEvent::where('website_id', $websiteId)
                    ->where('form_type', 'investment')
                    ->where('funnel_step', 'form_view')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
                
                $completionRate = $investmentViews > 0 ? ($investmentData->completed / $investmentViews) * 100 : 0;
                
                $investmentData = collect([[
                    'type' => 'investment',
                    'id' => 0,
                    'name' => 'Investment Opportunities',
                    'price' => (float) $investmentData->avg_amount / 100,
                    'available' => $investmentViews,
                    'sold' => (int) $investmentData->completed,
                    'remaining' => $investmentViews - $investmentData->completed,
                    'sell_through_rate' => round($completionRate, 2),
                    'revenue' => (float) $investmentData->total_revenue / 100
                ]]);
            }
        }

        return $ticketData->concat($investmentData);
    }

    /**
     * Get geographic data for mapping
     */
    public function getGeoMapData($websiteId, $startDate, $endDate)
    {
        // Get visitor counts by country
        $visitorCounts = UniqueVisitor::where('website_id', $websiteId)
            ->whereBetween('visited_at', [$startDate, $endDate])
            ->whereNotNull('country')
            ->groupBy('country')
            ->selectRaw('country, COUNT(*) as visitors, COUNT(DISTINCT session_id) as sessions')
            ->get()
            ->keyBy('country');
        
        // Get conversion counts by country
        $conversionCounts = PaymentFunnelEvent::join('unique_visitors', 'payment_funnel_events.visitor_id', '=', 'unique_visitors.visitor_id')
            ->where('payment_funnel_events.website_id', $websiteId)
            ->where('payment_funnel_events.funnel_step', 'payment_completed')
            ->whereBetween('payment_funnel_events.created_at', [$startDate, $endDate])
            ->whereNotNull('unique_visitors.country')
            ->groupBy('unique_visitors.country')
            ->selectRaw('unique_visitors.country, COUNT(*) as conversions, SUM(payment_funnel_events.amount) as revenue')
            ->get()
            ->keyBy('country');
        
        return $visitorCounts->map(function ($item) use ($conversionCounts) {
            $conversions = $conversionCounts->get($item->country);
            $coordinates = $this->getCountryCoordinates($item->country);
            
            return [
                'country_code' => $item->country,
                'country_name' => $this->getCountryName($item->country),
                'lat' => $coordinates['lat'],
                'lng' => $coordinates['lng'],
                'visitors' => (int) $item->visitors,
                'sessions' => (int) $item->sessions,
                'conversions' => (int) ($conversions->conversions ?? 0),
                'revenue' => (float) (($conversions->revenue ?? 0) / 100),
                'conversion_rate' => $item->visitors > 0 ? round((($conversions->conversions ?? 0) / $item->visitors) * 100, 2) : 0
            ];
        })->values();
    }

    /**
     * Helper methods
     */
    protected function getDateFormat($groupBy)
    {
        switch ($groupBy) {
            case 'hour':
                return '%Y-%m-%d %H:00:00';
            case 'day':
                return '%Y-%m-%d';
            case 'week':
                return '%Y-%u';
            case 'month':
                return '%Y-%m';
            case 'year':
                return '%Y';
            default:
                return '%Y-%m-%d';
        }
    }

    protected function getSelectFormat($groupBy)
    {
        switch ($groupBy) {
            case 'hour':
                return "DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00')";
            case 'day':
                return "DATE_FORMAT(created_at, '%Y-%m-%d')";
            case 'week':
                return "DATE_FORMAT(created_at, '%Y-%u')";
            case 'month':
                return "DATE_FORMAT(created_at, '%Y-%m')";
            case 'year':
                return "DATE_FORMAT(created_at, '%Y')";
            default:
                return "DATE_FORMAT(created_at, '%Y-%m-%d')";
        }
    }

    protected function formatPeriodLabel($period, $groupBy)
    {
        switch ($groupBy) {
            case 'hour':
                return Carbon::parse($period)->format('M j, g A');
            case 'day':
                return Carbon::parse($period)->format('M j');
            case 'week':
                $parts = explode('-', $period);
                return 'Week ' . $parts[1] . ', ' . $parts[0];
            case 'month':
                return Carbon::parse($period . '-01')->format('M Y');
            case 'year':
                return $period;
            default:
                return Carbon::parse($period)->format('M j');
        }
    }

    protected function getCountryName($countryCode)
    {
        $countries = [
            'US' => 'United States',
            'CA' => 'Canada',
            'GB' => 'United Kingdom',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'JP' => 'Japan',
            'CN' => 'China',
            'IN' => 'India',
            'BR' => 'Brazil',
            'MX' => 'Mexico',
            'RU' => 'Russia',
            'NL' => 'Netherlands',
            'SE' => 'Sweden',
            'NO' => 'Norway',
            'DK' => 'Denmark',
            'FI' => 'Finland',
            'CH' => 'Switzerland'
        ];

        return $countries[$countryCode] ?? $countryCode;
    }

    protected function getCountryCoordinates($countryCode)
    {
        $coordinates = [
            'US' => ['lat' => 39.8283, 'lng' => -98.5795],
            'CA' => ['lat' => 56.1304, 'lng' => -106.3468],
            'GB' => ['lat' => 55.3781, 'lng' => -3.4360],
            'AU' => ['lat' => -25.2744, 'lng' => 133.7751],
            'DE' => ['lat' => 51.1657, 'lng' => 10.4515],
            'FR' => ['lat' => 46.2276, 'lng' => 2.2137],
            'IT' => ['lat' => 41.8719, 'lng' => 12.5674],
            'ES' => ['lat' => 40.4637, 'lng' => -3.7492],
            'JP' => ['lat' => 36.2048, 'lng' => 138.2529],
            'CN' => ['lat' => 35.8617, 'lng' => 104.1954],
            'IN' => ['lat' => 20.5937, 'lng' => 78.9629],
            'BR' => ['lat' => -14.2350, 'lng' => -51.9253],
            'MX' => ['lat' => 23.6345, 'lng' => -102.5528],
            'RU' => ['lat' => 61.5240, 'lng' => 105.3188],
            'NL' => ['lat' => 52.1326, 'lng' => 5.2913],
            'SE' => ['lat' => 60.1282, 'lng' => 18.6435],
            'NO' => ['lat' => 60.4720, 'lng' => 8.4689],
            'DK' => ['lat' => 56.2639, 'lng' => 9.5018],
            'FI' => ['lat' => 61.9241, 'lng' => 25.7482],
            'CH' => ['lat' => 46.8182, 'lng' => 8.2275]
        ];

        return $coordinates[$countryCode] ?? ['lat' => 0, 'lng' => 0];
    }
}
