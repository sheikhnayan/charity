@php
$state = $data && $data->state ? (is_string($data->state) ? json_decode($data->state, true) : $data->state) : [];
// Handle both old format (direct array) and new format (object with components)
if (isset($state['components'])) {
    $state = $state['components'];
}
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data->name ?? 'Page' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{background:#f9fafb;}</style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <style>
    /* Base Component Styles */
    #studentTable {
        background-color: #fff !important;
        border: none !important;
    }

    #studentTable th, #studentTable td {
        background-color: #fff !important;
        border: none !important;
    }

    #studentTable tbody tr {
        background-color: #fff !important;
    }

    #studentTable_filter, #studentTable_length {
        display: none;
    }

    #studentTable thead {
        display: none;
    }

    .non-float{
        margin-bottom: -111px;
    }

    /* Investment Tier Background Image Support */
    .investment-tier-bg {
        background-attachment: scroll !important;
        background-clip: border-box !important;
    }

    .perk-wrap {
        background-attachment: scroll !important;
    }

    /* Auction Components Styles */
    .c-node-ap__auction-results{
        margin-right: 36px;
        margin-bottom: 24px;
        display: inline-block;
        background-color: #f8f9fa;
        border-color: #DBDCDD;
        border: 1px solid;
        border-radius: 4px;
        padding: 24px;
        font-size: 1rem;
    }

    .c-node-ap__fundraising-target{
        margin-bottom: 12px;
    }

    .c-node-ap__auction-total-label {
        margin-bottom: 12px;
        font-size: 1.25rem;
        line-height: 1.2;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
        color: #355159
    }
    .c-node-ap__auction-total-amount {
        font-size: 2rem;
        line-height: 1.5;
        color: #d9b730;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
    }

    .c-node-ap__totalizer{
        height: 18px;
        border-radius: 12px;
        --color-ui: #d9b730;
    }

    .c-node-ap__auction-total-component-label{
        color: #6d6e71
    }

    .c-node-ap__auction-total-component-amount{
        font-size: 1rem;
        line-height: 1.2;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
        color: #000
    }
    .c-view__item.c-view__item--teaser {
        width: 100% !important;
        max-width: 100% !important;
        flex-basis: 100% !important;
        min-width: 330px !important;
    }

    .c-content__bottom{
        background-color: #f9fafb;
    }
    .gallery-img-preview {
        height: 421px !important;
    }

    .owl-item .item img{
        height: 425px !important;
    }

    .footer-socials .nav-item {
        margin-right: 1rem !important;
    }

    .footer-socials .nav-item a i {
        font-size: 1.5rem;
    }

    footer{
        position: relative;
        width: 100%;
        bottom: 0;
        margin-top: 2rem;
    }

    .ticket-mask {
        --mask: conic-gradient(from 45deg at left,#0000,#000 1deg 89deg,#0000 90deg) left/51% 16.00px repeat-y,conic-gradient(from -135deg at right,#0000,#000 1deg 89deg,#0000 90deg) 100% calc(50% + 8px)/51% 16.00px repeat-y;
        -webkit-mask: var(--mask);
        mask: var(--mask);
        padding: 1.5rem;
        background-color: #eee;
        border: unset;
    }

    /* Universal Inner Section Wrapper - Completely Invisible by Default */
    .page-inner-section {
        width: 100%;
        margin: 0;
        padding: 0;
        background: transparent;
        border: none;
        box-sizing: border-box;
    }

    .page-inner-section .inner-column {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        background: transparent;
        border: none;
    }

    /* Component Styling - All components get consistent spacing */
    .page-component {
        width: 100%;
        box-sizing: border-box;
        position: relative;
    }

    /* Responsive Grid System for Inner Sections - No Visual Styling */
    .inner-section-grid {
        display: grid;
        width: 100%;
        gap: 0;
        grid-template-columns: 1fr;
        background: transparent;
        border: none;
        margin: 0;
        padding: 0;
    }

    .inner-section-grid.cols-2 {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .inner-section-grid.cols-3 {
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .inner-section-grid.cols-4 {
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .inner-section-grid.cols-5 {
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
    }

    .inner-section-grid.cols-6 {
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
    }

    /* Responsive breakpoints for grid columns */
    @media (max-width: 1200px) {
        .inner-section-grid.cols-6 {
            grid-template-columns: repeat(4, 1fr);
        }
        .inner-section-grid.cols-5 {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 992px) {
        .inner-section-grid.cols-6,
        .inner-section-grid.cols-5,
        .inner-section-grid.cols-4 {
            grid-template-columns: repeat(3, 1fr);
        }
        .inner-section-grid.cols-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .inner-section-grid.cols-6,
        .inner-section-grid.cols-5,
        .inner-section-grid.cols-4,
        .inner-section-grid.cols-3,
        .inner-section-grid.cols-2 {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }

    @media (max-width: 576px) {
        .inner-section-grid {
            gap: 10px;
        }
    }

    @php
        // Generate comprehensive responsive CSS for all components and nested components
        function generateResponsiveStyles($state) {
            $css = '';
            
            if (!is_array($state)) return $css;
            
            foreach ($state as $index => $component) {
                // Handle main components (including auto-wrapped ones)
                if (isset($component['responsiveStyles'])) {
                    $componentId = "component-{$index}";
                    $css .= generateComponentResponsiveCSS($componentId, $component['responsiveStyles']);
                }
                
                // Handle auto-wrapped components
                if (isset($component['type']) && $component['type'] === 'inner-section') {
                    // Check for nested components in auto-wrapped inner-sections
                    if (isset($component['nestedComponents']) && is_array($component['nestedComponents'])) {
                        foreach ($component['nestedComponents'] as $columnIndex => $columnComponents) {
                            if (is_array($columnComponents)) {
                                foreach ($columnComponents as $nestedIndex => $nestedComponent) {
                                    if (isset($nestedComponent['responsiveStyles'])) {
                                        $nestedId = "nested-{$columnIndex}-{$nestedIndex}";
                                        $css .= generateComponentResponsiveCSS($nestedId, $nestedComponent['responsiveStyles']);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    // Handle legacy components that might have been auto-wrapped
                    if (isset($component['responsiveStyles'])) {
                        $autoWrappedId = "auto-wrapped-{$index}";
                        $css .= generateComponentResponsiveCSS($autoWrappedId, $component['responsiveStyles']);
                    }
                }
            }
            
            return $css;
        }
        
        function generateComponentResponsiveCSS($componentId, $styles) {
            $css = "/* Component {$componentId} responsive styles */\n";
            
            // Desktop styles (default - 992px and up)
            if (isset($styles['desktop']) && is_array($styles['desktop'])) {
                $desktopStyles = [];
                foreach ($styles['desktop'] as $prop => $value) {
                    if (!empty($value) && trim($value) !== '') {
                        $desktopStyles[] = "{$prop}: {$value}";
                    }
                }
                if (!empty($desktopStyles)) {
                    $css .= "#{$componentId} { " . implode('; ', $desktopStyles) . "; }\n";
                }
            }
            
            // Tablet styles (768px to 991px)
            if (isset($styles['tablet']) && is_array($styles['tablet'])) {
                $tabletStyles = [];
                foreach ($styles['tablet'] as $prop => $value) {
                    if (!empty($value) && trim($value) !== '') {
                        $tabletStyles[] = "{$prop}: {$value} !important";
                    }
                }
                if (!empty($tabletStyles)) {
                    $css .= "@media screen and (max-width: 991px) and (min-width: 768px) {\n";
                    $css .= "  #{$componentId} { " . implode('; ', $tabletStyles) . "; }\n";
                    $css .= "}\n";
                }
            }
            
            // Mobile styles (up to 767px)
            if (isset($styles['mobile']) && is_array($styles['mobile'])) {
                $mobileStyles = [];
                foreach ($styles['mobile'] as $prop => $value) {
                    if (!empty($value) && trim($value) !== '') {
                        $mobileStyles[] = "{$prop}: {$value} !important";
                    }
                }
                if (!empty($mobileStyles)) {
                    $css .= "@media screen and (max-width: 767px) {\n";
                    $css .= "  #{$componentId} { " . implode('; ', $mobileStyles) . "; }\n";
                    $css .= "}\n";
                }
            }
            
            return $css;
        }
        
        echo generateResponsiveStyles($state);
    @endphp
    </style>
</head>
<body style="overflow-x: hidden; background-color: {{ $data->background_color ?? '#fff'}};">
    @php
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $check = \App\Models\Website::where('domain', $domain)->first();
        $groups = \App\Models\User::where('website_id', $check->id)->where('role','group_leader')->get();
        $auction = \App\Models\Auction::where('website_id', $check->id)->where('status',1)->latest()->get();

        $header = \App\Models\Header::where('website_id', $check->id)->first();
        $footer = \App\Models\Footer::where('website_id', $check->id)->first();
    @endphp
    
    @if ($header && $header->status == 1)
        @include('layouts.nav')
    @endif
    
    <main style="margin-top: 6.9rem;">
        @session('success')
            <div class="alert alert-success mt-4" role="alert">
                Purchase Pending
            </div>
        @endsession

        @session('error')
            <div class="alert alert-danger mt-4" role="alert">
                {{ $value }}
            </div>
        @endsession
        @session('errors')
            <div class="alert alert-danger mt-4" role="alert">
                @foreach($errors->all() as $value)
                    <div>{{ $value }}</div>
                @endforeach
            </div>
        @endsession

        {{-- Handle banner components that need special positioning --}}
        @foreach($state as $key => $component)
            @if($key == 0 && isset($component['type']) && $component['type'] == 'custom-banner')
                @php $banner = $component['customBannerData'] ?? []; @endphp
                <div style="position:relative; text-align:{{ $banner['textAlign'] ?? 'center' }};
                    @if($header && $header->floating == 1) margin-top: -7px; @endif">
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
                        <p style="position:absolute; top:45%; left:50%; transform:translate(-50%,-50%);
                            color:{{ $banner['subtitleColor'] ?? '#fff' }};
                            text-shadow:{{ $banner['subtitleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                            font-size:{{ $banner['subtitleFontSize'] ?? '1.2em' }}; width: 90%;
                            text-align:{{ $banner['textAlign'] ?? 'center' }};
                            margin-top: {{ $banner['subtitleMarginTop'] ?? '0px' }}">
                            {{ $banner['subtitle'] }}
                        </p>
                    @endif
                </div>
            @endif
        @endforeach

        {{-- Main content area with universal inner-section handling --}}
        <div class="container px-3" id="rendered-page">
            @foreach($state as $index => $component)
                @php 
                    $componentType = $component['type'] ?? '';
                    $componentId = "component-{$index}";
                    
                    // Check if this inner-section has menu data and should use section ID
                    if ($componentType === 'inner-section' && isset($component['innerSectionData'])) {
                        $innerSectionData = $component['innerSectionData'];
                        if (isset($innerSectionData['addToMenu']) && $innerSectionData['addToMenu'] && 
                            isset($innerSectionData['sectionId']) && !empty($innerSectionData['sectionId'])) {
                            $componentId = $innerSectionData['sectionId'];
                        }
                    }
                    
                    // Skip banner if it was already rendered above
                    if ($index == 0 && $componentType == 'custom-banner') {
                        continue;
                    }
                    
                    // Since all components are now wrapped in inner-sections, 
                    // we need to handle both actual inner-sections and auto-wrapped ones
                @endphp
                
                {{-- Universal Inner Section Wrapper --}}
                <div class="page-inner-section" id="{{ $componentId }}">
                    @if($componentType === 'inner-section')
                        {{-- This is an actual inner-section component --}}
                        @php
                            $innerSectionData = $component['innerSectionData'] ?? [];
                            $nestedComponents = $component['nestedComponents'] ?? [];
                            $columns = $innerSectionData['columns'] ?? 1;
                            $gap = $innerSectionData['gap'] ?? '0px';
                            
                            // Apply custom styling ONLY if explicitly set in page-builder
                            $sectionStyle = '';
                            
                            // Only add background if explicitly set and not transparent/empty
                            if (isset($innerSectionData['backgroundColor']) 
                                && $innerSectionData['backgroundColor'] !== 'transparent' 
                                && $innerSectionData['backgroundColor'] !== '' 
                                && $innerSectionData['backgroundColor'] !== '#f8f9fa') {
                                $sectionStyle .= "background-color: {$innerSectionData['backgroundColor']};";
                            }
                            
                            // Only add padding if explicitly set and not 0
                            if (isset($innerSectionData['padding']) 
                                && $innerSectionData['padding'] !== '0px' 
                                && $innerSectionData['padding'] !== '20px' 
                                && $innerSectionData['padding'] !== '') {
                                $sectionStyle .= "padding: {$innerSectionData['padding']};";
                            }
                            
                            // Only add margin if explicitly set and not 0
                            if (isset($innerSectionData['margin']) 
                                && $innerSectionData['margin'] !== '0px' 
                                && $innerSectionData['margin'] !== '10px 0' 
                                && $innerSectionData['margin'] !== '') {
                                $sectionStyle .= "margin: {$innerSectionData['margin']};";
                            }
                            
                            // Only add border if explicitly set and not default dashed
                            if (isset($innerSectionData['borderStyle']) 
                                && $innerSectionData['borderStyle'] !== 'none' 
                                && $innerSectionData['borderStyle'] !== 'dashed'
                                && $innerSectionData['borderStyle'] !== '') {
                                $borderWidth = $innerSectionData['borderWidth'] ?? '2px';
                                $borderColor = $innerSectionData['borderColor'] ?? '#ddd';
                                $sectionStyle .= "border: {$borderWidth} {$innerSectionData['borderStyle']} {$borderColor};";
                            }
                            
                            // Only add border-radius if explicitly set and not default
                            if (isset($innerSectionData['borderRadius']) 
                                && $innerSectionData['borderRadius'] !== '0px' 
                                && $innerSectionData['borderRadius'] !== '8px' 
                                && $innerSectionData['borderRadius'] !== '') {
                                $sectionStyle .= "border-radius: {$innerSectionData['borderRadius']};";
                            }
                        @endphp
                        
                        <div class="inner-section-grid cols-{{ $columns }}" 
                             style="{{ $sectionStyle }} gap: {{ $gap }};">
                            @for($columnIndex = 0; $columnIndex < $columns; $columnIndex++)
                                <div class="inner-column">
                                    @if(isset($nestedComponents[$columnIndex]) && is_array($nestedComponents[$columnIndex]))
                                        @foreach($nestedComponents[$columnIndex] as $nestedIndex => $nestedComponent)
                                            @php $nestedComponentId = "nested-{$columnIndex}-{$nestedIndex}"; @endphp
                                            <div class="page-component" id="{{ $nestedComponentId }}">
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
                        {{-- This is an auto-wrapped component (1-column transparent inner-section) --}}
                        <div class="inner-section-grid cols-1">
                            <div class="inner-column">
                                <div class="page-component" id="auto-wrapped-{{ $index }}">
                                    @include('page-components.render-component', [
                                        'component' => $component, 
                                        'componentId' => "auto-wrapped-{$index}",
                                        'isNested' => false
                                    ])
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </main>

    @if($footer)
        {!! $footer->content !!}
    @endif

    <!-- Gallery Image Modal -->
    <div class="modal" id="galleryImageModal" tabindex="-1" aria-labelledby="galleryImageModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
          <div class="modal-body text-center p-0">
            <img id="galleryImageModalImg" src="" alt="Gallery Preview" style="max-width:100%;max-height:80vh;border-radius:12px;">
          </div>
        </div>
      </div>
    </div>

    <script>
        function openGalleryImageModal(src, alt) {
            document.getElementById('galleryImageModalImg').src = src;
            document.getElementById('galleryImageModalImg').alt = alt;
            new bootstrap.Modal(document.getElementById('galleryImageModal')).show();
        }

        // Add scroll margin for menu navigation
        document.addEventListener('DOMContentLoaded', function() {
            @if(isset($menuSections) && is_array($menuSections))
                const menuSections = @json($menuSections);
                
                // Add scroll margin to sections that have menu links
                menuSections.forEach(function(menuSection) {
                    const sectionElement = document.getElementById(menuSection.sectionId);
                    if (sectionElement) {
                        sectionElement.style.scrollMarginTop = '{{ $header && $header->floating == 1 ? "100px" : "20px" }}';
                    }
                });
            @endif
        });
    </script>
</body>
</html>
