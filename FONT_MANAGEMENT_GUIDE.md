# Custom Font Management System - Developer Guide

## Overview
The custom font management system allows administrators to upload custom fonts (TTF, OTF, WOFF, WOFF2) and automatically makes them available in all CKEditor instances throughout the site.

## Components

### 1. Database
- **Table**: `custom_fonts`
- **Fields**:
  - `font_name` - Display name shown in font selector
  - `font_family` - CSS font-family identifier (auto-generated slug)
  - `file_path` - Storage path to font file
  - `file_format` - File extension (ttf, otf, woff, woff2)
  - `file_size` - Size in bytes
  - `is_active` - Enable/disable font

### 2. Routes
- `GET /admin/fonts` - Font management page
- `POST /admin/fonts` - Upload new font
- `PATCH /admin/fonts/{id}/toggle` - Toggle font active status
- `DELETE /admin/fonts/{id}` - Delete font
- `GET /fonts/custom.css` - Public CSS endpoint (generates @font-face rules)

### 3. Files
- **Controller**: `app/Http/Controllers/Admin/FontController.php`
- **Model**: `app/Models/CustomFont.php`
- **Views**: `resources/views/admin/fonts/index.blade.php`
- **Migration**: `database/migrations/2025_11_11_164509_create_custom_fonts_table.php`
- **JavaScript**: `public/js/ckeditor-custom-fonts.js`

## How It Works

### Font Upload Flow
1. Admin uploads font file via `/admin/fonts`
2. File stored in `storage/app/public/fonts/`
3. Database record created with metadata
4. Font immediately available in all editors

### Font Loading Flow
1. Page loads with `<link href="/fonts/custom.css">`
2. CSS endpoint generates @font-face rules for all active fonts
3. JavaScript (`ckeditor-custom-fonts.js`) fetches font list
4. CKEditor instances are configured with custom fonts in dropdown

### Automatic Integration
The system automatically enhances ALL CKEditor instances on pages that include:
- `resources/views/admin/main.blade.php`
- `resources/views/user/main.blade.php`

## Usage in CKEditor

### Method 1: Automatic (Recommended)
Simply create CKEditor instances as usual. Custom fonts will be automatically added:

```javascript
ClassicEditor
    .create(document.querySelector('#description'))
    .catch(error => {
        console.error(error);
    });
```

### Method 2: Enhanced Wrapper
Use the enhanced wrapper for explicit font configuration:

```javascript
ClassicEditorWithFonts
    .create(document.querySelector('#description'))
    .then(editor => {
        console.log('Editor with custom fonts initialized', editor);
    })
    .catch(error => {
        console.error(error);
    });
```

### Method 3: Manual Configuration
For complete control, manually configure fonts:

```javascript
ClassicEditor
    .create(document.querySelector('#description'), {
        fontFamily: {
            options: [
                'my-custom-font/my-custom-font, sans-serif',
                'Arial/Arial, Helvetica, sans-serif',
                // ... more fonts
            ],
            supportAllValues: true
        },
        toolbar: [
            'heading', '|',
            'fontFamily', 'fontSize', '|',
            'bold', 'italic', 'underline', '|',
            // ... more toolbar items
        ]
    })
    .catch(error => {
        console.error(error);
    });
```

## File Format Recommendations

### Best Performance
1. **WOFF2** - Smallest size, best compression, modern browser support
2. **WOFF** - Good size, wide browser support
3. **TTF/OTF** - Larger files, use only if WOFF2/WOFF not available

### File Size Guidelines
- **Optimal**: < 200KB per font
- **Acceptable**: 200KB - 500KB
- **Large**: > 500KB (consider subsetting or alternative format)

## Storage Structure
```
storage/
  └── app/
      └── public/
          └── fonts/
              ├── my-custom-font-1234567890.woff2
              ├── another-font-1234567891.ttf
              └── ...

public/
  └── storage/  (symlink to storage/app/public)
      └── fonts/
          └── (same files via symlink)
```

## Admin Interface Features

### Upload Page (`/admin/fonts`)
- ✅ Drag-and-drop font upload
- ✅ Live font preview with sample text
- ✅ File format and size display
- ✅ Toggle active/inactive status
- ✅ Delete fonts with confirmation
- ✅ Created date for each font

### Font Preview
Each uploaded font shows:
- Font name and family
- File format and size
- Upload date
- Live preview with:
  - "The quick brown fox jumps over the lazy dog"
  - Full alphabet (uppercase/lowercase)
  - Numbers 0-9
  - CSS font-family code

## CSS Generation

The `/fonts/custom.css` endpoint generates:

```css
/* Custom Fonts - Generated on 2025-11-11 */

@font-face {
    font-family: 'my-custom-font';
    src: url('https://example.com/storage/fonts/my-custom-font-1234567890.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'another-font';
    src: url('https://example.com/storage/fonts/another-font-1234567891.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}
```

## Troubleshooting

### Fonts not appearing in editor
1. Check font is marked as "Active" (green badge)
2. Clear browser cache and hard refresh (Ctrl+F5)
3. Check browser console for errors
4. Verify storage link exists: `php artisan storage:link`

### Font file upload fails
1. Check file size (must be < 10MB)
2. Verify file format (TTF, OTF, WOFF, WOFF2 only)
3. Check storage permissions: `chmod -R 775 storage/`
4. Verify disk space available

### Font displays incorrectly
1. Ensure font file is not corrupted
2. Try different font format (WOFF2 preferred)
3. Check CSS is loading: visit `/fonts/custom.css`
4. Inspect element to verify font-family applied

## Security Considerations

### File Validation
- ✅ File extension whitelist (ttf, otf, woff, woff2)
- ✅ MIME type validation
- ✅ File size limit (10MB max)
- ✅ Unique font family names (prevents duplicates)

### Access Control
- ✅ Upload/delete restricted to admin users only
- ✅ Font CSS publicly accessible (required for frontend)
- ✅ Font files served from storage (can be CDN-cached)

## Performance Optimization

### Caching
The CSS endpoint includes cache headers:
```
Cache-Control: public, max-age=3600
```

### Font Loading
Uses `font-display: swap` to prevent FOIT (Flash of Invisible Text):
- Shows fallback font immediately
- Swaps to custom font when loaded
- Improves perceived performance

## Browser Compatibility

### Supported Formats
| Format | Chrome | Firefox | Safari | Edge |
|--------|--------|---------|--------|------|
| WOFF2  | ✅ 36+  | ✅ 39+  | ✅ 12+ | ✅ 14+ |
| WOFF   | ✅ All  | ✅ 3.6+ | ✅ 5.1+| ✅ All |
| TTF    | ✅ All  | ✅ 3.5+ | ✅ All | ✅ All |
| OTF    | ✅ All  | ✅ 3.5+ | ✅ All | ✅ All |

## Future Enhancements (Optional)

### Possible Features
- [ ] Font variant support (bold, italic, weights)
- [ ] Font subsetting to reduce file size
- [ ] Font family grouping (Regular, Bold, Italic)
- [ ] Google Fonts integration
- [ ] Font preview with custom text
- [ ] Bulk font upload
- [ ] Font usage analytics
- [ ] CDN integration

## API Reference

### FontController Methods

#### `index()`
Display font management page with all uploaded fonts.

#### `store(Request $request)`
Upload and save new font file.
- **Validates**: font_name, font_file
- **Returns**: Redirect with success/error message

#### `toggle($id)`
Toggle font active/inactive status.
- **Returns**: Redirect with success message

#### `destroy($id)`
Delete font and its file.
- **Returns**: Redirect with success message

#### `css()`
Generate CSS with @font-face rules for all active fonts.
- **Returns**: text/css response with cache headers

## Integration Examples

### Page Builder Integration
Fonts automatically available in page builder text editors.

### Email Template Editor
Include custom fonts CSS in email template editor:
```html
<link rel="stylesheet" href="{{ route('fonts.css') }}">
```

### Frontend Integration
Use custom fonts in frontend templates:
```html
<style>
    .custom-heading {
        font-family: 'my-custom-font', sans-serif;
    }
</style>
```

## Maintenance

### Database Cleanup
Remove orphaned font records:
```sql
DELETE FROM custom_fonts 
WHERE file_path NOT IN (
    SELECT CONCAT('fonts/', file_name) 
    FROM files_in_storage
);
```

### Storage Cleanup
Remove font files not in database:
```bash
cd storage/app/public/fonts
# List files not in database
# Manually delete orphaned files
```

### Regular Tasks
- Monitor storage usage
- Archive old/unused fonts
- Update documentation
- Test on multiple browsers

## Support
For issues or questions:
1. Check troubleshooting section above
2. Review browser console for errors
3. Verify file permissions and storage
4. Contact system administrator
