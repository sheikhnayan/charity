# Page Builder Components - Pure HTML Serialization Analysis

## Overview
This document identifies all components in the page builder that save their data as **pure HTML** rather than as structured JSON/object data.

---

## Components Saving as PURE HTML

### 1. **TEXT** Component ❌
**File:** [page-builder.blade.php](page-builder.blade.php)  
**Serialization:** DEFAULT CASE (line 15855)  
**Storage:** `data.html = content.innerHTML`

**Problem:**
- NO explicit case for `'text'` in the serializeBuilder() switch statement
- Falls through to DEFAULT case which captures raw HTML: `data.html = content.innerHTML`
- Pure HTML is stored with all inline styles, spans, formatting tags, etc.
- Makes it impossible to parse or modify the content programmatically on the backend

**Example Data Stored:**
```html
{
  "type": "text",
  "html": "<div id=\"text-content-1234\" style=\"min-height: 50px; padding: 10px;\"><strong>Bold Text</strong> with <em>italic</em> and <u>underline</u></div>",
  "style": { /* CSS properties */ }
}
```

**Risks:**
- Cannot extract plain text without parsing HTML
- Cannot validate or sanitize content before storage
- XSS vulnerability if content contains malicious scripts
- No structured data means frontend must parse HTML every time
- Cannot perform text-level analytics (character count, word count, etc.)

---

### 2. **SECTION-TITLE** Component ❌
**File:** [page-builder.blade.php](page-builder.blade.php#L15476)  
**Serialization:** Line 15476-15479

**Code:**
```javascript
case 'section-title':
    const sectionTitleEl = content.querySelector('[id^="section-title-content-"]');
    data.text = sectionTitleEl ? sectionTitleEl.innerHTML : content.textContent;
    break;
```

**Problem:**
- Stores as `data.text` using `.innerHTML` (pure HTML)
- Captures all HTML tags, styles, formatting from the content element
- Title data should be plain text with a separate style object

**Example Data Stored:**
```json
{
  "type": "section-title",
  "text": "<div id=\"section-title-content-456\" style=\"font-size: 32px; font-weight: 700;\">My Section Title</div>",
  "style": { /* CSS properties */ }
}
```

**Risks:**
- Same as TEXT component
- Unclear if this is meant to be HTML or plain text
- Inconsistent with other title/text components

---

## Components Saving as STRUCTURED DATA ✅

All other components properly serialize their data into structured objects:

| Component | Storage Type | Data Field |
|-----------|-------------|-----------|
| **slider** | Object | `data.sliderData` |
| **gallery** | Object | `data.galleryData` |
| **image** | Object | `data.imageData` |
| **numbered-timeline** | Object | `data.timelineData` |
| **invest-cta** | Object | `data.investCtaData` |
| **newsletter** | Object | `data.newsletterData` |
| **video-background** | Object | `data.videoData` |
| **full-width-text-image** | Object | `data.fwtiData` |
| **press-card** | Object | `data.pressCardData` |
| **social-share** | Object | `data.shareData` |
| **custom-html** | Object | `data.customHtmlData` |
| **video** | Object | `data.videoData` |
| **divider** | Object/Style | `data.properties` |
| **button** | Object | `data.buttonData` |
| **alert-message** | Object | `data.alertData` |
| **site-banner** | Properties | `data.src`, `data.alt` |
| **custom-banner** | Object | `data.customBannerData` |
| **faq** | Object | `data.faqData` |
| **simple-comments** | Object | `data.simpleCommentsData` |
| **disqus** | Object | `data.disqusData` |
| **event-countdown** | Object | `data.countdownData` |
| **event-information** | Object | `data.eventInfoData` |
| **site-goal** | Object | `data.goalData` |
| **text-images** | Object | `data.textImagesData` |
| **feature-grid** | Object | `data.featureGridData` |
| **investment-tier** | Object | `data.investmentTierData` |
| **statistics-metric** | Object | `data.statisticsData` |
| **custom-form** | Array | `data.customFormFields` |
| **inner-section** | Object | `data.innerSectionData` |
| **auth-form** | Object | `data.authFormData` |
| **contact-form** | Object | `data.contactFormData` |
| **donation-form** | Object | `data.donationFormData` |
| **ticket-carousel** | Object | `data.properties` |
| **ticket-category-carousel** | Object | `data.properties` |
| **property-category-carousel** | Object | `data.properties` |
| **property-listing-grid** | Object | `data.properties` |
| **product-listing-grid** | Object | `data.properties` |
| **sell-tickets** | Object | `data.sellTicketsData` |

---

## DEFAULT CASE Handling

**Location:** Line 15854-15855

```javascript
default:
    data.html = content.innerHTML;
```

**Impact:**
- ANY component type that doesn't have an explicit case will save pure HTML
- This is a catch-all for unhandled components
- Creates risk for future components added without serialization logic

---

## Recommendations for Fix

### 1. **Convert TEXT Component**
- [ ] Add explicit case for `'text'` in serializeBuilder()
- [ ] Extract plain text content with `element.textContent` instead of `innerHTML`
- [ ] Store in structured format: `data.textData = { content: plainText, ... }`
- [ ] Keep styles separate in `data.style`

### 2. **Convert SECTION-TITLE Component**
- [ ] Extract only plain text: `data.title = element.textContent`
- [ ] Separate styles into `data.style`
- [ ] Validate title length/content

### 3. **Improve DEFAULT Case**
- [ ] Add warning/error for unhandled component types
- [ ] Log component type when falling through to default
- [ ] Consider throwing error instead of silently saving HTML

### 4. **Validation Layer**
- [ ] Add pre-save validation to prevent HTML injection
- [ ] Sanitize HTML content before storage
- [ ] Store content type indicator (text vs HTML)

### 5. **Migration**
- [ ] Create database migration to update existing TEXT and SECTION-TITLE data
- [ ] Provide script to convert existing HTML content to structured format
- [ ] Handle backwards compatibility for older pages

---

## Database Considerations

**Affected Table:** `page_builder_states`

**Impact:**
- Pure HTML stored in `state` JSON column
- Cannot be queried for specific fields
- Requires full page parse to search content
- XSS vectors if HTML contains scripts

**Suggested Schema Change:**
```sql
-- Add content_type indicator
ALTER TABLE page_builder_states ADD COLUMN content_validation_status ENUM('unvalidated', 'sanitized', 'safe');

-- Store plain text separately for full-text search
ALTER TABLE page_builder_states ADD FULLTEXT INDEX ft_content (state);
```

---

## Security Impact

### Current Risks:
1. **XSS Vulnerability** - User can inject scripts via TEXT/SECTION-TITLE components
2. **Data Validation** - No way to validate HTML structure on backend
3. **Malicious Content** - Difficult to detect/prevent malicious payloads
4. **Sanitization** - Must sanitize on frontend display, not at save time

### Mitigation:
- Implement server-side HTML sanitization (e.g., HTMLPurifier for PHP)
- Add content security policy checks
- Validate component data structure before saving
- Log/audit TEXT component modifications

---

## Summary Table

| Metric | Value |
|--------|-------|
| **Total Component Types** | 38+ |
| **Components Using Pure HTML** | 2 |
| **Percentage** | ~5% |
| **At Risk** | TEXT, SECTION-TITLE |
| **Critical Priority** | YES - Both components accept user input |

---

**Generated:** January 10, 2026  
**File:** page-builder.blade.php  
**Analysis Scope:** serializeBuilder() function (lines 15281-15875)
