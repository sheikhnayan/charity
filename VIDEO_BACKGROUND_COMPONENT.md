# Video Background Component

## Overview
A powerful video background component for the page builder that allows you to create stunning hero sections with video backgrounds, customizable overlays, and flexible content options (text, images, or both).

## Features

### 🎥 Video Settings
- **Video URL**: Direct video file URL (MP4, WebM, OGG)
- **Video Type**: MP4, WebM, or OGG format
- **Min Height**: Adjustable minimum height (200-1000px)
- **Autoplay**: Auto-play video on load
- **Loop**: Continuous video playback
- **Muted**: Mute audio by default (recommended for autoplay)
- **Controls**: Show/hide video player controls

### 🎨 Overlay Settings
- **Overlay Color**: Any hex color
- **Overlay Opacity**: 0-1 (adjustable slider with live preview)
- Creates professional darkening effect over video for better text readability

### 📝 Content Settings
Three content types:
1. **Text Only**: Heading, subheading, and call-to-action button
2. **Image Only**: Display an image over the video
3. **Both**: Combine text and image

#### Text Content Options:
- **Heading**: Large, bold headline
- **Subheading**: Supporting description text
- **Button Text**: Call-to-action button label
- **Button URL**: Link destination
- **Button Color**: Customizable button background
- **Text Color**: Color for all text elements

#### Image Content Options:
- **Image URL**: Direct image file URL
- **Image Width**: Control image size (100-800px)

### 📐 Layout Settings
- **Text Alignment**: Left, Center, or Right
- **Vertical Alignment**: Top, Center, or Bottom
- Control where content appears on the video

## How to Use

### Step 1: Add Component
1. Open the page builder
2. Find "Video Background" in the Components panel (📹 icon)
3. Drag and drop onto your canvas

### Step 2: Configure Video
1. Select the component
2. In Properties Panel → Video Settings:
   - Enter your video URL
   - Select video type (usually MP4)
   - Set minimum height (500px default)
   - Enable autoplay, loop, and mute (recommended)

### Step 3: Adjust Overlay
1. In Properties Panel → Overlay Settings:
   - Choose overlay color (black #000000 works well)
   - Adjust opacity slider (0.5 provides good contrast)

### Step 4: Add Content
1. In Properties Panel → Content Settings:
   - Select content type (Text, Image, or Both)
   - For Text: Enter heading, subheading, button text
   - For Image: Enter image URL and set width
   - Customize colors to match your brand

### Step 5: Fine-tune Layout
1. In Properties Panel → Layout Settings:
   - Set text alignment (center works best for hero sections)
   - Set vertical alignment (center for hero, top/bottom for other uses)

## Best Practices

### Video Selection
✅ **Do:**
- Use short video clips (15-30 seconds work great with loop)
- Optimize video file size (compress before uploading)
- Use MP4 format for best browser compatibility
- Choose videos with subtle motion (not distracting)
- Use muted videos for autoplay (browser requirement)

❌ **Don't:**
- Use very long video files (impacts page load)
- Use videos with important audio (muted by default)
- Use extremely high resolution (1080p is plenty for background)

### Overlay Settings
- **Light overlay (0.3-0.4)**: For darker videos, subtle effect
- **Medium overlay (0.5-0.6)**: Most versatile, works with any video
- **Heavy overlay (0.7-0.8)**: For bright videos, dramatic effect
- **Black overlay**: Classic, professional look
- **Brand color overlay**: Match your website theme

### Content Design
- **Hero Section**: Center-aligned text, medium height (500-600px)
- **Full Screen**: Increase min-height to 100vh for fullscreen effect
- **Split Layout**: Use left/right alignment with image on opposite side
- **Minimal Text**: Less is more - keep text concise and impactful

## Example Use Cases

### 1. Hero Section
```
Video: Company showcase video (30 sec loop)
Overlay: Black, 0.5 opacity
Content Type: Text
Heading: "Transform Your Future"
Subheading: "Join thousands making a difference"
Button: "Get Started"
Alignment: Center / Center
Min Height: 600px
```

### 2. Campaign Landing Page
```
Video: Emotional impact footage
Overlay: Dark blue (#000066), 0.6 opacity
Content Type: Both
Heading: "Every Donation Matters"
Image: Campaign logo
Alignment: Center / Center
Min Height: 700px
```

### 3. Event Announcement
```
Video: Event highlight reel
Overlay: Black, 0.4 opacity
Content Type: Text
Heading: "Annual Gala 2025"
Subheading: "September 15th • Grand Ballroom"
Button: "Reserve Your Seat"
Alignment: Left / Bottom
Min Height: 500px
```

### 4. About Us Section
```
Video: Team at work
Overlay: White (#FFFFFF), 0.3 opacity
Content Type: Both
Text: Mission statement
Image: Organization logo
Alignment: Right / Center
Min Height: 450px
```

## Technical Details

### Supported Video Formats
- **MP4** (H.264 codec) - Best compatibility
- **WebM** (VP8/VP9 codec) - Modern browsers
- **OGG** (Theora codec) - Older browsers

### Browser Compatibility
- ✅ Chrome/Edge (all features)
- ✅ Firefox (all features)
- ✅ Safari (all features)
- ✅ Mobile browsers (iOS/Android)

### Performance Considerations
- Video is set to `object-fit: cover` for responsive scaling
- Uses native `<video>` element (hardware accelerated)
- Autoplay requires `muted` attribute (browser policy)
- Consider lazy loading for videos below the fold

### Responsive Behavior
- Video maintains aspect ratio and covers entire container
- Text scales down on mobile devices:
  - Heading: 3rem → 2rem → 1.5rem
  - Subheading: 1.5rem → 1.2rem → 1rem
  - Button: Maintains readability on all devices
- Image width adjusts to 90% on mobile

## Data Storage

### Serialization
All component data is automatically saved to the database including:
- Video settings (URL, type, dimensions, playback options)
- Overlay configuration (color, opacity)
- Content (text, images, buttons)
- Layout preferences (alignment, positioning)

### Front-End Compatibility
Component properties are saved in both:
- `videoData` object (builder format)
- `properties` object (front-end format)

This ensures seamless rendering in both builder and live page views.

## Troubleshooting

### Video Not Playing
- Check if video URL is direct file link (not YouTube/Vimeo)
- Ensure autoplay is enabled AND muted is checked
- Verify video format matches selected type
- Test video URL in browser directly

### Video Quality Issues
- Video appears pixelated: Use higher resolution source
- Video loads slowly: Compress video file size
- Video stutters: Reduce video resolution or bitrate

### Content Not Visible
- Check overlay opacity (too dark may hide content)
- Verify text color contrasts with overlay
- Check if content type is set correctly
- Ensure vertical/horizontal alignment is appropriate

### Mobile Issues
- Video not showing: Some mobile browsers limit autoplay
- Layout broken: Test responsive preview in builder
- Performance slow: Reduce video file size

## Tips & Tricks

1. **Test Multiple Devices**: Always preview on desktop, tablet, and mobile
2. **Optimize Before Upload**: Use tools like HandBrake to compress videos
3. **Provide Fallback**: Set a background color that matches video tone
4. **Accessibility**: Ensure sufficient color contrast for text
5. **Loading State**: Consider adding a poster image for slow connections
6. **SEO**: Include meaningful heading text (not just in video)
7. **Call-to-Action**: Make button text clear and action-oriented

## Component Structure

```html
<div class="video-background-component">
  <div class="video-background-section">
    <video class="video-bg">...</video>
    <div class="video-overlay">...</div>
    <div class="content-wrapper">
      <!-- Text and/or Image content -->
    </div>
  </div>
</div>
```

## CSS Classes

- `.video-background-component` - Outer wrapper
- `.video-background-section` - Main container
- `.video-bg` - Video element
- `.video-overlay` - Overlay layer
- Includes responsive breakpoints at 768px and 480px

## Implementation Complete ✅

All features implemented and tested:
- ✅ Component creation and rendering
- ✅ Properties panel with all controls
- ✅ Real-time preview updates
- ✅ Database serialization/deserialization
- ✅ Responsive CSS styles
- ✅ Browser compatibility
- ✅ Content type switching (text/image/both)

---

**Version**: 1.0  
**Last Updated**: November 6, 2025  
**Component Type**: video-background
