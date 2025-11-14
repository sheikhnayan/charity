<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ticket->name }} | Property Investment Details</title>
    <meta name="description" content="Invest in {{ $ticket->name }} for as little as ${{ number_format($ticket->price_per_share, 2) }} per share!">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Fonts CSS -->
    <link href="{{ route('fonts.css') }}" rel="stylesheet">
    
    <style>

        nav a {
            color: #9da3ab !important;
            font-size: 17px !important;
            }

            .collapse{
                visibility: visible !important;
            }
        .property-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        /* Responsive number sizing for stat cards */
        .responsive-number {
            font-size: 1.5rem;
            line-height: 1.2;
            word-break: break-all;
            overflow-wrap: break-word;
            hyphens: auto;
        }
        
        /* Adjust font size based on content length */
        @media (min-width: 768px) {
            .responsive-number {
                font-size: clamp(1rem, 4vw, 1.5rem);
            }
        }
        
        @media (max-width: 767px) {
            .responsive-number {
                font-size: clamp(0.9rem, 3.5vw, 1.25rem);
            }
        }
        
        /* Container constraints for stat cards */
        .stat-card {
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .stat-card > div {
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .investment-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .investment-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .markdown-content h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #1e293b;
        }
        
        .markdown-content ul {
            list-style-type: disc;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .markdown-content li {
            margin-bottom: 0.5rem;
        }
        
        .markdown-content a {
            color: #667eea;
            text-decoration: underline;
        }
        
        .markdown-content p {
            margin-bottom: 1rem;
            line-height: 1.7;
        }
        
        /* Contact Topbar Styles */
        .contact-topbar {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        
        .contact-item a {
            text-decoration: none;
        }
        
        .contact-item a:hover {
            opacity: 0.8;
        }
        
        /* Investor Exclusives Bar */
        .investor-exclusives-bar {
            width: 100%;
            text-align: center;
        }
        
        .investor-exclusives-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 8px 0;
        }
        
        .investor-exclusives-text {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .invest-button-section {
            flex-shrink: 0;
        }

        .invest-mobile{
            max-width: 1320px !important;
        }

        .invest-now-btn {
            background: #28a745;
            color: #ffffff;
            border: none;
            padding: 12px 32px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            min-width: 140px;
        }

        /* Responsive adjustments for smaller screens */
        @media (max-width: 360px) {
            .sticky-cta-content {
                padding: 10px 12px;
            }
            
            .price-value {
                font-size: 16px;
            }
            
            .invest-now-btn {
                padding: 10px 24px;
                font-size: 13px;
                min-width: 120px;
            }

            footer{
                margin-bottom: 100px !important;
            }
        }
        

        .sssssttttt{
            padding: 1.25rem 2.7rem !important;
            border-radius: 0px !important;
            font-family: sans-serif !important;
        }

        .invest-now-btn:hover {
            background: #218838;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        .invest-now-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
        }

        /* Investor Exclusives Bar Styles - Dynamic Positioning */
    .investor-exclusives-bar {
        padding: 0px 0px;
        text-align: center;
        position: fixed;
        top: calc(var(--navbar-total-height, 6rem) - 0.23rem); /* Dynamic position minus gap adjustment */
        left: 0;
        right: 0;
        width: 100%;
        z-index: 999; /* Just below navbar but above content */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }


        /* Custom Fonts @font-face declarations */
    @if(isset($customFonts) && $customFonts->count() > 0)
    /* DEBUG: {{ $customFonts->count() }} custom fonts loaded */
    @foreach($customFonts as $font)
    @font-face {
        font-family: '{{ $font->font_family }}';
        src: url('{{ asset('storage/' . $font->file_path) }}') format('{{ $font->file_format == 'ttf' ? 'truetype' : ($font->file_format == 'otf' ? 'opentype' : $font->file_format) }}');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }
    
    /* Apply custom font classes (for Quill editor content) */
    .ql-font-{{ $font->font_family }} {
        font-family: '{{ $font->font_family }}', sans-serif !important;
    }
    @endforeach
    @else
    /* DEBUG: No custom fonts available */
    @endif
    
    /* System font classes (for Quill editor content) */
    .ql-font-arial {
        font-family: Arial, sans-serif !important;
    }
    .ql-font-helvetica {
        font-family: Helvetica, sans-serif !important;
    }
    .ql-font-times {
        font-family: 'Times New Roman', serif !important;
    }
    .ql-font-georgia {
        font-family: Georgia, serif !important;
    }
    .ql-font-verdana {
        font-family: Verdana, sans-serif !important;
    }
    .ql-font-courier {
        font-family: 'Courier New', monospace !important;
    }
    .ql-font-outfit {
        font-family: 'Outfit', sans-serif !important;
    }
    
    /* Menu Font Family Styling */
    @if(isset($header) && $header && $header->menu_font_family)
    .navbar .nav-link,
    .navbar .navbar-brand,
    .navbar .btn {
        font-family: '{{ $header->menu_font_family }}', sans-serif !important;
    }
    @endif
    
    /* Contact Topbar Font Family Styling */
    @if(isset($header) && $header && $header->contact_topbar_font_family)
    .contact-topbar,
    .contact-topbar *:not(i):not(.fas):not(.fa):not(.far):not(.fab):not(.fal):not(.fad) {
        font-family: '{{ $header->contact_topbar_font_family }}', sans-serif !important;
    }
    @endif
    
    /* Investor Exclusives Font Family Styling */
    @if(isset($header) && $header && $header->investor_exclusives_font_family)
    .investor-exclusives-bar,
    .investor-exclusives-bar *:not(i):not(.fas):not(.fa):not(.far):not(.fab):not(.fal):not(.fad) {
        font-family: '{{ $header->investor_exclusives_font_family }}', sans-serif !important;
    }
    @endif


        /* Contact Top Bar Styles */
    .contact-topbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 1001; /* Above navbar */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .contact-topbar .contact-info {
        gap: 0;
    }
    
    .contact-topbar .contact-item {
        font-size: 14px;
        font-weight: 400 !important;
    }
    
    .contact-topbar .contact-item a {
        transition: all 0.3s ease;
        font-family: Outfit,sans-serif;
        text-decoration: underline !important;
    }
    
    .contact-topbar .contact-item a:hover {
        opacity: 0.8;
        text-decoration: none !important;
    }
    
    .contact-topbar .contact-item i {
        font-size: 12px;
        opacity: 0.9;
        display: inline-block;
        width: auto;
        min-width: 14px;
        text-align: center;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
    }
    
    /* Ensure FontAwesome icons are visible */
    .contact-topbar i.fas,
    .contact-topbar i.fa {
        font-family: "Font Awesome 6 Free" !important;
        font-weight: 900 !important;
    }
    
    .contact-topbar .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    /* Responsive design for contact top bar */
    @media (max-width: 768px) {
        .contact-topbar {
            padding: 8px 0 !important;
            font-size: 12px !important;
        }
        
        .contact-topbar .contact-item {
            font-size: 11px;
            margin-right: 8px !important;
            margin-bottom: 0 !important;
            text-align: center;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        
        .contact-topbar .contact-item:last-child {
            margin-right: 0 !important;
        }
        
        .contact-topbar .btn {
            font-size: 11px;
            padding: 4px 12px !important;
            margin-top: 2px;
        }
    }
    
    @media (max-width: 576px) {
        .contact-topbar {
            padding: 6px 0 !important;
        }
        
        .contact-topbar .contact-item {
            margin-right: 6px !important;
            margin-bottom: 0 !important;
            text-align: center;
            display: inline-flex;
            align-items: center;
            font-size: 10px;
            white-space: nowrap;
        }
        
        .contact-topbar .contact-item:last-child {
            margin-right: 0 !important;
        }
        
        .contact-topbar .btn {
            font-size: 10px;
            padding: 3px 10px !important;
            margin-top: 2px;
        }
    }
    
    /* Adjust navbar when contact top bar is present */
    .contact-topbar + nav.navbar {
        top: 2rem; /* Position navbar below contact bar */
    }
    
    @media (max-width: 768px) {
        .contact-topbar + nav.navbar {
            top: 1.7rem; /* Adjust for mobile */
        }

        .contact-topbar{
            height: 28px !important;
        }

        .close-on-mobile{
            display: none;
        }
    }


     .investor-exclusives-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }

    .marquee-content .item img{
        height: 64px !important;
        /* width: 64px !important; */
    }
    
    .investor-exclusives-text {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .investor-exclusives-link {
        background: rgba(255, 255, 255, 0.15);
        text-decoration: none;
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .text-style-eyebrow{
        font-family: Outfit,sans-serif !important;
    }

    .jqo-io-processed{
        padding: 0.3rem !important;
        padding-top: 0.5rem !important;
    }

    .link_wrap div{
        font-family: Outfit,sans-serif !important;
    }

    .footer_content_wrap div h1 strong {
        font-family: Outfit,sans-serif !important;
    }

    .footer_content_wrap div p {
        font-family: Outfit,sans-serif !important;
    }
    
    .investor-exclusives-link:hover {
        background: rgba(255, 255, 255, 0.25);
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
    }
    
    /* Icon styling */
    .investor-exclusives-link i {
        margin-left: 8px;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .investor-exclusives-bar {
            position: fixed;
            top: calc(var(--navbar-total-height-mobile, 9.5rem) - 0.23rem); /* Dynamic mobile position minus gap adjustment */
            padding-bottom: 0px;
        }
        
        .investor-exclusives-content {
            flex-direction: column;
            gap: 12px;
        }
        
        .investor-exclusives-text {
            font-size: 14px;
            text-align: center;
        }
        
        .investor-exclusives-link {
            font-size: 13px;
            padding: 6px 16px;
        }
    }
    
    @media (max-width: 480px) {
        /* Contact topbar mobile responsive */
        .contact-topbar {
            font-size: 12px !important;
            height: auto !important;
            padding: 4px 0 !important;
        }
        
        .contact-topbar .row {
            margin: 0 !important;
        }
        
        .contact-topbar .col-3, .contact-topbar .col-6 {
            padding: 2px 4px !important;
            font-size: 11px !important;
        }
        
        .contact-topbar .contact-item {
            margin: 0 !important;
            text-align: center !important;
        }
        
        .contact-topbar .contact-item i {
            margin-right: 4px !important;
        }
        
        .investor-exclusives-bar {
            padding: 10px 0;
            top: calc(var(--navbar-total-height-small, 1.7rem) - 0.23rem); /* Dynamic small mobile position minus gap adjustment */
            /* margin-top: 4rem; */
            padding-bottom: 0px;

        }
        
        .investor-exclusives-text {
            font-size: 13px;
            line-height: 1.4;
        }
        
        .investor-exclusives-link {
            font-size: 12px;
            padding: 5px 14px;
        }

        .navbar-brand{
            margin-left: 1rem !important;
            margin-top: 0.3rem !important;
            margin-bottom: 0.3rem !important;
        }

        .ticket-mask .row .col-md-10{
            text-align: center !important;
        }

        .ticket-mask .row .col-md-2 img{
            width: 100% !important;
        }
    }
    
    </style>
</head>
<body style="background-color: {{ $data->background_color ?? '#fff'}};">
    
    @php
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $check = \App\Models\Website::where('domain', $domain)->first();
        $groups = \App\Models\User::where('website_id', $check->id)->where('role','group_leader')->get();
        $auction = \App\Models\Auction::where('website_id', $check->id)->where('status',1)->latest()->get();
        
        // Use user_id to fetch header, footer, setting to match controller
        $user_id = $check->user_id;
        $header = \App\Models\Header::where('user_id', $user_id)->first();
        $footer = \App\Models\Footer::where('user_id', $user_id)->first();
        $setting = \App\Models\Setting::where('user_id', $user_id)->first();
        $menuSections = [];
        // dd($header->show_contact_topbar);
    @endphp
    
    <!-- Header -->
   @if ($header && $header->status == 1)
        {{-- Contact Information Top Bar --}}
        @if($header && $header->show_contact_topbar)
            <div class="contact-topbar" style="background: {{ $header->contact_topbar_bg_color ?? '#000000' }}; padding: 8px 0; font-size: 14px; height: 35px;">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        @if($header->contact_phone)
                        <div class="col-3 col-md-auto">
                            <div class="contact-item me-4 mb-1">
                                <i class="fas fa-phone me-2" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};"></i>
                                <a href="tel:{{ $header->contact_phone }}" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};">
                                    {{ $header->contact_phone }}
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($header->contact_email)
                        <div class="col-6 col-md-auto" style="text-align: center;">
                            <div class="contact-item me-4 mb-1">
                                <i class="fas fa-envelope me-2" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};"></i>
                                <a href="mailto:{{ $header->contact_email }}" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};">
                                    {{ $header->contact_email }}
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($header->contact_cta_text)
                        <div class="col-3 col-md-auto">
                            <div class="contact-item mb-1">
                                <i class="fas fa-map-marker-alt me-2" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};"></i>
                                <span style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }}; text-decoration : underline !important;">
                                    {{ $header->contact_cta_text }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

            @include('layouts.nav')

        
        
        {{-- Investor Exclusives Top Bar - Investment Websites Only --}}
        @if($check && $check->isInvestment() && $header && $header->show_investor_exclusives)
            <div class="investor-exclusives-bar" style="background: {{ $header->topbar_background_color ?? '#1e3a8a' }};">
                <div class="investor-exclusives-content">
                    <a href="{{ $header->investor_exclusives_url ?? '#' }}" style="text-decoration: none;">
                    <p class="investor-exclusives-text" style="color: {{ $header->topbar_text_color ?? '#ffffff' }}; font-size: 13px; padding-top: 5px; font-family: Outfit,sans-serif;text-transform: uppercase; padding-bottom: 4px;">
                        {{ $header->investor_exclusives_text ?? 'Exclusive access for investors' }}
                    </p>
                    </a>
                    {{-- <a href="{{ $header->investor_exclusives_url ?? '#' }}" class="investor-exclusives-link" style="color: {{ $header->topbar_text_color ?? '#ffffff' }};">
                        Learn More
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a> --}}
                </div>
            </div>

            {{-- Dynamic Navbar Height Calculator Script --}}
            <script>
                function updateNavbarHeights() {
                    const navbar = document.querySelector('.navbar');
                    const contactTopbar = document.querySelector('.contact-topbar');
                    const investorBar = document.querySelector('.investor-exclusives-bar');
                    
                    if (navbar) {
                        const navbarHeight = navbar.offsetHeight;
                        const contactTopbarHeight = contactTopbar ? contactTopbar.offsetHeight : 0;
                        const investorBarHeight = investorBar ? investorBar.offsetHeight : 0;
                        const totalNavHeight = navbarHeight + contactTopbarHeight;
                        const totalWithInvestorBar = totalNavHeight + investorBarHeight;
                        
                        // Convert to rem (assuming 16px base font size)
                        const totalHeightRem = totalNavHeight / 16;
                        const totalHeightRemMobile = (totalNavHeight + (contactTopbar ? 8 : 0)) / 16;
                        const totalHeightRemSmall = (totalNavHeight - (contactTopbar ? contactTopbarHeight * 0.3 : 0)) / 16;
                        
                        // Main content margin should account for investor bar if present
                        const mainContentMargin = totalWithInvestorBar / 16 + 0.5; // Extra space for clean separation
                        
                        // Set CSS custom properties
                        document.documentElement.style.setProperty('--navbar-total-height', `${totalHeightRem}rem`);
                        document.documentElement.style.setProperty('--navbar-total-height-mobile', `${totalHeightRemMobile}rem`);
                        document.documentElement.style.setProperty('--navbar-total-height-small', `${totalHeightRemSmall}rem`);
                        document.documentElement.style.setProperty('--main-content-margin-top', `${mainContentMargin}rem`);
                        
                        console.log('Dynamic Heights Updated:', {
                            navbar: navbarHeight,
                            contactTopbar: contactTopbarHeight,
                            investorBar: investorBarHeight,
                            totalNavHeight: totalNavHeight,
                            totalWithInvestor: totalWithInvestorBar,
                            mainMargin: mainContentMargin
                        });
                    }
                }
                
                // Run on load
                document.addEventListener('DOMContentLoaded', function() {
                    // Wait a bit for all elements to render
                    setTimeout(updateNavbarHeights, 50);
                });
                
                // Run on resize
                window.addEventListener('resize', updateNavbarHeights);
                
                // Run after fonts load (as this can affect navbar height)
                if (document.fonts) {
                    document.fonts.ready.then(updateNavbarHeights);
                }
                
                // Fallback: run after delays to catch any dynamic changes
                setTimeout(updateNavbarHeights, 100);
                setTimeout(updateNavbarHeights, 300);
                setTimeout(updateNavbarHeights, 500);
                setTimeout(updateNavbarHeights, 1000);
            </script>
        @endif
    @endif
    
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="margin-top: var(--main-content-margin-top, {{ 
        ($header && $header->show_contact_topbar && $check && $check->isInvestment() && $header->show_investor_exclusives) ? '14.2rem' : 
        (($header && $header->show_contact_topbar) ? '10.5rem' : 
        (($check && $check->isInvestment() && $header && $header->show_investor_exclusives) ? '10.6rem' : '6.9rem'))
    }});" 
          class="{{ 
            ($header && $header->show_contact_topbar && $check && $check->isInvestment() && $header && $header->show_investor_exclusives) ? 'with-contact-and-investor-bars' : 
            (($header && $header->show_contact_topbar) ? 'with-contact-bar' : 
            (($check && $check->isInvestment() && $header && $header->show_investor_exclusives) ? 'with-investor-bar' : ''))
        }}">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-900 font-medium">{{ $ticket->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <!-- Back Button -->
        <div class="mb-4">
            <button onclick="window.history.back()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Previous Page
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column - Property Images & Details -->
            <div class="lg:col-span-2">
                
                <!-- Property Status Badge -->
                <div class="mb-4">
                    <span class="property-badge inline-block px-4 py-2 rounded-full text-white text-sm font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>Active Investment
                    </span>
                </div>

                <!-- Property Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $ticket->name }}</h1>
                
                <!-- Property Location -->
                <div class="flex items-center text-gray-600 mb-6">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <span>{{ $ticket->website->name }}</span>
                </div>

                <!-- Image Gallery -->
                <div class="mb-8">
                    <div class="relative rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ asset($ticket->image) }}" alt="{{ $ticket->name }}" 
                             class="w-full h-96 object-cover" id="mainImage">
                        @if($ticket->images && count($ticket->images) > 0)
                        <button class="absolute top-4 right-4 bg-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-100 transition" onclick="toggleGallery()">
                            <i class="fas fa-images mr-2"></i>View all photos
                        </button>
                        @endif
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    @if($ticket->images && count($ticket->images) > 0)
                    <div class="grid grid-cols-4 md:grid-cols-6 gap-2 mt-4" id="thumbnailGallery">
                        <div class="thumbnail-item cursor-pointer rounded-lg overflow-hidden border-2 border-purple-500" onclick="changeImage('{{ asset($ticket->image) }}', this)">
                            <img src="{{ asset($ticket->image) }}" alt="Main" class="w-full h-20 object-cover">
                        </div>
                        @foreach($ticket->images as $image)
                        <div class="thumbnail-item cursor-pointer rounded-lg overflow-hidden border-2 border-transparent hover:border-purple-500 transition" 
                             onclick="changeImage('{{ asset($image->image_path) }}', this)">
                            <img src="{{ asset($image->image_path) }}" alt="Property image" class="w-full h-20 object-cover">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Key Metrics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="stat-card bg-white p-4 rounded-lg shadow-md">
                        <div class="text-gray-600 text-sm mb-1">Starting Price</div>
                        <div class="text-2xl font-bold text-purple-600 break-words responsive-number">${{ number_format($ticket->price_per_share, 2) }}</div>
                        <div class="text-xs text-gray-500 mt-1">per share</div>
                    </div>
                    
                    <div class="stat-card bg-white p-4 rounded-lg shadow-md">
                        <div class="text-gray-600 text-sm mb-1">Total Shares</div>
                        <div class="text-2xl font-bold text-gray-900 break-words responsive-number">{{ number_format($ticket->total_shares) }}</div>
                        <div class="text-xs text-gray-500 mt-1">shares total</div>
                    </div>
                    
                    <div class="stat-card bg-white p-4 rounded-lg shadow-md">
                        <div class="text-gray-600 text-sm mb-1">Available</div>
                        <div class="text-2xl font-bold text-green-600 break-words responsive-number">{{ number_format($ticket->available_shares) }}</div>
                        <div class="text-xs text-gray-500 mt-1">shares left</div>
                    </div>
                    
                    <div class="stat-card bg-white p-4 rounded-lg shadow-md">
                        <div class="text-gray-600 text-sm mb-1">Total Value</div>
                        <div class="text-2xl font-bold text-gray-900 break-words responsive-number">${{ number_format($ticket->price, 2) }}</div>
                        <div class="text-xs text-gray-500 mt-1">property value</div>
                    </div>
                </div>

                <!-- Ownership Progress Bar -->
                <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-gray-900">Ownership Progress</h3>
                        <span class="text-sm font-medium text-purple-600">
                            {{ number_format((($ticket->total_shares - $ticket->available_shares) / $ticket->total_shares) * 100, 1) }}% Sold
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-700 h-4 rounded-full transition-all duration-500" 
                             style="width: {{ (($ticket->total_shares - $ticket->available_shares) / $ticket->total_shares) * 100 }}%">
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 text-sm text-gray-600">
                        <span>{{ number_format($ticket->total_shares - $ticket->available_shares) }} shares sold</span>
                        <span>{{ number_format($ticket->available_shares) }} shares remaining</span>
                    </div>
                </div>

                <!-- Tabs Section -->
                <div class="bg-white rounded-lg shadow-md mb-8">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px">
                            <button class="tab-btn active px-6 py-4 text-sm font-medium border-b-2" data-tab="overview">
                                <i class="fas fa-info-circle mr-2"></i>Overview
                            </button>
                            <button class="tab-btn px-6 py-4 text-sm font-medium border-b-2" data-tab="financials">
                                <i class="fas fa-chart-line mr-2"></i>Financials
                            </button>
                            <button class="tab-btn px-6 py-4 text-sm font-medium border-b-2" data-tab="documents">
                                <i class="fas fa-file-alt mr-2"></i>Documents
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        <!-- Overview Tab -->
                        <div class="tab-content active" id="overview">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">About This Property</h2>
                            <div class="markdown-content text-gray-700">
                                {!! nl2br(e($ticket->description)) !!}
                            </div>
                        </div>

                        <!-- Financials Tab -->
                        <div class="tab-content hidden" id="financials">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Financial Details</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-gray-600 text-sm mb-2">Price Per Share</div>
                                    <div class="text-3xl font-bold text-purple-600">${{ number_format($ticket->price_per_share, 2) }}</div>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-gray-600 text-sm mb-2">Total Property Value</div>
                                    <div class="text-3xl font-bold text-gray-900">${{ number_format($ticket->price, 2) }}</div>
                                </div>
                            </div>

                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                    <div>
                                        <h4 class="font-semibold text-blue-900 mb-1">Investment Calculation</h4>
                                        <p class="text-sm text-blue-800">
                                            To own the entire property, you would need to purchase all {{ number_format($ticket->total_shares) }} shares 
                                            at ${{ number_format($ticket->price_per_share, 2) }} each, totaling ${{ number_format($ticket->price, 2) }}.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Share Calculator -->
                            <div class="bg-white border-2 border-purple-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Investment Calculator</h3>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Shares</label>
                                    <input type="number" id="shareCalc" max="{{ $ticket->available_shares }}" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                           oninput="calculateInvestment(); validateShares(this)">
                                    <div id="shareValidationMessage" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-600">Total Investment:</span>
                                        <span class="text-2xl font-bold text-purple-600" id="totalInvestment">
                                            ${{ number_format($ticket->price_per_share, 2) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Ownership Percentage:</span>
                                        <span class="font-semibold text-gray-900" id="ownershipPercent">
                                            {{ number_format((1 / $ticket->total_shares) * 100, 4) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Tab -->
                        <div class="tab-content hidden" id="documents">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Property Documents</h2>
                            <div class="space-y-3">
                                @if(isset($ticket->documents) && is_array($ticket->documents) && count($ticket->documents) > 0)
                                    @foreach($ticket->documents as $document)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                            <div class="flex items-center">
                                                @php
                                                    $extension = strtolower($document['type'] ?? 'file');
                                                    $iconClass = 'fa-file';
                                                    $iconColor = 'text-gray-500';
                                                    
                                                    if (in_array($extension, ['pdf'])) {
                                                        $iconClass = 'fa-file-pdf';
                                                        $iconColor = 'text-red-500';
                                                    } elseif (in_array($extension, ['doc', 'docx'])) {
                                                        $iconClass = 'fa-file-word';
                                                        $iconColor = 'text-blue-500';
                                                    } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                                        $iconClass = 'fa-file-excel';
                                                        $iconColor = 'text-green-500';
                                                    }
                                                @endphp
                                                <i class="fas {{ $iconClass }} {{ $iconColor }} text-2xl mr-4"></i>
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $document['name'] ?? 'Document' }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ strtoupper($document['type'] ?? 'FILE') }} 
                                                        @if(isset($document['size']))
                                                            • {{ number_format($document['size'] / 1024, 2) }} KB
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ asset($document['path']) }}" download class="text-purple-600 hover:text-purple-700">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-12">
                                        <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
                                        <p class="text-gray-500">No documents available for this property yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column - Investment Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-8">
                    
                    <!-- Investment Card Header -->
                    <div class="mb-6">
                        <div class="text-sm text-gray-600 mb-2">Starting Price</div>
                        <div class="text-4xl font-bold text-purple-600 mb-1">
                            ${{ number_format($ticket->price_per_share, 2) }}
                        </div>
                        <div class="text-sm text-gray-500">per share</div>
                    </div>

                    <hr class="my-6">

                    <!-- Investment Stats -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Shares</span>
                            <span class="font-semibold text-gray-900">{{ number_format($ticket->total_shares) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Available Shares</span>
                            <span class="font-semibold text-green-600">{{ number_format($ticket->available_shares) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Property Value</span>
                            <span class="font-semibold text-gray-900">${{ number_format($ticket->price, 2) }}</span>
                        </div>
                    </div>

                    <hr class="my-6">

                    <!-- Investment Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Number of Shares
                        </label>
                        <input type="number" max="{{ $ticket->available_shares }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-lg"
                               id="investShares" oninput="updateInvestmentCard(); validatePurchaseShares(this)">
                        <div class="mt-2 text-sm text-gray-500">
                            Min: 1 share • Max: {{ number_format($ticket->available_shares) }} shares
                        </div>
                    </div>

                    <!-- Total Investment Display -->
                    <div class="bg-purple-50 rounded-lg p-4 mb-6">
                        <div class="text-sm text-gray-600 mb-1">Your Investment</div>
                        <div class="text-3xl font-bold text-purple-600" id="cardTotalInvestment">
                            ${{ number_format($ticket->price_per_share, 2) }}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                        <form action="{{ route('tickets') }}" method="POST" id="buySharesForm">
                            @csrf
                            <input type="hidden" name="ticket[{{ $ticket->id }}][id]" value="{{ $ticket->id }}">
                            <input type="hidden" name="ticket[{{ $ticket->id }}][quantity]" id="formQuantity" value="1">
                            
                            <button type="submit" class="investment-btn w-full text-white py-4 rounded-lg font-semibold text-lg mb-3" id="buySharesButton">
                                <i class="fas fa-shopping-cart mr-2"></i>Buy Shares
                            </button>
                        </form>                    <hr class="my-6">

                    <!-- Additional Info -->
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Secure blockchain-based ownership</span>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-chart-line text-blue-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Track your investment in real-time</span>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-users text-purple-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Join {{ number_format($ticket->total_shares - $ticket->available_shares) }} other investors</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Tab Switching
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                const tabName = button.getAttribute('data-tab');
                
                // Remove active class from all tabs and buttons
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active', 'border-purple-600', 'text-purple-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                    content.classList.remove('active');
                });
                
                // Add active class to clicked tab and content
                button.classList.add('active', 'border-purple-600', 'text-purple-600');
                button.classList.remove('border-transparent', 'text-gray-500');
                document.getElementById(tabName).classList.remove('hidden');
                document.getElementById(tabName).classList.add('active');
            });
        });

        // Set initial active state
        document.querySelector('.tab-btn.active').classList.add('border-purple-600', 'text-purple-600');
        document.querySelectorAll('.tab-btn:not(.active)').forEach(btn => {
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        // Image Gallery
        function changeImage(src, element) {
            // Update main image
            document.getElementById('mainImage').src = src;
            
            // Remove border from all thumbnails
            document.querySelectorAll('.thumbnail-item').forEach(item => {
                item.classList.remove('border-purple-500');
                item.classList.add('border-transparent');
            });
            
            // Add border to clicked thumbnail
            if (element) {
                element.classList.remove('border-transparent');
                element.classList.add('border-purple-500');
            }
        }

        function toggleGallery() {
            // Scroll to thumbnail gallery
            const gallery = document.getElementById('thumbnailGallery');
            if (gallery) {
                gallery.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        // Investment Calculator
        const pricePerShare = {{ $ticket->price_per_share }};
        const totalShares = {{ $ticket->total_shares }};

        function calculateInvestment() {
            const shares = document.getElementById('shareCalc').value || 1;
            const total = shares * pricePerShare;
            const ownership = (shares / totalShares) * 100;
            
            document.getElementById('totalInvestment').textContent = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('ownershipPercent').textContent = ownership.toFixed(4) + '%';
        }

        function updateInvestmentCard() {
            const shares = document.getElementById('investShares').value || 1;
            const total = shares * pricePerShare;
            
            document.getElementById('cardTotalInvestment').textContent = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            // Update the hidden form quantity field
            document.getElementById('formQuantity').value = shares;
            
            // Also update the calculator if it exists
            if (document.getElementById('shareCalc')) {
                document.getElementById('shareCalc').value = shares;
                calculateInvestment();
            }
        }

        // Initialize
        calculateInvestment();
        updateInvestmentCard();
        
        // Share validation functions
        const availableShares = {{ $ticket->available_shares }};
        
        function validateShares(input) {
            const quantity = parseInt(input.value) || 0;
            const messageDiv = document.getElementById('shareValidationMessage');
            
            if (quantity > availableShares) {
                messageDiv.textContent = `Maximum ${availableShares} shares available`;
                messageDiv.style.display = 'block';
                input.style.borderColor = 'red';
                input.value = availableShares;
            } else if (quantity < 1) {
                messageDiv.textContent = 'Minimum 1 share required';
                messageDiv.style.display = 'block';
                input.style.borderColor = 'red';
                input.value = 1;
            } else {
                messageDiv.style.display = 'none';
                input.style.borderColor = '#d1d5db';
            }
            
            calculateInvestment();
        }
        
        function validatePurchaseShares(input) {
            const quantity = parseInt(input.value) || 0;
            const buyButton = document.getElementById('buySharesButton');
            const formQuantity = document.getElementById('formQuantity');
            
            if (quantity > availableShares) {
                alert(`Only ${availableShares} shares available for purchase`);
                input.value = availableShares;
            } else if (quantity < 1) {
                input.value = 1;
            }
            
            // Update form quantity
            if (formQuantity) {
                formQuantity.value = input.value;
            }
            
            // Disable buy button if no shares available
            if (availableShares <= 0) {
                buyButton.disabled = true;
                buyButton.innerHTML = '<i class="fas fa-ban mr-2"></i>Sold Out';
                buyButton.style.backgroundColor = '#6c757d';
                buyButton.style.cursor = 'not-allowed';
            } else {
                buyButton.disabled = false;
                buyButton.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i>Buy Shares';
                buyButton.style.backgroundColor = '';
                buyButton.style.cursor = '';
            }
            
            updateInvestmentCard();
        }
        
        // Form submission validation
        document.getElementById('buySharesForm').addEventListener('submit', function(e) {
            const quantity = parseInt(document.getElementById('formQuantity').value) || 0;
            
            if (quantity > availableShares) {
                e.preventDefault();
                alert(`Cannot purchase ${quantity} shares. Only ${availableShares} shares available.`);
                return false;
            }
            
            if (quantity < 1) {
                e.preventDefault();
                alert('Must purchase at least 1 share.');
                return false;
            }
            
            if (availableShares <= 0) {
                e.preventDefault();
                alert('This property is sold out.');
                return false;
            }
        });
        
        // Initialize validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const investShares = document.getElementById('investShares');
            if (investShares) {
                validatePurchaseShares(investShares);
            }
        });
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mobile Menu Auto-Close Fix -->
    <script>
        $('.nav-link').on('click', function(){
            $('.navbar-toggler').addClass('collapsed');
            $('#navbarNav').removeClass('show');
        })
    </script>

    <!-- Footer -->
    @if ($footer && $footer->status == 1)
        @include('layouts.new-footer')
    @endif

</body>
</html>
