# Quick Setup Guide - Server-Side Screenshots

## Immediate Actions Required

### 1. Install Puppeteer (REQUIRED)
```bash
npm install -g puppeteer
```

### 2. Start Queue Worker
```bash
# Option A: Sync (for immediate testing)
# Add to .env: QUEUE_CONNECTION=sync

# Option B: Database Queue (RECOMMENDED)
# Add to .env: QUEUE_CONNECTION=database
# Then run:
php artisan queue:work --tries=3 --timeout=120
```

### 3. Create Storage Link (if not exists)
```bash
php artisan storage:link
```

## Test It Works

### Quick Test
1. Open page builder for any page
2. Make a small change
3. Click Save
4. Wait 10 seconds
5. Check: `storage/app/public/screenshots/` - should see new PNG file

### Verify Heatmap Filtering
1. Go to heatmap dashboard
2. Verify ONLY page-builder pages appear in list
3. Static pages (product, property-details) should NOT appear

## Troubleshooting

### No screenshots being created?
```bash
# Check if Puppeteer is installed
npm list -g puppeteer

# Check failed jobs
php artisan queue:failed

# Check logs
tail -f storage/logs/laravel.log
```

### Queue worker not running?
```bash
# Start it manually
php artisan queue:work

# Or use sync mode in .env:
QUEUE_CONNECTION=sync
```

## What Was Changed

1. **Heatmap Filtering** - Only shows page-builder pages
2. **Screenshot Capture** - Now server-side, automatic on save
3. **Client-Side Disabled** - Removed unreliable browser screenshot

## Files Changed

- ✅ HotjarViewController.php - Added filtering
- ✅ PageBuilderController.php - Added screenshot trigger
- ✅ ScreenshotService.php - NEW (capture logic)
- ✅ CapturePageScreenshot.php - NEW (queue job)
- ✅ hotjar-tracker.js - Disabled client capture

## Done!

Everything is implemented. Just need to:
1. Install Puppeteer
2. Start queue worker
3. Test it

See `HEATMAP_FILTER_AND_SCREENSHOT_COMPLETE.md` for full details.
