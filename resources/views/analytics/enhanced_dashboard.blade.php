@extends('admin.main')

@section('content')
<!-- Load Chart.js UMD version and other dependencies FIRST -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: #ffffff !important;
        min-height: 100vh;
    }
    
    .analytics-container {
        background: #ffffff;
        padding: 20px;
        min-height: 100vh;
    }
    
    .dashboard-header {
        background: #ffffff;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .dashboard-title {
        color: #2c3e50;
        font-weight: 700;
        font-size: 2.5rem;
        margin: 0;
    }
    
    .dashboard-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        margin: 8px 0 0 0;
        font-weight: 400;
    }
    
    .filter-controls {
        background: #ffffff;
        border-radius: 15px;
        padding: 20px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .filter-controls select, .filter-controls input {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 12px 16px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .filter-controls select:focus, .filter-controls input:focus {
        background: white;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
        border-color: #86b7fe;
        outline: none;
    }
    
    .filter-controls label {
        color: #495057 !important;
        font-weight: 600;
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
        color: white;
    }
    
    .metric-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #0d6efd, #20c997, #fd7e14, #dc3545);
        border-radius: 20px 20px 0 0;
    }
    
    .metric-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }
    
    .metric-card .metric-value {
        animation: countUp 2s ease-out;
    }
    
    @keyframes countUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .metric-card h6 {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .metric-value {
        color: #2c3e50;
        font-size: 2.8rem;
        font-weight: 700;
        margin: 10px 0;
    }
    
    .metric-subtitle {
        color: #6c757d;
        font-size: 0.85rem;
        font-weight: 400;
    }
    
    .metric-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 2.5rem;
        color: rgba(108, 117, 125, 0.3);
    }
    
    .chart-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .chart-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }
    
    .chart-card:hover .chart-title i {
        transform: scale(1.2);
        color: #0d6efd;
    }
    
    .chart-title i {
        transition: all 0.3s ease;
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f8f9fa;
    }
    
    .chart-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }
    
    .chart-title i {
        margin-right: 10px;
        color: #667eea;
    }
    
    .chart-controls select {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 8px 12px;
        font-weight: 500;
        color: #495057;
        transition: all 0.3s ease;
    }
    
    .chart-controls select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    .chart-container {
        position: relative;
        height: 400px;
        margin: 20px 0;
    }
    
    .funnel-container {
        position: relative;
        height: 500px;
    }
    
    .geomap-container {
        height: 600px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: inset 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .funnel-step {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: white;
        padding: 20px;
        margin: 8px 0;
        border-radius: 15px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }
    
    .funnel-step:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
    }
    
    .funnel-step strong {
        display: block;
        font-size: 1.1rem;
        margin-bottom: 5px;
    }
    
    .product-item {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border: none;
        border-radius: 15px;
        padding: 20px;
        margin: 15px 0;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    
    .product-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .progress-custom {
        height: 12px;
        border-radius: 25px;
        background: #e9ecef;
        overflow: hidden;
        margin: 10px 0;
    }
    
    .progress-bar-custom {
        height: 100%;
        background: linear-gradient(90deg, #0d6efd, #0b5ed7);
        transition: width 0.8s ease;
        border-radius: 25px;
    }
    
    .badge-custom {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .funnel-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        align-items: start;
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        align-items: start;
    }
    
    @media (max-width: 768px) {
        .funnel-grid, .product-grid {
            grid-template-columns: 1fr;
        }
        
        .dashboard-title {
            font-size: 2rem;
        }
        
        .metric-value {
            font-size: 2.2rem;
        }
        
        .chart-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
    }
    
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .skeleton {
        background: linear-gradient(-90deg, #f0f0f0 0%, #e0e0e0 50%, #f0f0f0 100%);
        background-size: 400% 400%;
        animation: skeleton-loading 1.6s ease-in-out infinite;
        border-radius: 10px;
        height: 20px;
        margin: 10px 0;
    }
    
    @keyframes skeleton-loading {
        0% { background-position: 0% 0%; }
        100% { background-position: -135% 0%; }
    }
    
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<div class="analytics-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">
                    <i class="fas fa-chart-line"></i>
                    @if($selectedWebsite && $selectedWebsite->isInvestment())
                        Investment Analytics Dashboard
                    @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                        Fundraising Analytics Dashboard
                    @else
                        Analytics Dashboard
                    @endif
                </h1>
                <p class="dashboard-subtitle">
                    @if($selectedWebsite && $selectedWebsite->isInvestment())
                        Real-time insights into your investment platform's performance
                    @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                        Real-time insights into your fundraising campaign's performance
                    @else
                        Real-time insights into your charity's performance
                    @endif
                </p>
            </div>
            <div class="col-md-4">
                <div class="text-end">
                    <span class="badge badge-custom">
                        <i class="fas fa-clock"></i>
                        Last updated: <span id="last-updated">{{ now()->format('M j, Y g:i A') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="filter-controls mb-4">
        <form id="websiteForm" action="{{ route('analytics.dashboard') }}" method="GET">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-white fw-semibold">
                        <i class="fas fa-globe me-2"></i>Website
                    </label>
                    <select name="website_id" class="form-select" onchange="this.form.submit()">
                        @foreach($websites as $website)
                            <option value="{{ $website->id }}" {{ $selectedWebsiteId == $website->id ? 'selected' : '' }}>
                                {{ $website->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white fw-semibold">
                        <i class="fas fa-play me-2"></i>Start Date
                    </label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white fw-semibold">
                        <i class="fas fa-stop me-2"></i>End Date
                    </label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-filter w-100">
                        <i class="fas fa-filter me-2"></i>Apply Filter
                        <span class="loading-spinner d-none ms-2"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Overview Stats -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <i class="metric-icon fas fa-chart-line"></i>
                <h6>
                    @if($selectedWebsite && $selectedWebsite->isInvestment())
                        Total Investments
                    @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                        Total Donations
                    @else
                        Total Conversions
                    @endif
                </h6>
                <div class="metric-value" id="total-conversions">{{ number_format($stats['today']['conversions'] ?? 0) }}</div>
                <div class="metric-subtitle">
                    <i class="fas fa-dollar-sign me-1"></i>
                    @if($selectedWebsite && $selectedWebsite->isInvestment())
                        Invested: $<span id="total-revenue">{{ number_format(($stats['today']['revenue'] ?? 0) / 100, 2) }}</span>
                    @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                        Raised: $<span id="total-revenue">{{ number_format(($stats['today']['revenue'] ?? 0) / 100, 2) }}</span>
                    @else
                        Revenue: $<span id="total-revenue">{{ number_format(($stats['today']['revenue'] ?? 0) / 100, 2) }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h6 mb-0">Sessions</div>
                        <div class="display-6" id="total-sessions">{{ number_format($stats['today']['uniqueVisitors'] ?? 0) }}</div>
                    </div>
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
                <div class="small mt-2">👥 Unique Visitors</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <i class="metric-icon fas fa-percentage"></i>
                <h6>Conversion Rate</h6>
                <div class="metric-value" id="conversion-rate">0%</div>
                <div class="metric-subtitle">
                    <i class="fas fa-chart-line me-1"></i>
                    Performance Metric
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <i class="metric-icon fas fa-dollar-sign"></i>
                <h6>
                    @if($selectedWebsite && $selectedWebsite->isInvestment())
                        Total Capital Raised
                    @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                        Total Funds Raised
                    @else
                        Total Revenue
                    @endif
                </h6>
                <div class="metric-value" id="total-revenue-card">${{ number_format(($stats['today']['revenue'] ?? 0) / 100, 2) }}</div>
                <div class="metric-subtitle">
                    <i class="fas fa-coins me-1"></i>
                    @if($selectedWebsite && $selectedWebsite->isInvestment())
                        Avg Investment: $<span id="avg-order-value">{{ ($stats['today']['conversions'] ?? 0) > 0 ? number_format((($stats['today']['revenue'] ?? 0) / 100) / ($stats['today']['conversions'] ?? 1), 2) : '0.00' }}</span>
                    @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                        Avg Donation: $<span id="avg-order-value">{{ ($stats['today']['conversions'] ?? 0) > 0 ? number_format((($stats['today']['revenue'] ?? 0) / 100) / ($stats['today']['conversions'] ?? 1), 2) : '0.00' }}</span>
                    @else
                        Avg: $<span id="avg-order-value">{{ ($stats['today']['conversions'] ?? 0) > 0 ? number_format((($stats['today']['revenue'] ?? 0) / 100) / ($stats['today']['conversions'] ?? 1), 2) : '0.00' }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <i class="metric-icon fas fa-users"></i>
                <h6>Total Sessions</h6>
                <div class="metric-value" id="total-sessions">{{ number_format($stats['today']['sessions'] ?? 0) }}</div>
                <div class="metric-subtitle">
                    <i class="fas fa-eye me-1"></i>
                    Unique Visitors
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Activity Section -->
    <div class="chart-card">
        <div class="chart-header">
            <h4 class="chart-title">
                <i class="fas fa-broadcast-tower"></i>
                Real-time Activity
            </h4>
            <div class="chart-controls">
                <span class="badge badge-custom" id="active-users">
                    <i class="fas fa-users me-1"></i>
                    <span id="active-count">-</span> Active Users
                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table" id="realtime-activity">
                <thead>
                    <tr>
                        <th><i class="fas fa-clock me-1"></i>Time</th>
                        <th><i class="fas fa-file-alt me-1"></i>Page</th>
                        <th><i class="fas fa-user me-1"></i>User</th>
                        <th><i class="fas fa-mouse-pointer me-1"></i>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            Loading real-time data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Time-Based Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">
                        <i class="fas fa-chart-line"></i>
                        @if($selectedWebsite && $selectedWebsite->isInvestment())
                            Investments Over Time
                        @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                            Donations Over Time
                        @else
                            Conversions Over Time
                        @endif
                    </h4>
                    <div class="chart-controls">
                        <select id="conversions-timeframe" class="form-select">
                            <option value="day">Daily View</option>
                            <option value="week">Weekly View</option>
                            <option value="month">Monthly View</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="conversionsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">
                        <i class="fas fa-users"></i>
                        Sessions & Page Views
                    </h4>
                    <div class="chart-controls">
                        <select id="sessions-timeframe" class="form-select">
                            <option value="day">Daily View</option>
                            <option value="week">Weekly View</option>
                            <option value="month">Monthly View</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="sessionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversion Funnel -->
    <div class="chart-card">
        <div class="chart-header">
            <h4 class="chart-title">
                <i class="fas fa-funnel-dollar"></i>
                @if($selectedWebsite && $selectedWebsite->isInvestment())
                    Investment Funnel Analysis
                @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                    Donation Funnel Analysis
                @else
                    Conversion Funnel Analysis
                @endif
            </h4>
            <div class="chart-controls">
                <span class="badge badge-custom">
                    <i class="fas fa-chart-pie me-1"></i>
                    Detailed Breakdown
                </span>
            </div>
        </div>
        <div class="funnel-grid">
            <div class="funnel-container">
                <canvas id="funnelChart"></canvas>
            </div>
            <div id="funnel-breakdown">
                <!-- Funnel steps will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Device & Location Analytics -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">
                        <i class="fas fa-mobile-alt"></i>
                        Device Performance
                    </h4>
                    <div class="chart-controls">
                        <span class="badge badge-custom">
                            <i class="fas fa-chart-pie me-1"></i>
                            Usage Stats
                        </span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="deviceChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">
                        <i class="fas fa-globe-americas"></i>
                        Location Performance
                    </h4>
                    <div class="chart-controls">
                        <span class="badge badge-custom">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Geographic Data
                        </span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="locationChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Geomap -->
    <div class="chart-card">
        <div class="chart-header">
            <h4 class="chart-title">
                <i class="fas fa-map"></i>
                Interactive Visitor Map
            </h4>
            <div class="chart-controls">
                <span class="badge badge-custom">
                    <i class="fas fa-eye me-1"></i>
                    Real-time Locations
                </span>
            </div>
        </div>
        <div id="geomap" class="geomap-container"></div>
    </div>

    <!-- Product Sell-Through Rates -->
    <div class="chart-card">
        <div class="chart-header">
            <h4 class="chart-title">
                <i class="fas fa-shopping-cart"></i>
                Product Sell-Through Analysis
            </h4>
            <div class="chart-controls">
                <span class="badge badge-custom">
                    <i class="fas fa-chart-bar me-1"></i>
                    Performance Metrics
                </span>
            </div>
        </div>
        <div class="product-grid">
            <div class="chart-container">
                <canvas id="productChart"></canvas>
            </div>
            <div id="product-breakdown">
                <!-- Product items will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}
        </div>
    </div>
</div>

<script>
// Global variables
let currentWebsiteId = {{ $selectedWebsiteId }};
let currentStartDate = '{{ $startDate->format('Y-m-d') }}';
let currentEndDate = '{{ $endDate->format('Y-m-d') }}';

// Chart instances
let conversionsChart, sessionsChart, funnelChart, deviceChart, locationChart, productChart;
let geomap;

// Check if Chart.js is loaded and wait for it
function waitForChartJS(callback, attempts = 0) {
    if (typeof Chart !== 'undefined') {
        console.log('Chart.js loaded successfully');
        callback();
    } else if (attempts < 50) { // Wait up to 5 seconds
        console.log('Waiting for Chart.js to load... attempt', attempts + 1);
        setTimeout(() => waitForChartJS(callback, attempts + 1), 100);
    } else {
        console.error('Chart.js failed to load after 5 seconds');
        showToast('Failed to load Chart.js. Please refresh the page.', 'error');
    }
}

// Initialize all charts and components
document.addEventListener('DOMContentLoaded', function() {
    // Update last updated time
    updateLastUpdatedTime();
    
    // Wait for Chart.js to be available before initializing charts
    waitForChartJS(function() {
        initializeCharts();
        initializeGeomap();
        loadAllData();
        
        // Add event listeners for timeframe changes
        document.getElementById('conversions-timeframe').addEventListener('change', loadConversionsData);
        document.getElementById('sessions-timeframe').addEventListener('change', loadSessionsData);
    });
    
    // Add loading states to buttons
    addLoadingStates();
});

function updateLastUpdatedTime() {
    const now = new Date();
    const timeString = now.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
    const lastUpdatedEl = document.getElementById('last-updated');
    if (lastUpdatedEl) {
        lastUpdatedEl.textContent = timeString;
    }
}

function addLoadingStates() {
    const filterBtn = document.querySelector('.btn-filter');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            const spinner = this.querySelector('.loading-spinner');
            if (spinner) {
                spinner.classList.remove('d-none');
            }
        });
    }
}

function showToast(message, type = 'info') {
    // Create a simple toast notification
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : 'info'} position-fixed fade-in`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

function showLoadingSkeleton(containerId) {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = `
            <div class="skeleton" style="height: 30px; width: 80%; margin-bottom: 15px;"></div>
            <div class="skeleton" style="height: 20px; width: 60%; margin-bottom: 10px;"></div>
            <div class="skeleton" style="height: 20px; width: 70%; margin-bottom: 10px;"></div>
            <div class="skeleton" style="height: 15px; width: 50%;"></div>
        `;
    }
}

function initializeCharts() {
    // Conversions Chart
    const conversionsCtx = document.getElementById('conversionsChart').getContext('2d');
    conversionsChart = new Chart(conversionsCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Conversions',
                data: [],
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Revenue ($)',
                data: [],
                borderColor: '#FF9800',
                backgroundColor: 'rgba(255, 152, 0, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Conversions' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: { display: true, text: 'Revenue ($)' },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });

    // Sessions Chart
    const sessionsCtx = document.getElementById('sessionsChart').getContext('2d');
    sessionsChart = new Chart(sessionsCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Sessions',
                data: [],
                backgroundColor: '#2196F3',
                borderRadius: 6
            }, {
                label: 'Page Views',
                data: [],
                backgroundColor: '#03DAC6',
                borderRadius: 6
            }, {
                label: 'Unique Visitors',
                data: [],
                backgroundColor: '#FF5722',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Funnel Chart
    const funnelCtx = document.getElementById('funnelChart').getContext('2d');
    funnelChart = new Chart(funnelCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Count',
                data: [],
                backgroundColor: [
                    '#4CAF50', '#8BC34A', '#CDDC39', 
                    '#FFEB3B', '#FFC107', '#FF9800', '#FF5722'
                ],
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // Device Chart
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    deviceChart = new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Location Chart
    const locationCtx = document.getElementById('locationChart').getContext('2d');
    locationChart = new Chart(locationCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Visitors',
                data: [],
                backgroundColor: '#673AB7',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Product Chart
    const productCtx = document.getElementById('productChart').getContext('2d');
    productChart = new Chart(productCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Sell-Through Rate (%)',
                data: [],
                backgroundColor: '#E91E63',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true,
                    max: 100,
                    title: { display: true, text: 'Sell-Through Rate (%)' }
                }
            }
        }
    });
}

function initializeGeomap() {
    geomap = L.map('geomap').setView([20, 0], 2);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(geomap);
}

function loadAllData() {
    loadConversionsData();
    loadSessionsData();
    loadFunnelData();
    loadDeviceData();
    loadLocationData();
    loadProductData();
    loadGeoMapData();
    loadRealTimeData();
    
    // Set up real-time updates every 30 seconds
    setInterval(loadRealTimeData, 30000);
}

function loadConversionsData() {
    if (!conversionsChart) {
        console.error('Conversions chart not initialized');
        return;
    }
    
    const timeframe = document.getElementById('conversions-timeframe').value;
    
    fetch(`/analytics/api/conversions?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}&group_by=${timeframe}`)
        .then(response => response.json())
        .then(data => {
            conversionsChart.data.labels = data.map(item => item.period);
            conversionsChart.data.datasets[0].data = data.map(item => item.conversions);
            conversionsChart.data.datasets[1].data = data.map(item => item.revenue);
            conversionsChart.update();
            
            // Update overview stats
            const totalConversions = data.reduce((sum, item) => sum + item.conversions, 0);
            const totalRevenue = data.reduce((sum, item) => sum + item.revenue, 0);
            document.getElementById('total-conversions').textContent = totalConversions.toLocaleString();
            document.getElementById('total-revenue').textContent = totalRevenue.toLocaleString();
            document.getElementById('avg-order-value').textContent = totalConversions > 0 ? '$' + (totalRevenue / totalConversions).toFixed(2) : '$0';
        })
        .catch(error => console.error('Error loading conversions data:', error));
}

function loadSessionsData() {
    if (!sessionsChart) {
        console.error('Sessions chart not initialized');
        return;
    }
    
    const timeframe = document.getElementById('sessions-timeframe').value;
    
    fetch(`/analytics/api/sessions?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}&group_by=${timeframe}`)
        .then(response => response.json())
        .then(data => {
            sessionsChart.data.labels = data.map(item => item.period);
            sessionsChart.data.datasets[0].data = data.map(item => item.sessions);
            sessionsChart.data.datasets[1].data = data.map(item => item.page_views);
            sessionsChart.data.datasets[2].data = data.map(item => item.unique_visitors);
            sessionsChart.update();
            
            // Update sessions total
            const totalSessions = data.reduce((sum, item) => sum + item.sessions, 0);
            document.getElementById('total-sessions').textContent = totalSessions.toLocaleString();
        })
        .catch(error => console.error('Error loading sessions data:', error));
}

function loadFunnelData() {
    if (!funnelChart) {
        console.error('Funnel chart not initialized');
        return;
    }
    
    fetch(`/analytics/api/funnel?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}`)
        .then(response => response.json())
        .then(data => {
            funnelChart.data.labels = data.map(item => item.step);
            funnelChart.data.datasets[0].data = data.map(item => item.count);
            funnelChart.update();
            
            // Update funnel breakdown
            const breakdownHtml = data.map(item => `
                <div class="funnel-step">
                    <strong>${item.step}</strong><br>
                    <span>${item.count.toLocaleString()} (${item.conversion_rate}%)</span><br>
                    <small>Dropoff: ${item.dropoff_rate}%</small>
                </div>
            `).join('');
            document.getElementById('funnel-breakdown').innerHTML = breakdownHtml;
            
            // Calculate overall conversion rate
            const sessions = data[0]?.count || 0;
            const conversions = data[data.length - 1]?.count || 0;
            const conversionRate = sessions > 0 ? ((conversions / sessions) * 100).toFixed(2) : '0';
            document.getElementById('conversion-rate').textContent = conversionRate + '%';
        })
        .catch(error => console.error('Error loading funnel data:', error));
}

function loadDeviceData() {
    if (!deviceChart) {
        console.error('Device chart not initialized');
        return;
    }
    
    fetch(`/analytics/api/devices?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}`)
        .then(response => response.json())
        .then(data => {
            deviceChart.data.labels = data.map(item => item.device_type || 'Unknown');
            deviceChart.data.datasets[0].data = data.map(item => item.visitors);
            deviceChart.update();
        })
        .catch(error => console.error('Error loading device data:', error));
}

function loadLocationData() {
    if (!locationChart) {
        console.error('Location chart not initialized');
        return;
    }
    
    fetch(`/analytics/api/locations?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}`)
        .then(response => response.json())
        .then(data => {
            const topLocations = data.slice(0, 10); // Show top 10
            locationChart.data.labels = topLocations.map(item => item.country_name || item.country);
            locationChart.data.datasets[0].data = topLocations.map(item => item.visitors);
            locationChart.update();
        })
        .catch(error => console.error('Error loading location data:', error));
}

function loadProductData() {
    if (!productChart) {
        console.error('Product chart not initialized');
        return;
    }
    
    fetch(`/analytics/api/products?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}`)
        .then(response => response.json())
        .then(data => {
            productChart.data.labels = data.map(item => item.name);
            productChart.data.datasets[0].data = data.map(item => item.sell_through_rate);
            productChart.update();
            
            // Update product breakdown
            const breakdownHtml = data.map(item => `
                <div class="product-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>${item.name}</strong>
                        <span class="badge bg-primary">$${item.price}</span>
                    </div>
                    <div class="progress-custom mb-2">
                        <div class="progress-bar-custom" style="width: ${item.sell_through_rate}%"></div>
                    </div>
                    <div class="row small text-muted">
                        <div class="col">Sold: ${item.sold}/${item.available}</div>
                        <div class="col text-end">${item.sell_through_rate}%</div>
                    </div>
                    <div class="text-end"><strong>Revenue: $${item.revenue.toLocaleString()}</strong></div>
                </div>
            `).join('');
            document.getElementById('product-breakdown').innerHTML = breakdownHtml;
        })
        .catch(error => console.error('Error loading product data:', error));
}

function loadGeoMapData() {
    fetch(`/analytics/api/geomap?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}`)
        .then(response => response.json())
        .then(data => {
            // Clear existing markers
            geomap.eachLayer(layer => {
                if (layer instanceof L.Marker) {
                    geomap.removeLayer(layer);
                }
            });
            
            // Add markers for each location
            data.forEach(location => {
                const marker = L.marker([location.lat, location.lng])
                    .bindPopup(`
                        <div>
                            <h6>${location.country_name}</h6>
                            <p><strong>Visitors:</strong> ${location.visitors.toLocaleString()}</p>
                            <p><strong>Sessions:</strong> ${location.sessions.toLocaleString()}</p>
                            <p><strong>Conversions:</strong> ${location.conversions.toLocaleString()}</p>
                            <p><strong>Revenue:</strong> $${location.revenue.toLocaleString()}</p>
                            <p><strong>Conversion Rate:</strong> ${location.conversion_rate}%</p>
                        </div>
                    `)
                    .addTo(geomap);
                
                // Adjust marker size based on visitor count
                const size = Math.max(10, Math.min(50, location.visitors / 10));
                marker.setIcon(L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: #4CAF50; width: ${size}px; height: ${size}px; border-radius: 50%; border: 2px solid white; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold;">${location.visitors}</div>`,
                    iconSize: [size, size],
                    iconAnchor: [size/2, size/2]
                }));
            });
        })
        .catch(error => console.error('Error loading geomap data:', error));
}

// Real-time data loading function
function loadRealTimeData() {
    fetch(`/analytics/real-time?website_id=${currentWebsiteId}`)
        .then(response => response.json())
        .then(data => {
            // Update active users count
            document.getElementById('active-count').textContent = data.activeUsers || 0;
            
            // Update real-time activity table
            const tbody = document.querySelector('#realtime-activity tbody');
            if (data.recentPageViews && data.recentPageViews.length > 0) {
                tbody.innerHTML = '';
                
                data.recentPageViews.slice(0, 10).forEach(activity => {
                    const timeAgo = formatTimeAgo(new Date(activity.created_at));
                    const user = activity.user_id ? `User ${activity.user_id}` : `Visitor ${activity.session_id.substring(0, 8)}`;
                    
                    // Determine action type and badge color
                    let action = '';
                    let badgeColor = 'primary';
                    
                    if (activity.event_type === 'payment_completed') {
                        const amount = activity.amount ? ` ($${(activity.amount / 100).toFixed(2)})` : '';
                        action = `${activity.form_type.charAt(0).toUpperCase() + activity.form_type.slice(1)} Completed${amount}`;
                        badgeColor = 'success';
                    } else if (activity.event_type === 'amount_entered') {
                        const amount = activity.amount ? ` ($${(activity.amount / 100).toFixed(2)})` : '';
                        action = `${activity.form_type.charAt(0).toUpperCase() + activity.form_type.slice(1)} Amount Entered${amount}`;
                        badgeColor = 'warning';
                    } else if (activity.event_type === 'form_view') {
                        action = `${activity.form_type.charAt(0).toUpperCase() + activity.form_type.slice(1)} Form Viewed`;
                        badgeColor = 'info';
                    } else if (activity.event_type === 'auction_activity') {
                        const amount = activity.amount ? ` ($${(activity.amount / 100).toFixed(2)})` : '';
                        action = `Auction Bid${amount}`;
                        badgeColor = 'danger';
                    } else {
                        action = activity.event_type.replace('_', ' ');
                        badgeColor = 'primary';
                    }
                    
                    const row = `
                        <tr>
                            <td><small class="text-muted">${timeAgo}</small></td>
                            <td><code>${activity.page_url || activity.url || 'Unknown'}</code></td>
                            <td><span class="badge bg-secondary">${user}</span></td>
                            <td><span class="badge bg-${badgeColor}">${action}</span></td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            No recent activity
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading real-time data:', error);
            document.getElementById('active-count').textContent = '-';
        });
}

// Helper function to format time ago
function formatTimeAgo(date) {
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return `${diffInSeconds}s ago`;
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
    return `${Math.floor(diffInSeconds / 86400)}d ago`;
}
</script>
@endsection