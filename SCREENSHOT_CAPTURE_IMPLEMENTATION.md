# Server-Side Screenshot Capture Implementation

## Overview

This implementation replaces client-side screenshot capture with a more reliable server-side solution using Browsershot (Puppeteer). Screenshots are now captured automatically whenever a page is saved from the page builder.

## What Changed

### 1. Heatmap Data Filtering ✅
**Modified Files:**
- `app/Http/Controllers/HotjarViewController.php`

**Changes:**
- `getPopularPages()`: Now only returns page-builder pages (pages with `state`)
- `getClickHeatmap()`: Validates page is from page-builder before returning data
- `getMoveHeatmap()`: Validates page is from page-builder before returning data
- `getScrollDepth()`: Validates page is from page-builder before returning data

**Result:** Heatmaps now ONLY show data for dynamic pages created from page-builder. Static pages (product.blade.php, product-details.blade.php, property-details.blade.php, invest.blade.php) are excluded.

### 2. Server-Side Screenshot Capture ✅
**New Files Created:**
- `app/Services/ScreenshotService.php` - Core screenshot capture logic
- `app/Jobs/CapturePageScreenshot.php` - Async queue job for screenshots

**Modified Files:**
- `app/Http/Controllers/Api/PageBuilderController.php` - Added screenshot trigger

**New Package:**
- `spatie/browsershot` (v5.2.0) - Installed via Composer

## How It Works

### Capture Flow

```
User Saves Page in Builder
         ↓
PageBuilderController@save()
         ↓
State Saved to Database
         ↓
CapturePageScreenshot Job Dispatched (5 second delay)
         ↓
Queue Worker Processes Job
         ↓
ScreenshotService@capturePageScreenshot()
         ↓
1. Validates page has builder state
2. Builds full URL (e.g., https://yoursite.com/page/about-us)
3. Uses Browsershot to load page server-side
4. Waits for page load (2 seconds + network idle)
5. Captures full-page screenshot (1920x1080 viewport)
6. Saves to storage/app/public/screenshots/
7. Updates page_screenshots table
         ↓
Screenshot Available for Heatmap Overlay
```

### Technical Details

**Screenshot Settings:**
- Window Size: 1920x1080 (desktop viewport)
- Delay: 2000ms after page load
- Full Page: Yes (captures entire page height)
- Wait Condition: Network idle (all requests complete)
- Auto-dismiss dialogs: Yes

**Storage:**
- Path: `storage/app/public/screenshots/`
- Format: PNG
- Naming: `page_{id}_{timestamp}.png`
- Database: `page_screenshots` table stores metadata

## Setup Requirements

### 1. Install Node.js & Puppeteer

Browsershot requires Node.js and Puppeteer to be installed on the server:

```bash
# Install Node.js (if not already installed)
# Windows: Download from https://nodejs.org/

# Install Puppeteer globally
npm install -g puppeteer
```

### 2. Configure Queue Worker

Since screenshots are captured asynchronously, you need a queue worker running:

**Option A: Sync Queue (Development)**
Add to `.env`:
```env
QUEUE_CONNECTION=sync
```
Screenshots will be captured immediately (blocks page save for ~3-5 seconds).

**Option B: Database Queue (Production - RECOMMENDED)**
Add to `.env`:
```env
QUEUE_CONNECTION=database
```

Then run the queue worker:
```bash
php artisan queue:work --tries=3 --timeout=120
```

Keep this running in the background (use supervisor, systemd, or Task Scheduler on Windows).

### 3. Verify Storage Link

Ensure the storage link is created:
```bash
php artisan storage:link
```

This creates a symlink: `public/storage` → `storage/app/public`

### 4. Run Queue Migration

If not already done:
```bash
php artisan migrate
```

This ensures the `jobs` table exists.

## Usage

### Automatic Capture

Screenshots are captured automatically whenever you:
1. Open the page builder
2. Make changes to a page
3. Click "Save"

The screenshot is captured 5 seconds after save to ensure the database is updated.

### Manual Capture (Optional)

You can manually capture screenshots via code:

```php
use App\Services\ScreenshotService;

$screenshotService = new ScreenshotService();
$success = $screenshotService->capturePageScreenshot($pageId);
```

Or dispatch the job manually:

```php
use App\Jobs\CapturePageScreenshot;

CapturePageScreenshot::dispatch($pageId);
```

## Benefits vs Client-Side

| Feature | Client-Side (Old) | Server-Side (New) |
|---------|------------------|-------------------|
| Reliability | ❌ Inconsistent | ✅ Reliable |
| User Disruption | ❌ Auto-scroll, delays | ✅ No impact |
| Full Page Capture | ⚠️ Sometimes | ✅ Always |
| Cross-Origin Content | ❌ CORS issues | ✅ Full access |
| Dynamic Content | ⚠️ Timing issues | ✅ Waits for load |
| Header/Footer | ⚠️ Sometimes missing | ✅ Always included |
| Performance | ❌ Slows user browser | ✅ Server handles it |

## Monitoring & Debugging

### Check Job Status

```bash
# View failed jobs
php artisan queue:failed

# Retry failed job
php artisan queue:retry {job-id}

# Clear failed jobs
php artisan queue:flush
```

### Check Logs

Screenshot captures are logged to `storage/logs/laravel.log`:

```
[INFO] Capturing screenshot for page 123: http://yoursite.com/page/about-us
[INFO] Screenshot captured successfully for page 123
```

Or on failure:
```
[ERROR] Failed to capture screenshot for page 123: Puppeteer error...
```

### Verify Storage

Check screenshots directory:
```bash
dir storage\app\public\screenshots
```

Should see PNG files like:
- `page_123_1642345678.png`
- `page_456_1642345890.png`

## Troubleshooting

### Issue: Screenshots not being captured

**Check 1: Is queue worker running?**
```bash
php artisan queue:work
```

**Check 2: Is Puppeteer installed?**
```bash
npm list -g puppeteer
```

**Check 3: Check failed jobs**
```bash
php artisan queue:failed
```

### Issue: Blank/white screenshots

**Solution:** Increase delay in ScreenshotService.php:
```php
->setDelay(5000) // Change from 2000 to 5000 (5 seconds)
```

### Issue: Screenshots too small

**Solution:** Increase window size in ScreenshotService.php:
```php
->windowSize(2560, 1440) // Change from 1920x1080
```

### Issue: Job timeout

**Solution:** Increase timeout when running queue worker:
```bash
php artisan queue:work --timeout=300
```

## Files Changed Summary

### Modified
1. `app/Http/Controllers/HotjarViewController.php` - Added page-builder filtering
2. `app/Http/Controllers/Api/PageBuilderController.php` - Added screenshot dispatch

### Created
1. `app/Services/ScreenshotService.php` - Screenshot capture service
2. `app/Jobs/CapturePageScreenshot.php` - Queue job for async processing

### Dependencies
1. `spatie/browsershot` (v5.2.0) - Added to composer.json

## Next Steps (Optional Enhancements)

1. **Admin UI Button**: Add "Recapture Screenshot" button in page list
2. **Bulk Capture**: Command to capture screenshots for all pages
3. **Webhook**: Trigger screenshot on external events
4. **Mobile Screenshots**: Capture additional mobile viewport screenshots
5. **Screenshot History**: Keep previous versions of screenshots

## Configuration Options

You can customize the ScreenshotService behavior:

### Change Screenshot Quality
```php
->setScreenshotType('jpeg', 90) // JPEG at 90% quality
```

### Change Viewport Size
```php
->windowSize(1366, 768) // Tablet size
->windowSize(375, 667)  // Mobile size
```

### Disable JavaScript
```php
->noSandbox() // For Docker environments
->setOption('args', ['--disable-web-security']) // Bypass security
```

## Production Checklist

- [x] Browsershot package installed
- [x] ScreenshotService created
- [x] Queue job created
- [x] PageBuilderController hooked
- [x] Heatmap filtering implemented
- [ ] Node.js installed on server
- [ ] Puppeteer installed on server
- [ ] Queue worker configured (supervisor/systemd)
- [ ] Storage directory permissions set (775)
- [ ] `.env` QUEUE_CONNECTION set
- [ ] Storage link created
- [ ] Test screenshot capture working
- [ ] Monitor logs for errors

## Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check queue failed jobs: `php artisan queue:failed`
3. Verify Puppeteer installation: `npm list -g puppeteer`
4. Test Browsershot directly: `Browsershot::url('http://google.com')->save('test.png')`
