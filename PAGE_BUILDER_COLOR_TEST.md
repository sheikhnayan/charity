# Page Builder Color Controls Test

## Test Instructions

To test the new color controls in the page builder:

### 1. Access Page Builder
- Go to your admin panel
- Navigate to Pages → Page Builder
- Open any page for editing

### 2. Add Event Countdown Component
- Drag the "Event Countdown" component from the components panel to your page
- The component should appear with default settings

### 3. Test Color Controls
1. Click on the Event Countdown component to select it
2. In the settings panel on the right, you should see:
   - **Event Date & Time** (datetime picker)
   - **Countdown Label** (text input)
   - **Number Color** (color picker) - Controls countdown numbers
   - **Text Color** (color picker) - Controls labels (Months, Days, etc.)
   - **Label Text Color** (color picker) - Controls custom label text

### 4. Test Color Changes
1. **Number Color Test:**
   - Change "Number Color" to red (#ff0000)
   - The countdown numbers (0, 1, 2, etc.) should turn red
   - Preview should update immediately

2. **Text Color Test:**
   - Change "Text Color" to blue (#0000ff)
   - The labels "Months", "Days", "Hours", etc. should turn blue
   - Preview should update immediately

3. **Label Text Color Test:**
   - Change "Label Text Color" to green (#00ff00)
   - The custom label text at bottom should turn green
   - Preview should update immediately

### 5. Test Data Persistence
1. Set different colors for each option
2. Save the page
3. Reload the page builder
4. Verify all color settings are preserved
5. Verify the front-end displays the correct colors

## Expected Results

### ✅ What Should Work:
- Color pickers appear in settings panel
- Colors change immediately in preview
- Colors persist when saving/loading
- Front-end displays correct colors
- Default colors (#000000) when no colors set

### ❌ Potential Issues to Check:
- Color pickers not appearing
- Colors not updating in preview
- Colors not saving/loading
- JavaScript errors in console
- Default colors not applied

## Technical Details

### Files Modified:
1. **page-builder.blade.php** - Added color controls and functions
2. **render-component.blade.php** - Updated to use color options

### New Functions Added:
- `updateCountdownNumberColor(value)`
- `updateCountdownTextColor(value)`
- `updateCountdownVerbiageColor(value)`

### Data Structure:
```javascript
content._countdownData = {
    date: '2025-04-30T00:00',
    label: 'Remaining to Apr 30, 2025 (00:00 PST)',
    numberColor: '#000000',
    textColor: '#000000', 
    remainingVerbiageColor: '#000000'
}
```

### Color Application:
- **Number Color**: Applied to `<h1>` elements (countdown numbers)
- **Text Color**: Applied to `<p>` elements (time unit labels)
- **Verbiage Color**: Applied to custom label `<p>` element

## Troubleshooting

### If colors don't appear:
1. Check browser console for JavaScript errors
2. Verify the component is properly selected
3. Check if page builder was saved after changes
4. Clear browser cache and reload

### If colors don't save:
1. Verify the save function is working
2. Check server-side data persistence
3. Test with simple colors first (red, blue, green)

### If front-end doesn't show colors:
1. Check if render-component.blade.php is updated
2. Verify countdownData is being passed correctly
3. Check for CSS conflicts

## Browser Developer Tools

Use F12 Developer Tools to check:

1. **Console Tab**: Look for JavaScript errors
2. **Elements Tab**: Inspect the countdown HTML for inline styles
3. **Network Tab**: Check if data is being saved properly

## Success Criteria

The test is successful when:
- ✅ All three color pickers are visible and functional
- ✅ Colors update instantly in preview
- ✅ Colors persist after save/reload
- ✅ Front-end displays correct colors
- ✅ No JavaScript errors in console
- ✅ Backward compatibility maintained (existing countdowns work)

Report any issues found during testing!