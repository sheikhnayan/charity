# Hotjar-Style Session Recording & Heatmap Integration

Complete guide for integrating session recording and heatmaps into your Laravel application.

## Features Implemented ✅

### Session Recording
- ✅ Full DOM snapshot and incremental updates
- ✅ Mouse movements, clicks, scrolls
- ✅ Form interactions (with privacy masking)
- ✅ Session replay with timeline
- ✅ Rage click detection (3+ clicks in 1 second)
- ✅ Error tracking
- ✅ Session starring, notes, tags
- ✅ Device, browser, location tracking
- ✅ Inactivity detection (auto-complete after 30s)

### Heatmap Tracking
- ✅ Click heatmap
- ✅ Move heatmap (attention zones)
- ✅ Scroll depth tracking
- ✅ Viewport normalization
- ✅ Element click statistics
- ✅ Device filtering
- ✅ 10% sampling for performance

## Installation

### 1. Include rrweb Library (CDN)

Add these scripts to your page layout **BEFORE** `hotjar-tracker.js`:

```html
<!-- Add to your main layout (e.g., resources/views/layouts/app.blade.php) -->
<head>
    <!-- rrweb for session recording (same library Hotjar uses) -->
    <script src="https://cdn.jsdelivr.net/npm/rrweb@2.0.0-alpha.11/dist/rrweb.min.js"></script>
</head>
```

### 2. Include Tracker Script

Add the tracker after rrweb:

```html
<body>
    <!-- Your content -->

    <!-- Hotjar Tracker -->
    <script src="{{ asset('js/hotjar-tracker.js') }}"></script>
    
    <!-- Initialize tracker -->
    <script>
        // Option 1: Auto-initialize with data attribute
        // Just add this anywhere in your HTML:
        // <div data-hotjar-tracker data-website-id="{{ $website->id }}"></div>

        // Option 2: Manual initialization
        window.hotjarTracker = new HotjarTracker({{ $website->id }}, {
            sampleRate: 1.0, // Record 100% of sessions (set to 0.1 for 10%)
            privacy: {
                maskAllInputs: true, // Mask password/credit card inputs
                maskTextSelector: '[data-mask]', // Mask elements with data-mask attribute
                blockSelector: '[data-block]' // Block elements from recording
            },
            batchSize: 50, // Events per batch
            batchInterval: 10000, // Send events every 10 seconds
            heatmapSampleRate: 0.1 // Track heatmap for 10% of users
        });
    </script>
</body>
```

### 3. Example Integration in Blade

```blade
<!DOCTYPE html>
<html>
<head>
    <title>{{ $website->title }}</title>
    
    <!-- rrweb library -->
    <script src="https://cdn.jsdelivr.net/npm/rrweb@2.0.0-alpha.11/dist/rrweb.min.js"></script>
</head>
<body>
    <!-- Your content -->
    <div class="container">
        <h1>Welcome to {{ $website->title }}</h1>
        
        <!-- Sensitive data? Add data-mask attribute -->
        <div class="user-info" data-mask>
            Email: {{ $user->email }}
        </div>
        
        <!-- Block entire sections from recording -->
        <div class="payment-form" data-block>
            <!-- This won't be recorded -->
        </div>
    </div>

    <!-- Hotjar Tracker (auto-initialize) -->
    <div data-hotjar-tracker data-website-id="{{ $website->id }}"></div>
    <script src="{{ asset('js/hotjar-tracker.js') }}"></script>
</body>
</html>
```

## API Endpoints

### Session Recording

```javascript
// All endpoints under /api/session-recording/

POST /api/session-recording/start
{
    "session_id": "session_123",
    "website_id": 1,
    "visitor_id": "visitor_456",
    "url": "https://example.com/page",
    "viewport_width": 1920,
    "viewport_height": 1080,
    "device_type": "desktop"
}

POST /api/session-recording/events
{
    "session_id": "session_123",
    "website_id": 1,
    "events": [
        { "timestamp": 1000, "type": 2, "data": {...} }
    ]
}

POST /api/session-recording/complete
{
    "session_id": "session_123",
    "website_id": 1,
    "duration_ms": 45000
}

GET /api/session-recording/{recordingId}
// Returns session with events for playback

GET /api/session-recording?website_id=1&status=completed&has_rage_clicks=true
// List sessions with filters
```

### Heatmap

```javascript
// All endpoints under /api/heatmap/

POST /api/heatmap/track
{
    "website_id": 1,
    "page_url": "https://example.com/page",
    "event_type": "click",
    "x": 100,
    "y": 200,
    "viewport_width": 1920,
    "viewport_height": 1080,
    "element_selector": "button.submit"
}

POST /api/heatmap/track/batch
{
    "events": [...]
}

GET /api/heatmap/click?website_id=1&page_path=/page&days=7
// Returns click heatmap data

GET /api/heatmap/move?website_id=1&page_path=/page
// Returns move/attention heatmap

GET /api/heatmap/scroll?website_id=1&page_path=/page
// Returns scroll depth statistics

GET /api/heatmap/aggregated?website_id=1&page_path=/page&type=click
// Returns normalized heatmap (1440x2400 viewport)
```

## Database Tables

### session_recordings
- `id`, `session_id`, `visitor_id`, `user_id`
- `website_id`, `url`, `page_title`
- `duration_ms`, `event_count`
- `viewport_width`, `viewport_height`
- `device_type`, `browser`, `os`
- `ip_address`, `country`, `country_code`, `state`, `city`
- `status` (recording/completed/archived)
- `has_rage_clicks`, `has_errors`
- `is_starred`, `notes`, `tags`
- `started_at`, `ended_at`

### session_events
- `id`, `session_recording_id`
- `timestamp` (milliseconds since session start)
- `event_type` (0-5 rrweb standard)
- `data` (longText JSON - full rrweb event)
- `action` (click/move/scroll/input)
- `target_element`, `x`, `y`

### heatmap_data
- `id`, `website_id`, `page_url`, `page_path`
- `event_type` (click/move/scroll/attention)
- `x`, `y`, `viewport_width`, `viewport_height`
- `element_selector`, `element_text`, `element_class`, `element_id`
- `scroll_depth`, `max_scroll`, `duration_ms`
- `device_type`, `session_id`, `visitor_id`

## Privacy Features

### Input Masking
```html
<!-- All inputs masked by default -->
<input type="password" name="password"> <!-- Always masked -->
<input type="text" name="credit_card"> <!-- Masked by default -->

<!-- Explicitly mask elements -->
<div data-mask>Sensitive content</div>
```

### Block Elements
```html
<!-- Completely block from recording -->
<div data-block>
    <!-- This entire section won't be recorded -->
    <iframe src="third-party"></iframe>
</div>
```

### Sampling
```javascript
// Only record 10% of sessions for performance
new HotjarTracker(websiteId, {
    sampleRate: 0.1, // 10% of sessions
    heatmapSampleRate: 0.1 // 10% for heatmap
});
```

## Performance Optimization

1. **Batch Processing**: Events sent in batches (50 events or 10 seconds)
2. **Throttling**: Mouse moves throttled to 500ms
3. **Sampling**: Default 100% recording, 10% heatmap
4. **Checkpoints**: Full snapshot every 5 minutes
5. **Inactivity Detection**: Auto-complete after 30 seconds
6. **CDN**: rrweb loaded from jsDelivr CDN

## Service Layer

### SessionRecordingService

```php
use App\Services\SessionRecordingService;

$service = new SessionRecordingService();

// Start session
$recording = $service->startSession([
    'session_id' => 'session_123',
    'website_id' => 1,
    'url' => 'https://example.com'
]);

// Store events
$service->storeEvents($recording, $events);

// Complete session
$service->completeSession($recording, 45000);

// Get for playback
$session = $service->getSessionForPlayback($recordingId);

// List with filters
$sessions = $service->listSessions([
    'website_id' => 1,
    'has_rage_clicks' => true,
    'min_duration' => 10000,
    'device_type' => 'mobile'
]);
```

### HeatmapService

```php
use App\Services\HeatmapService;

$service = new HeatmapService();

// Store event
$service->storeEvent([
    'website_id' => 1,
    'page_url' => 'https://example.com/page',
    'event_type' => 'click',
    'x' => 100,
    'y' => 200
]);

// Get click heatmap
$clicks = $service->getClickHeatmap(1, '/page', ['days' => 7]);

// Get normalized heatmap
$heatmap = $service->getAggregatedHeatmap(1, '/page', 'click');

// Get scroll depth
$scrollData = $service->getScrollHeatmap(1, '/page');

// Get element stats
$stats = $service->getElementClickStats(1, '/page');
```

## Next Steps

### Phase 4: Session Replay Player
- Create replay UI with rrweb-player
- Add timeline with event markers
- Playback controls (play/pause/speed)
- Console logs panel
- Skip inactivity feature

### Phase 5: Heatmap Visualization
- Create heatmap overlay component
- Render click intensity colors
- Move heatmap with attention zones
- Scroll depth visualization
- Device filtering UI

### Phase 6: Dashboard Integration
- Add "Session Recordings" navigation
- Recordings list with filters
- Replay player page
- "Heatmaps" section
- Page selector for heatmaps

## Testing

### Test Session Recording
```javascript
// Open browser console
console.log('Session ID:', hotjarTracker.sessionId);
console.log('Recording:', hotjarTracker.isRecording);

// Trigger rage click (click 3+ times rapidly)
// Check network tab for /api/session-recording/events

// Wait 30 seconds to test auto-complete
// Or close tab to test beforeunload
```

### Test Heatmap
```javascript
// Click around the page
// Check network tab for /api/heatmap/track

// View in database
SELECT * FROM heatmap_data ORDER BY created_at DESC LIMIT 10;
```

### Query Sessions
```sql
-- All sessions with rage clicks
SELECT * FROM session_recordings WHERE has_rage_clicks = 1;

-- Sessions longer than 1 minute
SELECT * FROM session_recordings WHERE duration_ms > 60000;

-- Click heatmap for homepage
SELECT x, y, COUNT(*) as clicks 
FROM heatmap_data 
WHERE page_path = '/' AND event_type = 'click' 
GROUP BY x, y 
ORDER BY clicks DESC;
```

## Architecture Notes

- **rrweb**: Same library Hotjar uses (industry standard)
- **Rage Click Detection**: 3+ clicks within 1 second on same element
- **Event Types**: 0=DomContentLoaded, 1=Load, 2=FullSnapshot, 3=IncrementalSnapshot, 4=Meta, 5=Custom
- **Viewport Normalization**: Heatmaps normalized to 1440x2400 for aggregation
- **Cleanup**: Old recordings deleted after 90 days (starred preserved)

## Troubleshooting

### rrweb not defined
- Make sure rrweb CDN script loads BEFORE hotjar-tracker.js
- Check browser console for 404 errors

### Events not saving
- Check network tab for API errors
- Verify website_id is correct
- Check Laravel logs: `storage/logs/laravel.log`

### High database usage
- Reduce sampleRate (e.g., 0.1 for 10%)
- Run cleanup: `HeatmapService::deleteOldData(30)` for 30 days
- Add indexes on commonly filtered columns
