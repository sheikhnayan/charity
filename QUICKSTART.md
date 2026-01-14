# 🎉 HOTJAR INTEGRATION COMPLETE - QUICK START GUIDE

## ✅ All Phases Completed

1. ✅ Database Structure (session_recordings, session_events, heatmap_data)
2. ✅ Models (SessionRecording, SessionEvent, HeatmapData)
3. ✅ Services (SessionRecordingService, HeatmapService)
4. ✅ API Controllers (SessionRecordingController, HeatmapController)
5. ✅ JavaScript Tracker (hotjar-tracker.js)
6. ✅ Session Replay Player (rrweb-player UI)
7. ✅ Heatmap Visualization (click/move/scroll heatmaps)

## 🚀 Quick Start (3 Steps)

### 1. Test the Demo Page

Visit the demo page to see tracking in action:

```
http://your-domain.com/hotjar/demo
```

This page includes:
- Session recording active
- Click tracking
- Rage click detection
- Form interactions (privacy masked)
- Scroll depth tracking
- Blocked content example

**Actions to try:**
- Click buttons multiple times
- Rapidly click "Rage Click Me!" 3+ times (triggers rage click)
- Fill out the form (password will be masked)
- Scroll to the bottom
- Move your mouse around

### 2. View Session Recordings

After testing the demo, view your recordings:

```
http://your-domain.com/hotjar/recordings
```

**Features:**
- Filter by website, device, duration, rage clicks, errors
- Star important sessions
- Add notes and tags
- Delete unwanted recordings

**Click "Watch" to replay any session with:**
- Full DOM playback
- Timeline with events
- Playback speed controls
- Skip inactivity
- Device/browser/location info

### 3. View Heatmaps

See where users click, move, and scroll:

```
http://your-domain.com/hotjar/heatmaps
```

**Available heatmaps:**
- **Click Heatmap**: See where users click most
- **Move Heatmap**: See where users hover (attention zones)
- **Scroll Depth**: See how far users scroll

**Features:**
- Filter by device type
- Date range selection
- Element click statistics
- Color-coded intensity (red = high, green = low)

## 📝 Add Tracking to Your Pages

### Basic Integration

Add to any page/layout:

```html
<head>
    <!-- rrweb library -->
    <script src="https://cdn.jsdelivr.net/npm/rrweb@2.0.0-alpha.11/dist/rrweb.min.js"></script>
</head>
<body>
    <!-- Your content -->

    <!-- Auto-initialize Hotjar tracking -->
    <div data-hotjar-tracker data-website-id="{{ $website->id }}"></div>
    <script src="{{ asset('js/hotjar-tracker.js') }}"></script>
</body>
```

### Custom Configuration

```html
<script src="{{ asset('js/hotjar-tracker.js') }}"></script>
<script>
    window.hotjarTracker = new HotjarTracker({{ $website->id }}, {
        sampleRate: 0.5, // Record 50% of sessions (1.0 = 100%)
        heatmapSampleRate: 0.1, // Track heatmap for 10%
        privacy: {
            maskAllInputs: true, // Mask all inputs
            maskTextSelector: '[data-mask]', // Mask specific elements
            blockSelector: '[data-block]' // Block from recording
        }
    });
</script>
```

### Privacy Protection

**Mask sensitive data:**
```html
<div data-mask>This content will be masked in recordings</div>
```

**Block entire sections:**
```html
<div data-block>
    <!-- This won't be recorded at all -->
    <p>Credit Card: 1234-5678-9012-3456</p>
</div>
```

**All password inputs are automatically masked!**

## 🔗 Important URLs

| Feature | URL | Auth Required |
|---------|-----|---------------|
| Demo Page | `/hotjar/demo` | No |
| Recordings List | `/hotjar/recordings` | Yes (Admin) |
| Replay Player | `/hotjar/recordings/{id}/replay` | Yes (Admin) |
| Heatmaps | `/hotjar/heatmaps` | Yes (Admin) |

## 📊 API Endpoints

### Session Recording
- `POST /api/session-recording/start` - Start new session
- `POST /api/session-recording/events` - Store events (batch)
- `POST /api/session-recording/complete` - Complete session
- `GET /api/session-recording/{id}` - Get session for playback
- `GET /api/session-recording` - List sessions with filters
- `DELETE /api/session-recording/{id}` - Delete recording
- `POST /api/session-recording/{id}/star` - Toggle star
- `POST /api/session-recording/{id}/meta` - Update notes/tags

### Heatmap
- `POST /api/heatmap/track` - Track single event
- `POST /api/heatmap/track/batch` - Track multiple events
- `GET /api/heatmap/click` - Get click heatmap
- `GET /api/heatmap/move` - Get move heatmap
- `GET /api/heatmap/scroll` - Get scroll depth
- `GET /api/heatmap/aggregated` - Get normalized heatmap
- `GET /api/heatmap/popular-pages` - Get popular pages
- `GET /api/heatmap/element-stats` - Get element click stats

## 🎯 Key Features

### Session Recording
✅ Full DOM snapshots with rrweb (same as Hotjar)
✅ Mouse movements, clicks, scrolls
✅ Form interactions (privacy masked)
✅ Rage click detection (3+ clicks in 1 second)
✅ Error tracking
✅ Device, browser, OS detection
✅ Geographic location (country, state, city)
✅ Session starring, notes, tags
✅ Inactivity detection (auto-complete after 30s)
✅ Playback controls (play/pause/speed/skip inactivity)

### Heatmap Tracking
✅ Click heatmap with intensity colors
✅ Move heatmap (attention zones)
✅ Scroll depth analysis (0-100%)
✅ Element click statistics
✅ Viewport normalization (scales for different screen sizes)
✅ Device filtering (desktop/mobile/tablet)
✅ Date range filtering
✅ 10% sampling for performance

### Privacy & Performance
✅ Password inputs automatically masked
✅ Custom masking with `data-mask`
✅ Block sections with `data-block`
✅ Batch event processing (50 events or 10 seconds)
✅ Throttled mouse tracking (500ms)
✅ Session sampling (configurable)
✅ Heatmap sampling (10% default)
✅ CDN delivery (rrweb from jsDelivr)

## 🧪 Testing Checklist

- [ ] Visit `/hotjar/demo` page
- [ ] Click buttons (should track clicks)
- [ ] Rapid click "Rage Click Me!" 3+ times
- [ ] Fill out form (check password masking)
- [ ] Scroll to bottom (check scroll depth)
- [ ] Check browser Network tab for API calls
- [ ] Visit `/hotjar/recordings` (login required)
- [ ] Find your test session in list
- [ ] Click "Watch" to replay session
- [ ] Check rage click badge appears
- [ ] Visit `/hotjar/heatmaps` (login required)
- [ ] Select website from dropdown
- [ ] Choose demo page from list
- [ ] View click heatmap (red dots where you clicked)
- [ ] Switch to scroll depth view
- [ ] Check element click statistics table

## 📁 File Structure

```
app/
├── Http/Controllers/
│   ├── SessionRecordingController.php
│   ├── HeatmapController.php
│   └── HotjarViewController.php
├── Models/
│   ├── SessionRecording.php
│   ├── SessionEvent.php
│   └── HeatmapData.php
└── Services/
    ├── SessionRecordingService.php
    └── HeatmapService.php

database/migrations/
├── 2025_11_01_170902_create_session_recordings_table.php
├── 2025_11_01_170915_create_session_events_table.php
└── 2025_11_01_170936_create_heatmap_data_table.php

public/js/
└── hotjar-tracker.js

resources/views/hotjar/
├── demo.blade.php
├── recordings/
│   ├── index.blade.php (list)
│   └── replay.blade.php (player)
└── heatmaps/
    └── index.blade.php (visualization)

routes/
├── api.php (API endpoints)
└── web.php (view routes)
```

## 🔧 Configuration Options

**Tracker Configuration:**
```javascript
{
    apiBaseUrl: '/api',
    websiteId: 1, // REQUIRED
    sampleRate: 1.0, // 100% of sessions (0.1 = 10%)
    privacy: {
        maskAllInputs: true,
        maskTextSelector: '[data-mask]',
        blockSelector: '[data-block]'
    },
    batchSize: 50, // Events per batch
    batchInterval: 10000, // 10 seconds
    inactivityThreshold: 30000, // 30 seconds
    heatmapSampleRate: 0.1, // 10% for heatmap
    mouseMoveThrottle: 500 // Track mouse every 500ms
}
```

## 🎨 Customization

### Change Heatmap Colors
Edit `resources/views/hotjar/heatmaps/index.blade.php`:
```javascript
gradient: {
    0.0: 'rgba(0, 255, 0, 0)',   // Transparent green
    0.5: 'rgba(255, 255, 0, 0.7)', // Yellow
    1.0: 'rgba(255, 0, 0, 1)'      // Red
}
```

### Adjust Sample Rates
Edit `public/js/hotjar-tracker.js`:
```javascript
sampleRate: 0.5,        // 50% of sessions
heatmapSampleRate: 0.2  // 20% for heatmaps
```

### Cleanup Old Data
```php
use App\Services\SessionRecordingService;
use App\Services\HeatmapService;

// Delete recordings older than 90 days (keeps starred)
(new SessionRecordingService())->deleteOldRecordings(90);

// Delete heatmap data older than 30 days
(new HeatmapService())->deleteOldData(30);
```

## 🐛 Troubleshooting

**Problem: "rrweb is not defined"**
- Solution: Make sure rrweb CDN loads BEFORE hotjar-tracker.js

**Problem: No recordings appearing**
- Check browser console for errors
- Verify website_id is correct
- Check Network tab for failed API calls
- Ensure migrations ran: `php artisan migrate:status`

**Problem: Heatmap is blank**
- Need at least some interactions on the page
- Check if data exists: `SELECT * FROM heatmap_data LIMIT 10;`
- Verify 10% sampling didn't exclude you (set to 1.0 for testing)

**Problem: High database usage**
- Reduce sampleRate to 0.1 (10%)
- Run cleanup scripts more frequently
- Add database indexes on filtered columns

## 📖 Full Documentation

See `HOTJAR_INTEGRATION.md` for complete documentation including:
- Detailed API reference
- Database schema
- Service layer methods
- Architecture notes
- Performance optimization
- Security best practices

## 🎊 What's Next?

Your Hotjar integration is **100% complete**! You now have:

1. ✅ Full session recording capability
2. ✅ Session replay player with controls
3. ✅ Click/move/scroll heatmaps
4. ✅ Privacy protection (masking/blocking)
5. ✅ Admin interface for viewing data
6. ✅ Comprehensive filtering options
7. ✅ Performance optimization
8. ✅ Demo page for testing

**Start tracking real users:**
1. Add tracking code to your main layout
2. Deploy to production
3. Watch how users interact with your site
4. Identify usability issues
5. Optimize based on real behavior data

Enjoy your enterprise-grade analytics! 🚀
