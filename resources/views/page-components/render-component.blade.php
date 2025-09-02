{{-- Universal Component Renderer for Inner-Section Architecture --}}
@php
    // Check if $component is an array of components (multiple components)
    if (is_array($component) && isset($component[0]) && is_array($component[0])) {
        // Handle multiple components - render each one in its own container
        foreach ($component as $index => $singleComponent) {
            echo '<div class="component-group-item" style="width: 100%;">';
            echo view('page-components.render-component', [
                'component' => $singleComponent,
                'componentId' => ($componentId ?? 'component') . '-' . $index,
                'isNested' => $isNested ?? false
            ])->render();
            echo '</div>';
        }
        return;
    }
    
    // Single component processing
    $componentType = $component['type'] ?? '';
    $componentData = $component['data'] ?? [];
    $style = $component['style'] ?? [];
    $wrapperStyle = $component['wrapperStyle'] ?? [];
    $responsiveStyles = $component['responsiveStyles'] ?? [];
    
    // Temporary debugging - remove after testing
    error_log("RENDER COMPONENT DEBUG: Type={$componentType}, IsNested=" . (isset($isNested) ? ($isNested ? 'true' : 'false') : 'undefined'));
    if ($componentType === 'feature-grid' || $componentType === 'numbered-timeline' || $componentType === 'investment-tier') {
        error_log("RENDER COMPONENT FOUND: {$componentType}");
    }
    $componentId = $componentId ?? ('component-' . uniqid());
    
    // Generate style strings
    $styleStr = '';
    foreach ($style as $key => $value) {
        if ($value) {
            $styleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $key)) . ":$value;";
        }
    }
    
    $wrapperStyleStr = '';
    foreach ($wrapperStyle as $key => $value) {
        if ($value) {
            $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $key)) . ":$value;";
        }
    }
    
    // Generate responsive CSS if available
    $responsiveCSS = '';
    if (!empty($responsiveStyles)) {
        $responsiveCSS = "/* Component {$componentId} responsive styles */\n";
        
        // Desktop styles (default - 992px and up)
        if (isset($responsiveStyles['desktop']) && is_array($responsiveStyles['desktop'])) {
            $desktopStyles = [];
            foreach ($responsiveStyles['desktop'] as $prop => $value) {
                if (!empty($value) && trim($value) !== '') {
                    $desktopStyles[] = "{$prop}: {$value}";
                }
            }
            if (!empty($desktopStyles)) {
                $responsiveCSS .= "#{$componentId} { " . implode('; ', $desktopStyles) . "; }\n";
            }
        }
        
        // Tablet styles (768px to 991px)
        if (isset($responsiveStyles['tablet']) && is_array($responsiveStyles['tablet'])) {
            $tabletStyles = [];
            foreach ($responsiveStyles['tablet'] as $prop => $value) {
                if (!empty($value) && trim($value) !== '') {
                    $tabletStyles[] = "{$prop}: {$value} !important";
                }
            }
            if (!empty($tabletStyles)) {
                $responsiveCSS .= "@media screen and (max-width: 991px) and (min-width: 768px) {\n";
                $responsiveCSS .= "  #{$componentId} { " . implode('; ', $tabletStyles) . "; }\n";
                $responsiveCSS .= "}\n";
            }
        }
        
        // Mobile styles (up to 767px)
        if (isset($responsiveStyles['mobile']) && is_array($responsiveStyles['mobile'])) {
            $mobileStyles = [];
            foreach ($responsiveStyles['mobile'] as $prop => $value) {
                if (!empty($value) && trim($value) !== '') {
                    $mobileStyles[] = "{$prop}: {$value} !important";
                }
            }
            if (!empty($mobileStyles)) {
                $responsiveCSS .= "@media screen and (max-width: 767px) {\n";
                $responsiveCSS .= "  #{$componentId} { " . implode('; ', $mobileStyles) . "; }\n";
                $responsiveCSS .= "}\n";
            }
        }
    }
@endphp

@if(!empty($responsiveCSS))
<style>
{!! $responsiveCSS !!}

/* Quill.js Class-based Font Styles for Frontend */
.ql-size-10px { font-size: 10px !important; }
.ql-size-12px { font-size: 12px !important; }
.ql-size-14px { font-size: 14px !important; }
.ql-size-16px { font-size: 16px !important; }
.ql-size-18px { font-size: 18px !important; }
.ql-size-20px { font-size: 20px !important; }
.ql-size-24px { font-size: 24px !important; }
.ql-size-28px { font-size: 28px !important; }
.ql-size-32px { font-size: 32px !important; }
.ql-size-36px { font-size: 36px !important; }
.ql-size-40px { font-size: 40px !important; }
.ql-size-48px { font-size: 48px !important; }

.ql-font-arial { font-family: Arial, sans-serif !important; }
.ql-font-helvetica { font-family: 'Helvetica Neue', Helvetica, sans-serif !important; }
.ql-font-times { font-family: 'Times New Roman', Times, serif !important; }
.ql-font-georgia { font-family: Georgia, serif !important; }
.ql-font-verdana { font-family: Verdana, sans-serif !important; }
.ql-font-courier { font-family: 'Courier New', Courier, monospace !important; }

/* Global Mobile Fixes */
@media screen and (max-width: 767px) {
    /* Prevent horizontal overflow on mobile */
    body {
        overflow-x: hidden !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* Fix any page container margins on mobile */
    html, body, .page {
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        box-sizing: border-box !important;
    }
    
    /* Fix container margins on mobile */
    .container-fluid, .container {
        padding-left: 15px !important;
        padding-right: 15px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* Fix any component wrappers on mobile */
    .component-wrapper, .component {
        margin-left: 0 !important;
        margin-right: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* Ensure all components fit within viewport */
    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .row > [class*="col-"] {
        padding-left: 15px !important;
        padding-right: 15px !important;
    }
    
    /* Fix investment CTA overflow */
    [data-component-type="invest-cta"],
    .investment-tier-component,
    .perk-wrap,
    .investment-tier {
        max-width: 100% !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
    }
    
    /* Ensure proper Bootstrap responsive behavior */
    .col-sm-12 {
        width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    /* Fix text-image component on mobile */
    .text-images-component .row {
        margin: 0 !important;
    }
    
    .text-images-component [class*="col-"] {
        padding-left: 15px !important;
        padding-right: 15px !important;
    }
    
    /* Fix full-width sections on mobile */
    .inner-section-fullwidth {
        width: 100vw !important;
        margin-left: calc(-50vw + 50%) !important;
        padding-left: 15px !important;
        padding-right: 15px !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
    }
}
</style>
@else
{{-- Include Quill.js styles even when no responsive CSS --}}
<style>
/* Quill.js Class-based Font Styles for Frontend */
.ql-size-10px { font-size: 10px !important; }
.ql-size-12px { font-size: 12px !important; }
.ql-size-14px { font-size: 14px !important; }
.ql-size-16px { font-size: 16px !important; }
.ql-size-18px { font-size: 18px !important; }
.ql-size-20px { font-size: 20px !important; }
.ql-size-24px { font-size: 24px !important; }
.ql-size-28px { font-size: 28px !important; }
.ql-size-32px { font-size: 32px !important; }
.ql-size-36px { font-size: 36px !important; }
.ql-size-40px { font-size: 40px !important; }
.ql-size-48px { font-size: 48px !important; }

.ql-font-arial { font-family: Arial, sans-serif !important; }
.ql-font-helvetica { font-family: 'Helvetica Neue', Helvetica, sans-serif !important; }
.ql-font-times { font-family: 'Times New Roman', Times, serif !important; }
.ql-font-georgia { font-family: Georgia, serif !important; }
.ql-font-verdana { font-family: Verdana, sans-serif !important; }
.ql-font-courier { font-family: 'Courier New', Courier, monospace !important; }
</style>
@endif

<div class="component-wrapper" style="{{ $wrapperStyleStr }}" id="{{ $componentId }}">
    @switch($componentType)
        
        @case('inner-section')
            @php
                $innerSectionData = $component['innerSectionData'] ?? [];
                $nestedComponents = $component['nestedComponents'] ?? [];
                $columns = $innerSectionData['columns'] ?? 1;
                $gap = $innerSectionData['gap'] ?? '15px';
                $fullWidth = $innerSectionData['fullWidth'] ?? false;
                $contentWidth = $innerSectionData['contentWidth'] ?? 'full';
                $contentWidth = $innerSectionData['contentWidth'] ?? 'full'; // 'full' or 'boxed'
                
                // Apply inner-section styling - NO BORDERS for frontend
                $sectionStyle = '';
                
                // Background color
                if (isset($innerSectionData['backgroundColor']) && $innerSectionData['backgroundColor'] !== 'transparent' && $innerSectionData['backgroundColor'] !== '' && $innerSectionData['backgroundColor'] !== '#f8f9fa') {
                    $sectionStyle .= "background-color: {$innerSectionData['backgroundColor']};";
                }
                
                // Padding - only if explicitly set and not default
                if (isset($innerSectionData['padding']) && $innerSectionData['padding'] !== '' && $innerSectionData['padding'] !== '20px') {
                    $sectionStyle .= "padding: {$innerSectionData['padding']};";
                }
                
                // Margin - only if explicitly set and not default
                if (isset($innerSectionData['margin']) && $innerSectionData['margin'] !== '' && $innerSectionData['margin'] !== '10px 0') {
                    $sectionStyle .= "margin: {$innerSectionData['margin']};";
                }
                
                // Border radius - only if explicitly set
                if (isset($innerSectionData['borderRadius']) && $innerSectionData['borderRadius'] !== '' && $innerSectionData['borderRadius'] !== '8px') {
                    $sectionStyle .= "border-radius: {$innerSectionData['borderRadius']};";
                }
                
                // Handle background image with fixed gradient format and attachment
                if (isset($innerSectionData['backgroundType']) && $innerSectionData['backgroundType'] === 'image' && !empty($innerSectionData['backgroundImage'])) {
                    $imageUrl = trim($innerSectionData['backgroundImage']);
                    if (!empty($imageUrl)) {
                        // Get background attachment setting (scroll, fixed, local)
                        $backgroundAttachment = $innerSectionData['backgroundAttachment'] ?? 'scroll';
                        
                        // Build background style with attachment
                        $sectionStyle .= "background: linear-gradient(#000,#000c 18%),url({$imageUrl}); ";
                        $sectionStyle .= "background-position: 0 0,50%; ";
                        $sectionStyle .= "background-size: auto,cover; ";
                        $sectionStyle .= "background-attachment: {$backgroundAttachment}; ";
                        $sectionStyle .= "background-repeat: no-repeat; ";
                    }
                }
                
                // Calculate Bootstrap column classes for proper grid
                $bootstrapClass = '';
                switch($columns) {
                    case 1: $bootstrapClass = 'col-12'; break;
                    case 2: $bootstrapClass = 'col-lg-6 col-md-6 col-sm-12'; break;
                    case 3: $bootstrapClass = 'col-lg-4 col-md-6 col-sm-12'; break;
                    case 4: $bootstrapClass = 'col-lg-3 col-md-6 col-sm-12'; break;
                    case 5: $bootstrapClass = 'col-lg-2 col-md-4 col-sm-6 col-12'; break;
                    case 6: $bootstrapClass = 'col-lg-2 col-md-4 col-sm-6 col-12'; break;
                    default: $bootstrapClass = 'col-lg-4 col-md-6 col-sm-12';
                }
            @endphp
            
            @if($fullWidth)
                {{-- Full Width Section - Use CSS to break out of container --}}
                <div class="inner-section-fullwidth" style="{{ $sectionStyle }}">
                    <style>
                        .inner-section-fullwidth {
                            width: 100vw;
                            margin-left: calc(-50vw + 50%);
                            position: relative;
                            overflow-x: hidden; /* Prevent horizontal scroll on mobile */
                        }
                        
                        @if($contentWidth === 'boxed')
                        /* Boxed content - keep components centered like a regular container */
                        .inner-section-fullwidth .content-wrapper {
                            max-width: 1200px;
                            margin: 0 auto;
                            padding: 0 15px;
                        }
                        .inner-section-fullwidth .row {
                            margin: 0;
                        }
                        @else
                        /* Full width content - components spread across full width */
                        .inner-section-fullwidth .row {
                            margin: 0;
                            width: 100%;
                            max-width: 100%;
                        }
                        @endif
                        
                        /* Custom gap handling for full width */
                        @if($gap !== '0px' && $gap !== '15px')
                        .inner-section-fullwidth .row > [class*="col-"] {
                            padding-left: calc({{ $gap }} / 2);
                            padding-right: calc({{ $gap }} / 2);
                        }
                        .inner-section-fullwidth .row {
                            margin-left: calc(-{{ $gap }} / 2) !important;
                            margin-right: calc(-{{ $gap }} / 2) !important;
                        }
                        @else
                        /* Default Bootstrap gutters */
                        .inner-section-fullwidth .row > [class*="col-"] {
                            padding-left: 15px;
                            padding-right: 15px;
                        }
                        @endif
                        
                        /* Mobile responsiveness fixes */
                        @media (max-width: 767px) {
                            .inner-section-fullwidth {
                                width: 100vw;
                                margin-left: calc(-50vw + 50%);
                                padding-left: 15px;
                                padding-right: 15px;
                                box-sizing: border-box;
                            }
                            
                            @if($contentWidth === 'boxed')
                            .inner-section-fullwidth .content-wrapper {
                                padding: 0 15px;
                            }
                            @endif
                        }
                    </style>
                    
                    @if($contentWidth === 'boxed')
                        {{-- Boxed content wrapper --}}
                        <div class="content-wrapper">
                            <div class="row">
                                @for($columnIndex = 0; $columnIndex < $columns; $columnIndex++)
                                    <div class="{{ $bootstrapClass }}">
                                        @if(isset($nestedComponents[$columnIndex]) && is_array($nestedComponents[$columnIndex]))
                                            @foreach($nestedComponents[$columnIndex] as $nestedIndex => $nestedComponent)
                                                @php $nestedComponentId = "nested-{$columnIndex}-{$nestedIndex}"; @endphp
                                                <div class="nested-component">
                                                    @include('page-components.render-component', [
                                                        'component' => $nestedComponent, 
                                                        'componentId' => $nestedComponentId,
                                                        'isNested' => true
                                                    ])
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @else
                        {{-- Full width content - direct row --}}
                        <div class="row">
                            @for($columnIndex = 0; $columnIndex < $columns; $columnIndex++)
                                <div class="{{ $bootstrapClass }}">
                                    @if(isset($nestedComponents[$columnIndex]) && is_array($nestedComponents[$columnIndex]))
                                        @foreach($nestedComponents[$columnIndex] as $nestedIndex => $nestedComponent)
                                            @php $nestedComponentId = "nested-{$columnIndex}-{$nestedIndex}"; @endphp
                                            <div class="nested-component">
                                                @include('page-components.render-component', [
                                                    'component' => $nestedComponent, 
                                                    'componentId' => $nestedComponentId,
                                                    'isNested' => true
                                                ])
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endfor
                        </div>
                    @endif
                </div>
            @else
                {{-- Regular Section - Stay within container --}}
                <div class="inner-section-frontend" style="{{ $sectionStyle }}">
                    @if($gap !== '0px' && $gap !== '15px')
                        {{-- Custom gap using CSS variables and margin --}}
                        <style>
                            .inner-section-frontend .row > [class*="col-"] {
                                padding-left: calc({{ $gap }} / 2);
                                padding-right: calc({{ $gap }} / 2);
                            }
                            .inner-section-frontend .row {
                                margin-left: calc(-{{ $gap }} / 2);
                                margin-right: calc(-{{ $gap }} / 2);
                            }
                        </style>
                        <div class="row">
                            @for($columnIndex = 0; $columnIndex < $columns; $columnIndex++)
                                <div class="{{ $bootstrapClass }}">
                                    @if(isset($nestedComponents[$columnIndex]) && is_array($nestedComponents[$columnIndex]))
                                        @foreach($nestedComponents[$columnIndex] as $nestedIndex => $nestedComponent)
                                            @php $nestedComponentId = "nested-{$columnIndex}-{$nestedIndex}"; @endphp
                                            <div class="nested-component">
                                                @include('page-components.render-component', [
                                                    'component' => $nestedComponent, 
                                                    'componentId' => $nestedComponentId,
                                                    'isNested' => true
                                                ])
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endfor
                        </div>
                    @else
                        {{-- Standard Bootstrap grid with default spacing --}}
                        <div class="row">
                            @for($columnIndex = 0; $columnIndex < $columns; $columnIndex++)
                                <div class="{{ $bootstrapClass }}">
                                    @if(isset($nestedComponents[$columnIndex]) && is_array($nestedComponents[$columnIndex]))
                                        @foreach($nestedComponents[$columnIndex] as $nestedIndex => $nestedComponent)
                                            @php $nestedComponentId = "nested-{$columnIndex}-{$nestedIndex}"; @endphp
                                            <div class="nested-component">
                                                @include('page-components.render-component', [
                                                    'component' => $nestedComponent, 
                                                    'componentId' => $nestedComponentId,
                                                    'isNested' => true
                                                ])
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endfor
                        </div>
                    @endif
                </div>
            @endif
        @break

        @case('heading')
            @php
                $level = $component['level'] ?? 'h2';
                $text = $component['html'] ?? 'Heading';
            @endphp
            <{{ $level }} style="{{ $styleStr }}">
                {!! $text !!}
            </{{ $level }}>
        @break

        @case('text')
            @php
                $text = $component['html'] ?? 'Text content';
                // Remove border-related styles from $styleStr
                $noBorderStyleStr = preg_replace('/border(-[a-z]+)?\s*:[^;]+;?/i', '', $styleStr);
            @endphp
            <div style="{{ $noBorderStyleStr }}">
                {!! $text !!}
            </div>
        @break

        @case('button')
            @php
                $text = $component['html'] ?? 'Button';
                $href = $component['href'] ?? '#';
                $target = ($component['openInNewTab'] ?? false) ? '_blank' : '_self';
            @endphp
            <a href="{{ $href }}" target="{{ $target }}" class="btn" style="{{ $styleStr }}">
                {{ $text }}
            </a>
        @break

        @case('image')
            @php
                $imageData = $component['imageData'] ?? [];
                $src = $imageData['src'] ?? 'https://via.placeholder.com/400x250';
                $alt = $imageData['alt'] ?? 'Image';
                $width = $imageData['width'] ?? '100%';
                $height = $imageData['height'] ?? 'auto';
                $objectFit = $imageData['objectFit'] ?? 'cover';
                $link = $imageData['link'] ?? '';
                $openInNewTab = $imageData['openInNewTab'] ?? false;
                $alignment = $component['properties']['alignment'] ?? 'left';
            @endphp
            
            <div style="text-align: {{ $alignment }}; {{ $styleStr }}">
                @if($link)
                    <a href="{{ $link }}" {{ $openInNewTab ? 'target="_blank"' : '' }} style="display:inline-block;">
                @endif
                <img src="{{ $src }}" alt="{{ $alt }}" 
                     style="width:{{ $width }};height:{{ $height }};object-fit:{{ $objectFit }};border-radius:8px;" 
                     class="img-fluid"/>
                @if($link)
                    </a>
                @endif
            </div>
        @break

        @case('gallery')
            @php
                $galleryData = $component['galleryData'] ?? [];
                $images = $galleryData['images'] ?? [];
                $columns = $galleryData['columns'] ?? 3;
                $bootstrapClass = '';
                switch($columns) {
                    case 2: $bootstrapClass = 'col-md-6'; break;
                    case 3: $bootstrapClass = 'col-md-4'; break;
                    case 4: $bootstrapClass = 'col-md-3'; break;
                    case 5: $bootstrapClass = 'col-md-2'; break;
                    case 6: $bootstrapClass = 'col-md-2'; break;
                    default: $bootstrapClass = 'col-md-4';
                }
            @endphp
            <div class="row gallery-component" style="{{ $styleStr }}">
                @foreach($images as $image)
                    <div class="{{ $bootstrapClass }} mb-3">
                        <img src="{{ $image['src'] ?? 'https://via.placeholder.com/300x200' }}" 
                             alt="{{ $image['alt'] ?? 'Gallery Image' }}" 
                             class="img-fluid gallery-img-preview" 
                             style="width:100%;height:250px;object-fit:cover;border-radius:8px;cursor:pointer;">
                    </div>
                @endforeach
            </div>
        @break

        @case('slider')
            @php
                $sliderData = $component['sliderData'] ?? [];
                $images = $sliderData['images'] ?? [];
                $slidesToShow = $sliderData['slidesToShow'] ?? 1;
                $slideSpeed = $sliderData['slideSpeed'] ?? 2000;
                $sliderId = 'slider-' . ($componentId ?? uniqid());
            @endphp
            <div class="owl-carousel owl-theme" id="{{ $sliderId }}" style="{{ $styleStr }}">
                @foreach($images as $image)
                    <div class="item">
                        <img src="{{ $image['src'] ?? 'https://via.placeholder.com/800x400' }}" 
                             alt="{{ $image['alt'] ?? 'Slider Image' }}" 
                             style="width:100%;height:400px;object-fit:cover;border-radius:8px;">
                    </div>
                @endforeach
            </div>
            <script>
                $(document).ready(function(){
                    $("#{{ $sliderId }}").owlCarousel({
                        items: {{ $slidesToShow }},
                        loop: true,
                        margin: 10,
                        autoplay: true,
                        autoplayTimeout: {{ $slideSpeed }},
                        responsive: {
                            0: { items: 1 },
                            600: { items: {{ min(2, $slidesToShow) }} },
                            1000: { items: {{ $slidesToShow }} }
                        }
                    });
                });
            </script>
        @break

        @case('custom-form')
            @php
                $formFields = $component['customFormFields'] ?? [];
            @endphp
            <form class="custom-form" style="{{ $styleStr }}">
                @foreach($formFields as $field)
                    <div class="mb-3">
                        <label class="form-label">{{ $field['label'] ?? 'Field' }}</label>
                        @switch($field['type'] ?? 'text')
                            @case('textarea')
                                <textarea class="form-control" name="{{ $field['name'] ?? 'field' }}" 
                                          {{ ($field['required'] ?? false) ? 'required' : '' }}></textarea>
                            @break
                            @case('select')
                                <select class="form-control" name="{{ $field['name'] ?? 'field' }}" 
                                        {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                    @foreach($field['options'] ?? [] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            @break
                            @default
                                <input type="{{ $field['type'] ?? 'text' }}" class="form-control" 
                                       name="{{ $field['name'] ?? 'field' }}" 
                                       {{ ($field['required'] ?? false) ? 'required' : '' }}>
                        @endswitch
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        @break

        @case('divider')
            @php
                $height = $style['height'] ?? '2px';
                $backgroundColor = $style['backgroundColor'] ?? '#ddd';
            @endphp
            <hr style="height:{{ $height }};background-color:{{ $backgroundColor }};border:none;{{ $styleStr }}">
        @break

        @case('spacer')
            @php
                $height = $style['height'] ?? '20px';
            @endphp
            <div style="height:{{ $height }};{{ $styleStr }}"></div>
        @break

        @case('custom-banner')
            @php
                $banner = $component['customBannerData'] ?? [];
            @endphp
            <div style="position:relative; text-align:{{ $banner['textAlign'] ?? 'center' }};{{ $styleStr }}">
                @if(!empty($banner['imgSrc']))
                    <img src="{{ $banner['imgSrc'] }}" style="width:100%;height:auto;">
                @endif
                @if(!empty($banner['title']))
                    <h3 style="position:absolute; top:40%; left:50%; transform:translate(-50%,-50%);
                        color:{{ $banner['titleColor'] ?? '#fff' }};
                        text-shadow:{{ $banner['titleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                        font-size:{{ $banner['titleFontSize'] ?? '2em' }}; width: 90%;
                        text-align:{{ $banner['textAlign'] ?? 'center' }};" class="custom-banner-title">
                        {{ $banner['title'] }}
                    </h3>
                @endif
                @if(!empty($banner['subtitle']))
                    <p style="position:absolute; top:55%; left:50%; transform:translate(-50%,-50%);
                        color:{{ $banner['subtitleColor'] ?? '#fff' }};
                        text-shadow:{{ $banner['subtitleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                        font-size:{{ $banner['subtitleFontSize'] ?? '1.2em' }}; width: 90%;
                        text-align:{{ $banner['textAlign'] ?? 'center' }};
                        margin-top: {{ $banner['subtitleMarginTop'] ?? '0px' }}">
                        {{ $banner['subtitle'] }}
                    </p>
                @endif
            </div>
        @break

        @case('custom-banner')
            @php
                $banner = $component['customBannerData'] ?? [];
            @endphp
            <div style="position:relative; text-align:{{ $banner['textAlign'] ?? 'center' }};{{ $styleStr }}">
                @if(!empty($banner['imgSrc']))
                    <img src="{{ $banner['imgSrc'] }}" style="width:100%;height:auto;">
                @endif
                @if(!empty($banner['title']))
                    <h3 style="position:absolute; top:40%; left:50%; transform:translate(-50%,-50%);
                        color:{{ $banner['titleColor'] ?? '#fff' }};
                        text-shadow:{{ $banner['titleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                        font-size:{{ $banner['titleFontSize'] ?? '2em' }}; width: 90%;
                        text-align:{{ $banner['textAlign'] ?? 'center' }};" class="custom-banner-title">
                        {{ $banner['title'] }}
                    </h3>
                @endif
                @if(!empty($banner['subtitle']))
                    <p style="position:absolute; top:55%; left:50%; transform:translate(-50%,-50%);
                        color:{{ $banner['subtitleColor'] ?? '#fff' }};
                        text-shadow:{{ $banner['subtitleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                        font-size:{{ $banner['subtitleFontSize'] ?? '1.2em' }}; width: 90%;
                        text-align:{{ $banner['textAlign'] ?? 'center' }};
                        margin-top: {{ $banner['subtitleMarginTop'] ?? '0px' }}">
                        {{ $banner['subtitle'] }}
                    </p>
                @endif
            </div>
        @break

        @case('donor-list')
            <div style="{{ $styleStr }}">
                <div class="col-12 mt-4 donor-list-component">
                    <div class="col-12 mt-4">
                        <div id="studentTable_wrapper" class="dataTables_wrapper no-footer">
                            <table id="studentTable" class="display table dataTable no-footer" role="grid">
                                <tbody>
                                    @php
                                        $domain = request()->getHttpHost();
                                        $website = \App\Models\Website::where('domain', $domain)->first();
                                    @endphp
                                    @if($website)
                                        @php
                                            $donations = \App\Models\Donation::where('website_id', $website->id)->with('user')->where('status', 1)->get();
                                        @endphp
                                        @if($donations && count($donations) > 0)
                                            @foreach($donations as $donation)
                                                <tr class="grid" role="row">
                                                    <td class="grid-data">
                                                        <div class="non-float">
                                                            <p style="color: #f4f3f0; font-size: 16px !important; font-weight: bold; margin-bottom: 0;">{{ $donation->amount }}</p>
                                                            <p style="color: #f4f3f0; font-size: 12px; margin-bottom: 0;">
                                                                @php
                                                                    $firstName = $donation->user->name ?? 'Anonymous';
                                                                    $words = explode(' ', $firstName);
                                                                    $displayName = $words[0];
                                                                    if (isset($words[1])) {
                                                                        $displayName .= ' ' . substr($words[1], 0, 1) . '.';
                                                                    }
                                                                    echo $displayName;
                                                                @endphp
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="grid" role="row">
                                                <td class="grid-data">
                                                    <div class="non-float">
                                                        <p style="color: #f4f3f0; font-size: 16px !important; font-weight: bold; margin-bottom: 0;">No donations found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr class="grid" role="row">
                                            <td class="grid-data">
                                                <div class="non-float">
                                                    <p style="color: #f4f3f0; font-size: 16px !important; font-weight: bold; margin-bottom: 0;">Website not found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @break

        @case('faq')
            <div style="{{ $styleStr }}">
                @php
                    $domain = request()->getHttpHost();
                    $website = \App\Models\Website::where('domain', $domain)->first();
                @endphp
                @if($website)
                    @php
                        $faqs = \App\Models\Page::where('website_id', $website->id)->where('name', 'FAQ')->first();
                    @endphp
                    @if($faqs && $faqs->state)
                        @php
                            $faqState = is_string($faqs->state) ? json_decode($faqs->state, true) : $faqs->state;
                        @endphp
                        @if(is_array($faqState))
                            <div class="accordion" id="faqAccordion">
                                @foreach($faqState as $faqIndex => $faq)
                                    @if(isset($faq['type']) && $faq['type'] === 'text')
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $faqIndex }}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faqIndex }}" aria-expanded="false" aria-controls="collapse{{ $faqIndex }}">
                                                    {{ strip_tags($faq['html'] ?? "Question $faqIndex") }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $faqIndex }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $faqIndex }}" data-bs-parent="#faqAccordion">
                                                <div class="accordion-body">
                                                    {!! $faq['html'] ?? '' !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @else
                        <p>No FAQ content found.</p>
                    @endif
                @else
                    <p>Website not found.</p>
                @endif
            </div>
        @break

        @case('invest-cta')
           <div style="max-width: 100%; overflow: hidden; {{ $styleStr }}">
               {!! $component['html'] !!}
           </div>
        @break

        @case('investment-tier')
            @php
                $tierData = $component['investmentTierData'] ?? [];
                $tierName = $tierData['tierName'] ?? 'TIER 1';
                $tierPrice = $tierData['tierPrice'] ?? '$2,500';
                $tierDescription = $tierData['tierDescription'] ?? 'Investment tier description';
                $buttonText = $tierData['buttonText'] ?? 'INVEST NOW';
                $buttonUrl = $tierData['buttonUrl'] ?? '#';
                $buttonTarget = $tierData['buttonTarget'] ?? '_self';
                $backgroundColor = $tierData['backgroundColor'] ?? '#1a1a1a';
                $backgroundImage = $tierData['backgroundImage'] ?? '';
                $backgroundType = $tierData['backgroundType'] ?? 'color';
                $textColor = $tierData['textColor'] ?? '#ffffff';
                $buttonBgColor = $tierData['buttonBgColor'] ?? '#28a745';
                $buttonTextColor = $tierData['buttonTextColor'] ?? '#ffffff';
                $borderRadius = $tierData['borderRadius'] ?? '12px';
                $padding = $tierData['padding'] ?? '2rem';
                
                // Build background style based on type
                $backgroundStyle = '';
                if ($backgroundType === 'image' && !empty($backgroundImage)) {
                    $imageUrl = trim($backgroundImage);
                    $backgroundStyle = "background-image: linear-gradient(359deg,#000000a3,#000),url({$imageUrl}); background-position: 0 0,50%; background-size: auto,cover;";
                } else {
                    $backgroundStyle = "background-color: {$backgroundColor};";
                }
                
                // Temporary debug - remove after testing
                if ($backgroundType === 'image') {
                    error_log("INVESTMENT TIER FRONTEND DEBUG: Type={$backgroundType}, Image={$backgroundImage}, Style={$backgroundStyle}");
                }
                
                // Temporary debug - remove after testing
                if ($backgroundType === 'image') {
                    error_log("INVESTMENT TIER FRONTEND DEBUG: Type={$backgroundType}, Image={$backgroundImage}, Style={$backgroundStyle}");
                }
            @endphp
            {!! $component['html'] !!}
        @break

        @case('feature-grid')
            @php
                // Debug feature-grid component
                error_log("FEATURE GRID DEBUG: Component received");
                
                // Try multiple data sources
                $featureGridData = $component['featureGridData'] ?? [];
                $features = $featureGridData['features'] ?? [];
                
                // If no features in featureGridData, check if features are directly in component
                if (empty($features) && isset($component['features'])) {
                    $features = $component['features'];
                }
                
                // Get colors
                $iconColor = $featureGridData['iconColor'] ?? '#000000';
                $titleColor = $featureGridData['titleColor'] ?? '#1f2937';
                $descriptionColor = $featureGridData['descriptionColor'] ?? '#000000';
                
                error_log("FEATURE GRID DEBUG: Features count = " . count($features));
                error_log("FEATURE GRID DEBUG: Icon color = " . $iconColor);
                error_log("FEATURE GRID DEBUG: featureGridData = " . json_encode($featureGridData));
                
                // If still no features but we have html, fall back to html
                if (empty($features) && isset($component['html']) && !empty($component['html'])) {
                    error_log("FEATURE GRID DEBUG: Falling back to HTML content");
                    echo $component['html'];
                    break;
                }
            @endphp
            
            @if(count($features) > 0)
                <div class="feature-grid-frontend row">
                    @foreach($features as $index => $feature)
                        <div class="feature-item col-md-6" style="display: block;">
                            <div class="feature-icon" style="width: 48px; height: 48px; color: {{ $iconColor }}; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                                <i class="{{ $feature['icon'] ?? 'fas fa-star' }}" style="font-size: 24px;"></i>
                            </div>
                            <div class="feature-content">
                                <h3 class="feature-title" style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600; color: {{ $titleColor }};">{{ $feature['title'] ?? 'Feature Title' }}</h3>
                                <p class="feature-description" style="margin: 0; color: {{ $descriptionColor }}; line-height: 1.5;">{{ $feature['description'] ?? 'Feature description' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <style>
                    @media (max-width: 768px) {
                        .feature-grid-frontend {
                            grid-template-columns: 1fr !important;
                            gap: 1.5rem !important;
                            padding: 1rem !important;
                        }
                    }
                </style>
            @else
                {{-- Fallback: Use HTML content if available --}}
                @if(isset($component['html']) && !empty($component['html']))
                    {!! $component['html'] !!}
                @else
                    <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 8px;">
                        <p style="color: #6b7280; margin: 0;">Feature Grid: No features data found</p>
                        <p style="color: #9ca3af; font-size: 0.875rem; margin: 0.5rem 0 0 0;">featureGridData: {{ json_encode($featureGridData) }}</p>
                    </div>
                @endif
            @endif
        @break

        @case('numbered-timeline')
            @php
                $timelineData = $component['timelineData'] ?? [];
                $items = $timelineData['items'] ?? [];
                $colors = $timelineData['colors'] ?? [];
                $numberBackground = $colors['numberBackground'] ?? '#22c55e';
                $numberText = $colors['numberText'] ?? '#22c55e';
                $titleColor = $colors['titleColor'] ?? '#22c55e';
                $descriptionColor = $colors['descriptionColor'] ?? '#374151';
                $lineColor = $colors['lineColor'] ?? '#22c55e';
            @endphp
            <div class="numbered-timeline" style="display: flex; flex-wrap: wrap; gap: 2rem; position: relative;">
                @php
                    $itemsPerColumn = 4;
                    $columns = array_chunk($items, $itemsPerColumn);
                @endphp
                @foreach($columns as $columnIndex => $column)
                    <div class="timeline-column" style="flex: 1; min-width: 250px; position: relative;">
                        @foreach($column as $index => $item)
                            @php
                                $globalIndex = $columnIndex * $itemsPerColumn + $index;
                            @endphp
                            <div class="timeline-item" style="display: flex; align-items: flex-start; margin-bottom: 2rem; position: relative;">
                                <div class="timeline-number" style="
                                    width: 50px; 
                                    height: 50px; 
                                    border: 3px solid {{ $numberBackground }}; 
                                    border-radius: 50%; 
                                    display: flex; 
                                    align-items: center; 
                                    justify-content: center; 
                                    background: var(--page-bg-color, #fff); 
                                    color: {{ $numberText }}; 
                                    font-weight: bold; 
                                    font-size: 18px; 
                                    margin-right: 1rem; 
                                    flex-shrink: 0; 
                                    position: relative; 
                                    z-index: 2;
                                ">
                                    {{ $item['number'] ?? ($globalIndex + 1) }}
                                </div>
                                <div class="timeline-content" style="flex: 1;">
                                    <h3 style="color: {{ $titleColor }}; margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600;">
                                        {{ $item['title'] ?? 'Timeline Item' }}
                                    </h3>
                                    <p style="color: {{ $descriptionColor }}; margin: 0; line-height: 1.5;">
                                        {{ $item['description'] ?? 'Timeline description' }}
                                    </p>
                                </div>
                                @if($index < count($column) - 1)
                                    <div class="timeline-line" style="
                                        position: absolute; 
                                        left: 22px; 
                                        top: 50px; 
                                        width: 6px; 
                                        height: calc(100% + 2rem); 
                                        border-style: none dashed none none; 
                                        border-width: 3px; 
                                        border-color: {{ $lineColor }}; 
                                        z-index: 1;
                                    "></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            <style>
                @media (max-width: 768px) {
                    .numbered-timeline {
                        flex-direction: column !important;
                    }
                    .timeline-column {
                        min-width: auto !important;
                    }
                }
            </style>
        @break

        @case('investment-tier')
            @php
                $tierData = $component['investmentTierData'] ?? [];
                $tierName = $tierData['tierName'] ?? 'TIER 1';
                $tierPrice = $tierData['tierPrice'] ?? '$2,500';
                $tierDescription = $tierData['tierDescription'] ?? '';
                $buttonText = $tierData['buttonText'] ?? 'INVEST NOW';
                $buttonUrl = $tierData['buttonUrl'] ?? '#';
                $buttonTarget = $tierData['buttonTarget'] ?? '_self';
                $backgroundType = $tierData['backgroundType'] ?? 'color';
                $backgroundColor = $tierData['backgroundColor'] ?? '#f8f9fa';
                $backgroundImage = $tierData['backgroundImage'] ?? '';
                
                $backgroundStyle = 'background-color: ' . $backgroundColor . ';';
                if ($backgroundType === 'image' && !empty($backgroundImage)) {
                    $imageUrl = trim($backgroundImage);
                    if (!empty($imageUrl)) {
                        $backgroundStyle = 'background: linear-gradient(0deg, rgba(0,0,0,0.85) 80%, rgba(0,0,0,0.85) 100%), url(\'' . $imageUrl . '\') center/cover no-repeat;';
                    }
                }
            @endphp
            
            {{-- Add specific CSS to override any responsive margin conflicts --}}
            <style>
                #{{ $componentId }} .investment-tier {
                    margin: 0 auto !important;
                    max-width: 370px !important;
                }
            </style>
            
            <div class="investment-tier" style="{{ $backgroundStyle }} padding: 2rem; border-radius: 8px; text-align: center; color: white; margin: 0 auto !important; max-width: 370px;">
                <h2 style="color: white; margin: 0 0 1rem 0; font-size: 2rem; font-weight: bold;">{{ $tierName }}</h2>
                <div style="font-size: 3rem; font-weight: bold; margin: 1rem 0; color: white;">{{ $tierPrice }}</div>
                @if($tierDescription)
                    <p style="color: white; margin: 1rem 0; line-height: 1.6; font-size: 1.1rem;">{{ $tierDescription }}</p>
                @endif
                <a href="{{ $buttonUrl }}" target="{{ $buttonTarget }}" style="
                    display: inline-block; 
                    background: #22c55e; 
                    color: white; 
                    padding: 1rem 2rem; 
                    text-decoration: none; 
                    border-radius: 4px; 
                    font-weight: bold; 
                    margin-top: 1rem; 
                    transition: background 0.3s ease;
                " onmouseover="this.style.background='#16a34a'" onmouseout="this.style.background='#22c55e'">
                    {{ $buttonText }}
                </a>
            </div>
        @break

        @case('section-title')
            @php
                // Try multiple data sources for backwards compatibility
                $sectionTitleData = $component['sectionTitleData'] ?? [];
                // dd($component);
                $title = $sectionTitleData['title'] ?? $component['text'] ?? $component['textContent'] ?? $component['html'] ?? 'Section Title';
                $subtitle = $sectionTitleData['subtitle'] ?? '';
                $alignment = $sectionTitleData['alignment'] ?? $component['properties']['alignment'] ?? 'left';
                
                // Check if color is set in component styles
                $hasStyleColor = !empty($component['style']['color']);
                $titleColor = $hasStyleColor ? $component['style']['color'] : ($sectionTitleData['titleColor'] ?? '#1f2937');
                $subtitleColor = $sectionTitleData['subtitleColor'] ?? '#6b7280';
                
                // Clean HTML from title if it exists
                $title = strip_tags($title);
                
                // Remove color from styleStr if we're going to apply it specifically to elements
                $filteredStyleStr = $styleStr;
                if ($hasStyleColor) {
                    $filteredStyleStr = preg_replace('/color\s*:[^;]+;?/', '', $styleStr);
                }
            @endphp
            <div class="section-title" style="text-align: {{ $alignment }} !important; margin: 2rem 0; {{ $filteredStyleStr }}">
                <h2 style="color: {{ $titleColor }}; margin: 0 0 1rem 0; font-size: 2.5rem; font-weight: bold;">{{ $title }}</h2>
                @if($subtitle)
                    <p style="color: {{ $subtitleColor }}; margin: 0; font-size: 1.25rem; line-height: 1.6;">{{ $subtitle }}</p>
                @endif
            </div>
        @break

        @case('video')
            @php
                // Handle multiple data formats for video - check all possible locations
                $allComponentData = $component;
                $videoData = null;
                
                // Try different possible keys where video data might be stored
                if (isset($component['videoData'])) {
                    $videoData = $component['videoData'];
                } elseif (isset($component['_videoData'])) {
                    $videoData = $component['_videoData'];
                } elseif (isset($component['properties']['videoData'])) {
                    $videoData = $component['properties']['videoData'];
                } elseif (isset($component['content']['_videoData'])) {
                    $videoData = $component['content']['_videoData'];
                } else {
                    // NEW: Extract from HTML if videoData is missing
                    $videoData = [
                        'url' => '',
                        'type' => 'youtube',
                        'autoplay' => false,
                        'width' => null,
                        'height' => null
                    ];
                    
                    // Try to extract video info from HTML content
                    if (isset($component['html']) && !empty($component['html'])) {
                        $html = $component['html'];
                        
                        // Check for uploaded video (video tag with source)
                        if (preg_match('/<source\s+src="([^"]+)"/', $html, $matches)) {
                            $videoData['url'] = $matches[1];
                            $videoData['type'] = 'uploaded';
                            
                            // Check for autoplay
                            if (strpos($html, 'autoplay') !== false) {
                                $videoData['autoplay'] = true;
                            }
                            
                            // Try to extract width and height
                            if (preg_match('/width="([^"]+)"/', $html, $widthMatch)) {
                                $width = $widthMatch[1];
                                if (is_numeric($width)) {
                                    $videoData['width'] = (int)$width;
                                }
                            }
                            if (preg_match('/height="([^"]+)"/', $html, $heightMatch)) {
                                $height = $heightMatch[1];
                                if (is_numeric($height)) {
                                    $videoData['height'] = (int)$height;
                                }
                            }
                        }
                        // Check for YouTube iframe
                        elseif (preg_match('/<iframe[^>]+src="([^"]*youtube[^"]*)"/', $html, $matches)) {
                            $videoData['url'] = $matches[1];
                            $videoData['type'] = 'youtube';
                            
                            // Check for autoplay in iframe src
                            if (strpos($matches[1], 'autoplay=1') !== false) {
                                $videoData['autoplay'] = true;
                            }
                        }
                    }
                    
                    // Fallback: check direct component properties
                    if (empty($videoData['url'])) {
                        $videoData['url'] = $component['videoUrl'] ?? $component['src'] ?? $component['url'] ?? '';
                        $videoData['type'] = $component['videoType'] ?? $component['type'] ?? 'youtube';
                        $videoData['autoplay'] = $component['autoplay'] ?? false;
                        $videoData['width'] = $component['width'] ?? null;
                        $videoData['height'] = $component['height'] ?? null;
                    }
                }
                
                // Ensure videoData is an array
                if (!is_array($videoData)) {
                    $videoData = [];
                }
                
                $videoUrl = $videoData['url'] ?? '';
                $videoType = $videoData['type'] ?? 'youtube';
                $autoplay = $videoData['autoplay'] ?? false;
                $customWidth = isset($videoData['width']) && $videoData['width'] ? $videoData['width'] . 'px' : '100%';
                $customHeight = isset($videoData['height']) && $videoData['height'] ? $videoData['height'] . 'px' : 'auto';
                
                // Convert YouTube URLs to embed format 
                if ($videoType === 'youtube' && !empty($videoUrl)) {
                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                        $videoId = $matches[1];
                        $autoplayParam = $autoplay ? '&autoplay=1&mute=1' : '';
                        $embedUrl = "https://www.youtube.com/embed/{$videoId}?rel=0{$autoplayParam}";
                    } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $videoUrl, $matches)) {
                        $videoId = $matches[1];
                        $autoplayParam = $autoplay ? '&autoplay=1&mute=1' : '';
                        $embedUrl = "https://www.youtube.com/embed/{$videoId}?rel=0{$autoplayParam}";
                    } else {
                        $embedUrl = $videoUrl;
                    }
                } else {
                    $embedUrl = $videoUrl;
                }
            @endphp
            {{ $videoData['type'] }}
            <div style="{{ $styleStr }}">
                @if($videoUrl)
                    @if($videoType === 'uploaded')
                        <!-- Uploaded video file -->
                        <div class="video-container" style="width: {{ $customWidth }}; max-width: 100%; border-radius: 8px; overflow: hidden;">
                            <video 
                                width="100%" 
                                height="{{ $customHeight === 'auto' ? 'auto' : $customHeight }}"
                                controls 
                                @if($autoplay) autoplay muted @endif 
                                style="display: block; {{ $styleStr }}"
                                preload="metadata">
                                <source src="{{ $videoUrl }}" type="video/mp4">
                                <source src="{{ $videoUrl }}" type="video/webm">
                                <source src="{{ $videoUrl }}" type="video/ogg">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @else
                        <!-- YouTube video -->
                        <div class="video-container" style="width: {{ $customWidth }}; max-width: 100%; {{ $customHeight !== 'auto' ? 'height: ' . $customHeight . ';' : 'height: 0; padding-bottom: 56.25%;' }} position: relative; overflow: hidden;">
                            <iframe 
                                src="{{ $embedUrl }}" 
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                                allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                            </iframe>
                        </div>
                    @endif
                @else
                    <div style="background: #f3f4f6; padding: 2rem; text-align: center; border-radius: 8px;">
                        <p style="color: #6b7280; margin: 0;">No video provided</p>
                        <details style="margin-top: 1rem; text-align: left;">
                            <summary style="cursor: pointer; color: #9ca3af;">Debug Info</summary>
                            <pre style="background: #374151; color: #f3f4f6; padding: 1rem; border-radius: 4px; font-size: 12px; overflow: auto;">All Component Data: {{ json_encode($allComponentData, JSON_PRETTY_PRINT) }}
                                
Extracted Video Data: {{ json_encode($videoData, JSON_PRETTY_PRINT) }}</pre>
                        </details>
                    </div>
                @endif
            </div>
        @break

        @case('alert-message')
            @php
                $alertData = $component['alertData'] ?? [];
                $message = $alertData['message'] ?? 'Alert message';
                $type = $alertData['type'] ?? 'info';
                $dismissible = $alertData['dismissible'] ?? false;
                
                $alertColors = [
                    'success' => ['bg' => '#d4edda', 'text' => '#155724', 'border' => '#c3e6cb'],
                    'danger' => ['bg' => '#f8d7da', 'text' => '#721c24', 'border' => '#f5c6cb'],
                    'warning' => ['bg' => '#fff3cd', 'text' => '#856404', 'border' => '#ffeaa7'],
                    'info' => ['bg' => '#d1ecf1', 'text' => '#0c5460', 'border' => '#bee5eb']
                ];
                $colors = $alertColors[$type] ?? $alertColors['info'];
            @endphp
            <div class="alert alert-{{ $type }}" style="
                background-color: {{ $colors['bg'] }}; 
                color: {{ $colors['text'] }}; 
                border: 1px solid {{ $colors['border'] }}; 
                padding: 1rem; 
                border-radius: 4px; 
                margin: 1rem 0;
                {{ $dismissible ? 'position: relative; padding-right: 3rem;' : '' }}
            ">
                {{ $message }}
                @if($dismissible)
                    <button type="button" style="
                        position: absolute; 
                        right: 1rem; 
                        top: 50%; 
                        transform: translateY(-50%); 
                        background: none; 
                        border: none; 
                        font-size: 1.5rem; 
                        cursor: pointer; 
                        color: {{ $colors['text'] }};
                    " onclick="this.parentElement.style.display='none'">×</button>
                @endif
            </div>
        @break

        @case('press-card')
            @php
                $pressCardData = $component['pressCardData'] ?? [];
                $logoSrc = $pressCardData['logoSrc'] ?? 'https://via.placeholder.com/200x80?text=Press+Logo';
                $logoAlt = $pressCardData['logoAlt'] ?? 'Press Logo';
                $title = $pressCardData['title'] ?? 'Press Article Title';
                $url = $pressCardData['url'] ?? '#';
                $date = $pressCardData['date'] ?? 'Date';
                $target = $pressCardData['target'] ?? '_blank';
                $cardBackgroundColor = $pressCardData['cardBackgroundColor'] ?? '#ffffff';
                $cardBorderRadius = $pressCardData['cardBorderRadius'] ?? '8px';
                $cardBoxShadow = $pressCardData['cardBoxShadow'] ?? '0 2px 8px rgba(0,0,0,0.1)';
                $overlayOpacity = $pressCardData['overlayOpacity'] ?? '0.1';
                $logoBackgroundColor = $pressCardData['logoBackgroundColor'] ?? '#f8f9fa';
                $titleColor = $pressCardData['titleColor'] ?? '#1a1a1a';
                $dateColor = $pressCardData['dateColor'] ?? '#666666';
            @endphp
            <div style="{{ $styleStr }}">
                <div class="press-card-2" style="
                    position: relative;
                    background: {{ $cardBackgroundColor }};
                    border-radius: {{ $cardBorderRadius }};
                    overflow: hidden;
                    box-shadow: {{ $cardBoxShadow }};
                    transition: all 0.3s ease;
                    cursor: pointer;
                    max-width: 400px;
                    margin: 0 auto;
                ">
                    <!-- Press Logo -->
                    <div style="padding: 20px; text-align: center; background: {{ $logoBackgroundColor }};">
                        <img src="{{ $logoSrc }}" 
                             alt="{{ $logoAlt }}" 
                             style="max-width: 150px; height: auto; filter: brightness(0);" 
                             class="press-logo">
                    </div>
                    
                    <!-- Press Content -->
                    <a href="{{ $url }}" 
                       target="{{ $target }}" 
                       style="
                           display: block;
                           text-decoration: none;
                           color: inherit;
                           padding: 20px;
                           position: relative;
                       "
                       class="press-link">
                        <div class="press-text-wrapper" style="margin-bottom: 15px;">
                            <div style="
                                font-size: 16px;
                                font-weight: 600;
                                line-height: 1.4;
                                color: {{ $titleColor }};
                                margin-bottom: 10px;
                                display: flex;
                                align-items: flex-start;
                                justify-content: space-between;
                                gap: 10px;
                            ">
                                <span>{{ $title }}</span>
                                <div style="
                                    width: 16px;
                                    height: 16px;
                                    flex-shrink: 0;
                                    margin-top: 2px;
                                    color: {{ $titleColor }};
                                ">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                         width="100%" height="100%" 
                                         viewBox="0 0 32 32" 
                                         fill="currentColor">
                                        <path d="M10 6v2h12.59L6 24.59L7.41 26L24 9.41V22h2V6z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="press-date" style="
                            color: {{ $dateColor }};
                            font-size: 14px;
                            font-weight: 400;
                        ">{{ $date }}</div>
                    </a>
                    
                    <!-- Black Overlay (Always visible with adjustable opacity) -->
                    <div class="black-overlay" style="
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(0,0,0,{{ $overlayOpacity }});
                        transition: opacity 0.3s ease;
                        pointer-events: none;
                    "></div>
                </div>
                
                <style>
                    .press-card-2:hover .black-overlay {
                        opacity: 1;
                        background: rgba(0,0,0,{{ number_format((float)$overlayOpacity + 0.1, 1) }});
                    }
                    
                    .press-card-2:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
                    }
                    
                    .press-link:hover {
                        text-decoration: none !important;
                    }
                </style>
            </div>
        @break

        @case('text-images')
            @php
                $textImagesData = $component['textImagesData'] ?? [];
                $text = $textImagesData['text'] ?? 'Your text here';
                $imgSrc = $textImagesData['imgSrc'] ?? '';
                $imgPosition = $textImagesData['imgPosition'] ?? 'left';
                $imgSize = $textImagesData['imgSize'] ?? 200;
                $imgWidth = $textImagesData['imgWidth'] ?? $imgSize;
                $imgHeight = $textImagesData['imgHeight'] ?? 'auto';
                $showImage = $textImagesData['showImage'] ?? true;
                // Ensure width/height have px if numeric
                if (is_numeric($imgWidth)) {
                    $imgWidth = $imgWidth;
                }
                if (is_numeric($imgHeight)) {
                    $imgHeight = $imgHeight . 'px';
                }
            @endphp
            
            <div style="{{ $styleStr }}">
                @if($imgPosition === 'up')
                    <div class="row">
                        <div class="col-12">
                            @if($showImage && $imgSrc)
                                <div class="text-center mb-3">
                                    <img src="{{ $imgSrc }}" style="max-width:100%;width:{{ $imgWidth }}px;height:{{ $imgHeight }};object-fit:cover;" alt="" class="img-fluid">
                                </div>
                            @endif
                            <div class="text-content">
                                {!! $text !!}
                            </div>
                        </div>
                    </div>
                @elseif($imgPosition === 'down')
                    <div class="row">
                        <div class="col-12">
                            <div class="text-content mb-3">
                                {!! $text !!}
                            </div>
                            @if($showImage && $imgSrc)
                                <div class="text-center">
                                    <img src="{{ $imgSrc }}" style="max-width:100%;width:{{ $imgWidth }}px;height:{{ $imgHeight }};object-fit:cover;" alt="" class="img-fluid">
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($imgPosition === 'right')
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-7 col-sm-12">
                            <div class="text-content">
                                {!! $text !!}
                            </div>
                        </div>
                        @if($showImage && $imgSrc)
                            <div class="col-lg-6 col-md-5 col-sm-12 text-center">
                                <img src="{{ $imgSrc }}" style="max-width:100%;width:{{ $imgWidth }}px;height:{{ $imgHeight }};object-fit:cover;" alt="" class="img-fluid">
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Left position --}}
                    <div class="row align-items-center">
                        @if($showImage && $imgSrc)
                            <div class="col-lg-6 col-md-5 col-sm-12 text-center">
                                <img src="{{ $imgSrc }}" style="max-width:100%;width:{{ $imgWidth }}px;height:{{ $imgHeight }};object-fit:cover;" alt="" class="img-fluid">
                            </div>
                        @endif
                        <div class="col-lg-6 col-md-7 col-sm-12">
                            <div class="text-content">
                                {!! $text !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @break
                    {{-- <div style="flex:1;">
                        <div style="margin:0;">{!! $text !!}</div>
                    </div>
                </div>
            @endif --}}
        @break

        @default
            {{-- Fallback for any unhandled component types --}}
            <div style="{{ $styleStr }}">
                @if(isset($component['html']))
                    {!! $component['html'] !!}
                @else
                    <p>Component: {{ $componentType }}</p>
                @endif
            </div>
    @endswitch
</div>
