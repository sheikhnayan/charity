# Homepage Feature Implementation

## Overview
The homepage feature allows pages to be designated as the homepage for their respective websites. When a page is marked as homepage:
- It's accessible via the domain root (e.g., `domain.com` instead of `domain.com/page/page-slug`)
- The page title displays as "Home" instead of the actual page name
- Only ONE homepage can exist per website (automatically enforced)

---

## Database Changes

### Migration: `2025_12_12_152056_add_is_homepage_to_pages_table.php`

Added new field to the `pages` table:
- `is_homepage` (boolean, default: false) - Indicates if page is the homepage
- Index on `(website_id, is_homepage)` for faster queries
- Automatically migrated existing `default = 1` pages to `is_homepage = true`

**Run Migration:**
```bash
php artisan migrate
```

---

## Model Updates

### Page Model (`app/Models/Page.php`)

#### New Fillable Field:
```php
'is_homepage'
```

#### New Cast:
```php
'is_homepage' => 'boolean'
```

#### New Methods:

**1. `setAsHomepage()`**
- Sets the current page as homepage
- Automatically removes homepage status from all other pages of the same website
- Works for both main site pages and website-specific pages
- Uses database transaction for data integrity

```php
$page->setAsHomepage();
```

**2. `removeHomepageStatus()`**
- Removes homepage status from the current page
- Sets `is_homepage = false` and `default = 0`

```php
$page->removeHomepageStatus();
```

**3. `getDisplayTitle()`**
- Returns "Home" if page is homepage
- Returns actual page name if not homepage

```php
echo $page->getDisplayTitle(); // "Home" or "About Us"
```

**4. Scope: `homepage()`**
- Query scope to filter homepage pages

```php
$homepages = Page::homepage()->get();
```

---

## Controller Updates

### PageBuilderController (`app/Http/Controllers/Api/PageBuilderController.php`)

#### `store()` Method:
- Sets `is_homepage` based on `default` field value
- If `default == 1`, automatically calls `setAsHomepage()` to ensure only one homepage per website
- Works for both main site and website-specific pages

#### `update()` Method:
- Tracks homepage status changes
- If changing from non-homepage to homepage, calls `setAsHomepage()`
- If changing from homepage to non-homepage, calls `removeHomepageStatus()`

### FrontendController (`app/Http/Controllers/FrontendController.php`)

#### `index()` Method:
- Updated to prioritize `is_homepage = true` over legacy `default = 1`
- Fallback to `default` field for backward compatibility

```php
$data = Page::where('user_id', $user_id)
            ->where(function($query) {
                $query->where('is_homepage', true)
                      ->orWhere('default', 1);
            })
            ->orderBy('is_homepage', 'desc')
            ->first();
```

---

## View Updates

### 1. Page Creation Form (`resources/views/admin/page/create.blade.php`)

**Homepage Selection:**
```blade
<select name="default" id="homepage_select" class="form-control" required>
    <option value="0">No</option>
    <option value="1">Yes</option>
</select>
```

**Features:**
- Icon indicator (home icon)
- Helper text explaining homepage behavior
- Warning alert when selecting "Yes" (shown via JavaScript)
- Alert explains that other pages will lose homepage status

**JavaScript:**
- Shows/hides warning when homepage is selected
- Warning only displays when `value === '1'`

---

### 2. Page Edit Form (`resources/views/admin/page/edit.blade.php`)

**Homepage Selection:**
```blade
<select name="default" id="homepage_select" class="form-control" required>
    <option {{ $data->default == 0 ? 'selected' : '' }} value="0">No</option>
    <option {{ $data->default == 1 ? 'selected' : '' }} value="1">Yes</option>
</select>
```

**Features:**
- Shows success alert if page is currently the homepage
- Shows warning alert when changing TO homepage (only if not already homepage)
- Helper text explaining homepage behavior

**JavaScript:**
- Tracks current homepage status
- Shows warning only when changing a non-homepage page to homepage
- Prevents unnecessary warning for pages already marked as homepage

---

### 3. Page Index (`resources/views/admin/page/index.blade.php`)

**Main Site Pages Table:**
- Shows "Homepage" badge next to page name if `is_homepage = true`
- URL column displays `fundconnects.com` instead of full path for homepage
- Success message: "Accessible via domain root"

**Website Pages Table:**
- Shows "Homepage" badge next to page name if `is_homepage = true`

**Badge Styling:**
```blade
@if($item->is_homepage)
    <span class="badge bg-primary ms-1">
        <i class="fas fa-home me-1"></i>Homepage
    </span>
@endif
```

---

### 4. Page Investment Template (`resources/views/page-investment.blade.php`)

**Title Update:**
```blade
<title>{{ $data && $data->is_homepage ? 'Home' : ($data->name ?? 'Page') }}</title>
```

**Behavior:**
- Homepage pages show "Home" as title
- Non-homepage pages show their actual name
- Fallback to "Page" if no data exists

---

## How It Works

### Creating a Homepage:

1. **Admin goes to Create Page**
2. **Fills in page details**
3. **Selects "Make Homepage" = Yes**
4. **Clicks Submit**

**What Happens:**
```php
// PageBuilderController@store
$add->is_homepage = ($request->default == 1);
$add->save();

if ($add->is_homepage) {
    $add->setAsHomepage(); // Removes homepage from other pages
}
```

**Result:**
- New page is created as homepage
- All other pages of that website have `is_homepage = false` and `default = 0`
- Only ONE homepage per website

---

### Editing Homepage Status:

1. **Admin edits existing page**
2. **Changes "Make Homepage" from No to Yes**
3. **Clicks Update**

**What Happens:**
```php
// PageBuilderController@update
$newHomepageStatus = ($request->default == 1);
$oldHomepageStatus = $update->is_homepage;

if ($newHomepageStatus && !$oldHomepageStatus) {
    $update->setAsHomepage(); // Removes homepage from other pages
}
```

**Result:**
- Current page becomes homepage
- Previous homepage (if any) loses homepage status
- Automatic enforcement - no manual cleanup needed

---

### Accessing Homepage:

**User visits:** `yourdomain.com`

**What Happens:**
```php
// FrontendController@index
$data = Page::where('user_id', $user_id)
            ->where(function($query) {
                $query->where('is_homepage', true)
                      ->orWhere('default', 1);
            })
            ->orderBy('is_homepage', 'desc')
            ->first();
```

**Result:**
- Homepage page is loaded
- Page title shows "Home"
- No slug in URL (just domain.com)

---

## URL Structure

### Homepage Pages:
- **Main Site:** `fundconnects.com` (not `fundconnects.com/page/home`)
- **Website:** `yourdomain.com` (not `yourdomain.com/page/home`)

### Regular Pages:
- **Main Site:** `fundconnects.com/page/about-us`
- **Website:** `yourdomain.com/page/about-us`

---

## Backward Compatibility

The system maintains backward compatibility with the legacy `default` field:

1. **Migration** automatically converts `default = 1` to `is_homepage = true`
2. **Controller** sets both fields when creating/updating pages
3. **Frontend** checks both fields (prioritizes `is_homepage`)

**Why Both Fields?**
- `default` (legacy) - For existing code that may still reference it
- `is_homepage` (new) - Clear naming and boolean type for better clarity

---

## Testing Checklist

### ✅ Test Homepage Creation:
1. Create a new page
2. Set "Make Homepage" = Yes
3. Verify page is created successfully
4. Check database: `is_homepage = 1` and `default = 1`

### ✅ Test Single Homepage Enforcement:
1. Create Page A as homepage
2. Create Page B as homepage
3. Verify Page A is no longer homepage (`is_homepage = 0`)
4. Verify only Page B is homepage

### ✅ Test Homepage URL Access:
1. Set a page as homepage
2. Visit domain root (e.g., `yourdomain.com`)
3. Verify homepage loads correctly
4. Verify page title shows "Home"

### ✅ Test Regular Page URL:
1. Create a non-homepage page (e.g., "About Us")
2. Visit `yourdomain.com/page/about-us`
3. Verify page loads correctly
4. Verify page title shows "About Us" (not "Home")

### ✅ Test Homepage Badge Display:
1. Go to Admin → Pages → Index
2. Find homepage page
3. Verify "Homepage" badge is displayed
4. Verify URL shows domain root only

### ✅ Test Edit Homepage:
1. Edit existing homepage
2. Change "Make Homepage" from Yes to No
3. Update successfully
4. Verify website no longer has a homepage

### ✅ Test Change Homepage:
1. Website has Page A as homepage
2. Edit Page B and set as homepage
3. Verify Page A loses homepage status
4. Verify Page B is now homepage
5. Visit domain root - Page B should load

---

## Key Features Summary

✅ **Single Homepage Enforcement** - Automatic, no manual management needed
✅ **Clean URLs** - Homepage accessible via domain root only
✅ **Display Title** - Shows "Home" for homepage pages
✅ **Visual Indicators** - Homepage badge in admin panel
✅ **Database Transaction** - Data integrity maintained
✅ **Backward Compatible** - Works with existing `default` field
✅ **Website Scoped** - Each website can have its own homepage
✅ **Main Site Support** - Works for both main site and website-specific pages

---

## Code Examples

### Check if Page is Homepage:
```php
if ($page->is_homepage) {
    echo "This is the homepage!";
}
```

### Get Homepage for a Website:
```php
$homepage = Page::where('website_id', $websiteId)
                ->homepage()
                ->first();
```

### Set Page as Homepage:
```php
$page = Page::find($pageId);
$page->setAsHomepage();
```

### Remove Homepage Status:
```php
$page->removeHomepageStatus();
```

### Display Title in Blade:
```blade
<h1>{{ $page->getDisplayTitle() }}</h1>
<!-- Shows "Home" for homepage, actual name for others -->
```

---

## Troubleshooting

### Issue: Multiple Pages Marked as Homepage

**Solution:**
```php
// Find all homepage pages for a website
$homepages = Page::where('website_id', $websiteId)
                 ->where('is_homepage', true)
                 ->get();

// Keep first one as homepage, remove others
$homepages->first()->setAsHomepage();
```

### Issue: Homepage Not Loading

**Check:**
1. Is `is_homepage = 1` in database?
2. Is page `status = 1` (active)?
3. Is website's `site_status = 1`?

### Issue: Page Title Not Showing "Home"

**Check:**
1. Verify `is_homepage = true` in database
2. Check blade template uses: `{{ $data && $data->is_homepage ? 'Home' : $data->name }}`

---

## Future Enhancements (Optional)

- [ ] Add homepage indicator in page builder
- [ ] Add API endpoint to get homepage for a website
- [ ] Add homepage quick toggle in page index table
- [ ] Add homepage analytics dashboard
- [ ] Support multiple homepages for different languages

---

## Conclusion

The homepage feature is now fully implemented with:
- Database migration and field
- Model methods for homepage management
- Controller logic for automatic enforcement
- Updated forms with visual indicators
- Frontend display showing "Home" title
- Admin panel badges and indicators

Everything works seamlessly with automatic enforcement of the "one homepage per website" rule! 🎉
