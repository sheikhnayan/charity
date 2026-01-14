# Component HTML Serialization - Code Locations Reference

## Pure HTML Components

### 1. TEXT Component
**Issue:** Stores pure HTML instead of structured data  
**Root Cause:** No explicit case in serializeBuilder() - falls through to default

#### Creation Location
- **File:** page-builder.blade.php
- **Line:** 4581
```javascript
case 'text':
    content.innerHTML = `<div id="${textId}" style="min-height: 50px; padding: 10px;" oninput="updateTextImagesField(this.innerHTML, 'text')">New text block. Click to edit.</div>`;
```

#### Property Panel Location
- **File:** page-builder.blade.php
- **Line:** 8258
```javascript
case 'text':
    const textElement = content.querySelector('[id^="text-content-"]');
    const textContent = textElement ? textElement.innerHTML : content.textContent;
    const textEditorId = 'text-editor-' + Date.now();
    ckeditorinitTextBox(textEditorId);
    specificControls = `...`;
```

#### Deserialization Location
- **File:** page-builder.blade.php
- **Line:** 18084
```javascript
case 'text':
    // Loads from data.html
```

#### Serialization Location (DEFAULT CASE)
- **File:** page-builder.blade.php
- **Lines:** 15854-15855
```javascript
// ...add other types as needed...
default:
    data.html = content.innerHTML;  // ← TEXT FALLS HERE
```

---

### 2. SECTION-TITLE Component
**Issue:** Stores innerHTML instead of plain text

#### Creation Location
- **File:** page-builder.blade.php
- **Line:** 5392
```javascript
content.innerHTML = `<div id="${sectionTitleId}" style="min-height: 50px; padding: 10px;">Section Title</div>`;
```

#### Property Panel Location
- **File:** page-builder.blade.php
- **Line:** 8527
```javascript
case 'section-title':
    // Property panel code
```

#### Serialization Location (EXPLICIT CASE)
- **File:** page-builder.blade.php
- **Lines:** 15476-15479
```javascript
case 'section-title':
    const sectionTitleEl = content.querySelector('[id^="section-title-content-"]');
    data.text = sectionTitleEl ? sectionTitleEl.innerHTML : content.textContent;  // ← PURE HTML STORED
    break;
```

#### Deserialization Location
- **File:** page-builder.blade.php
- **Line:** 16227
```javascript
case 'section-title':
    // Loads from data.text (pure HTML)
```

---

## Comparison: Good Pattern (Structured Data)

### Example: BUTTON Component
**File:** page-builder.blade.php

#### Serialization (Line 15500-15517)
```javascript
case 'button':
    data.buttonData = content._buttonData;  // ← OBJECT, NOT HTML
    // Also save to properties for front-end compatibility
    if (content._buttonData) {
        data.properties = data.properties || {};
        data.properties.button_text = content._buttonData.buttonText || 'Click Me';
        data.properties.button_url = content._buttonData.buttonUrl || '#';
        // ... more properties
    }
    break;
```

#### Data Storage Format
```json
{
  "type": "button",
  "buttonData": {
    "buttonText": "Click Me",
    "buttonUrl": "#",
    "buttonBgColor": "#007bff",
    "buttonTextColor": "#ffffff",
    "buttonPadding": "10px 20px",
    "borderRadius": "4px",
    "fontSize": "16px"
  },
  "properties": {
    // duplicate for frontend
  },
  "style": { /* CSS styles */ }
}
```

---

## Summary of All Serialization Cases

**Total Component Types:** 38+  
**Using Objects/Structures:** 36  
**Using Pure HTML:** 2 ❌

### All Cases in Order

```
EXPLICITLY SERIALIZED:
✅ sell-tickets         → data.sellTicketsData (object)
✅ slider              → data.sliderData (object)
✅ gallery             → data.galleryData (object)
✅ image               → data.imageData (object)
✅ numbered-timeline   → data.timelineData (object)
✅ invest-cta          → data.investCtaData (object)
✅ newsletter          → data.newsletterData (object)
✅ video-background    → data.videoData (object)
✅ full-width-text-image → data.fwtiData (object)
✅ press-card          → data.pressCardData (object)
✅ social-share        → data.shareData (object)
✅ custom-html         → data.customHtmlData (object)
✅ section-title       → data.text (innerHTML) ❌ PROBLEM
✅ video               → data.videoData (object)
✅ divider             → data.properties (object)
✅ button              → data.buttonData (object)
✅ alert-message       → data.alertData (object)
✅ site-banner         → data.src, data.alt (properties)
✅ custom-banner       → data.customBannerData (object)
✅ faq                 → data.faqData (object)
✅ simple-comments     → data.simpleCommentsData (object)
✅ disqus              → data.disqusData (object)
✅ event-countdown     → data.countdownData (object)
✅ event-information   → data.eventInfoData (object)
✅ site-goal           → data.goalData (object)
✅ text-images         → data.textImagesData (object)
✅ feature-grid        → data.featureGridData (object)
✅ investment-tier     → data.investmentTierData (object)
✅ statistics-metric   → data.statisticsData (object)
✅ custom-form         → data.customFormFields (array)
✅ inner-section       → data.innerSectionData (object)
✅ auth-form           → data.authFormData (object)
✅ contact-form        → data.contactFormData (object)
✅ donation-form       → data.donationFormData (object)
✅ ticket-carousel     → data.properties (object)
✅ ticket-category-carousel → data.properties (object)
✅ property-category-carousel → data.properties (object)
✅ property-listing-grid → data.properties (object)
✅ product-listing-grid → data.properties (object)

DEFAULT (CATCH-ALL):
❌ text                → data.html (innerHTML) ❌ PROBLEM
❌ [anything else]     → data.html (innerHTML) ❌ PROBLEM
```

---

## File Structure

```
c:\wamp64\www\charity\
├── resources/views/admin/page/
│   └── page-builder.blade.php
│       ├── serializeBuilder()        [Lines 15281-15875]
│       │   └── switch(type)
│       │       ├── case 'text'      [MISSING - falls to default]
│       │       ├── case 'section-title' [Lines 15476-15479]
│       │       └── default          [Lines 15854-15855]
│       │
│       ├── deserializeBuilder()      [Lines 15876+]
│       │   └── switch(type)
│       │       ├── case 'text'      [Line 18084]
│       │       └── case 'section-title' [Line 16227]
│       │
│       └── createComponent()
│           ├── case 'text'           [Line 4581]
│           └── case 'section-title'  [Line 5392]
```

---

**Last Updated:** January 10, 2026  
**Analysis Scope:** Complete page builder serialization flow  
**Critical Issues Found:** 2
