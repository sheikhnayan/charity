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

.ticket-mask {
        --mask: conic-gradient(from 45deg at left,#0000,#000 1deg 89deg,#0000 90deg) left/51% 16.00px repeat-y,conic-gradient(from -135deg at right,#0000,#000 1deg 89deg,#0000 90deg) 100% calc(50% + 8px)/51% 16.00px repeat-y;
        -webkit-mask: var(--mask);
        mask: var(--mask);
        padding: 1.5rem;
        background-color: #eee;
        border: unset;
    }

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

/* SEO-friendly semantic heading styles for frontend */
h1, .ql-header-1 {
    font-size: 2.5rem !important;
    font-weight: bold !important;
    line-height: 1.2 !important;
    margin: 1rem 0 0.5rem 0 !important;
}
h2, .ql-header-2 {
    font-size: 2rem !important;
    font-weight: bold !important;
    line-height: 1.3 !important;
    margin: 0.8rem 0 0.4rem 0 !important;
}
h3, .ql-header-3 {
    font-size: 1.75rem !important;
    font-weight: bold !important;
    line-height: 1.4 !important;
    margin: 0.6rem 0 0.3rem 0 !important;
}
h4, .ql-header-4 {
    font-size: 1.5rem !important;
    font-weight: bold !important;
    line-height: 1.4 !important;
    margin: 0.5rem 0 0.25rem 0 !important;
}
h5, .ql-header-5 {
    font-size: 1.25rem !important;
    font-weight: bold !important;
    line-height: 1.5 !important;
    margin: 0.4rem 0 0.2rem 0 !important;
}

/* Global Mobile Fixes */
@media screen and (max-width: 767px) {
    /* Prevent horizontal overflow on mobile */
    body {
        overflow-x: hidden !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        box-sizing: border-box !important;
    }
    
    /* Enhanced Mobile Edge-to-Edge Experience */
    html {
        overflow-x: hidden !important;
    }
    
    /* Fix any page container margins on mobile */
    html, body, .page {
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        box-sizing: border-box !important;
    }
    
    /* Bootstrap Container Mobile Overrides - Minimal margins for edge-to-edge feel */
    .container-fluid, .container {
        padding-left: 5px !important;
        padding-right: 5px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* Fix any component wrappers on mobile - bring content closer to edges */
    .component-wrapper, .component {
        margin-left: 0 !important;
        margin-right: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    /* Ensure all components fit within viewport with minimal side spacing */
    .row {
        margin-left: -2px !important;
        margin-right: -2px !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .row > [class*="col-"] {
        padding-left: 2px !important;
        padding-right: 2px !important;
        max-width: 100% !important;
    }
    
    /* Inner sections mobile edge optimization */
    .inner-section-frontend {
        padding-left: 5px !important;
        padding-right: 5px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    .inner-section-fullwidth {
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* Component content mobile optimization */
    .component-content {
        padding-left: 5px !important;
        padding-right: 5px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    /* Text and content elements mobile spacing */
    .text-component, .heading-component {
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    /* Form elements mobile edge optimization */
    .form-component {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    /* Button components mobile spacing */
    .button-component {
        margin-left: 5px !important;
        margin-right: 5px !important;
    }
    
    /* Image components mobile edge behavior */
    .image-component img {
        max-width: calc(100% - 10px) !important;
        margin-left: 5px !important;
        margin-right: 5px !important;
    }
    
    /* Investment components mobile edge optimization */
    .investment-tier, .invest-cta-wrapper {
        margin-left: 5px !important;
        margin-right: 5px !important;
        max-width: calc(100% - 10px) !important;
    }
    
    /* Video components mobile behavior */
    .video-component {
        margin-left: 5px !important;
        margin-right: 5px !important;
        max-width: calc(100% - 10px) !important;
    }
    
    /* Gallery mobile edge behavior */
    .gallery-component {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    /* Divider mobile spacing */
    .divider-component {
        margin-left: 5px !important;
        margin-right: 5px !important;
        max-width: calc(100% - 10px) !important;
    }
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

/* SEO-friendly semantic heading styles for frontend */
h1, .ql-header-1 {
    font-size: 2.5rem !important;
    font-weight: bold !important;
    line-height: 1.2 !important;
    margin: 1rem 0 0.5rem 0 !important;
}
h2, .ql-header-2 {
    font-size: 2rem !important;
    font-weight: bold !important;
    line-height: 1.3 !important;
    margin: 0.8rem 0 0.4rem 0 !important;
}
h3, .ql-header-3 {
    font-size: 1.75rem !important;
    font-weight: bold !important;
    line-height: 1.4 !important;
    margin: 0.6rem 0 0.3rem 0 !important;
}
h4, .ql-header-4 {
    font-size: 1.5rem !important;
    font-weight: bold !important;
    line-height: 1.4 !important;
    margin: 0.5rem 0 0.25rem 0 !important;
}
h5, .ql-header-5 {
    font-size: 1.25rem !important;
    font-weight: bold !important;
    line-height: 1.5 !important;
    margin: 0.4rem 0 0.2rem 0 !important;
}
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
                    case 2: $bootstrapClass = 'col-lg-6 col-md-6'; break;
                    case 3: $bootstrapClass = 'col-lg-4 col-md-6'; break;
                    case 4: $bootstrapClass = 'col-lg-3 col-md-6'; break;
                    case 5: $bootstrapClass = 'col-lg-2 col-md-4 col-sm-6 col-12'; break;
                    case 6: $bootstrapClass = 'col-lg-2 col-md-4 col-sm-6 col-12'; break;
                    default: $bootstrapClass = 'col-lg-4 col-md-6';
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
                // Support both old format and new properties format
                $text = $component['properties']['button_text'] ?? $component['html'] ?? $component['text'] ?? 'Button';
                $href = $component['properties']['button_url'] ?? $component['href'] ?? '#';
                $target = $component['properties']['button_target'] ?? (($component['openInNewTab'] ?? false) ? '_blank' : '_self');
                
                // Button styling from properties
                $buttonBgColor = $component['properties']['button_bg_color'] ?? $style['backgroundColor'] ?? '#007bff';
                $buttonTextColor = $component['properties']['button_text_color'] ?? $style['color'] ?? '#ffffff';
                $buttonPadding = $component['properties']['button_padding'] ?? $style['padding'] ?? '10px 20px';
                $borderRadius = $component['properties']['border_radius'] ?? $style['borderRadius'] ?? '4px';
                $fontSize = $component['properties']['font_size'] ?? $style['fontSize'] ?? '16px';
                $fontWeight = $component['properties']['font_weight'] ?? $style['fontWeight'] ?? '400';
                $textAlign = $component['properties']['text_align'] ?? $style['textAlign'] ?? 'center';
                $textDecoration = $component['properties']['text_decoration'] ?? 'none';
                $border = $component['properties']['border'] ?? $style['border'] ?? 'none';
                $boxShadow = $component['properties']['box_shadow'] ?? $style['boxShadow'] ?? 'none';
                $transition = $component['properties']['transition'] ?? 'all 0.3s ease';
            @endphp
            <div style="text-align: {{ $textAlign }}; {{ $styleStr }}">
                <a href="{{ $href }}" target="{{ $target }}" 
                   style="display: inline-block; background-color: {{ $buttonBgColor }}; color: {{ $buttonTextColor }}; 
                          padding: {{ $buttonPadding }}; border-radius: {{ $borderRadius }}; font-size: {{ $fontSize }}; 
                          font-weight: {{ $fontWeight }}; text-decoration: {{ $textDecoration }}; border: {{ $border }}; 
                          box-shadow: {{ $boxShadow }}; transition: {{ $transition }}; cursor: pointer;"
                   class="btn custom-button">
                    {{ $text }}
                </a>
            </div>
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
                     style="width:{{ $width }};height:{{ $height }};object-fit:{{ $objectFit }};" 
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
                    @php
                        // Handle both string URLs and object format
                        $imageSrc = is_string($image) ? $image : ($image['src'] ?? 'https://via.placeholder.com/300x200');
                        $imageAlt = is_string($image) ? 'Gallery Image' : ($image['alt'] ?? 'Gallery Image');
                    @endphp
                    <div class="{{ $bootstrapClass }} mb-3">
                        <img src="{{ $imageSrc }}" 
                             alt="{{ $imageAlt }}" 
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
                    @php
                        // Handle both string URLs and object format
                        $imageSrc = is_string($image) ? $image : ($image['src'] ?? 'https://via.placeholder.com/800x400');
                        $imageAlt = is_string($image) ? 'Slider Image' : ($image['alt'] ?? 'Slider Image');
                    @endphp
                    <div class="item">
                        <img src="{{ $imageSrc }}" 
                             alt="{{ $imageAlt }}" 
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
                // Support both style (old format) and properties (new format)
                $height = $component['properties']['height'] ?? $style['height'] ?? '2px';
                $backgroundColor = $component['properties']['background_color'] ?? $style['backgroundColor'] ?? '#ddd';
                $margin = $component['properties']['margin'] ?? $style['margin'] ?? '1rem 0';
                $borderRadius = $component['properties']['border_radius'] ?? $style['borderRadius'] ?? '0';
                $opacity = $component['properties']['opacity'] ?? $style['opacity'] ?? '1';
            @endphp
            <hr style="height:{{ $height }};background-color:{{ $backgroundColor }};border:none;margin:{{ $margin }};border-radius:{{ $borderRadius }};opacity:{{ $opacity }};{{ $styleStr }}">
        @break

        @case('spacer')
            @php
                $height = $style['height'] ?? '20px';
            @endphp
            <div style="height:{{ $height }};{{ $styleStr }}"></div>
        @break

        @case('event-countdown')
            @if(isset($component['countdownData']))
                @php
                    $countdownData = $component['countdownData'];
                    $label = $countdownData['label'] ?? '';
                    $date = $countdownData['date'] ?? '';
                    $fontWeight = $countdownData['fontWeight'] ?? 'bold'; // Legacy support
                    
                    // Color options for different elements
                    $numberColor = $countdownData['numberColor'] ?? '#000';
                    $textColor = $countdownData['textColor'] ?? '#000';
                    $remainingVerbiageColor = $countdownData['remainingVerbiageColor'] ?? '#000';
                    
                    // Font weight options for different elements
                    $numberFontWeight = $countdownData['numberFontWeight'] ?? 'bold';
                    $textFontWeight = $countdownData['textFontWeight'] ?? 'normal';
                    $remainingFontWeight = $countdownData['remainingFontWeight'] ?? 'normal';
                    
                    // Show/hide remaining text option
                    $showRemainingText = $countdownData['showRemainingText'] ?? true;
                    
                    // Convert font weight values to CSS
                    $numberWeight = $numberFontWeight === 'bold' ? 600 : 400;
                    $textWeight = $textFontWeight === 'bold' ? 600 : 400;
                    $remainingWeight = $remainingFontWeight === 'bold' ? 600 : 400;
                    
                    // Build wrapper style (for margin, etc.)
                    $wrapperStyle = $wrapperStyleStr;
                    $backgroundColor = '';
                    
                    // Build countdown style (for color, background, padding, etc.)
                    $countdownStyle = $styleStr;
                    $color = '#000';
                    
                    // Extract color from style (fallback if individual colors not set)
                    if (isset($style['color'])) {
                        $color = $style['color'];
                        // Use main color as fallback if specific colors not provided
                        if ($countdownData['numberColor'] ?? false === false) $numberColor = $color;
                        if ($countdownData['textColor'] ?? false === false) $textColor = $color;
                        if ($countdownData['remainingVerbiageColor'] ?? false === false) $remainingVerbiageColor = $color;
                    }
                    
                    // Legacy fontWeight fallback support
                    if (!isset($countdownData['numberFontWeight']) && isset($countdownData['fontWeight'])) {
                        $numberWeight = $fontWeight === 'normal' ? 400 : 600;
                        $remainingWeight = $fontWeight === 'normal' ? 400 : 600;
                    }
                    
                    // Extract background color
                    if (isset($style['backgroundColor'])) {
                        $backgroundColor = $style['backgroundColor'];
                        $wrapperStyle .= 'background-color:' . $backgroundColor . ';';
                    }
                    
                    // Generate unique IDs for this countdown
                    $uniqueId = 'countdown_' . uniqid();
                @endphp
                <div class="event-countdown" style="padding:24px 16px;border-radius:8px;text-align:center;margin-bottom:24px;{{ $wrapperStyle }}">
                    <div class="timer text-center mt-5" style="{{ $countdownStyle }}">
                        <div class="d-flex justify-content-center align-items-center flex-wrap">
                            <div class="mx-2 counters">
                                <h1 id="months_{{ $uniqueId }}" class="display-4" style="font-weight:{{ $numberWeight }} !important;color:{{ $numberColor }}">0</h1>
                                <p style="color:{{ $textColor }};font-weight:{{ $textWeight }} !important">Months</p>
                            </div>
                            <div class="mx-2 counters">
                                <h1 id="days_{{ $uniqueId }}" class="display-4" style="font-weight:{{ $numberWeight }} !important;color:{{ $numberColor }}">0</h1>
                                <p style="color:{{ $textColor }};font-weight:{{ $textWeight }} !important">Days</p>
                            </div>
                            <div class="mx-2 counters">
                                <h1 id="hours_{{ $uniqueId }}" class="display-4" style="font-weight:{{ $numberWeight }} !important;color:{{ $numberColor }}">0</h1>
                                <p style="color:{{ $textColor }};font-weight:{{ $textWeight }} !important">Hours</p>
                            </div>
                            <div class="mx-2 counters">
                                <h1 id="minutes_{{ $uniqueId }}" class="display-4" style="font-weight:{{ $numberWeight }} !important;color:{{ $numberColor }}">0</h1>
                                <p style="color:{{ $textColor }};font-weight:{{ $textWeight }} !important">Minutes</p>
                            </div>
                            <div class="mx-2 counters">
                                <h1 id="seconds_{{ $uniqueId }}" class="display-4" style="font-weight:{{ $numberWeight }} !important;color:{{ $numberColor }}">0</h1>
                                <p style="color:{{ $textColor }};font-weight:{{ $textWeight }} !important">Seconds</p>
                            </div>
                        </div>
                        @if($showRemainingText && $label)
                            <p style="font-size: .8em; font-weight:{{ $remainingWeight }} !important; color:{{ $remainingVerbiageColor }}">{{ $label }}</p>
                        @endif
                    </div>
                    <input type="hidden" id="timer_{{ $uniqueId }}" class="date-countdown" value="{{ $date }}">
                </div>
                <script>
                    (function() {
                        const timerId = "{{ $uniqueId }}";
                        const dateValue = document.getElementById("timer_" + timerId).value;
                        
                        if (!dateValue) return;
                        
                        const targetDate = new Date(dateValue).getTime();
                        
                        function updateCountdown() {
                            const now = new Date().getTime();
                            const timeLeft = targetDate - now;
                            
                            if (timeLeft <= 0) {
                                document.getElementById("months_" + timerId).textContent = 0;
                                document.getElementById("days_" + timerId).textContent = 0;
                                document.getElementById("hours_" + timerId).textContent = 0;
                                document.getElementById("minutes_" + timerId).textContent = 0;
                                document.getElementById("seconds_" + timerId).textContent = 0;
                                return;
                            }
                            
                            const months = Math.floor(timeLeft / (1000 * 60 * 60 * 24 * 30));
                            const days = Math.floor((timeLeft % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                            
                            document.getElementById("months_" + timerId).textContent = months;
                            document.getElementById("days_" + timerId).textContent = days;
                            document.getElementById("hours_" + timerId).textContent = hours;
                            document.getElementById("minutes_" + timerId).textContent = minutes;
                            document.getElementById("seconds_" + timerId).textContent = seconds;
                        }
                        
                        // Initial update
                        updateCountdown();
                        
                        // Update every second
                        setInterval(updateCountdown, 1000);
                    })();
                </script>
            @endif
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
                        <div id="donorTable_wrapper" class="dataTables_wrapper no-footer">
                            <table id="donorTable" class="display table dataTable no-footer" role="grid">
                                <tbody>
                                    @php
                                        $url = url()->current();
                                        $domain = parse_url($url, PHP_URL_HOST);
                                        $check = \App\Models\Website::where('domain', $domain)->first();
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
                <div class="feature-grid-frontend row" style="{{ $styleStr }}">
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
                
                // Completion status colors
                $completedBackground = $colors['completedBackground'] ?? '#22c55e';
                $uncompletedBackground = $colors['uncompletedBackground'] ?? '#e5e7eb';
                $completedText = $colors['completedText'] ?? '#ffffff';
                $uncompletedText = $colors['uncompletedText'] ?? '#9ca3af';
                $completedLineColor = $colors['completedLineColor'] ?? '#22c55e';
                $uncompletedLineColor = $colors['uncompletedLineColor'] ?? '#e5e7eb';
            @endphp
            
            <style>
                .numbered-timeline-container {
                    position: relative;
                    max-width: 100%;
                    margin: 0 auto;
                }
                
                .timeline-item {
                    display: flex;
                    align-items: flex-start;
                    margin-bottom: 3rem;
                    position: relative;
                }
                
                .timeline-number {
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    font-size: 18px;
                    margin-right: 1.5rem;
                    flex-shrink: 0;
                    position: relative;
                    z-index: 3;
                    transition: all 0.3s ease;
                }
                
                .timeline-content {
                    flex: 1;
                    padding-top: 8px;
                }
                
                .timeline-line {
                    position: absolute;
                    left: 24px;
                    top: 50px;
                    width: 2px;
                    height: calc(100% + 1rem);
                    z-index: 1;
                    transition: background-color 0.3s ease;
                }
                
                /* Desktop layout */
                @media (min-width: 769px) {
                    .numbered-timeline {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 2rem;
                        position: relative;
                    }
                    
                    .timeline-column {
                        flex: 1;
                        min-width: 250px;
                        position: relative;
                    }
                }
                
                /* Mobile layout - single column with continuous line */
                @media (max-width: 768px) {
                    .numbered-timeline {
                        display: block !important;
                        position: relative;
                    }
                    
                    .timeline-column {
                        width: 100% !important;
                        min-width: auto !important;
                    }
                    
                    /* Continuous vertical line for mobile */
                    .numbered-timeline::before {
                        content: '';
                        position: absolute;
                        left: 24px;
                        top: 50px;
                        bottom: 50px;
                        width: 2px;
                        background: linear-gradient(to bottom, {{ $completedLineColor }} 0%, {{ $completedLineColor }} 70%, {{ $uncompletedLineColor }} 70%, {{ $uncompletedLineColor }} 100%);
                        z-index: 1;
                    }
                    
                    .timeline-line {
                        display: none; /* Hide individual lines on mobile */
                    }
                    
                    .timeline-item {
                        margin-bottom: 2.5rem;
                    }
                }
            </style>
            
            <div class="numbered-timeline-container" style="{{ $styleStr }}">
                <div class="numbered-timeline">
                    @php
                        $itemsPerColumn = 4;
                        $columns = array_chunk($items, $itemsPerColumn);
                    @endphp
                    @foreach($columns as $columnIndex => $column)
                        <div class="timeline-column">
                            @foreach($column as $index => $item)
                                @php
                                    $globalIndex = $columnIndex * $itemsPerColumn + $index;
                                    $isCompleted = $item['completed'] ?? true; // Default to completed if not specified
                                    $isLastInColumn = $index === count($column) - 1;
                                    $isLastOverall = $globalIndex === count($items) - 1;
                                    
                                    // Determine colors based on completion status
                                    $bgColor = $isCompleted ? $completedBackground : $uncompletedBackground;
                                    $textColor = $isCompleted ? $completedText : $uncompletedText;
                                    $itemLineColor = $isCompleted ? $completedLineColor : $uncompletedLineColor;
                                @endphp
                                <div class="timeline-item">
                                    <div class="timeline-number" style="
                                        background: {{ $bgColor }};
                                        color: {{ $textColor }};
                                        {{ !$isCompleted ? 'border: 2px solid ' . $uncompletedBackground . ';' : '' }}
                                    ">
                                        {{ $item['number'] ?? ($globalIndex + 1) }}
                                    </div>
                                    <div class="timeline-content">
                                        <h3 style="color: {{ $isCompleted ? $titleColor : $uncompletedText }}; margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600;">
                                            {{ $item['title'] ?? 'Timeline Item' }}
                                        </h3>
                                        <p style="color: {{ $isCompleted ? $descriptionColor : $uncompletedText }}; margin: 0; line-height: 1.6; opacity: {{ $isCompleted ? '1' : '0.7' }};">
                                            {{ $item['description'] ?? 'Timeline description' }}
                                        </p>
                                        @if(isset($item['status']) && !empty($item['status']))
                                            <div class="timeline-status" style="
                                                margin-top: 0.5rem;
                                                padding: 4px 12px;
                                                border-radius: 12px;
                                                font-size: 12px;
                                                font-weight: 500;
                                                display: inline-block;
                                                background: {{ $isCompleted ? 'rgba(34, 197, 94, 0.1)' : 'rgba(156, 163, 175, 0.1)' }};
                                                color: {{ $isCompleted ? '#059669' : '#6b7280' }};
                                            ">
                                                {{ $item['status'] }}
                                            </div>
                                        @endif
                                    </div>
                                    @if(!$isLastInColumn && !$isLastOverall)
                                        <div class="timeline-line" style="background: {{ $itemLineColor }};"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @break

        @case('investment-tier')
            @php
                $tierData = $component['investmentTierData'] ?? [];
                $tierName = $tierData['tierName'] ?? 'TIER 1';
                $tierPrice = $tierData['tierPrice'] ?? '$2,500';
                $tierDescription = $tierData['tierDescription'] ?? '';
                $receiveLabel = $tierData['receiveLabel'] ?? 'Receive';
                $buttonText = $tierData['buttonText'] ?? 'INVEST NOW';
                $buttonUrl = $tierData['buttonUrl'] ?? '#';
                $buttonTarget = $tierData['buttonTarget'] ?? '_self';
                $backgroundType = $tierData['backgroundType'] ?? 'color';
                $backgroundColor = $tierData['backgroundColor'] ?? '#f8f9fa';
                $backgroundImage = $tierData['backgroundImage'] ?? '';
                
                // Color fields with fallbacks
                $titleColor = $tierData['titleColor'] ?? $tierData['textColor'] ?? '#ffffff';
                $priceColor = $tierData['priceColor'] ?? $tierData['textColor'] ?? '#ffffff';
                $receiveLabelColor = $tierData['receiveLabelColor'] ?? $tierData['textColor'] ?? '#ffffff';
                $descriptionColor = $tierData['descriptionColor'] ?? $tierData['textColor'] ?? '#ffffff';
                $buttonBgColor = $tierData['buttonBgColor'] ?? '#28a745';
                $buttonTextColor = $tierData['buttonTextColor'] ?? '#ffffff';
                
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
            
            <div class="investment-tier" style="{{ $backgroundStyle }} padding: 2rem; border-radius: 8px; text-align: center; margin: 0 auto !important; max-width: 370px;">
                <h2 style="color: {{ $titleColor }}; margin: 0 0 1rem 0; font-size: 2rem; font-weight: bold;">{{ $tierName }}</h2>
                <div style="font-size: 3rem; font-weight: bold; margin: 1rem 0; color: {{ $priceColor }};">{{ $tierPrice }}</div>
                @if($receiveLabel || $tierDescription)
                    <div style="margin: 1rem 0;">
                        @if($receiveLabel)
                            <div style="color: {{ $receiveLabelColor }}; font-weight: bold; font-size: 1.2rem; margin-bottom: 0.5rem;">{{ $receiveLabel }}</div>
                        @endif
                        @if($tierDescription)
                            <p style="color: {{ $descriptionColor }}; margin: 0; line-height: 1.6; font-size: 1.1rem;">{{ $tierDescription }}</p>
                        @endif
                    </div>
                @endif
                <a href="{{ $buttonUrl }}" target="{{ $buttonTarget }}" style="
                    display: inline-block; 
                    background: {{ $buttonBgColor }}; 
                    color: {{ $buttonTextColor }}; 
                    padding: 1rem 2rem; 
                    text-decoration: none; 
                    border-radius: 4px; 
                    font-weight: bold; 
                    margin-top: 1rem; 
                    transition: background 0.3s ease;
                " onmouseover="this.style.background='{{ $buttonBgColor }}ee'" onmouseout="this.style.background='{{ $buttonBgColor }}'">
                    {{ $buttonText }}
                </a>
            </div>
        @break

        @case('section-title')
            @php
                // Try multiple data sources for backwards compatibility
                $sectionTitleData = $component['sectionTitleData'] ?? [];
                // Get rich HTML content from Quill editor or fallback to simple text
                $title = $sectionTitleData['title'] ?? $component['text'] ?? $component['textContent'] ?? $component['html'] ?? 'Section Title';
                $subtitle = $sectionTitleData['subtitle'] ?? '';
                $alignment = $sectionTitleData['alignment'] ?? $component['properties']['alignment'] ?? 'left';
                
                // Don't strip HTML tags - preserve Quill editor formatting including colors
                // Only clean if title contains suspicious content
                if (strpos($title, '<script') !== false || strpos($title, 'javascript:') !== false) {
                    $title = strip_tags($title);
                }
                
                // Check if this is rich HTML content from Quill editor
                $isRichContent = strip_tags($title) !== $title;
                
                // For fallback color when no Quill formatting is present
                $hasStyleColor = !empty($component['style']['color']);
                $titleColor = $hasStyleColor ? $component['style']['color'] : '#1f2937';
                $subtitleColor = $sectionTitleData['subtitleColor'] ?? '#6b7280';
                
                // Don't override colors in styleStr if content is rich HTML (Quill handles colors)
                $filteredStyleStr = $styleStr;
                if ($isRichContent && $hasStyleColor) {
                    $filteredStyleStr = preg_replace('/color\s*:[^;]+;?/', '', $styleStr);
                }
            @endphp
            <div class="section-title" style="text-align: {{ $alignment }} !important; margin: 2rem 0; {{ $filteredStyleStr }}">
                @if($isRichContent)
                    {{-- Rich HTML content from Quill editor - preserve all formatting including colors --}}
                    <div style="margin: 0 0 1rem 0;">{!! $title !!}</div>
                @else
                    {{-- Simple text content - apply fallback styling --}}
                    <h2 style="color: {{ $titleColor }}; margin: 0 0 1rem 0; font-size: 2.5rem; font-weight: bold;">{{ $title }}</h2>
                @endif
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
                        <div class="video-container" style="width: {{ $customWidth }}; max-width: 100%; overflow: hidden;">
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
                // Support both old alertData format and new properties format
                $alertData = $component['alertData'] ?? [];
                $properties = $component['properties'] ?? [];
                
                $message = $properties['message'] ?? $alertData['message'] ?? $component['html'] ?? $component['text'] ?? 'Alert message';
                $type = $properties['alert_type'] ?? $alertData['type'] ?? 'info';
                $dismissible = $properties['dismissible'] ?? $alertData['dismissible'] ?? false;
                
                // Custom styling from properties
                $backgroundColor = $properties['background_color'] ?? $style['backgroundColor'] ?? null;
                $textColor = $properties['text_color'] ?? $style['color'] ?? null;
                $borderColor = $properties['border_color'] ?? $style['borderColor'] ?? null;
                $borderRadius = $properties['border_radius'] ?? $style['borderRadius'] ?? '4px';
                $padding = $properties['padding'] ?? $style['padding'] ?? '1rem';
                $margin = $properties['margin'] ?? $style['margin'] ?? '1rem 0';
                $fontSize = $properties['font_size'] ?? $style['fontSize'] ?? '14px';
                $fontWeight = $properties['font_weight'] ?? $style['fontWeight'] ?? '400';
                
                // Default alert colors if no custom colors are set
                $alertColors = [
                    'success' => ['bg' => '#d4edda', 'text' => '#155724', 'border' => '#c3e6cb'],
                    'danger' => ['bg' => '#f8d7da', 'text' => '#721c24', 'border' => '#f5c6cb'],
                    'warning' => ['bg' => '#fff3cd', 'text' => '#856404', 'border' => '#ffeaa7'],
                    'info' => ['bg' => '#d1ecf1', 'text' => '#0c5460', 'border' => '#bee5eb']
                ];
                $defaultColors = $alertColors[$type] ?? $alertColors['info'];
                
                // Use custom colors if provided, otherwise use defaults
                $finalBgColor = $backgroundColor ?? $defaultColors['bg'];
                $finalTextColor = $textColor ?? $defaultColors['text'];
                $finalBorderColor = $borderColor ?? $defaultColors['border'];
            @endphp
            <div class="alert alert-{{ $type }}" style="
                background-color: {{ $finalBgColor }}; 
                color: {{ $finalTextColor }}; 
                border: 1px solid {{ $finalBorderColor }}; 
                padding: {{ $padding }}; 
                border-radius: {{ $borderRadius }}; 
                margin: {{ $margin }};
                font-size: {{ $fontSize }};
                font-weight: {{ $fontWeight }};
                {{ $dismissible ? 'position: relative; padding-right: 3rem;' : '' }}
                {{ $styleStr }}
            ">
                {!! $message !!}
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
                        color: {{ $finalTextColor }};
                    " onclick="this.parentElement.style.display='none'">×</button>
                @endif
            </div>
        @break

        @case('press-card')
            @php
                $pressCardData = $component['pressCardData'] ?? [];
                $logoSrc = $pressCardData['logoSrc'] ?? '';
                $logoAlt = $pressCardData['logoAlt'] ?? 'Press Logo';
                $title = $pressCardData['title'] ?? '';
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
                        @if($logoSrc)
                            <img src="{{ $logoSrc }}" 
                                 alt="{{ $logoAlt }}" 
                                 style="max-width: 150px; height: auto; filter: brightness(0);" 
                                 class="press-logo">
                        @else
                            <div style="width: 150px; height: 50px; margin: 0 auto; background: #e9ecef; border: 2px dashed #adb5bd; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #6c757d;">No Logo</div>
                        @endif
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
                
                // Generate unique component ID for responsive CSS
                $uniqueId = 'auction-list-' . uniqid();
                
                // Debug logging
                \Log::info('Auction List Component: Domain=' . $domain . ', Website ID=' . ($check->id ?? 'none') . ', Auction Count=' . $auction->count());
            @endphp
            
            @if($auction->count() > 0)
            <div class="auction-component-container {{ $uniqueId }}" style="{{ $wrapperStyleStr }}">
                <!-- Auction Component Styles -->
                <style>
                .{{ $uniqueId }} .auction-items-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 20px;
                    padding: 20px 0;
                }
                
                .{{ $uniqueId }} .auction-card {
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    transition: all 0.3s ease;
                    overflow: hidden;
                }
                
                .{{ $uniqueId }} .auction-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
                }
                
                .{{ $uniqueId }} .c-node-ai__image {
                    position: relative;
                    padding-bottom: 60%;
                    overflow: hidden;
                }
                
                .{{ $uniqueId }} .c-node-ai__image img {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
                
                .{{ $uniqueId }} .c-node-ai__details-wrap {
                    padding: 15px;
                }
                
                .{{ $uniqueId }} .c-node-ai__title {
                    margin-bottom: 15px;
                    font-size: 1.2rem;
                    font-weight: bold;
                }
                
                .{{ $uniqueId }} .c-node-ai__title a {
                    text-decoration: none;
                    color: #333;
                }
                
                .{{ $uniqueId }} .c-node-ai__title a:hover {
                    color: #007bff;
                }
                
                .{{ $uniqueId }} .auction-details-layout {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    gap: 15px;
                    flex-wrap: wrap;
                }
                
                .{{ $uniqueId }} .auction-timer-section,
                .{{ $uniqueId }} .auction-price-section {
                    flex: 1;
                    min-width: 120px;
                }
                
                .{{ $uniqueId }} .c-timer__title {
                    font-size: 0.9rem;
                    color: #666;
                    margin-bottom: 8px;
                    font-weight: 500;
                }
                
                .{{ $uniqueId }} .c-timer__body {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                }
                
                .{{ $uniqueId }} .c-timer__element {
                    text-align: center;
                    background: #f8f9fa;
                    padding: 8px 6px;
                    border-radius: 4px;
                    min-width: 50px;
                }
                
                .{{ $uniqueId }} .c-timer__value {
                    display: block;
                    font-weight: bold;
                    font-size: 1.1rem;
                    color: #333;
                }
                
                .{{ $uniqueId }} .c-timer__period {
                    display: block;
                    font-size: 0.8rem;
                    color: #666;
                    margin-top: 2px;
                }
                
                .{{ $uniqueId }} .c-price__title {
                    font-size: 0.9rem;
                    color: #666;
                    margin-bottom: 8px;
                    font-weight: 500;
                }
                
                .{{ $uniqueId }} .c-price__value {
                    font-size: 1.3rem;
                    font-weight: bold;
                    color: #28a745;
                    background: #e8f5e8;
                    padding: 8px 12px;
                    border-radius: 4px;
                    display: inline-block;
                }
                
                /* Mobile responsive */
                @media (max-width: 768px) {
                    .{{ $uniqueId }} .auction-items-grid {
                        grid-template-columns: 1fr;
                        gap: 15px;
                        padding: 15px 5px;
                    }
                    
                    .{{ $uniqueId }} .auction-details-layout {
                        flex-direction: column;
                        gap: 12px;
                    }
                    
                    .{{ $uniqueId }} .c-timer__body {
                        justify-content: center;
                    }
                    
                    .{{ $uniqueId }} .c-timer__element {
                        min-width: 45px;
                        padding: 6px 4px;
                    }
                }
                </style>
                
                <div class="auction-main-wrapper">
                    <div class="auction-display-container" style="{{ $styleStr }}">
                        <div class="auction-items-wrapper">
                            <div class="auction-items-grid">
                                @foreach ($auction as $item)
                                <div class="auction-item-wrapper">
                                    <div class="auction-card">
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
                                                        <div class="auction-details-layout">
                                                            <div class="auction-timer-section">
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
                                                            <div class="auction-price-section">
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
                
                <!-- Auction Timer JavaScript -->
                <script>
                // Improved auction timer with better error handling and fallbacks
                function startAuctionListTimer(deadline, id) {
                    console.log('Starting auction list timer for auction', id, 'with deadline', deadline);
                    
                    function update() {
                        const now = new Date().getTime();
                        const target = new Date(deadline).getTime();
                        let timeLeft = target - now;

                        if (timeLeft <= 0) {
                            const daysEl = document.getElementById('days-' + id);
                            const hoursEl = document.getElementById('hours-' + id);
                            const minutesEl = document.getElementById('minutes-' + id);
                            
                            if (daysEl) daysEl.textContent = 0;
                            if (hoursEl) hoursEl.textContent = 0;
                            if (minutesEl) minutesEl.textContent = 0;
                            console.log('Timer expired for auction', id);
                            return;
                        }

                        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));

                        const daysEl = document.getElementById('days-' + id);
                        const hoursEl = document.getElementById('hours-' + id);
                        const minutesEl = document.getElementById('minutes-' + id);
                        
                        if (daysEl) daysEl.textContent = days;
                        if (hoursEl) hoursEl.textContent = hours;
                        if (minutesEl) minutesEl.textContent = minutes;
                    }
                    
                    // Initial update
                    update();
                    
                    // Set interval for updates
                    const intervalId = setInterval(update, 1000);
                    
                    // Store interval ID for potential cleanup
                    if (!window.auctionListTimers) {
                        window.auctionListTimers = {};
                    }
                    window.auctionListTimers[id] = intervalId;
                }
                
                // Enhanced initialization function
                function initializeAuctionListTimers() {
                    console.log('Initializing auction list timers for auction-list component');
                    @foreach ($auction as $item)
                        // Check if elements exist before starting timer - no variable declaration needed
                        if (document.getElementById('auction-timer-{{ $item->id }}')) {
                            console.log('Found timer container for auction {{ $item->id }}');
                            startAuctionListTimer("{{ $item->dead_line }}", "{{ $item->id }}");
                        } else {
                            console.log('Timer container not found for auction {{ $item->id }}');
                        }
                    @endforeach
                }

                // Initialize timers for auction list component
                document.addEventListener('DOMContentLoaded', function() {
                    // Multiple initialization attempts to handle different loading scenarios
                    setTimeout(initializeAuctionListTimers, 100);
                    setTimeout(initializeAuctionListTimers, 500);
                    setTimeout(initializeAuctionListTimers, 1000);
                });
                
                // Also initialize if page is already loaded
                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    setTimeout(initializeAuctionListTimers, 100);
                }
                </script>
                
                <!-- Firebase Real-time Price Updates -->
                <script type="module">
                try {
                    const { initializeApp } = await import("https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js");
                    const { getFirestore, collection, query, where, orderBy, getDocs, limit } = await import("https://www.gstatic.com/firebasejs/11.9.1/firebase-firestore.js");

                    const firebaseConfig = {
                        apiKey: "AIzaSyD0QsLeSIAFeBBUouzhgUQ3WEGfM1MAYA4",
                        authDomain: "charity-390ca.firebaseapp.com",
                        projectId: "charity-390ca",
                        storageBucket: "charity-390ca.firebasestorage.app",
                        messagingSenderId: "875958450032",
                        appId: "1:875958450032:web:338aeac86307e5ab3e41b5",
                        measurementId: "G-FC73HL5XF3"
                    };

                    const app = initializeApp(firebaseConfig);
                    const firestore = getFirestore(app);

                    // Function to update auction prices
                    async function updateAuctionPrices() {
                        console.log('Updating auction prices from Firebase');
                        
                        @foreach ($auction as $item)
                            {
                                const auctionId = "{{ $item->id }}";
                                const priceDiv = document.getElementById('auction-price-{{ $item->id }}');
                                
                                if (priceDiv) {
                                    try {
                                        const bidsRef = collection(firestore, "bid");
                                        const q = query(
                                            bidsRef,
                                            where("auction_id", "==", auctionId),
                                            orderBy("amount", "desc"),
                                            limit(1)
                                        );
                                        
                                        const querySnapshot = await getDocs(q);
                                        
                                        if (!querySnapshot.empty) {
                                            let highestBid = 0;
                                            querySnapshot.forEach((doc) => {
                                                const data = doc.data();
                                                if (data.amount > highestBid) {
                                                    highestBid = data.amount;
                                                }
                                            });
                                            
                                            if (highestBid > 0) {
                                                priceDiv.textContent = `$${highestBid.toLocaleString()}`;
                                                console.log('Updated price for auction {{ $item->id }}:', highestBid);
                                            }
                                        } else {
                                            // No bids yet, show starting price
                                            priceDiv.textContent = `${{ $item->starting_price ?? 0 }}`;
                                        }
                                    } catch (error) {
                                        console.log("Firebase query failed for auction {{ $item->id }}:", error);
                                        // Fallback to starting price if Firebase fails
                                        priceDiv.textContent = `${{ $item->starting_price ?? 0 }}`;
                                    }
                                } else {
                                    console.log('Price element not found for auction {{ $item->id }}');
                                }
                            }
                        @endforeach
                    }

                    // Initialize prices when DOM is ready
                    document.addEventListener('DOMContentLoaded', function() {
                        setTimeout(updateAuctionPrices, 500);
                        
                        // Update prices every 30 seconds
                        setInterval(updateAuctionPrices, 30000);
                    });
                    
                } catch (error) {
                    console.log('Firebase initialization failed:', error);
                    // Ensure fallback prices are displayed
                    document.addEventListener('DOMContentLoaded', function() {
                        @foreach ($auction as $item)
                            const priceDiv{{ $item->id }} = document.getElementById('auction-price-{{ $item->id }}');
                            if (priceDiv{{ $item->id }}) {
                                priceDiv{{ $item->id }}.textContent = `${{ $item->starting_price ?? 0 }}`;
                            }
                        @endforeach
                    });
                }
                </script>
            @else
                <div style="{{ $wrapperStyleStr }}">
                    <div style="{{ $styleStr }}; padding: 40px; text-align: center; background: #f8f9fa; border-radius: 8px; color: #6c757d;">
                        <i style="font-size: 3em; margin-bottom: 20px; display: block;">🎯</i>
                        <h3 style="margin-bottom: 10px; color: #495057;">No Active Auctions</h3>
                        <p style="margin: 0;">There are currently no active auctions to display. Please check back later!</p>
                    </div>
                </div>
            @endif
        @break

        @case('student-leaderboard')
                @php
                    $st = App\Models\User::limit(5)->whereIn('role',['individual','group_leader','member'])->where('website_id',$check->id)->get();
                    $sortedStudents = $st->sortByDesc(function($student) {
                        return $student->donations->sum('amount');
                    });
                    $key = 0 ;
                    // dd($sortedStudents);
                @endphp
                @php
                    $style = $component['style'] ?? [];
                    $wrapperStyle = $component['wrapperStyle'] ?? [];
                    $wrapperStyleStr = '';
                    foreach ($wrapperStyle as $k => $v) {
                        if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                    $alertStyleStr = '';
                    foreach ($style as $k => $v) {
                        if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                    // dd($alertStyleStr);
                @endphp
                @php
                        // Ensure background color is applied to the wrapper
                        if (!empty($style['backgroundColor'])) {
                            $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                        }
                        // dd($style);
                    @endphp
<div class="col-md-12 mt-4" style="{{ $alertStyleStr }} {{ $wrapperStyleStr }}">

                @foreach($sortedStudents as $student)
                    <div class="col-lg-12" style="font-size: 12px; margin-bottom: 1rem; ">
                        <div class="position-relative bg- p-4 rounded-3 shadow-sm border"
                            style="width: 100%; max-width: 580px; margin-inline: auto; background: #ebebeb;">
                            <a href="/profile/{{ $student->id }}-{{ $student->name }}-{{ $student->last_name }}" style="color: {{ $style['color'] ?? '#000'}}; text-decoration: none;" target="_blank">
                            <div class="row gy-3 ">
                                <div class="col-lg-3 d-flex align-items-center">
                                    <span class="jk" style="font-size: 1.5rem !important; font-weight: bold; margin-right: 1rem;">{{ $key + 1}}</span>
                                    <div class="rounded-profile-picture border border-3 border-primary mx-auto" style="border-radius: 50%; border-color: #2e4053 !important">
                                        <img src="{{ asset($student->photo) }}" style="border-radius: 50%; width: 70px; min-width: 70px; height: 70px; min-height: 70px;">
                                    </div>
                                </div>

                                <div class="col-lg-7 d-flex flex-column justify-content-center" style="margin-top: 0px !important;">
                                    <h2 class="fs-1.25 fw-semibold text-center text-lg-start break-all" style="font-size: 1.25rem;">
                                        {{ $student->name }}
                                    </h2>

                                    {{-- <span class="opacity-75 text-center text-lg-start mt-2"></span> --}}

                                    <div class="progress" role="progressbar" aria-valuenow="{{ $student->donations->sum('amount') }}"
                                        aria-valuemin="0" aria-valuemax="{{ $student->goal }}" data-primary-color="#2e4053"
                                        data-secondary-color="#28a745" data-duration="5"
                                        data-goal-reached="true" style="height: 14px; border: 1px solid #28a745">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                            style="width:@if($student->goal > 0){{ ($student->donations->sum('amount') / $student->goal)*100 }}@else 1 @endif%; background-color: #28a745 !important;" > <span style="font-size: 13px; font-weight: bold; margin-top: -2px;"> @if($student->goal > 0){{ round(($student->donations->sum('amount') / $student->goal)*100) }}@else 1 @endif% </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="position-absolute top-0 end-0 m-2 opacity-50 small">
                                <i class="fa-solid fa-award fa-2xl fa-fw position-absolute" aria-hidden="true" style="
                                @if($key == 0)
                                    color: #FFDf01;
                                @elseif($key == 1)
                                    color: #c0c0c0;
                                @elseif($key == 2)
                                    color: #996515;
                                @else
                                    display: none;
                                @endif
                                    top: 30px; right: 25px; font-size: 2.5rem !important;"></i>
                                <span class="small fw-bold" style="top: 57px; position: relative; left: -36px; right: unset; font-size: 0.74rem; color: #000;">
                                    $ {{ $student->donations->sum('amount') }}
                                </span>
                            </span>
                            </a>
                        </div>
                    </div>
                    @php
                        $key +=1;
                    @endphp
                @endforeach
            </div>
            <div class="col-md-12 mt-4">
                <p class="lead text-center mt-3" style="color: {{ $style['color'] }} !important">
                    @php
                        $count = App\Models\Donation::where('website_id',$check->id)->count();
                    @endphp
                    {{ $count }} donations have been made to this site
                </p>
            </div>

@break

@case('sponsorships')
            @php
                // Get current website based on domain
                $url = url()->current();
                $domain = parse_url($url, PHP_URL_HOST);
                $check = \App\Models\Website::where('domain', $domain)->first();
                $sponsors = \App\Models\Sponsor::where('website_id', $check->id ?? 1)->latest()->get();
                
                // Debug logging
                \Log::info('Sponsorships Component: Domain=' . $domain . ', Website ID=' . ($check->id ?? 'none') . ', Sponsors Count=' . $sponsors->count());
            @endphp
            
            <div class="sponsorships-component" style="{{ $styleStr }}">
                @if($sponsors->count() > 0)
                    <h4 style="text-align: center; margin-bottom: 2rem;">Our Sponsors</h4>
                    <div class="row justify-content-center align-items-center g-4">
                        @foreach($sponsors as $sponsor)
                            <div class="col-6 col-md-3 text-center">
                                <div class="sponsor-logo">
                                    @if($sponsor->image)
                                    <a href="{{ $sponsor->link }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ asset($sponsor->image) }}" 
                                             alt="Sponsor {{ $loop->iteration }}" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="max-height: 180px; object-fit: contain; width: 100%; transition: transform 0.3s ease;"
                                             onmouseover="this.style.transform='scale(1.05)'"
                                             onmouseout="this.style.transform='scale(1)'">
                                    </a>
                                    @else
                                        <div class="sponsor-placeholder" style="height: 100px; background: #f8f9fa; border: 2px dashed #dee2e6; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                            <span style="color: #6c757d; font-size: 14px;">No Image</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 3rem 1rem; background: #f8f9fa; border-radius: 8px; border: 2px dashed #dee2e6;">
                        <i class="fas fa-handshake" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                        <h5 style="color: #6c757d; margin-bottom: 0.5rem;">No Sponsors Yet</h5>
                        <p style="color: #6c757d; margin: 0; font-size: 14px;">Sponsors will be displayed here once they are added to this website.</p>
                    </div>
                @endif
            </div>
                                @break

@case('site-goal')
                                    @if(isset($component['goalData']))
                                        @php
                                        // Example data for the new site-goal component (replace with your dynamic data as needed)
                                        $goal = isset($component['goalData']['goal']) ? (float)$component['goalData']['goal'] : 10000;
                                        $raised = isset($component['goalData']['raised']) ? (float)$component['goalData']['raised'] : 3500;
                                        $percent = $goal > 0 ? min(100, round(($raised / $goal) * 100, 2)) : 0;
                                        $label = $component['goalData']['label'] ?? 'Fundraising Goal';
                                        $showTicks = true;
                                        $ticks = $component['goalData']['ticks'] ?? [0, 0.25, 0.5, 0.75, 1];
                                        @endphp

                                        @php
                                            $style = $component['style'] ?? [];
                                            $wrapperStyle = $component['wrapperStyle'] ?? [];
                                            $wrapperStyleStr = '';
                                            foreach ($wrapperStyle as $k => $v) {
                                                if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                            }
                                            $alertStyleStr = '';
                                            foreach ($style as $k => $v) {
                                                if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                            }
                                            // dd($alertStyleStr);
                                        @endphp
                                        @php
                                                // Ensure background color is applied to the wrapper
                                                if (!empty($style['backgroundColor'])) {
                                                    $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                                                }
                                            @endphp

                                        <div class="site-goal-modernmb-4" style="{{ $wrapperStyleStr }} {{ $alertStyleStr }}">
                                            <div class="p-4">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    {{-- <button type="button" class="btn-close" style="font-size: 1.1rem; opacity: 0.7;" aria-label="Close" onclick="this.closest('.site-goal-modern').style.display='none';"></button> --}}
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="fw-semibold" style="color: {{ $component['style']['color'] }};">${{ number_format($raised, 2) }}</span>
                                                    <span class="mx-2" style="color: {{ $component['style']['color'] }};">/</span>
                                                    <span style="color: {{ $component['style']['color'] }};">${{ number_format($goal, 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span class="text-muted small"></span>
                                                    <span class="text-muted small" style="font-weight: bold; padding-bottom: 10px; color: {{ $component['style']['color'] }} !important">${{ $raised }} Raised</span>
                                                </div>
                                                <div class="progress position-relative" style="height: 35px; background: #e5e7eb; border-radius: 9px;">
                                                @php $barId = 'siteGoalProgressBar_' . uniqid(); @endphp
                                                <div class="progress-bar" role="progressbar"
                                                    style="background-color: {{ $component['goalData']['barColor'] ?? '#0d6efd'}}; width:0%; border-radius: 9px; transition: width 0.8s cubic-bezier(0.4,0,0.2,1);"
                                                    aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"
                                                    id="{{ $barId }}">
                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                    var bar = document.getElementById('{{ $barId }}');
                                                    if (bar) {
                                                        setTimeout(function() {
                                                        bar.style.width = '{{ $percent }}%';
                                                        }, 150);
                                                    }
                                                    });
                                                </script>
                                                <div class="site-goal-ticks position-absolute w-100" style="top: 100%; left: 0; height: 24px; pointer-events: none; z-index: 10;">
                                                    @foreach($ticks as $tick)
                                                        @php
                                                            $tickPercent = 0;
                                                            $tickValue = 0;
                                                            if (is_numeric($tick)) {
                                                                if ($tick <= 1) {
                                                                    $tickPercent = $tick * 100;
                                                                    $tickValue = $tick * $goal;
                                                                } else {
                                                                    $tickPercent = min($tick / $goal, 1) * 100;
                                                                    $tickValue = $tick;
                                                                }
                                                            }
                                                        @endphp
                                                        @if($tickPercent >= 0 && $tickPercent <= 100)
                                                        <div class="site-goal-tick" style="position: absolute; left: {{ $tickPercent }}%; top: 0; width: 2px; height: 24px; background: #6f7c8b; z-index: 11;">
                                                            <div class="site-goal-tick-label" style="position: absolute; top: 22px; left: 50%; transform: translateX(-50%); font-size: 12px; color: {{ $component['style']['color'] ?? '#222' }}; white-space: nowrap; background: #fff; padding: 0 2px; border-radius: 2px; z-index: 12;">
                                                                ${{ number_format($tickValue, 0) }}
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
{{-- @if($showTicks && !empty($ticks) && $goal > 0)
@endif --}}
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span class="text-muted small"></span>
                                                    <span class="text-muted small" style="font-weight: bold; color: {{ $component['style']['color'] }} !important">${{ $goal }} Goal</span>
                                                </div>
                                                <div class="mt-3" style="font-size: 1.1rem; color: {{ $component['style']['color'] }};">
                                                    <span class="fw-bold">{{ $percent }}%</span> of goal reached
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @break

@case('student-listing')
@php
                    $style = $component['style'] ?? [];
                    $wrapperStyle = $component['wrapperStyle'] ?? [];
                    $wrapperStyleStr = '';
                    foreach ($wrapperStyle as $k => $v) {
                        if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                    $alertStyleStr = '';
                    foreach ($style as $k => $v) {
                        if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                @endphp
                @php
                        // Ensure background color is applied to the wrapper
                        if (!empty($style['backgroundColor'])) {
                            $alertStyleStr .= 'background-color:' . $style['backgroundColor'] . ' !important;';
                        }
                    // dd($alertStyleStr);

                    @endphp
        <div class="row" style="{{ $wrapperStyleStr }}">
                <div class="col-12 col-md-11 col-lg-9 col-xl-7 d-flex align-items-center" style="margin: auto;">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                    </div>
                </div>
                <div class="col-12 mt-4">
                        <table id="studentTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
    @php
        $students = App\Models\User::limit(10)->whereIn('role', ['individual', 'group_leader', 'member'])->where('website_id', $check->id)->latest()->get();
    @endphp

    @foreach ($students->chunk(2) as $item)
        <tr>
            @foreach ($item as $key => $student)
                <td style="{{ $alertStyleStr }}">
                    <!-- full student content here -->
                    <div class="row">
                        <div class="col-lg-12 klklklk" style="font-size: 12px;">
                            <div class="position-relative rounded-3 shadow-sm border listingg"
                                style="width: 100%; max-width: 580px; margin-inline: auto;">
                                <a href="/profile/{{ $student->id }}-{{ $student->name }}-{{ $student->last_name }}" style="color: {{ $style['color'] ?? '#000'}}; text-decoration: none;" target="_blank">
                                    <div class="row lsls gy-3" style="padding: 0.5rem;">
                                        <div class="col-lg-2 d-flex align-items-center">
                                            <div class="rounded-profile-picture border border-3 border-primary mx-auto" style="border-radius: 50%; border-color: #2e4053 !important; overflow: hidden;">
                                                <img src="{{ asset($student->photo) }}" style="width: 80px; min-width: 80px; height: 80px; min-height: 80px;">
                                            </div>
                                        </div>

                                        <div class="col-lg-8 d-flex flex-column justify-content-center">
                                            <h2 class="fs-1.25 fw-semibold text-center text-lg-start break-all" style="font-size: 1.25rem;">
                                                {{ $student->name }}
                                            </h2>
                                            <span class="opacity-75 text-center text-lg-start mt-2"></span>
                                            <div class="progress mt-3" role="progressbar"
                                                aria-valuenow="{{ $student->donations->sum('amount') }}"
                                                aria-valuemin="0"
                                                aria-valuemax="{{ $student->goal }}"
                                                data-primary-color="#2e4053"
                                                data-secondary-color="#b7bcc4"
                                                data-duration="5"
                                                data-goal-reached="true"
                                                style="height: 14px">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                                    style="width: @if($student->goal > 0){{ ($student->donations->sum('amount') / $student->goal) * 100 }}@else 0 @endif%;">
                                                    <span style="font-size: 13px; font-weight: bold;">@if($student->goal > 0){{ round(($student->donations->sum('amount') / $student->goal) * 100) }}@else 1 @endif%</span>
                                                </div>
                                            </div>
                                            <span class="fw-semibold d-block text-center mt-2">
                                                @php $to = $student->donations->sum('amount'); @endphp
                                                ${{ $to }} <small class="opacity-75 fw-light">of</small> ${{ $student->goal ?? 0 }} <small class="opacity-75 fw-light">raised</small>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- {{ $key }} --}}
                </td>
            @endforeach

            {{-- Add one empty <td> only if this is the last row and has only one student --}}
            @if ($loop->last && count($item) < 2)
                <td></td>
            @endif
        </tr>
    @endforeach
</tbody>

                        </table>
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
                
                // Get sell tickets data with defaults
                $sellTicketsData = $component['sellTicketsData'] ?? [];
                $title = $sellTicketsData['title'] ?? 'Buy Tickets';
                $buttonText = $sellTicketsData['buttonText'] ?? 'Buy Now';
                $buttonBg = $sellTicketsData['buttonBg'] ?? '#007bff';
                $buttonColor = $sellTicketsData['buttonColor'] ?? '#fff';
                $buttonPadding = $sellTicketsData['buttonPadding'] ?? '10px 20px';
                $buttonRadius = $sellTicketsData['buttonRadius'] ?? '4px';
            @endphp
            
            <section style="{{ $wrapperStyleStr }} {{ $styleStr }}" class="mt-2 mb-2">
                @if($tickets->count() > 0)
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            @if($title && $title !== 'Buy Tickets' || !empty($sellTicketsData))
                                <div class="text-center mb-4">
                                    <h3>{{ $title }}</h3>
                                </div>
                            @endif
                            <form action="/tickets" method="POST">
                                @csrf
                                @foreach ($tickets as $item)
                                    <div class="card ticket-mask mt-2 mb-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-2 col-2">
                                                        <img src="{{ asset($item->image) }}" width="64px" height="64px;" alt="{{ $item->name }}">
                                                    </div>
                                                    <div class="col-md-10 col-10" style="color: {{ $buttonColor }};">
                                                        <h4 style="margin-bottom: 2px;" style="color: {{ $buttonColor }};">{{ $item->name }} (${{ $item->price }})</h4>
                                                        <p style="margin-bottom: 2px;" style="color: {{ $buttonColor }};">{{ $item->description }}</p>
                                                        <span style="color: {{ $buttonColor }};">Only {{ $item->quantity }} left!</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="ticket[{{ $item->id }}][id]" value="{{ $item->id }}">
                                                <select name="ticket[{{ $item->id }}][quantity]" class="form-control tickets">
                                                    <option value="null">Select an option</option>
                                                    @for ($i = 1; $i <= $item->quantity; $i++)
                                                        <option value="{{ $i }}">You selected a total of {{ $i }} {{ $item->name }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-md-12 text-center mt-4 mb-4">
                                    <button type="submit" class="btn" style="
                                        background: {{ $buttonBg }};
                                        color: {{ $buttonColor }};
                                        padding: {{ $buttonPadding }};
                                        border-radius: {{ $buttonRadius }};
                                        border: none;
                                        font-size: 16px;
                                        cursor: pointer;
                                        transition: all 0.3s ease;
                                    " onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                        {{ $buttonText }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div style="padding: 40px; text-align: center; background: #f8f9fa; border-radius: 8px; color: #6c757d;">
                                <i style="font-size: 3em; margin-bottom: 20px; display: block;">🎫</i>
                                <h3 style="margin-bottom: 10px; color: #495057;">No Tickets Available</h3>
                                <p style="margin: 0;">There are currently no tickets available for purchase. Please check back later!</p>
                            </div>
                        </div>
                    </div>
                @endif
            </section>
        @break

        @case('newsletter')
            @php
                // Get properties (for admin-created components) or data (for direct use)
                $properties = $component['properties'] ?? [];
                $componentData = $component['data'] ?? [];
                
                // Use properties first, fallback to data, then defaults
                $title = $properties['title'] ?? $componentData['title'] ?? 'Newsletter';
                $subtitle = $properties['subtitle'] ?? $componentData['subtitle'] ?? 'Subscribe to our newsletter';
                $placeholder = $properties['placeholder'] ?? $componentData['placeholder'] ?? 'Enter your email address';
                $buttonText = $properties['button_text'] ?? $componentData['buttonText'] ?? 'SIGN UP';
                
                // Styling properties
                $backgroundColor = $properties['background_color'] ?? $style['backgroundColor'] ?? '#ffffff';
                $textColor = $properties['text_color'] ?? $style['color'] ?? '#000000';
                $buttonColor = $properties['button_color'] ?? $style['buttonColor'] ?? '#28a745';
                $buttonTextColor = $properties['button_text_color'] ?? $style['buttonTextColor'] ?? '#ffffff';
                $borderRadius = $properties['border_radius'] ?? $style['borderRadius'] ?? '8';
                $textAlign = $properties['text_align'] ?? $style['textAlign'] ?? 'center';
                $maxWidth = $properties['max_width'] ?? $style['maxWidth'] ?? '600';
                $padding = $properties['padding'] ?? $style['padding'] ?? '40';
                
                // Typography
                $titleFontSize = $properties['title_font_size'] ?? '24';
                $titleFontWeight = $properties['title_font_weight'] ?? '600';
                $subtitleFontSize = $properties['subtitle_font_size'] ?? '16';
                $subtitleFontWeight = $properties['subtitle_font_weight'] ?? '400';
                $buttonFontSize = $properties['button_font_size'] ?? '16';
                $buttonFontWeight = $properties['button_font_weight'] ?? '600';
                $buttonPadding = $properties['button_padding'] ?? '12';
                
                // Input styling
                $inputBorderColor = $properties['input_border_color'] ?? '#ddd';
                $inputPadding = $properties['input_padding'] ?? '12';
                $inputFontSize = $properties['input_font_size'] ?? '16';
                
                // Convert numeric values to px units
                $paddingPx = is_numeric($padding) ? $padding . 'px' : $padding;
                $maxWidthPx = is_numeric($maxWidth) ? $maxWidth . 'px' : $maxWidth;
                $borderRadiusPx = is_numeric($borderRadius) ? $borderRadius . 'px' : $borderRadius;
                $titleFontSizePx = is_numeric($titleFontSize) ? $titleFontSize . 'px' : $titleFontSize;
                $subtitleFontSizePx = is_numeric($subtitleFontSize) ? $subtitleFontSize . 'px' : $subtitleFontSize;
                $buttonFontSizePx = is_numeric($buttonFontSize) ? $buttonFontSize . 'px' : $buttonFontSize;
                $buttonPaddingPx = is_numeric($buttonPadding) ? $buttonPadding . 'px' : $buttonPadding;
                $inputPaddingPx = is_numeric($inputPadding) ? $inputPadding . 'px' : $inputPadding;
                $inputFontSizePx = is_numeric($inputFontSize) ? $inputFontSize . 'px' : $inputFontSize;
            @endphp
            <section class="newsletter-section" style="background-color: {{ $backgroundColor }}; color: {{ $textColor }}; padding: {{ $paddingPx }} 20px; text-align: {{ $textAlign }}; {{ $styleStr }}" id="{{ $componentId }}">
                <div style="max-width: {{ $maxWidthPx }}; margin: 0 auto;">
                    @if($title)
                        <h3 class="newsletter-title" style="margin-bottom: 10px; font-size: {{ $titleFontSizePx }}; font-weight: {{ $titleFontWeight }}; color: {{ $textColor }};">{{ $title }}</h3>
                    @endif
                    @if($subtitle)
                        <p class="newsletter-subtitle" style="margin-bottom: 30px; font-size: {{ $subtitleFontSizePx }}; font-weight: {{ $subtitleFontWeight }}; color: {{ $textColor }};">{{ $subtitle }}</p>
                    @endif
                    
                    <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; align-items: center;">
                        @csrf
                        <input type="hidden" name="website_id" value="{{ $website->id ?? '' }}">
                        
                        <div style="flex: 1; min-width: 250px; max-width: 400px;">
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control newsletter-email" 
                                placeholder="{{ $placeholder }}" 
                                required
                                style="border: 1px solid {{ $inputBorderColor }}; border-radius: {{ $borderRadiusPx }}; padding: {{ $inputPaddingPx }} 15px; font-size: {{ $inputFontSizePx }}; width: 100%; outline: none;"
                            >
                        </div>
                        
                        <button 
                            type="submit" 
                            class="btn newsletter-submit-btn"
                            style="background-color: {{ $buttonColor }}; color: {{ $buttonTextColor }}; border: none; border-radius: {{ $borderRadiusPx }}; padding: {{ $buttonPaddingPx }} 25px; font-size: {{ $buttonFontSizePx }}; font-weight: {{ $buttonFontWeight }}; cursor: pointer; transition: all 0.3s ease;"
                            onmouseover="this.style.opacity='0.9'"
                            onmouseout="this.style.opacity='1'"
                        >
                            {{ $buttonText }}
                        </button>
                    </form>
                    
                    <div class="newsletter-message" style="margin-top: 15px; display: none;">
                        <!-- Success/Error messages will appear here -->
                    </div>
                </div>
            </section>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.querySelector('#{{ $componentId }} .newsletter-form');
                    const messageDiv = document.querySelector('#{{ $componentId }} .newsletter-message');
                    
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            
                            const formData = new FormData(form);
                            const button = form.querySelector('.newsletter-submit-btn');
                            const originalText = button.textContent;
                            
                            button.textContent = 'Subscribing...';
                            button.disabled = true;
                            
                            fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                messageDiv.style.display = 'block';
                                if (data.success) {
                                    messageDiv.innerHTML = '<div style="color: green; font-weight: 500;">' + data.message + '</div>';
                                    form.reset();
                                } else {
                                    messageDiv.innerHTML = '<div style="color: red; font-weight: 500;">' + data.message + '</div>';
                                }
                            })
                            .catch(error => {
                                messageDiv.style.display = 'block';
                                messageDiv.innerHTML = '<div style="color: red; font-weight: 500;">An error occurred. Please try again.</div>';
                            })
                            .finally(() => {
                                button.textContent = originalText;
                                button.disabled = false;
                                
                                // Hide message after 5 seconds
                                setTimeout(() => {
                                    messageDiv.style.display = 'none';
                                }, 5000);
                            });
                        });
                    }
                });
            </script>

            <style>
                @media (max-width: 768px) {
                    #{{ $componentId }} .newsletter-form {
                        flex-direction: column;
                        align-items: stretch;
                    }
                    
                    #{{ $componentId }} .newsletter-form > div {
                        max-width: 100%;
                    }
                    
                    #{{ $componentId }} .newsletter-submit-btn {
                        width: 100%;
                        margin-top: 10px;
                    }
                }
                
                #{{ $componentId }} .newsletter-email:focus {
                    border-color: {{ $buttonColor }};
                    box-shadow: 0 0 0 2px rgba({{ hexdec(substr($buttonColor, 1, 2)) }}, {{ hexdec(substr($buttonColor, 3, 2)) }}, {{ hexdec(substr($buttonColor, 5, 2)) }}, 0.25);
                }
            </style>
        @break

        @case('contact-form')
            @php
                // Get contact form data with defaults
                $contactFormData = $component['contactFormData'] ?? [];
                // dd($component);
                $hasContactFormData = !empty($contactFormData);
                
                // Contact form settings
                $title = $contactFormData['title'] ?? 'Contact Us';
                $nameLabel = $contactFormData['nameLabel'] ?? 'Your name';
                $emailLabel = $contactFormData['emailLabel'] ?? 'Email address';
                $messageLabel = $contactFormData['messageLabel'] ?? 'Message';
                $buttonText = $contactFormData['buttonText'] ?? 'Submit';
                $nameRequired = $contactFormData['nameRequired'] ?? true;
                $emailRequired = $contactFormData['emailRequired'] ?? true;
                $messageRequired = $contactFormData['messageRequired'] ?? true;
                $showPrivacyText = $contactFormData['showPrivacyText'] ?? true;
                $privacyText = $contactFormData['privacyText'] ?? 'This form is protected by reCAPTCHA and the Google Privacy Policy and Terms of Service apply.';
                
                // Styling options
                $backgroundColor = $contactFormData['backgroundColor'] ?? '#ffffff';
                $buttonColor = $contactFormData['buttonColor'] ?? '#2e4053';
                $buttonTextColor = $contactFormData['buttonTextColor'] ?? '#ffffff';
                $labelColor = $contactFormData['labelColor'] ?? '#000000';
                $borderRadius = $contactFormData['borderRadius'] ?? '4px';
                $buttonPadding = $contactFormData['buttonPadding'] ?? '12px 24px';
                
                // Legacy support
                $emails = $component['contactEmails'] ?? [];
                $style = $component['style'] ?? [];
                $wrapperStyle = $component['wrapperStyle'] ?? [];
                $wrapperStyleStr = '';
                foreach ($wrapperStyle as $k => $v) {
                    if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                }
                $alertStyleStr = '';
                foreach ($style as $k => $v) {
                    if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                }
                
                // Apply background color to wrapper if set in style
                if (!empty($style['backgroundColor'])) {
                    $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                }
            @endphp
            
            @if($hasContactFormData)
                {{-- New contact-form with customizable properties --}}
                <div class="contact-form-component" style="{{ $wrapperStyleStr }} background-color: {{ $backgroundColor }}; padding: 2rem; border-radius: {{ $borderRadius }};">
                    @if($title)
                        <h3 class="text-center mb-4" style="color: {{ $labelColor }};">{{ $title }}</h3>
                    @endif
                    
                    <form method="POST" action="/contact-form">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <label for="name" class="form-label fw-semibold" style="color: {{ $labelColor }};">
                                            {{ $nameLabel }}@if($nameRequired) <span style="color: red;">*</span>@endif
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name" @if($nameRequired) required @endif>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="email" class="form-label fw-semibold" style="color: {{ $labelColor }};">
                                            {{ $emailLabel }}@if($emailRequired) <span style="color: red;">*</span>@endif
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" @if($emailRequired) required @endif>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="message" class="form-label fw-semibold" style="color: {{ $labelColor }};">
                                            {{ $messageLabel }}@if($messageRequired) <span style="color: red;">*</span>@endif
                                        </label>
                                        <textarea class="form-control" id="message" name="message" rows="8" @if($messageRequired) required @endif></textarea>
                                    </div>
                                    
                                    @foreach($emails as $email)
                                        <input type="hidden" name="notification_emails[]" value="{{ $email }}">
                                    @endforeach
                                    
                                    @if($showPrivacyText && $privacyText)
                                        <div class="col-12">
                                            <small class="text-muted" style="color: {{ $labelColor }} !important;">{!! $privacyText !!}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-3 mt-md-4">
                            <button type="submit" class="btn btn-lg" style="
                                background-color: {{ $buttonColor }}; 
                                color: {{ $buttonTextColor }}; 
                                border-color: {{ $buttonColor }};
                                padding: {{ $buttonPadding }};
                                border-radius: {{ $borderRadius }};
                                transition: all 0.3s ease;
                            " onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                {{ $buttonText }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                {{-- Legacy contact-form with basic styling --}}
                <form method="POST" action="/contact-form" class="contact-form-component" style="{{ $wrapperStyleStr }} {{ $alertStyleStr }}">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                            <div class="row gy-3">
                                <div class="col-12">
                                    <label for="name" class="form-label fw-semibold">
                                        Your name
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label fw-semibold">
                                        Email address
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label fw-semibold">
                                        Message
                                    </label>
                                    <textarea class="form-control" id="message" name="message" rows="8"></textarea>
                                </div>
                                <input type="hidden" name="template" value="e7d0b613d125406ea714907d6507c2a9">
                                @foreach($emails as $email)
                                    <input type="hidden" name="notification_emails[]" value="{{ $email }}">
                                @endforeach
                                <div class="col-12">
                                    <small class="text-muted">This form is protected by reCAPTCHA and the Google <a
                                            href="https://policies.google.com/privacy" style="color: #2e4053">Privacy Policy</a>
                                        and <a href="https://policies.google.com/terms" style="color: #2e4053">Terms of Service</a>
                                        apply.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3 mt-md-4">
                        <button type="submit" class="btn btn-primary btn-lg text-white" style="background-color: #2e4053; border-color: #2e4053">
                            Submit
                        </button>
                    </div>
                </form>
            @endif
@break

        @case('auth-form')
            @php
                // Check if this is a new auth-form with authFormData or old one with hardcoded HTML
                $authFormData = $component['authFormData'] ?? [];
                $hasAuthFormData = !empty($authFormData);
                
                // Get colors from authFormData if available, otherwise use defaults
                $backgroundColor = $authFormData['backgroundColor'] ?? '#ffffff';
                $buttonColor = $authFormData['buttonColor'] ?? '#2e4053';
                $buttonTextColor = $authFormData['buttonTextColor'] ?? '#ffffff';
                $avatarIconColor = $authFormData['avatarIconColor'] ?? '#2e4053';
                $linkColor = $authFormData['linkColor'] ?? '#2e4053';
            @endphp
            @php
                // Check if this is a new auth-form with authFormData or old one with hardcoded HTML
                $authFormData = $component['authFormData'] ?? [];
                $hasAuthFormData = !empty($authFormData);
                
                // Get colors from authFormData if available, otherwise use defaults
                $backgroundColor = $authFormData['backgroundColor'] ?? '#ffffff';
                $buttonColor = $authFormData['buttonColor'] ?? '#2e4053';
                $buttonTextColor = $authFormData['buttonTextColor'] ?? '#ffffff';
                $avatarIconColor = $authFormData['avatarIconColor'] ?? '#2e4053';
                $linkColor = $authFormData['linkColor'] ?? '#2e4053';
            @endphp
            
            @if($hasAuthFormData)
                {{-- New auth-form with dynamic colors --}}
                <style>
                    /* Auth form background styling */
                    .auth-form-container {
                        background-color: {{ $backgroundColor }} !important;
                        padding: 2rem;
                        border-radius: 0.5rem;
                    }
                </style>
                <div style="{{ $styleStr }}" class="auth-form-container">
                    <div class="row">
                        <div class="col-md-12 mt-4 mb-4 text-center">
                            <i class="fa-solid fa-circle-user fa-fw mb-3" aria-hidden="true" style="font-size: 8rem; color: {{ $avatarIconColor }} !important;"></i>
                            <h2 class="display-6 tit">Register</h2>
                        </div>
                    </div>
                    <div class="register">
                        <div class="container">
                            <form action="/register" method="POST">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <label for="first_name" class="form-label">First name</label>
                                        <input type="text" class="form-control" id="first_name" name="name">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="last_name" class="form-label">Last name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name">
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="email" name="email">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="confirm_email" class="form-label">Confirm email address</label>
                                        <input type="email" class="form-control" id="confirm_email" name="confirm_email">
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <label for="register_as" class="form-label">Register as</label>
                                        <select class="form-select" id="register_as" name="register_as" onchange="toggleGroupSelect(this)">
                                            <option value="individual">Individual</option>
                                            <option value="group">Group Member</option>
                                            <option value="group_leader">Group Leader</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4" id="group_select_wrapper" style="display:none;">
                                        <label for="group_id" class="form-label">Select Group</label>
                                        <select class="form-select" id="group_id" name="group_id">
                                            <option value="">Select a group</option>
                                            @if(isset($groups))
                                                @foreach($groups as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="confirm_password" class="form-label">Confirm password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-8">
                                        <div class="d-grid gap-3 mt-2">
                                            <button class="btn btn-lg text-white" type="submit" style="background-color: {{ $buttonColor }} !important; border-color: transparent; color: {{ $buttonTextColor }} !important;">
                                                <i class="fa-solid fa-door-open me-1" aria-hidden="true"></i>
                                                Register
                                            </button>
                                            <button class="btn btn-lg p-0 shadow-none view-login-form" type="button" style="color: {{ $linkColor }} !important;">
                                                Login
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="login" style="display: none;">
                        <div class="container">
                            <form action="/login" method="POST">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <label for="login_email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="login_email" name="email">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="login_password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="login_password" name="password">
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-8">
                                        <div class="d-grid gap-3 mt-2">
                                            <button class="btn btn-lg text-white" type="submit" style="background-color: {{ $buttonColor }} !important; border-color: transparent; color: {{ $buttonTextColor }} !important;">
                                                <i class="fa-solid fa-door-open me-1" aria-hidden="true"></i>
                                                Login
                                            </button>
                                            <button class="btn btn-lg p-0 shadow-none view-register-form" type="button" style="color: {{ $linkColor }} !important;">
                                                Register
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Toggle between login and register forms
                            const loginButtons = document.querySelectorAll('.view-login-form');
                            const registerButtons = document.querySelectorAll('.view-register-form');
                            const registerForms = document.querySelectorAll('.register');
                            const loginForms = document.querySelectorAll('.login');

                            loginButtons.forEach(button => {
                                button.addEventListener('click', function() {
                                    registerForms.forEach(form => form.style.display = 'none');
                                    loginForms.forEach(form => form.style.display = 'block');
                                });
                            });

                            registerButtons.forEach(button => {
                                button.addEventListener('click', function() {
                                    loginForms.forEach(form => form.style.display = 'none');
                                    registerForms.forEach(form => form.style.display = 'block');
                                });
                            });
                        });

                        function toggleGroupSelect(selectElement) {
                            const groupWrapper = document.getElementById('group_select_wrapper');
                            if (selectElement.value === 'group') {
                                groupWrapper.style.display = 'block';
                            } else {
                                groupWrapper.style.display = 'none';
                            }
                        }
                    </script>
                </div>
            @else
                {{-- Legacy auth-form with hardcoded HTML - fallback for existing components --}}
                <div style="{{ $styleStr }}">
                    @if(isset($component['html']))
                        {!! $component['html'] !!}
                    @else
                        {{-- Default auth form if no HTML is available --}}
                        <div class="auth-form-container" style="background-color: {{ $backgroundColor }}; padding: 2rem; border-radius: 0.5rem;">
                            <div class="row">
                                <div class="col-md-12 mt-4 mb-4 text-center">
                                    <i class="fa-solid fa-circle-user fa-fw mb-3" aria-hidden="true" style="font-size: 8rem; color: {{ $avatarIconColor }} !important;"></i>
                                    <h2 class="display-6 tit">Register</h2>
                                </div>
                            </div>
                            <p class="text-center">Please configure this auth form component in the admin panel.</p>
                        </div>
                    @endif
                </div>
            @endif
        @break

        @case('social-share')
            @php
                // Always use dynamic rendering for social-share, ignore legacy HTML
                $shareData = $component['shareData'] ?? [];
                
                // If no structured data exists, use smart defaults
                if (empty($shareData)) {
                    $shareData = [
                        'title' => 'I Just Want to Help!',
                        'show_title' => true,
                        'icon_size' => '4rem',
                        'icon_color' => '#1877f2',
                        'text_color' => '#000000',
                        'title_color' => '#000000',
                        'max_columns' => 4,
                        'platforms' => [
                            'facebook' => ['enabled' => true, 'url' => '', 'text' => 'Share on Facebook'],
                            'twitter' => ['enabled' => true, 'url' => '', 'text' => 'Share on Twitter'], 
                            'linkedin' => ['enabled' => true, 'url' => '', 'text' => 'Share on LinkedIn'],
                            'instagram' => ['enabled' => true, 'url' => '', 'text' => 'Share on Instagram'],
                            'tiktok' => ['enabled' => false, 'url' => '', 'text' => 'Share on TikTok'],
                            'youtube' => ['enabled' => false, 'url' => '', 'text' => 'Share on YouTube'],
                            'pinterest' => ['enabled' => false, 'url' => '', 'text' => 'Share on Pinterest'],
                            'whatsapp' => ['enabled' => false, 'url' => '', 'text' => 'Share on WhatsApp'],
                            'telegram' => ['enabled' => false, 'url' => '', 'text' => 'Share on Telegram'],
                            'copy' => ['enabled' => true, 'url' => url()->current(), 'text' => 'Copy Link']
                        ]
                    ];
                }
                
                $platforms = $shareData['platforms'] ?? [
                    'facebook' => ['enabled' => true, 'url' => '', 'text' => 'Share on Facebook'],
                    'twitter' => ['enabled' => true, 'url' => '', 'text' => 'Share on Twitter'], 
                    'linkedin' => ['enabled' => true, 'url' => '', 'text' => 'Share on LinkedIn'],
                    'instagram' => ['enabled' => true, 'url' => '', 'text' => 'Share on Instagram'],
                    'tiktok' => ['enabled' => false, 'url' => '', 'text' => 'Share on TikTok'],
                    'youtube' => ['enabled' => false, 'url' => '', 'text' => 'Share on YouTube'],
                    'pinterest' => ['enabled' => false, 'url' => '', 'text' => 'Share on Pinterest'],
                    'whatsapp' => ['enabled' => false, 'url' => '', 'text' => 'Share on WhatsApp'],
                    'telegram' => ['enabled' => false, 'url' => '', 'text' => 'Share on Telegram'],
                    /* 'copy' => ['enabled' => true, 'url' => url()->current(), 'text' => 'Copy Link'] */
                ];
                
                $mainTitle = $shareData['title'] ?? 'I Just Want to Help!';
                $iconSize = $shareData['icon_size'] ?? $shareData['iconSize'] ?? '4rem';
                $iconColor = $shareData['icon_color'] ?? $shareData['iconColor'] ?? '';
                $textColor = $shareData['text_color'] ?? $shareData['textColor'] ?? '#000000';
                $titleColor = $shareData['title_color'] ?? $shareData['titleColor'] ?? '#000000';
                $showTitle = $shareData['show_title'] ?? $shareData['showTitle'] ?? true;
                $layout = $shareData['layout'] ?? 'grid';
                $maxColumns = $shareData['max_columns'] ?? $shareData['maxColumns'] ?? 4;
                
                // Calculate bootstrap class based on maxColumns with better responsive behavior
                $colXl = 12 / $maxColumns; // Large screens: use max columns
                $colLg = min(12 / max(1, $maxColumns - 1), 6); // Medium screens: reduce by 1 column, max 6 cols (2 per row)
                $colMd = min(12 / max(1, $maxColumns - 2), 4); // Small tablets: reduce by 2, max 4 cols (3 per row)
                $colSm = 6; // Small screens: always 2 per row
                $col = 12; // Extra small: 1 per row
                
                $bootstrapClass = "col-{$col} col-sm-{$colSm} col-md-{$colMd} col-lg-{$colLg} col-xl-{$colXl}";
                
                // Get current page URL for sharing
                $currentUrl = url()->current();
                $pageTitle = $mainTitle;
            @endphp
            
            <div class="social-share-component" style="{{ $styleStr }}">
                {{-- Add responsive CSS for better icon layout --}}
                <style>
                .social-share-component .row {
                    --bs-gutter-x: 1rem;
                    --bs-gutter-y: 1rem;
                }
                
                .social-share-component a {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    transition: transform 0.2s ease, opacity 0.2s ease;
                    padding: 0.5rem;
                    border-radius: 0.5rem;
                }
                
                .social-share-component a:hover {
                    transform: scale(1.1);
                    opacity: 0.8;
                }
                
                @media (max-width: 576px) {
                    .social-share-component .row {
                        --bs-gutter-x: 0.5rem;
                        --bs-gutter-y: 0.75rem;
                    }
                    
                    .social-share-component a {
                        padding: 0.25rem;
                    }
                }
                </style>
                
                @if($showTitle && $mainTitle)
                    <div class="text-center mb-4">
                        <h2 class="display-5 fw-normal" style="color: {{ $titleColor }};">{{ $mainTitle }}</h2>
                    </div>
                @endif
                
                <div class="row justify-content-center align-items-center">
                    @foreach($platforms as $platform => $config)
                        @if($config['enabled'] ?? false)
                            {{-- <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center"> --}}
                                    @switch($platform)
                                        @case('facebook')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($currentUrl);
                                            @endphp
                                            <a class="text-center btn-facebook-share" href="{{ $shareUrl }}" target="_blank" 
                                               style="color: {{ $iconColor ?: '#1877f2' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('twitter')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://twitter.com/intent/tweet?url=" . urlencode($currentUrl) . "&text=" . urlencode($pageTitle);
                                            @endphp
                                            <a class="text-center btn-twitter-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#1da1f2' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('linkedin')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://www.linkedin.com/sharing/share-offsite/?url=" . urlencode($currentUrl);
                                            @endphp
                                            <a class="text-center btn-linkedin-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#0077b5' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('instagram')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://www.instagram.com/";
                                            @endphp
                                            <a class="text-center btn-instagram-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#e1306c' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('tiktok')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://www.tiktok.com/";
                                            @endphp
                                            <a class="text-center btn-tiktok-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#000000' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('youtube')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://www.youtube.com/";
                                            @endphp
                                            <a class="text-center btn-youtube-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#ff0000' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('pinterest')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://pinterest.com/pin/create/button/?url=" . urlencode($currentUrl) . "&description=" . urlencode($pageTitle);
                                            @endphp
                                            <a class="text-center btn-pinterest-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#bd081c' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.404-5.958 1.404-5.958s-.359-.219-.359-1.219c0-1.141.66-1.993 1.482-1.993.699 0 1.037.525 1.037 1.155 0 .703-.449 1.753-.68 2.723-.194.821.412 1.492 1.222 1.492 1.467 0 2.595-1.544 2.595-3.773 0-1.972-1.415-3.353-3.437-3.353-2.343 0-3.718 1.756-3.718 3.571 0 .708.273 1.466.614 1.878.067.082.077.154.057.238-.062.26-.2.814-.227.927-.035.146-.116.177-.268.107-1.001-.465-1.624-1.926-1.624-3.1 0-2.596 1.884-4.982 5.432-4.982 2.851 0 5.071 2.032 5.071 4.75 0 2.837-1.789 5.121-4.27 5.121-.834 0-1.622-.435-1.89-1.013l-.514 1.96c-.185.716-.685 1.613-1.019 2.16C9.394 23.924 10.675 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('whatsapp')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://wa.me/?text=" . urlencode($pageTitle . ' ' . $currentUrl);
                                            @endphp
                                            <a class="text-center btn-whatsapp-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#25d366' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                        
                                        @case('telegram')
                                         <div class="{{ $bootstrapClass }}">
                                <div class="d-flex justify-content-center align-items-center">
                                            @php
                                                $shareUrl = !empty($config['url']) ? $config['url'] : "https://t.me/share/url?url=" . urlencode($currentUrl) . "&text=" . urlencode($pageTitle);
                                            @endphp
                                            <a class="text-center btn-telegram-share" href="{{ $shareUrl }}" target="_blank"
                                               style="color: {{ $iconColor ?: '#0088cc' }}; text-decoration: none;">
                                                <div style="font-size: {{ $iconSize }};">
                                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                                    </svg>
                                                </div>
                                            </a>
                                            </div>
                            </div>
                                        @break
                                    @endswitch
                                {{-- </div>
                            </div> --}}
                        @endif
                    @endforeach
                </div>
            </div>
        @break

        @default
            {{-- Fallback for any unhandled component types --}}
            <div style="{{ $styleStr }}">
                @if(isset($component['html']))
                    {!! $component['html'] !!}
                @else
                    {{-- Silent fallback - no placeholder text displayed --}}
                    <div style="display: none;"></div>
                @endif
            </div>
    @endswitch
</div>
