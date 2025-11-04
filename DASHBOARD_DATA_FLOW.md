# Dashboard Data Flow Documentation

## 📊 Where Dashboard Data Comes From

This document explains exactly where each dashboard gets its data and how to populate it with real information.

---

## 1. 🛡️ Fraud Detection Dashboard

**URL**: `/fraud/`  
**View**: `resources/views/fraud/index.blade.php`  
**Controller**: `app/Http/Controllers/FraudDetectionController.php`

### Data Sources

#### Stats Cards (Top Row)
JavaScript calls: `GET /fraud/api/stats`

**Controller Method**: `getStatistics()`
```php
// Returns from database:
FraudDetection::count() // total_detections
FraudDetection::where('risk_level', 'high')->count() // high_risk  
FraudDetection::where('action_taken', 'blocked')->count() // blocked
FraudDetection::where('action_taken', 'blocked')->sum('amount') // amount_saved
```

**Database Tables**:
- `fraud_detections` - All fraud detection records
- `fraud_rules` - Detection rules configuration
- `fraud_statistics` - Aggregated statistics
- `fraud_blacklist` - Blocked entities

#### Recent Detections Table
JavaScript calls: `GET /fraud/api/recent`

**Controller Method**: `getRecentDetections()` (just added)
```php
// Queries:
FraudDetection::with(['user', 'transaction', 'donation'])
    ->orderBy('created_at', 'desc')
    ->limit(50)
    ->get()
```

**Populates**:
- Transaction ID
- User ID
- Amount
- Risk Score (0-100)
- Risk Level (low/medium/high/critical)
- Action Taken (allowed/flagged/blocked)
- Detection Reason
- Timestamp

#### Charts (Risk Trends & Distribution)
**Currently**: Hardcoded demo data in JavaScript
**To Make Real**: Modify the chart data arrays to use API response

---

## 2. 👥 Cohort Analysis Dashboard

**URL**: `/cohorts/`  
**View**: `resources/views/cohorts/index.blade.php`  
**Controller**: `app/Http/Controllers/CohortController.php`

### Data Sources

#### Stats Cards (Top Row)
**From Controller** (passed via `$cohorts` and `$stats` variables):
```php
Cohort::where('website_id', $websiteId)
    ->with('members')
    ->paginate(20)
```

**Database Query Results**:
- `count($cohorts)` → Active Cohorts
- Calculated from `$stats` → Avg Retention, Customer LTV, Total Members

#### Retention Heatmap Table
JavaScript calls: `GET /cohorts/api/retention-heatmap`

**Controller Method**: `getRetentionHeatmap()` (just added)
```php
// For each cohort, calculates:
$cohort->members->count() // cohort size
calculateRetentionRate($cohort->id, 1)  // Day 1 retention %
calculateRetentionRate($cohort->id, 7)  // Day 7 retention %
calculateRetentionRate($cohort->id, 30) // Day 30 retention %
// etc.
```

**Database Tables**:
- `cohorts` - Cohort definitions
- `cohort_members` - User assignments to cohorts
- `cohort_retention` - Retention tracking data
- `cohort_comparisons` - Comparison results

#### Cohorts List Table
**From Controller** (Blade loop):
```blade
@forelse($cohorts ?? [] as $cohort)
    {{ $cohort->name }}
    {{ $cohort->members_count }}
    {{ $cohort->average_ltv }}
    {{ $cohort->retention_30d }}
@endforelse
```

**How to Populate**:
Add these calculated fields to the Cohort model or controller:
- `members_count` - Count of `cohort_members`
- `average_ltv` - Avg value from `users` or `donations`
- `retention_30d` - % of members active after 30 days

#### Charts (Retention Curve, Comparison, LTV)
**Currently**: Hardcoded demo data in JavaScript
**To Make Real**: Query `cohort_retention` table and pass to chart

---

## 3. 🧪 A/B Testing Dashboard

**URL**: `/ab-tests/`  
**View**: `resources/views/abtests/index.blade.php`  
**Controller**: `app/Http/Controllers/ABTestController.php`

### Data Sources

#### Stats Cards (Top Row)
**From Controller** (passed via `$stats` variable):
```php
$stats = [
    'running' => ABTest::where('status', 'running')->count(),
    'winners' => ABTest::whereNotNull('winner_variant')->count(),
    'total_participants' => ABTestAssignment::count(),
    'avg_lift' => ABTest::avg('conversion_lift_percentage')
];
```

**Database Tables**:
- `ab_tests` - Test configurations
- `ab_test_variants` - Different versions being tested
- `ab_test_assignments` - User assignments to variants
- `ab_test_conversions` - Conversion events
- `ab_test_results` - Statistical results
- `ab_test_events` - All test events

#### Tests List Table
**From Controller** (Blade loop):
```blade
@forelse($tests ?? [] as $test)
    {{ $test->name }}
    {{ $test->status }}
    {{ $test->participants_count }}
    {{ $test->confidence_level }}
@endforelse
```

**How to Populate**:
Controller needs to pass:
```php
$tests = ABTest::with(['variants', 'results'])
    ->withCount('assignments as participants_count')
    ->get();
```

#### Charts (Conversion Trends, Funnel)
**Currently**: Hardcoded demo data in JavaScript
**To Make Real**: Query `ab_test_conversions` grouped by date/variant

---

## 4. 🔥 Heatmaps & Session Recordings Dashboard

**URL**: `/heatmaps/`  
**View**: `resources/views/heatmaps/index.blade.php`  
**Controller**: Simple closure (returns view directly)

### Data Sources

**Currently**: 100% static/demo HTML

**To Make Real**, you need to integrate a real heatmap service:

#### Option A: Use Existing Hotjar Routes
Routes already exist:
- `/hotjar/recordings` - Session recordings list
- `/hotjar/heatmaps` - Heatmap data
- Uses `HotjarViewController`

#### Option B: Integrate Third-Party Service
Popular options:
- **Hotjar** - Add tracking script, pull data via API
- **Microsoft Clarity** - Free, includes heatmaps + recordings
- **FullStory** - Enterprise option
- **Mouseflow** - Mid-tier option

#### Option C: Build Custom Tracking
Create tables:
- `session_recordings` - Video replay data
- `heatmap_clicks` - X/Y coordinates of clicks
- `heatmap_scrolls` - Scroll depth per page
- `rage_clicks` - Frustrated user behavior

---

## 🔄 How to Populate Dashboards with Real Data

### Step 1: Fraud Detection

**Already Working!** Just needs transactions to detect:

1. Create fraud rules in database:
```sql
INSERT INTO fraud_rules (name, rule_type, conditions, action, is_active) 
VALUES ('High Value Check', 'amount', '{"threshold": 5000}', 'review', 1);
```

2. When processing donations, call:
```php
$fraudService->analyzeDonation($donation);
```

3. Dashboard will automatically show results

### Step 2: Cohort Analysis

1. Create cohorts:
```php
POST /cohorts
{
    "name": "January 2024 Donors",
    "type": "by_date",
    "definition": {"month": "2024-01"},
    "start_date": "2024-01-01",
    "end_date": "2024-01-31"
}
```

2. Service automatically populates `cohort_members` from users table

3. Calculate retention:
```php
POST /cohorts/{id}/retention
```

4. Dashboard shows real retention rates in heatmap

### Step 3: A/B Testing

1. Create a test:
```php
POST /ab-tests
{
    "name": "Button Color Test",
    "test_type": "element",
    "page_url": "/donate",
    "control_variant": {"color": "blue"},
    "variants": [
        {"name": "Green Button", "config": {"color": "green"}},
        {"name": "Red Button", "config": {"color": "red"}}
    ]
}
```

2. Start test:
```php
POST /ab-tests/{id}/start
```

3. Track user assignments:
```php
POST /ab-tests/{id}/assign
{"user_id": 123, "variant_id": 2}
```

4. Track conversions:
```php
POST /ab-tests/{id}/conversion
{"user_id": 123, "variant_id": 2}
```

5. Calculate results:
```php
POST /ab-tests/{id}/calculate
```

6. Dashboard shows winner with confidence level

### Step 4: Heatmaps

**Easiest Option**: Add Microsoft Clarity (Free)

1. Sign up at clarity.microsoft.com
2. Add tracking script to your layout
3. Wait 24 hours for data
4. Use Clarity's built-in dashboard
5. OR pull data via their API to your dashboard

**Alternative**: Keep the demo dashboard as a "Coming Soon" feature

---

## 🎯 Quick Start: Demo Data

Want to test dashboards immediately? Add sample data:

### Fraud Detection Sample Data
```php
// Run this in tinker: php artisan tinker

// Create sample fraud rule
\App\Models\FraudRule::create([
    'name' => 'High Amount Alert',
    'rule_type' => 'amount',
    'conditions' => json_encode(['threshold' => 5000]),
    'action' => 'review',
    'is_active' => true,
    'priority' => 100
]);

// Create sample fraud detections
for ($i = 0; $i < 50; $i++) {
    \App\Models\FraudDetection::create([
        'fraud_rule_id' => 1,
        'user_id' => rand(1, 100),
        'transaction_id' => 'TXN-' . rand(1000, 9999),
        'risk_score' => rand(20, 100),
        'risk_level' => ['low', 'medium', 'high', 'critical'][rand(0, 3)],
        'action_taken' => ['allowed', 'flagged', 'blocked'][rand(0, 2)],
        'detection_reason' => 'Sample detection reason',
        'amount' => rand(100, 10000),
        'is_false_positive' => false,
        'reviewed_at' => null,
    ]);
}
```

### Cohort Analysis Sample Data
```php
// Create sample cohort
$cohort = \App\Models\Cohort::create([
    'website_id' => 1,
    'name' => 'January 2024 Donors',
    'cohort_type' => 'registration_date',
    'definition' => json_encode(['month' => '2024-01']),
    'start_date' => '2024-01-01',
    'end_date' => '2024-01-31',
    'is_active' => true
]);

// Add members to cohort
$users = \App\Models\User::whereBetween('created_at', ['2024-01-01', '2024-01-31'])->get();
foreach ($users as $user) {
    \App\Models\CohortMember::create([
        'cohort_id' => $cohort->id,
        'user_id' => $user->id,
        'joined_at' => $user->created_at
    ]);
}
```

### A/B Testing Sample Data
```php
// Create sample test
$test = \App\Models\ABTest::create([
    'website_id' => 1,
    'name' => 'Donate Button Color',
    'test_type' => 'element',
    'hypothesis' => 'Green button will convert better',
    'page_url' => '/donate',
    'traffic_allocation' => 100,
    'status' => 'running',
    'started_at' => now(),
    'confidence_threshold' => 95
]);

// Create variants
$control = \App\Models\ABTestVariant::create([
    'ab_test_id' => $test->id,
    'name' => 'Control (Blue)',
    'is_control' => true,
    'configuration' => json_encode(['color' => 'blue']),
    'traffic_percentage' => 50
]);

$variant = \App\Models\ABTestVariant::create([
    'ab_test_id' => $test->id,
    'name' => 'Variant (Green)',
    'is_control' => false,
    'configuration' => json_encode(['color' => 'green']),
    'traffic_percentage' => 50
]);

// Add sample conversions
for ($i = 0; $i < 100; $i++) {
    $variantId = rand(0, 1) ? $control->id : $variant->id;
    
    \App\Models\ABTestConversion::create([
        'ab_test_id' => $test->id,
        'ab_test_variant_id' => $variantId,
        'user_id' => rand(1, 100),
        'session_id' => 'sess-' . rand(1000, 9999),
        'converted' => rand(0, 100) < 30, // 30% conversion rate
        'conversion_value' => rand(0, 100) < 30 ? rand(25, 500) : null,
        'event_type' => 'donation'
    ]);
}
```

---

## 📋 Summary

| Dashboard | Data Status | Next Steps |
|-----------|-------------|------------|
| **Fraud Detection** | ✅ Fully connected | Just needs transactions to monitor |
| **Cohort Analysis** | ✅ API ready | Create cohorts, calculate retention |
| **A/B Testing** | ⚠️ Needs data | Create tests, track conversions |
| **Heatmaps** | ❌ Demo only | Integrate Clarity or build tracking |

**Best Approach**: 
1. Add sample data using scripts above
2. Test all dashboards with fake data
3. Show to client for approval
4. Integrate with real donation system
5. Watch metrics in real-time!

All the infrastructure is built - you just need to populate the tables! 🎉
