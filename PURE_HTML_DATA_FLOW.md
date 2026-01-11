# Page Builder Data Flow - Pure HTML Components Analysis

## Component Data Lifecycle

```
┌─────────────────────────────────────────────────────────────────┐
│                    COMPONENT LIFECYCLE                          │
└─────────────────────────────────────────────────────────────────┘

┌──────────────┐
│   CREATE     │
│ Component    │
└──────┬───────┘
       │
       ▼
┌──────────────────────────────────────────┐
│  createComponent(type)                   │
│  • Generates DOM element                 │
│  • Sets initial innerHTML                │
│  • Stores data in _data properties       │
└──────┬───────────────────────────────────┘
       │
       ├─── TEXT ──────────────────────────────────────────────┐
       │    Line 4581                                           │
       │    Creates: <div id="text-content-X">...</div>        │
       │    Stores data in: element.innerHTML (NO _data)       │
       │                                                        │
       ├─── SECTION-TITLE ──────────────────────────────────┐  │
       │    Line 5392                                        │  │
       │    Creates: <div id="section-title-content-X">...</>│  │
       │    Stores data in: element.innerHTML (NO _data)    │  │
       │                                                    │  │
       └─── OTHER COMPONENTS ──────────────────────────────┘  │
            Creates proper _data property objects             │
            Example: content._buttonData = { ... }           │
                     content._imageData = { ... }            │
                     content._sliderData = { ... }           │
                                                             │
       ▼                                                      │
┌──────────────────────────────────────────────────────────────┐
│  EDIT COMPONENT                                              │
│  • User modifies via UI                                      │
│  • updatePropertyPanel() called                              │
│  • HTML updated in real-time                                │
└──────────────────────────────────────────────────────────────┘
       │
       ├─── TEXT ──────────────────────────────────────────────┐
       │    Property panel at Line 8258                        │
       │    Reads: element.innerHTML (PURE HTML)              │
       │    Updates: element.innerHTML (PURE HTML)            │
       │    Via Quill editor for rich text                    │
       │                                                        │
       ├─── SECTION-TITLE ──────────────────────────────────┐  │
       │    Property panel at Line 8527                     │  │
       │    Reads: element.innerHTML (PURE HTML)            │  │
       │    Updates: element.innerHTML (PURE HTML)          │  │
       │                                                    │  │
       └─── OTHER COMPONENTS ──────────────────────────────┘  │
            Read/Write from _data property objects           │
            Example: content._buttonData.buttonText = "..."  │
                                                             │
       ▼
┌─────────────────────────────────────────────────────────────┐
│  SAVE STATE                                                 │
│  • serializeBuilder() called                                │
│  • Components converted to JSON                             │
│  • Sent to API endpoint                                     │
└──────────┬──────────────────────────────────────────────────┘
           │
           ▼
        SERIALIZE SWITCH
           │
    ┌──────┴───────────────────────────────────┬──────────────┐
    │                                           │              │
    ▼                                           ▼              ▼
EXPLICIT CASE                          SECTION-TITLE        DEFAULT
(36 components)                        (Line 15476)          (Line 15854)
    │                                      │                  │
    │ ✅ Structured Data                   │ ❌ Pure HTML     │ ❌ Pure HTML
    │                                      │                  │
    ├─ slider                              │                  ├─ TEXT falls here!
    ├─ gallery                     ┌───────┘                  │
    ├─ button                      │                          ├─ Any unmapped type
    ├─ image                       ▼                          │
    ├─ video-background     case 'section-title':   │
    ├─ newsletter                 const titleEl =    │  default:
    │  ... (33 more)              querySelector();  │      data.html = 
    │                             data.text =        │      content.innerHTML
    │                             titleEl ?          │
    │                                innerHTML : ...│
    │                                                │
    ▼                                                ▼
data = {                                 data = {
  type: 'button',                          type: 'section-title',
  buttonData: {              ✅            text: '<div>Title</div>',  ❌
    buttonText: '...',                     style: {...}
    buttonUrl: '...',                    }
    buttonBgColor: '...',
    ...
  },
  style: {...}
}

           │                               │
           │                               │
           └───────────────────┬───────────┘
                               │
                               ▼
                    SEND TO BACKEND API
                    POST /admins/page/save/{id}
                               │
                               ▼
                    DATABASE STORAGE
                    page_builder_states.state
                               │
    ┌───────────────────────────┼────────────────────┐
    │                           │                    │
    ▼                           ▼                    ▼
✅ STRUCTURED             ❌ PURE HTML          ❌ PURE HTML
   Can be:                Can't be:             Can't be:
   • Searched            • Validated           • Validated
   • Parsed              • Searched            • Searched
   • Modified            • Modified            • Modified
   • Validated           • Sanitized           • Sanitized
   • Analyzed            • Analyzed            • Analyzed
                         • Re-rendered         • Re-rendered


           │                               │                    │
           └───────────────────┬───────────┴────────────────────┘
                               │
                               ▼
                      LOAD STATE (GET API)
                      GET /admins/page/load/{id}
                               │
                               ▼
                    DESERIALIZE COMPONENTS
                    deserializeBuilder()
                               │
    ┌──────────────────────────┼──────────────────┐
    │                          │                  │
    ▼                          ▼                  ▼
✅ Reconstructed      ❌ Re-insert HTML     ❌ Re-insert HTML
   from objects      (must parse again)    (must parse again)
   Type-safe         
   Property-based    case 'text':
   Renderable        // Must extract content
                     // from HTML each time
                               │
                               ▼
                    FRONTEND DISPLAY
                               │
                          [rendered
                           to user]
```

---

## Data Flow Summary

### ✅ CORRECT FLOW (Button, Gallery, Slider, etc.)

```
USER EDIT
    ↓
UPDATE _data OBJECT
    ↓
SERIALIZE → data.buttonData = { ...structured object... }
    ↓
SAVE TO DB → JSON stored with schema
    ↓
LOAD FROM DB → Parse JSON to object
    ↓
DESERIALIZE → Reconstruct component from object
    ↓
FRONTEND → Type-safe, validated, renderable
```

### ❌ PROBLEMATIC FLOW (Text, Section-Title)

```
USER EDIT
    ↓
UPDATE element.innerHTML (PURE HTML)
    ↓
SERIALIZE → data.html = element.innerHTML
    ↓
SAVE TO DB → Raw HTML string stored (unvalidated!)
    ↓
LOAD FROM DB → Raw HTML string retrieved
    ↓
DESERIALIZE → Must re-insert HTML into element
    ↓
FRONTEND → Unvalidated, unparseable, vulnerable
```

---

## Risk Comparison

### TEXT Component Risk Profile

```
┌─────────────────────────────────────┐
│       TEXT Component Risks          │
└─────────────────────────────────────┘

SECURITY:
├─ XSS Vulnerability          🔴 CRITICAL
│  └─ Can store <script> tags
│  └─ Runs when page loads
│  └─ Can access user data
│
├─ HTML Injection              🔴 HIGH
│  └─ <iframe>, <form>, etc.
│  └─ Redirect users
│  └─ Phishing attacks
│
└─ No Input Validation        🔴 HIGH
   └─ Backend can't validate
   └─ Must validate on frontend

FUNCTIONALITY:
├─ Can't Search Content       🟠 MEDIUM
│  └─ No plain text extraction
│  └─ Site search broken
│
├─ Can't Analytics            🟠 MEDIUM
│  └─ Can't count words/chars
│  └─ Can't track content size
│
└─ Can't Re-render            🟠 MEDIUM
   └─ Must parse HTML each time
   └─ Performance impact

DATABASE:
├─ Can't Query Content        🟠 MEDIUM
│  └─ Can't find by text
│  └─ Full-text search broken
│
└─ Can't Modify Content       🟠 MEDIUM
   └─ Can't bulk edit
   └─ Can't auto-format
```

---

## Current Implementation Issues

### TEXT Component

| Aspect | Status | Issue |
|--------|--------|-------|
| **Data Storage** | ❌ HTML | No structured format |
| **Creation** | Line 4581 | Sets raw innerHTML |
| **Editing** | Line 8258 | Uses Quill (good) but stores HTML |
| **Serialization** | Line 15854 | Falls to DEFAULT case |
| **Deserialization** | Line 18084 | Re-inserts raw HTML |
| **Validation** | ❌ None | Backend can't validate |
| **Security** | 🔴 XSS Risk | Script tags possible |

### SECTION-TITLE Component

| Aspect | Status | Issue |
|--------|--------|-------|
| **Data Storage** | ❌ HTML | data.text contains HTML |
| **Creation** | Line 5392 | Sets innerHTML |
| **Editing** | Line 8527 | Edits innerHTML |
| **Serialization** | Line 15476 | Explicit case stores innerHTML |
| **Deserialization** | Line 16227 | Re-inserts raw HTML |
| **Validation** | ❌ None | Backend can't validate |
| **Security** | 🔴 XSS Risk | Script tags possible |

---

## Recommended Fix Strategy

### Phase 1: Create Structured Format
```javascript
// TEXT Component - BEFORE
data.html = "<div><strong>Bold</strong> text</div>"

// TEXT Component - AFTER  
data.textData = {
  content: "Bold text",           // Plain text
  formatting: {                    // Structured formatting
    bold: [[0, 4]],               // Character ranges
    text_color: "#000000"
  },
  style: {
    fontSize: "16px",
    color: "#333333"
  }
}
```

### Phase 2: Migration
1. Create database migration script
2. Parse existing HTML content
3. Extract plain text + formatting
4. Update all existing records
5. Maintain backwards compatibility

### Phase 3: Validation
1. Add server-side sanitization
2. Validate content structure
3. Prevent HTML injection
4. Log security violations

---

**Analysis Complete**  
**Date:** January 10, 2026  
**Critical Issues:** 2  
**Risk Level:** 🔴 HIGH (Security & Functionality)
