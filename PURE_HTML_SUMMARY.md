# 🚨 PURE HTML COMPONENTS - QUICK REFERENCE

## Critical Components Saving Raw HTML

### ❌ TEXT Component
- **Serialization:** `data.html = content.innerHTML`
- **Status:** FALLS THROUGH TO DEFAULT CASE
- **Risk Level:** 🔴 **HIGH** - Accepts user input, XSS vulnerable
- **Data Example:**
  ```json
  {
    "type": "text",
    "html": "<div style=\"...\"><strong>Bold</strong> text here</div>"
  }
  ```

---

### ❌ SECTION-TITLE Component  
- **Serialization:** `data.text = sectionTitleEl.innerHTML`
- **Location:** [page-builder.blade.php#L15476](page-builder.blade.php#L15476)
- **Risk Level:** 🔴 **HIGH** - Stores HTML as text field
- **Data Example:**
  ```json
  {
    "type": "section-title",
    "text": "<div id=\"section-title-content-456\" style=\"font-size: 32px;\">Title</div>"
  }
  ```

---

## Why This Is a Problem

| Issue | Impact |
|-------|--------|
| **No Validation** | Malicious HTML/scripts can be stored |
| **Backend Parsing** | Must parse HTML to get actual content |
| **Frontend Parsing** | Must parse HTML on every render |
| **Searchability** | Can't search plain text content |
| **Analytics** | Can't count words, chars, etc. |
| **XSS Risk** | Script injection possible |
| **API Inconsistency** | Conflicts with other component patterns |

---

## All Other Components (✅ 36+)

Use **structured objects** with proper data separation:

```javascript
// CORRECT PATTERN
case 'button':
    data.buttonData = content._buttonData;  // Object with properties
    break;

case 'gallery':
    data.galleryData = content._galleryData;  // Object with properties
    break;

// etc...
```

---

## Where This Happens

**File:** `resources/views/admin/page/page-builder.blade.php`

**Function:** `serializeBuilder()` (lines 15281-15875)

**Default Case:** Line 15854-15855
```javascript
default:
    data.html = content.innerHTML;  // ← CATCH-ALL FOR UNHANDLED TYPES
```

---

## Next Steps

1. **Add explicit TEXT case** - Extract `.textContent`, not `.innerHTML`
2. **Fix SECTION-TITLE case** - Store as plain text in structured format  
3. **Improve validation** - Sanitize HTML on backend before storage
4. **Audit existing data** - Check for malicious content in current TEXT/TITLE fields
5. **Add security layer** - Implement XSS prevention + content type validation

---

**Analysis Date:** January 10, 2026  
**Critical Components Found:** 2  
**Total Components Affected:** 2/38+ (5%)
