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
.ql-font-outfit { font-family: 'Outfit', sans-serif !important; }

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
.ql-font-outfit { font-family: 'Outfit', sans-serif !important; }
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
                
                // Apply inner-section styling - ENABLE ALL STYLES for frontend
                $sectionStyle = '';
                
                // Background color - always apply if set
                if (isset($innerSectionData['backgroundColor']) && $innerSectionData['backgroundColor'] !== '' && $innerSectionData['backgroundColor'] !== 'transparent') {
                    $sectionStyle .= "background-color: {$innerSectionData['backgroundColor']} !important;";
                }
                
                // Padding - always apply if set
                if (isset($innerSectionData['padding']) && $innerSectionData['padding'] !== '') {
                    $sectionStyle .= "padding: {$innerSectionData['padding']} !important;";
                }
                
                // Margin - always apply if set
                if (isset($innerSectionData['margin']) && $innerSectionData['margin'] !== '') {
                    $sectionStyle .= "margin: {$innerSectionData['margin']} !important;";
                }
                
                // Border - always apply if set
                if (isset($innerSectionData['border']) && $innerSectionData['border'] !== '') {
                    $sectionStyle .= "border: {$innerSectionData['border']} !important;";
                }
                
                // Border radius - always apply if set
                if (isset($innerSectionData['borderRadius']) && $innerSectionData['borderRadius'] !== '') {
                    $sectionStyle .= "border-radius: {$innerSectionData['borderRadius']} !important;";
                }
                
                // Handle background image with parallax support
                if (isset($innerSectionData['backgroundType']) && $innerSectionData['backgroundType'] === 'image' && !empty($innerSectionData['backgroundImage'])) {
                    $imageUrl = trim($innerSectionData['backgroundImage']);
                    if (!empty($imageUrl)) {
                        // Get background attachment setting (scroll, fixed, local)
                        $backgroundAttachment = $innerSectionData['backgroundAttachment'] ?? 'scroll';
                        
                        // FIXED: Use same background syntax as page-builder for consistency
                        $sectionStyle .= "background-image: linear-gradient(#000,#000c 18%), url({$imageUrl}) !important; ";
                        $sectionStyle .= "background-position: 0 0, 50% !important; ";
                        $sectionStyle .= "background-size: auto, cover !important; ";
                        $sectionStyle .= "background-attachment: scroll, {$backgroundAttachment} !important; ";
                        $sectionStyle .= "background-repeat: no-repeat !important; ";
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
                <div class="inner-section-fullwidth" id="{{ $componentId }}" style="{{ $sectionStyle }}">
                    <style>
                        #{{ $componentId }} {
                            width: 100vw;
                            position: relative;
                            left: 50%;
                            transform: translateX(-50%);
                            box-sizing: border-box;
                        }
                        
                        @if($contentWidth === 'boxed')
                        /* Boxed content - keep components centered like a regular container */
                        #{{ $componentId }} .content-wrapper {
                            max-width: 1200px;
                            margin: 0 auto;
                            padding: 0 15px;
                        }
                        #{{ $componentId }} .row {
                            margin: 0;
                        }
                        @else
                        /* Full width content - components spread across full width */
                        #{{ $componentId }} .row {
                            margin: 0;
                            width: 100%;
                            max-width: 100%;
                        }
                        @endif
                        
                        /* Custom gap handling for full width */
                        @if($gap !== '0px' && $gap !== '15px')
                        #{{ $componentId }} .row > [class*="col-"] {
                            padding-left: calc({{ $gap }} / 2);
                            padding-right: calc({{ $gap }} / 2);
                        }
                        #{{ $componentId }} .row {
                            margin-left: calc(-{{ $gap }} / 2) !important;
                            margin-right: calc(-{{ $gap }} / 2) !important;
                        }
                        @else
                        /* Default Bootstrap gutters */
                        #{{ $componentId }} .row > [class*="col-"] {
                            padding-left: 15px;
                            padding-right: 15px;
                        }
                        @endif
                        
                        /* Parallax background support - Force fixed attachment */
                        @if(isset($innerSectionData['backgroundType']) && $innerSectionData['backgroundType'] === 'image' && 
                            !empty($innerSectionData['backgroundImage']) && 
                            isset($innerSectionData['backgroundAttachment']) && $innerSectionData['backgroundAttachment'] === 'fixed')
                        #{{ $componentId }} {
                            background-attachment: fixed !important;
                            background-position: center center !important;
                            background-size: cover !important;
                            background-repeat: no-repeat !important;
                        }
                        @endif
                        
                        /* Mobile responsiveness fixes - Enhanced for true full-width */
                        @media (max-width: 767px) {
                            #{{ $componentId }} {
                                width: 100vw !important;
                                position: relative !important;
                                left: 50% !important;
                                transform: translateX(-50%) !important;
                                margin-left: 0 !important;
                                margin-right: 0 !important;
                                max-width: none !important;
                                padding-left: 0 !important;
                                padding-right: 0 !important;
                                box-sizing: border-box !important;
                            }
                            
                            /* Force parallax backgrounds to scroll on mobile */
                            #{{ $componentId }}[style*="background-attachment"] {
                                background-attachment: scroll !important;
                            }
                            
                            @if($contentWidth === 'boxed')
                            #{{ $componentId }} .content-wrapper {
                                padding: 0 15px !important;
                                max-width: 100% !important;
                            }
                            @else 
                            #{{ $componentId }} .row {
                                margin: 0 !important;
                                padding: 0 15px !important;
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
                                                @php $nestedComponentId = "{$componentId}-nested-{$columnIndex}-{$nestedIndex}"; @endphp
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
                                            @php $nestedComponentId = "{$componentId}-nested-{$columnIndex}-{$nestedIndex}"; @endphp
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
                <div class="inner-section-frontend" id="{{ $componentId }}" style="{{ $sectionStyle }}">
                    <style>
                        #{{ $componentId }} {
                            max-width: 1200px;
                            margin: 0 auto;
                            padding: 0 15px;
                        }
                        
                        @if($gap !== '0px' && $gap !== '15px')
                        /* Custom gap using CSS variables and margin */
                        #{{ $componentId }} .row > [class*="col-"] {
                            padding-left: calc({{ $gap }} / 2);
                            padding-right: calc({{ $gap }} / 2);
                        }
                        #{{ $componentId }} .row {
                            margin-left: calc(-{{ $gap }} / 2);
                            margin-right: calc(-{{ $gap }} / 2);
                        }
                        @endif
                    </style>
                    @if($gap !== '0px' && $gap !== '15px')
                        <div class="row">
                            @for($columnIndex = 0; $columnIndex < $columns; $columnIndex++)
                                <div class="{{ $bootstrapClass }}">
                                    @if(isset($nestedComponents[$columnIndex]) && is_array($nestedComponents[$columnIndex]))
                                        @foreach($nestedComponents[$columnIndex] as $nestedIndex => $nestedComponent)
                                            @php $nestedComponentId = "{$componentId}-nested-{$columnIndex}-{$nestedIndex}"; @endphp
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
                                            @php $nestedComponentId = "{$componentId}-nested-{$columnIndex}-{$nestedIndex}"; @endphp
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
            @php
                // Debug: Log the component data structure
                error_log("FAQ COMPONENT DEBUG: " . json_encode($component));
                
                $faqData = $component['_faqData'] ?? $component['faqData'] ?? [
                    'questions' => [],
                    'questionBackgroundColor' => '#f3f4f6',
                    'questionTextColor' => '#1f2937',
                    'answerBackgroundColor' => '#ffffff',
                    'answerTextColor' => '#374151',
                    'iconColor' => '#059669',
                    'borderRadius' => '8px',
                    'spacing' => '10px'
                ];
                
                // Debug: Log the extracted FAQ data
                error_log("FAQ DATA EXTRACTED: " . json_encode($faqData));
            @endphp
            <div id="{{ $componentId }}" class="faq-component" style="{{ $styleStr }}">
                @php
                    error_log("FAQ QUESTIONS CHECK: " . (empty($faqData['questions']) ? 'EMPTY' : 'HAS DATA'));
                    error_log("FAQ QUESTIONS COUNT: " . count($faqData['questions'] ?? []));
                @endphp
                @if(!empty($faqData['questions']))
                    <div class="faq-container" style="max-width: 100%;">
                        @foreach($faqData['questions'] as $index => $item)
                            <div class="faq-item" style="
                                margin-bottom: {{ $faqData['spacing'] }};
                                border-radius: {{ $faqData['borderRadius'] }};
                                overflow: hidden;
                                border: 1px solid #e5e7eb;
                            ">
                                <div class="faq-question" style="
                                    background-color: {{ $faqData['questionBackgroundColor'] }};
                                    color: {{ $faqData['questionTextColor'] }};
                                    padding: 16px 20px;
                                    cursor: pointer;
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    font-weight: 500;
                                    font-size: 16px;
                                    user-select: none;
                                " onclick="toggleFrontendFaqItem(this, {{ $index }})">
                                    <span>{{ $item['question'] ?? 'Question' }}</span>
                                    <div class="faq-icon" style="
                                        width: 32px;
                                        height: 32px;
                                        border-radius: 50%;
                                        background-color: {{ $faqData['iconColor'] }};
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: white;
                                        font-weight: bold;
                                        font-size: 18px;
                                        flex-shrink: 0;
                                        margin-left: 15px;
                                    ">+</div>
                                </div>
                                <div class="faq-answer" style="
                                    background-color: {{ $faqData['answerBackgroundColor'] }};
                                    color: {{ $faqData['answerTextColor'] }};
                                    padding: 0 20px;
                                    max-height: 0;
                                    overflow: hidden;
                                    transition: all 0.3s ease;
                                    font-size: 15px;
                                    line-height: 1.6;
                                ">{{ $item['answer'] ?? 'Answer' }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding: 40px; text-align: center; background: #f9fafb; border: 2px dashed #d1d5db; border-radius: 8px;">
                        <p style="color: #6b7280; margin: 0;">No FAQ questions added yet.</p>
                        <!-- DEBUG INFO -->
                        <details style="margin-top: 10px; text-align: left;">
                            <summary style="cursor: pointer; color: #059669;">Debug Info (Remove in production)</summary>
                            <pre style="background: #1f2937; color: #fff; padding: 10px; margin-top: 10px; font-size: 12px; overflow-x: auto;">
Component Data: {{ json_encode($component, JSON_PRETTY_PRINT) }}

FAQ Data: {{ json_encode($faqData, JSON_PRETTY_PRINT) }}

Questions Empty: {{ empty($faqData['questions']) ? 'YES' : 'NO' }}
Questions Count: {{ count($faqData['questions'] ?? []) }}
                            </pre>
                        </details>
                    </div>
                @endif
            </div>
            
            <script>
                function toggleFrontendFaqItem(questionElement, index) {
                    const faqItem = questionElement.closest('.faq-item');
                    const answerElement = faqItem.querySelector('.faq-answer');
                    const iconElement = questionElement.querySelector('.faq-icon');
                    const faqContainer = questionElement.closest('.faq-container');
                    
                    // Close all other items (accordion behavior)
                    if (faqContainer) {
                        const allItems = faqContainer.querySelectorAll('.faq-item');
                        allItems.forEach((item, i) => {
                            if (i !== index) {
                                const otherAnswer = item.querySelector('.faq-answer');
                                const otherIcon = item.querySelector('.faq-icon');
                                otherAnswer.style.maxHeight = '0';
                                otherAnswer.style.padding = '0 20px';
                                otherIcon.textContent = '+';
                            }
                        });
                    }
                    
                    // Toggle current item
                    const isExpanded = answerElement.style.maxHeight !== '0px' && answerElement.style.maxHeight !== '';
                    
                    if (isExpanded) {
                        answerElement.style.maxHeight = '0';
                        answerElement.style.padding = '0 20px';
                        iconElement.textContent = '+';
                    } else {
                        answerElement.style.maxHeight = '1000px';
                        answerElement.style.padding = '20px';
                        iconElement.textContent = '−';
                    }
                }
            </script>
        @break

        @case('simple-comments')
            @php
                $simpleCommentsData = $component['_simpleCommentsData'] ?? $component['simpleCommentsData'] ?? [
                    'title' => 'Comments',
                    'showTitle' => true,
                    'allowAnonymous' => true,
                    'moderationEnabled' => false,
                    'requireEmail' => true,
                    'maxComments' => 100,
                    'sortOrder' => 'newest',
                    'backgroundColor' => '#ffffff',
                    'borderColor' => '#e0e0e0',
                    'textColor' => '#333333',
                    'buttonColor' => '#007bff'
                ];
                
                // Get the current page identifier and component ID for comments
                $pageIdentifier = request()->path();
                $componentId = $componentId ?? 'comments-' . uniqid();
                
                // Get website_id from context (available as $check variable)
                $websiteId = isset($check) ? $check->id : (isset($website) ? $website->id : null);
                
                // If no website_id available, try to get from URL or set a default
                if (!$websiteId) {
                    // Try to get website by domain from request
                    $domain = request()->getHost();
                    $websiteFromDomain = \App\Models\Website::where('domain', $domain)->first();
                    $websiteId = $websiteFromDomain ? $websiteFromDomain->id : '1'; // fallback to website ID 1
                }
                
                // Fetch existing comments for this component - with error handling
                $existingComments = collect();
                try {
                    if (class_exists('\App\Models\PageComment') && $websiteId) {
                        $existingComments = \App\Models\PageComment::where('page_identifier', $pageIdentifier)
                            ->where('component_id', $componentId)
                            ->where('website_id', $websiteId)
                            ->where('is_approved', true)
                            ->whereNull('parent_id')
                            ->with(['replies' => function($query) use ($websiteId) {
                                $query->where('is_approved', true)
                                      ->where('website_id', $websiteId)
                                      ->orderBy('created_at', 'asc');
                            }])
                            ->orderBy('created_at', $simpleCommentsData['sortOrder'] === 'newest' ? 'desc' : 'asc')
                            ->limit($simpleCommentsData['maxComments'])
                            ->get();
                    }
                } catch (\Exception $e) {
                    // If there's any error fetching comments, just use empty collection
                    $existingComments = collect();
                }
            @endphp
            
            <div id="{{ $componentId }}" class="simple-comments-component" style="{{ $styleStr }}">
                <div style="
                    background: {{ $simpleCommentsData['backgroundColor'] }};
                    border: 1px solid {{ $simpleCommentsData['borderColor'] }};
                    border-radius: 8px;
                    padding: 20px;
                    color: {{ $simpleCommentsData['textColor'] }};
                ">
                    @if($simpleCommentsData['showTitle'])
                        <h3 style="margin: 0 0 20px 0; color: {{ $simpleCommentsData['textColor'] }};">
                            {{ $simpleCommentsData['title'] }}
                        </h3>
                    @endif
                    
                    <!-- Comment Form -->
                    <form id="comment-form-{{ $componentId }}" class="comment-form" style="margin-bottom: 30px;">
                        @csrf
                        <input type="hidden" name="page_identifier" value="{{ $pageIdentifier }}">
                        <input type="hidden" name="component_id" value="{{ $componentId }}">
                        <input type="hidden" name="website_id" value="{{ $websiteId }}">
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div>
                                <input type="text" name="author_name" placeholder="Your Name" required
                                       style="width: 100%; padding: 10px; border: 1px solid {{ $simpleCommentsData['borderColor'] }}; border-radius: 4px;">
                            </div>
                            @if($simpleCommentsData['requireEmail'])
                                <div>
                                    <input type="email" name="author_email" placeholder="Your Email" required
                                           style="width: 100%; padding: 10px; border: 1px solid {{ $simpleCommentsData['borderColor'] }}; border-radius: 4px;">
                                </div>
                            @endif
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <textarea name="comment" placeholder="Write your comment..." required
                                      style="width: 100%; padding: 10px; border: 1px solid {{ $simpleCommentsData['borderColor'] }}; border-radius: 4px; min-height: 100px; resize: vertical;"></textarea>
                        </div>
                        
                        @if($simpleCommentsData['allowAnonymous'])
                            <div style="margin-bottom: 15px;">
                                <label style="display: flex; align-items: center; font-size: 14px;">
                                    <input type="checkbox" name="is_anonymous" value="1" style="margin-right: 8px;">
                                    Post as Anonymous
                                </label>
                            </div>
                        @endif
                        
                        <button type="submit" style="
                            background: {{ $simpleCommentsData['buttonColor'] }};
                            color: white;
                            border: none;
                            padding: 10px 20px;
                            border-radius: 4px;
                            cursor: pointer;
                            font-size: 16px;
                        ">
                            Post Comment
                        </button>
                        
                        @if($simpleCommentsData['moderationEnabled'])
                            <p style="font-size: 12px; color: #666; margin-top: 10px;">
                                <i class="fas fa-info-circle"></i> Comments are moderated and may take some time to appear.
                            </p>
                        @endif
                    </form>
                    
                    <!-- Comments List -->
                    <div id="comments-list-{{ $componentId }}" class="comments-list">
                        @if($existingComments->count() > 0)
                            @foreach($existingComments as $comment)
                                <div class="comment-item" style="
                                    background: #f8f9fa;
                                    border-left: 4px solid {{ $simpleCommentsData['buttonColor'] }};
                                    padding: 15px;
                                    border-radius: 4px;
                                    margin-bottom: 15px;
                                ">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                        <strong style="color: {{ $simpleCommentsData['textColor'] }};">
                                            {{ $comment->is_anonymous ? 'Anonymous' : $comment->author_name }}
                                        </strong>
                                        <small style="color: #666;">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p style="margin: 0; color: {{ $simpleCommentsData['textColor'] }}; line-height: 1.5;">
                                        {{ $comment->comment }}
                                    </p>
                                    
                                    <!-- Replies -->
                                    @if($comment->replies && $comment->replies->count() > 0)
                                        <div style="margin-top: 15px; padding-left: 20px; border-left: 2px solid #dee2e6;">
                                            @foreach($comment->replies as $reply)
                                                <div style="
                                                    background: #ffffff;
                                                    padding: 10px;
                                                    border-radius: 4px;
                                                    margin-bottom: 10px;
                                                    border: 1px solid {{ $simpleCommentsData['borderColor'] }};
                                                ">
                                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                                        <strong style="color: {{ $simpleCommentsData['textColor'] }}; font-size: 14px;">
                                                            {{ $reply->is_anonymous ? 'Anonymous' : $reply->author_name }}
                                                        </strong>
                                                        <small style="color: #666; font-size: 12px;">{{ $reply->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <p style="margin: 0; color: {{ $simpleCommentsData['textColor'] }}; font-size: 14px; line-height: 1.4;">
                                                        {{ $reply->comment }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div style="
                                text-align: center;
                                padding: 40px 20px;
                                color: #666;
                                font-style: italic;
                            ">
                                <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 16px; color: #ccc;"></i>
                                <p>No comments yet. Be the first to share your thoughts!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('comment-form-{{ $componentId }}').addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.textContent;
                    
                    // Handle checkbox properly - if not checked, explicitly set to false
                    const anonymousCheckbox = this.querySelector('input[name="is_anonymous"]');
                    if (anonymousCheckbox && !anonymousCheckbox.checked) {
                        formData.set('is_anonymous', '0');
                    }
                    
                    // Show loading state
                    submitButton.textContent = 'Posting...';
                    submitButton.disabled = true;
                    
                    // Get CSRF token - try multiple methods
                    let csrfToken = '';
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfInput = document.querySelector('input[name="_token"]');
                    
                    if (csrfMeta) {
                        csrfToken = csrfMeta.getAttribute('content');
                    } else if (csrfInput) {
                        csrfToken = csrfInput.value;
                    } else {
                        // Get token from the form's CSRF input
                        const formCsrf = this.querySelector('input[name="_token"]');
                        if (formCsrf) {
                            csrfToken = formCsrf.value;
                        }
                    }
                    
                    // Prepare headers
                    const headers = {};
                    if (csrfToken) {
                        headers['X-CSRF-TOKEN'] = csrfToken;
                    }
                    
                    fetch('/comments', {
                        method: 'POST',
                        body: formData,
                        headers: headers
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reset form
                            this.reset();
                            
                            // Show success message
                            const successMsg = document.createElement('div');
                            successMsg.style.cssText = 'background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;';
                            successMsg.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                            this.parentNode.insertBefore(successMsg, this.nextSibling);
                            
                            // Remove success message after 5 seconds
                            setTimeout(() => successMsg.remove(), 5000);
                            
                            // Reload comments if not moderated
                            @if(!$simpleCommentsData['moderationEnabled'])
                                setTimeout(() => location.reload(), 1000);
                            @endif
                        } else {
                            throw new Error(data.message || 'Failed to post comment');
                        }
                    })
                    .catch(error => {
                        console.error('Comment submission error:', error);
                        // Show error message
                        const errorMsg = document.createElement('div');
                        errorMsg.style.cssText = 'background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;';
                        errorMsg.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + (error.message || 'Failed to submit comment. Please try again.');
                        this.parentNode.insertBefore(errorMsg, this.nextSibling);
                        
                        // Remove error message after 5 seconds
                        setTimeout(() => errorMsg.remove(), 5000);
                    })
                    .finally(() => {
                        // Reset button state
                        submitButton.textContent = originalText;
                        submitButton.disabled = false;
                    });
                });
            </script>
        @break

        @case('disqus')
            @php
                $disqusData = $component['_disqusData'] ?? $component['disqusData'] ?? [
                    'shortname' => '',
                    'identifier' => '',
                    'title' => '',
                    'url' => '',
                    'showInPreview' => true
                ];
                
                // Fallback to empty if no shortname provided
                if (empty($disqusData['shortname'])) {
                    echo '<div style="padding: 40px 20px; text-align: center; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; color: #6c757d;">
                        <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 16px; color: #adb5bd;"></i>
                        <h4 style="margin: 0 0 8px 0; color: #495057;">Disqus Comments</h4>
                        <p style="margin: 0; font-size: 14px;">Configure your Disqus shortname to enable comments.</p>
                    </div>';
                    return;
                }
            @endphp
            
            <div id="{{ $componentId }}" class="disqus-component" style="{{ $styleStr }}">
                <div id="disqus_thread_{{ $componentId }}"></div>
                
                <script>
                    (function() {
                        // Generate unique identifier for this instance
                        const componentId = '{{ $componentId }}';
                        const disqusThread = document.getElementById('disqus_thread_' + componentId);
                        
                        // Disqus configuration variables
                        var disqus_config = function () {
                            @if(!empty($disqusData['identifier']))
                                this.page.identifier = '{{ $disqusData['identifier'] }}';
                            @else
                                this.page.identifier = window.location.pathname;
                            @endif
                            
                            @if(!empty($disqusData['url']))
                                this.page.url = '{{ $disqusData['url'] }}';
                            @else
                                this.page.url = window.location.href;
                            @endif
                            
                            @if(!empty($disqusData['title']))
                                this.page.title = '{{ addslashes($disqusData['title']) }}';
                            @else
                                this.page.title = document.title;
                            @endif
                        };
                        
                        // Load Disqus script
                        var d = document, s = d.createElement('script');
                        s.src = 'https://{{ $disqusData['shortname'] }}.disqus.com/embed.js';
                        s.setAttribute('data-timestamp', +new Date());
                        
                        // Override DISQUS global to use our specific thread container
                        window.DISQUS = window.DISQUS || {};
                        const originalReset = window.DISQUS.reset;
                        
                        s.onload = function() {
                            // If Disqus is already loaded, reset it for this container
                            if (window.DISQUS && window.DISQUS.reset) {
                                window.DISQUS.reset({
                                    reload: true,
                                    config: disqus_config
                                });
                            }
                        };
                        
                        (d.head || d.body).appendChild(s);
                        
                        // Set the container for Disqus to use
                        window.disqus_container_id = 'disqus_thread_' + componentId;
                    })();
                </script>
                
                <noscript>
                    <div style="padding: 20px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; text-align: center; color: #6c757d;">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        Please enable JavaScript to view the 
                        <a href="https://disqus.com/?ref_noscript" style="color: #007bff;">comments powered by Disqus.</a>
                    </div>
                </noscript>
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
                
                // Extract numeric value from tier price for URL parameter
                $numericPrice = preg_replace('/[^0-9.,]/', '', $tierPrice);
                $numericPrice = str_replace(',', '', $numericPrice);
                
                // Always redirect to /invest with amount parameter
                $buttonUrl = '/invest?amount=' . urlencode($numericPrice);
                
                $backgroundStyle = 'background-color: ' . $backgroundColor . ';';
                if ($backgroundType === 'image' && !empty($backgroundImage)) {
                    $imageUrl = trim($backgroundImage);
                    if (!empty($imageUrl)) {
                        $backgroundStyle = 'background: linear-gradient(0deg, rgba(0,0,0,0.85) 80%, rgba(0,0,0,0.85) 100%), url(\'' . $imageUrl . '\') center/cover no-repeat;';
                    }
                }
            @endphp
            
            {{-- Add specific CSS to override any responsive margin conflicts and ensure links work --}}
            <style>
                #{{ $componentId }} .investment-tier {
                    margin: 0 auto !important;
                    max-width: 370px !important;
                    position: relative !important;
                    z-index: 1 !important;
                }
                
                #{{ $componentId }} .investment-tier a {
                    pointer-events: auto !important;
                    position: relative !important;
                    z-index: 10 !important;
                    display: inline-block !important;
                }
                
                /* Ensure investment tier works in full-width sections */
                .inner-section-fullwidth #{{ $componentId }} .investment-tier,
                .inner-section-frontend #{{ $componentId }} .investment-tier {
                    pointer-events: auto !important;
                    position: relative !important;
                    z-index: 1 !important;
                }
                
                .inner-section-fullwidth #{{ $componentId }} .investment-tier a,
                .inner-section-frontend #{{ $componentId }} .investment-tier a {
                    pointer-events: auto !important;
                    position: relative !important;
                    z-index: 10 !important;
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
            
            {{-- Force responsive video styles --}}
            <style>
                #{{ $componentId }} .video-container {
                    width: 100% !important;
                    max-width: 100% !important;
                    position: relative !important;
                    overflow: hidden !important;
                }
                
                #{{ $componentId }} .video-container iframe,
                #{{ $componentId }} .video-container video {
                    width: 100% !important;
                    height: auto !important;
                    max-width: 100% !important;
                    display: block !important;
                }
                
                /* Force override custom dimensions */
                #{{ $componentId }} .video-container[style] {
                    width: 100% !important;
                    max-width: 100% !important;
                }
                
                #{{ $componentId }} .video-container[style] iframe,
                #{{ $componentId }} .video-container[style] video {
                    width: 100% !important;
                    height: auto !important;
                    max-width: 100% !important;
                }
                
                /* Mobile specific video fixes */
                @media (max-width: 768px) {
                    #{{ $componentId }} .video-container {
                        width: 100% !important;
                        height: auto !important;
                        padding-bottom: 56.25% !important;
                        position: relative !important;
                    }
                    
                    #{{ $componentId }} .video-container iframe,
                    #{{ $componentId }} .video-container video {
                        position: absolute !important;
                        top: 0 !important;
                        left: 0 !important;
                        width: 100% !important;
                        height: 100% !important;
                        min-height: 200px !important;
                        max-height: none !important;
                    }
                    
                    /* Force remove any custom dimensions on mobile */
                    #{{ $componentId }} .video-container[style*="width"],
                    #{{ $componentId }} .video-container[style*="height"] {
                        width: 100% !important;
                        height: auto !important;
                        padding-bottom: 56.25% !important;
                    }
                }
            </style>
            
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

        @case('auction-list')
            @php
                // Get current website based on domain
                $url = url()->current();
                $domain = parse_url($url, PHP_URL_HOST);
                $check = \App\Models\Website::where('domain', $domain)->first();
                $auction = \App\Models\Auction::where('website_id', $check->id ?? 1)->where('status',1)->latest()->get();
            @endphp
            
            <div class="c-content__bottom" style="{{ $wrapperStyleStr }}">
                <div class="u-wrap--auction-main">
                    <div id="ai-display" class="c-ai-display c-ai-display--full"><span></span>
                        <div class="o-wrapper c-ai-display__items c-ai-display__items--full" style="{{ $styleStr }}">
                            <div class="view-dom-id-ad4934c196a50f6f72cd5a8f4b22c874 js-view js-view-air-auction-items c-view c-view--air-auction-items c-view--display_teaser c-view--display-handler_block c-view--style_default jquery-once-2-processed jqo-vr-processed"
                                data-view-name="air_auction_items" data-view-display="teaser" data-view-page="0">
                                <div class="row">
                                        @foreach ($auction as $item)
                                        <div class="col-md-4 mt-4 mb-4">
                                            <div class="c-view__item c-view__item--teaser">
                                                <div id="node-{{ $item->id }}"
                                                    class="c-node-ai c-node-ai--teaser js-ai js-ai--teaser js-eq js-ai--teaser-view c-node-ai--teaser-view"
                                                    about="/auction/{{ $item->id }}" typeof="sioc:Item foaf:Document"
                                                    data-entity-id="{{ $item->id }}" data-unmet-reserve="0" data-live-id="{{ $item->id }}"
                                                    data-updated="{{ \Carbon\Carbon::parse($item->updated_at)->timestamp }}"
                                                    data-leader="{{ $item->leader_id ?? '' }}" data-status="bidding" data-lec="false"
                                                    data-expiry="{{ \Carbon\Carbon::parse($item->dead_line)->timestamp }}">
                                                    <div class="c-node-ai__content">
                                                        <div id="air-ai-status-indicator-{{ $item->id }}"
                                                            class="js-ai-status-indicator c-node-ai__status c-node-ai__status--teaser c-tooltip c-tooltip--n"
                                                            aria-label="Bidding is under way."></div>
                                                        <div class="c-node-ai__image-wrap">
                                                            <div class="c-node-ai__image">
                                                                <svg viewBox="0 0 100 100"></svg>
                                                                <a href="/auction/{{ $item->id }}" class="">
                                                                    <img alt="{{ $item->title }}"
                                                                        sizes="(min-width: 110em) 420px, (min-width: 90em) 25vw, (min-width: 60em) 33vw, (min-width: 30em) 50vw, 100vw"
                                                                        data-src="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}"
                                                                        data-srcset="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}"
                                                                        class="jqo-io-processed"
                                                                        src="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}"
                                                                        srcset="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="c-node-ai__details-wrap">
                                                            <h3 class="c-node-ai__title c-heading--gamma">
                                                                <a href="/auction/{{ $item->id }}" data-mousetrap-trigger="4">
                                                                    {{ $item->title }}
                                                                </a>
                                                            </h3>
                                                            <div class="c-node-ai__bidding-details">
                                                                <div class="o-layout">
                                                                    <div class="o-layout__item u-7/12">
                                                                        <div class="c-node-ai__timer">
                                                                            <div id="ai-timer-{{ $item->id }}"
                                                                                class="js-timer-wrapper c-timer c-timer--small-block u-hide-no-js">
                                                                                <div class="c-timer__title"><span class="js-timer-title">Time remaining</span></div>
                                                                                <span class="c-timer__body">
                                                                                    <span class="js-timer"
                                                                                        data-timer_id="ai-{{ $item->id }}-long-small-block"
                                                                                        data-type="expiry"
                                                                                        data-timeout="{{ \Carbon\Carbon::parse($item->dead_line)->timestamp }}"
                                                                                        data-format_num="long"
                                                                                        data-deadline="{{ $item->dead_line }}"
                                                                                        id="auction-timer-{{ $item->id }}">
                                                                                        <span class="js-timer-element-days c-timer__element">
                                                                                            <span class="c-timer__value" id="days-{{ $item->id }}">0</span>
                                                                                            <span class="c-timer__period">Days</span>
                                                                                        </span>
                                                                                        <span class="c-timer__element">
                                                                                            <span class="c-timer__value" id="hours-{{ $item->id }}">0</span>
                                                                                            <span class="c-timer__period">Hrs</span>
                                                                                        </span>
                                                                                        <span class="c-timer__element">
                                                                                            <span class="c-timer__value" id="minutes-{{ $item->id }}">0</span>
                                                                                            <span class="c-timer__period">Mins</span>
                                                                                        </span>
                                                                                    </span>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="o-layout__item u-5/12">
                                                                        <div class="c-node-ai__price">
                                                                            <div id="ai-price-{{ $item->id }}" class="c-price  c-price--small-block">
                                                                                <div class="c-price__title"><span class="js-price-title">Current bid</span></div>
                                                                                <div class="c-price__wrapper">
                                                                                    <div class="c-price__value js-resize-bid-text u-tc--highlight-bg"
                                                                                        id="auction-price-{{ $item->id }}"
                                                                                        data-live-item="price"
                                                                                        data-tcid="{{ $item->id }}:price"
                                                                                        style="font-size: 16px;">
                                                                                        ${{ $item->starting_price ?? 0 }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @break

        @case('sell-tickets')
            @php
                // Get current website based on domain
                $url = url()->current();
                $domain = parse_url($url, PHP_URL_HOST);
                $check = \App\Models\Website::where('domain', $domain)->first();
                $tickets = \App\Models\Ticket::where('website_id', $check->id ?? 1)->where('status',1)->latest()->get();
            @endphp
            
            <section style="{{ $wrapperStyleStr }} {{ $styleStr }}" class="mt-2 mb-2">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <form action="/tickets" method="POST">
                            @csrf
                                @foreach ($tickets as $item)
                                <div class="card ticket-mask mt-2 mb-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-2 col-2">
                                                    <img src="{{ asset($item->image) }}" width="64px" height="64px;">
                                                </div>
                                                <div class="col-md-10 col-10">
                                                    <h4 style="margin-bottom: 2px;">{{ $item->name }} (${{ $item->price }})</h4>
                                                    <p style="margin-bottom: 2px;">{{ $item->description }}</p>
                                                    <span>Only {{ $item->quantity }} left!</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="hidden" name="ticket[{{ $item->id }}][id]" value="{{ $item->id }}">
                                            <select name="ticket[{{ $item->id }}][quantity]" class="form-control tickets">
                                                <option value="null">Select a option</option>
                                                @for ($i = 1; $i <= $item->quantity; $i++)
                                                    <option value="{{ $i }}">You selected a total of {{ $i }} {{ $item->name }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-md-12 text-center mt-4 mb-4">
                                <button type="submit" class="btn btn-primary"> Buy </button>
                            </div>
                        </form>
                </div>
            </section>
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
