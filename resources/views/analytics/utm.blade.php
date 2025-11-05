@extends('admin.main')

@section('title', 'UTM Attribution Analytics')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2">UTM Attribution Analytics</h1>
                    <p class="text-muted">Track campaign performance and marketing ROI (Website-Based)</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('analytics.utm.export', array_merge(request()->all(), ['format' => 'csv'])) }}" class="btn btn-success">
                        <i class="bi bi-filetype-csv"></i> Export CSV
                    </a>
                    <a href="{{ route('analytics.utm.export', array_merge(request()->all(), ['format' => 'excel'])) }}" class="btn btn-primary">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('analytics.utm') }}" class="row g-3">
                @if(count($websites) > 1)
                <div class="col-md-3">
                    <label class="form-label">Website</label>
                    <select name="website_id" class="form-select">
                        <option value="">All Websites</option>
                        @foreach($websites as $website)
                            <option value="{{ $website->id }}" {{ $websiteId == $website->id ? 'selected' : '' }}>
                                {{ $website->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Sessions with UTM</p>
                            <h3 class="mb-0">{{ number_format($stats['total_with_utm']) }}</h3>
                            <small class="text-primary">{{ $stats['utm_percentage'] }}% of total</small>
                        </div>
                        <div class="text-primary fs-1">
                            <i class="bi bi-link-45deg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">UTM Conversions</p>
                            <h3 class="mb-0">{{ number_format($stats['utm_conversions']) }}</h3>
                            <small class="text-success">Tracked conversions</small>
                        </div>
                        <div class="text-success fs-1">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">UTM Revenue</p>
                            <h3 class="mb-0">${{ number_format($stats['utm_revenue'], 2) }}</h3>
                            <small class="text-warning">${{ number_format($stats['avg_revenue_per_conversion'], 2) }} avg</small>
                        </div>
                        <div class="text-warning fs-1">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Active Campaigns</p>
                            <h3 class="mb-0">{{ $stats['unique_campaigns'] }}</h3>
                            <small class="text-info">{{ $stats['unique_sources'] }} sources</small>
                        </div>
                        <div class="text-info fs-1">
                            <i class="bi bi-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trending Campaigns -->
    @if($trending->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Trending Campaigns</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($trending as $trend)
                <div class="col-md-4 mb-3">
                    <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                        <div>
                            <h6 class="mb-1">{{ $trend['campaign'] }}</h6>
                            <small class="text-muted">{{ $trend['current_conversions'] }} conversions</small>
                        </div>
                        <div class="text-end">
                            @if($trend['trending'] === 'up')
                                <span class="badge bg-success">
                                    <i class="bi bi-arrow-up"></i> {{ $trend['growth_percentage'] }}%
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-arrow-down"></i> {{ abs($trend['growth_percentage']) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Campaign Performance Table -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-table"></i> Campaign Performance</h5>
                </div>
                <div class="card-body">
                    @if($campaigns->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Campaign</th>
                                    <th>Source</th>
                                    <th>Medium</th>
                                    <th class="text-end">Sessions</th>
                                    <th class="text-end">Conversions</th>
                                    <th class="text-end">Conv. Rate</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($campaigns as $campaign)
                                <tr>
                                    <td>
                                        <strong>{{ $campaign['campaign'] }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $campaign['source'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $campaign['medium'] }}</span>
                                    </td>
                                    <td class="text-end">{{ number_format($campaign['sessions']) }}</td>
                                    <td class="text-end">
                                        <strong>{{ number_format($campaign['conversions']) }}</strong>
                                    </td>
                                    <td class="text-end">
                                        @if($campaign['conversion_rate'] >= 5)
                                            <span class="badge bg-success">{{ $campaign['conversion_rate'] }}%</span>
                                        @elseif($campaign['conversion_rate'] >= 2)
                                            <span class="badge bg-warning">{{ $campaign['conversion_rate'] }}%</span>
                                        @else
                                            <span class="badge bg-danger">{{ $campaign['conversion_rate'] }}%</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-success">${{ number_format($campaign['revenue'], 2) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No UTM data available for the selected period</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Source/Medium Breakdown -->
        <div class="col-md-4">
            <!-- Top Sources -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bullseye"></i> Top Sources</h5>
                </div>
                <div class="card-body">
                    @if($sources['sources']->count() > 0)
                    <div class="mb-3">
                        <canvas id="sourcesChart" height="200"></canvas>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($sources['sources']->take(5) as $source)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $source['source'] }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ number_format($source['sessions']) }} sessions
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="text-success fw-bold">
                                        ${{ number_format($source['revenue'], 2) }}
                                    </div>
                                    <small class="text-muted">{{ $source['conversion_rate'] }}% conv</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center">No source data available</p>
                    @endif
                </div>
            </div>

            <!-- Medium Distribution -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Medium Distribution</h5>
                </div>
                <div class="card-body">
                    @if($sources['mediums']->count() > 0)
                    <canvas id="mediumChart" height="200"></canvas>
                    @else
                    <p class="text-muted text-center">No medium data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Conversion Attribution -->
    @if($attribution->count() > 0)
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Revenue Attribution</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Source</th>
                            <th>Medium</th>
                            <th>Campaign</th>
                            <th class="text-end">Conversions</th>
                            <th class="text-end">Revenue</th>
                            <th class="text-end">% of Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalRevenue = $attribution->sum('revenue'); @endphp
                        @foreach($attribution as $attr)
                        <tr>
                            <td>{{ $attr['source'] }}</td>
                            <td>{{ $attr['medium'] }}</td>
                            <td>{{ $attr['campaign'] }}</td>
                            <td class="text-end">{{ $attr['conversions'] }}</td>
                            <td class="text-end text-success fw-bold">${{ number_format($attr['revenue'], 2) }}</td>
                            <td class="text-end">
                                {{ $totalRevenue > 0 ? number_format(($attr['revenue'] / $totalRevenue) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3">Total</td>
                            <td class="text-end">{{ number_format($attribution->sum('conversions')) }}</td>
                            <td class="text-end text-success">${{ number_format($totalRevenue, 2) }}</td>
                            <td class="text-end">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($sources['sources']->count() > 0)
    // Sources Chart
    const sourcesData = {
        labels: {!! json_encode($sources['sources']->pluck('source')->take(5)) !!},
        datasets: [{
            label: 'Sessions',
            data: {!! json_encode($sources['sources']->pluck('sessions')->take(5)) !!},
            backgroundColor: [
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
            ]
        }]
    };

    new Chart(document.getElementById('sourcesChart'), {
        type: 'doughnut',
        data: sourcesData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif

    @if($sources['mediums']->count() > 0)
    // Medium Chart
    const mediumData = {
        labels: {!! json_encode($sources['mediums']->pluck('medium')) !!},
        datasets: [{
            label: 'Sessions',
            data: {!! json_encode($sources['mediums']->pluck('sessions')) !!},
            backgroundColor: [
                'rgba(255, 159, 64, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)'
            ]
        }]
    };

    new Chart(document.getElementById('mediumChart'), {
        type: 'pie',
        data: mediumData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif
});
</script>
@endpush
@endsection
