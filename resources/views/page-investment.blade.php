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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $data->name ?? 'Page' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{background:#f9fafb;}</style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    @if ($data->website->type == 'investment')
        <style>
            .navbar-expand-xl{
                padding-top: 0px !important;
                padding-bottom: 0px !important;
            }
        </style>
    @endif
    <style>
    /* COMPREHENSIVE FRONTEND FIXES */
    
    /* Global full-width support */
    html, body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
        width: 100%;
    }
    
    /* Main container adjustments for full-width support */
    #rendered-page {
        width: 100%;
        overflow-x: hidden;
    }
    .ticket-mask {
        --mask: conic-gradient(from 45deg at left,#0000,#000 1deg 89deg,#0000 90deg) left/51% 16.00px repeat-y,conic-gradient(from -135deg at right,#0000,#000 1deg 89deg,#0000 90deg) 100% calc(50% + 8px)/51% 16.00px repeat-y;
        -webkit-mask: var(--mask);
        mask: var(--mask);
        padding: 1.5rem;
        background-color: #eee;
        border: unset;
    }
    
    /* Full-width section support - Enhanced */
    .inner-section-fullwidth {
        width: 100vw !important;
        position: relative;
        left: 50% !important;
        transform: translateX(-50%) !important;
        box-sizing: border-box !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    
    /* Enable borders and padding for inner-sections */
    .inner-section-fullwidth,
    .inner-section-frontend {
        border: inherit !important;
        padding: inherit !important;
        margin: inherit !important;
    }
    
    /* Force apply styles that might be ignored */
    .inner-section-fullwidth[style],
    .inner-section-frontend[style] {
        border: inherit !important;
        padding: inherit !important;
        margin: inherit !important;
        background: inherit !important;
        background-color: inherit !important;
        background-image: inherit !important;
        background-attachment: inherit !important;
    }
    
    /* Parallax background fix - Enhanced implementation with higher specificity */
    .inner-section-fullwidth[style*="background-attachment: fixed"],
    .inner-section-frontend[style*="background-attachment: fixed"] {
        background-attachment: fixed !important;
        background-repeat: no-repeat !important;
        background-position: center center !important;
        background-size: cover !important;
    }
    
    /* Force parallax for any background image in full-width sections */
    .inner-section-fullwidth[style*="background"][style*="url"],
    .inner-section-frontend[style*="background"][style*="url"] {
        background-attachment: fixed !important;
        background-repeat: no-repeat !important;
        background-position: center center !important;
        background-size: cover !important;
    }
    
    /* CRITICAL: Target all background images with multi-value background-attachment syntax */
    .inner-section-fullwidth[style*="background-attachment: scroll,fixed"],
    .inner-section-frontend[style*="background-attachment: scroll,fixed"],
    .inner-section-fullwidth[style*="scroll,fixed"],
    .inner-section-frontend[style*="scroll,fixed"] {
        background-attachment: scroll, fixed !important;
        background-repeat: no-repeat, no-repeat !important;
        background-position: 0 0, center center !important;
        background-size: auto, cover !important;
    }
    
    /* Ensure parallax works by overriding transform conflicts */
    .inner-section-fullwidth[style*="background-attachment: fixed"] {
        transform: translateX(-50%) !important;
        will-change: transform !important;
    }
    
    /* Direct ID targeting with multi-value support */
    div[id].inner-section-fullwidth[style*="background-image"][style*="scroll,fixed"],
    div[id].inner-section-frontend[style*="background-image"][style*="scroll,fixed"] {
        background-attachment: scroll, fixed !important;
        background-position: 0 0, center center !important;
        background-size: auto, cover !important;
        background-repeat: no-repeat, no-repeat !important;
    }
    
    /* Additional parallax support for webkit browsers */
    @supports (-webkit-appearance: none) {
        .inner-section-fullwidth[style*="background-attachment: fixed"],
        .inner-section-frontend[style*="background-attachment: fixed"],
        .inner-section-fullwidth[style*="scroll,fixed"],
        .inner-section-frontend[style*="scroll,fixed"] {
            -webkit-background-attachment: fixed !important;
        }
    }
    
    /* Direct ID targeting for parallax - most specific */
    div[id][style*="background-image"][style*="background-attachment: fixed"],
    div[id][style*="background-image"][style*="scroll,fixed"] {
        background-attachment: fixed !important;
        background-position: center center !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
    }
    
    /* Universal parallax fallback - highest specificity with multi-value support */
    html body main div[style*="background-attachment: fixed"],
    html body main div[style*="scroll,fixed"],
    html body div[style*="background-attachment: fixed"],
    html body div[style*="scroll,fixed"],
    html body [style*="background-attachment: fixed"],
    html body [style*="scroll,fixed"] {
        background-attachment: fixed !important;
        background-position: center center !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
    }
    
    /* Specific class targeting with multi-value support */
    body .inner-section-fullwidth[style*="background-attachment: fixed"],
    body .inner-section-frontend[style*="background-attachment: fixed"],
    body .inner-section-fullwidth[style*="scroll,fixed"],
    body .inner-section-frontend[style*="scroll,fixed"] {
        background-attachment: fixed !important;
        background-position: center center !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
    }
    
    /* Parallax element class added by JavaScript */
    .parallax-element {
        background-attachment: fixed !important;
        background-position: center center !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
    }
    
    /* Force parallax with highest specificity possible - enhanced with multi-value */
    html body main #rendered-page div[style*="background-attachment"],
    html body main #rendered-page div[style*="scroll,fixed"],
    html body main #rendered-page [style*="background-attachment"],
    html body main #rendered-page [style*="scroll,fixed"] {
        background-attachment: fixed !important;
        background-position: center center !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
    }
    
    /* ULTIMATE fallback - target inline styles directly by attribute */
    [style*="linear-gradient"][style*="url"][style*="scroll,fixed"] {
        background-attachment: scroll, fixed !important;
        background-position: 0 0, center center !important;
        background-size: auto, cover !important;
        background-repeat: no-repeat, no-repeat !important;
    }
    
    /* Video component comprehensive responsive fixes */
    .video-component,
    .video-container {
        width: 100% !important;
        max-width: 100% !important;
        position: relative !important;
        overflow: hidden !important;
    }
    
    .video-component iframe,
    .video-component video,
    .video-container iframe,
    .video-container video {
        width: 100% !important;
        height: auto !important;
        max-width: 100% !important;
        display: block !important;
    }
    
    /* Force responsive behavior for videos with custom dimensions */
    .video-container[style*="width"],
    .video-container[style*="height"] {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .video-container[style*="width"] iframe,
    .video-container[style*="width"] video,
    .video-container[style*="height"] iframe,
    .video-container[style*="height"] video {
        width: 100% !important;
        height: auto !important;
        max-width: 100% !important;
        aspect-ratio: 16/9 !important;
    }
    
    /* Investment tier auto-amount fix for full-width sections */
    .inner-section-fullwidth .investment-tier a[href*="/invest?amount"],
    .inner-section-frontend .investment-tier a[href*="/invest?amount"] {
        pointer-events: auto !important;
        display: inline-block !important;
        position: relative !important;
        z-index: 10 !important;
    }
    
    /* Mobile specific fixes */
    @media (max-width: 768px) {
        /* Full-width sections on mobile */
        .inner-section-fullwidth {
            width: 100vw !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            margin-left: calc(-50vw + 50%) !important;
            margin-right: calc(-50vw + 50%) !important;
            max-width: none !important;
        }
        
        /* Force parallax to scroll on mobile */
        .inner-section-fullwidth[style*="background-attachment"],
        .inner-section-frontend[style*="background-attachment"] {
            background-attachment: scroll !important;
        }
        
        /* Video responsiveness on mobile */
        .video-component,
        .video-container {
            width: 100% !important;
            height: auto !important;
        }
        
        .video-component iframe,
        .video-component video,
        .video-container iframe,
        .video-container video {
            width: 100% !important;
            height: auto !important;
            min-height: 200px !important;
            max-height: 300px !important;
            aspect-ratio: 16/9 !important;
        }
        
        /* Force remove custom dimensions on mobile */
        .video-container[style] {
            width: 100% !important;
            height: auto !important;
            padding-bottom: 56.25% !important;
            position: relative !important;
        }
        
        .video-container[style] iframe,
        .video-container[style] video {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }

    }
    
    @media (max-width: 480px) {
        .video-component iframe,
        .video-component video,
        .video-container iframe,
        .video-container video {
            min-height: 180px !important;
            max-height: 220px !important;
        }
        
        /* Ensure very small screens get full-width treatment */
        .inner-section-fullwidth {
            width: 100vw !important;
            left: 0 !important;
            transform: none !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        footer{
                margin-bottom: 100px !important;
            }

        .close-on-mobile{
            display: none;
        }
    }
    
    /* Base Component Styles */
    #studentTable, #donorTable {
        background-color: #fff !important;
        border: none !important;
    }

    #studentTable th, #studentTable td,
    #donorTable th, #donorTable td {
        background-color: #fff !important;
        border: none !important;
    }

    #studentTable tbody tr,
    #donorTable tbody tr {
        background-color: #fff !important;
    }

    #studentTable_filter, #studentTable_length,
    #donorTable_filter, #donorTable_length {
        display: none;
    }

    #studentTable thead,
    #donorTable thead {
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

    /* Event Countdown Component Responsive Styles */
    .event-countdown .d-flex {
        gap: 0.5rem;
    }

    .event-countdown .counters {
        min-width: 80px;
        margin: 0.25rem !important;
    }

    @media (max-width: 768px) {
        .event-countdown .d-flex {
            gap: 0.25rem;
        }
        
        .event-countdown .counters {
            min-width: 60px;
            margin: 0.125rem !important;
        }
        
        .event-countdown .display-4 {
            font-size: 1.75rem !important;
        }
        
        .event-countdown p {
            font-size: 0.75rem !important;
            margin-bottom: 0.25rem !important;
        }
    }

    @media (max-width: 480px) {
        .event-countdown .d-flex {
            gap: 0.125rem;
        }
        
        .event-countdown .counters {
            min-width: 50px;
            margin: 0.0625rem !important;
        }
        
        .event-countdown .display-4 {
            font-size: 1.5rem !important;
        }
        
        .event-countdown p {
            font-size: 0.625rem !important;
        }
    }

    /* Auction Components Styles - from page-new.blade.php */
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

    /* Auction Component Styles - Container Responsive */
    .auction-component-container {
        width: 100%;
        background-color: transparent;
    }

    .auction-main-wrapper {
        width: 100%;
    }

    .auction-display-container {
        width: 100%;
    }

    .auction-items-wrapper {
        width: 100%;
    }

    .auction-items-grid {
        display: grid;
        gap: 20px;
        width: 100%;
        /* Default: 3 columns for full-width containers */
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }

    /* Container-aware responsive behavior */
    @media (min-width: 1200px) {
        .auction-items-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }
    }

    @media (max-width: 1199px) {
        .auction-items-grid {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        }
    }

    @media (max-width: 991px) {
        .auction-items-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
    }

    @media (max-width: 767px) {
        .auction-items-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }

    /* For when auction is in small containers (col-md-6, col-md-4, etc.) */
    @container (max-width: 600px) {
        .auction-items-grid {
            grid-template-columns: 1fr !important;
        }
    }

    @container (max-width: 900px) {
        .auction-items-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
        }
    }

    .auction-item-wrapper {
        width: 100%;
        min-width: 0; /* Prevents overflow */
    }

    .auction-card {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .c-node-ai {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .c-node-ai:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .c-node-ai__content {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .c-node-ai__image {
        position: relative;
        overflow: hidden;
        height: 200px;
        flex-shrink: 0;
    }

    .c-node-ai__image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .c-node-ai__details-wrap {
        padding: 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .c-node-ai__title {
        margin: 0 0 12px 0;
        font-size: 1.1rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .c-node-ai__title a {
        text-decoration: none;
        color: #333;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .c-node-ai__bidding-details {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    .auction-details-layout {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .auction-timer-section {
        flex: 1;
        min-width: 120px;
    }

    .auction-price-section {
        flex: 0 0 auto;
        min-width: 100px;
    }

    @media (max-width: 480px) {
        .auction-details-layout {
            flex-direction: column;
            gap: 8px;
        }
        
        .auction-timer-section,
        .auction-price-section {
            flex: none;
            min-width: auto;
        }
    }

    .c-timer {
        background: #f8f9fa;
        border-radius: 4px;
        padding: 8px;
        text-align: center;
        width: 100%;
    }

    .c-timer__title {
        font-size: 0.75rem;
        color: #666;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    .c-timer__element {
        display: inline-block;
        margin: 0 2px;
    }

    .c-timer__value {
        font-weight: bold;
        color: #333;
        font-size: 0.9rem;
    }

    .c-timer__period {
        font-size: 0.7rem;
        color: #666;
    }

    .c-price {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px;
        text-align: center;
        width: 100%;
    }

    .c-price__title {
        font-size: 0.75rem;
        color: #666;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    .c-price__value {
        font-weight: bold;
        font-size: 1rem;
        color: #2c3e50;
    }

    /* Mobile-specific adjustments for auction components */
    @media (max-width: 576px) {
        .c-node-ai__image {
            height: 160px;
        }
        
        .c-node-ai__details-wrap {
            padding: 12px;
        }
        
        .c-node-ai__title {
            font-size: 1rem;
        }
        
        .c-timer__element {
            margin: 0 1px;
        }
        
        .c-timer__value {
            font-size: 0.8rem;
        }
        
        .c-timer__period {
            font-size: 0.65rem;
        }
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

    nav{
        box-shadow: unset;
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

    .owl-dots{
        display: none !important;
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
        font-weight: 400;
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
    }
    
    /* Adjust main content margin when investor exclusives bar is present */
    @media (max-width: 768px) {
        main.with-investor-bar {
            margin-top: 8.5rem !important;
        }
    }
    
    @media (max-width: 480px) {
        main.with-investor-bar {
            margin-top: 8rem !important;
        }
    }
    </style>
</head>
<body style="background-color: {{ $data->background_color ?? '#fff'}}; margin: 0; padding: 0;">
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

        @if ($data->is_main_site == 1)
            @include('layouts.main_header')
        @else
            @include('layouts.nav')
        @endif
        
        
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
    
    <main style="margin-top: var(--main-content-margin-top, {{ 
        ($header && $header->show_contact_topbar && $check && $check->isInvestment() && $header->show_investor_exclusives) ? '14.2rem' : 
        (($header && $header->show_contact_topbar) ? '10.5rem' : 
        (($check && $check->isInvestment() && $header && $header->show_investor_exclusives) ? '10.6rem' : '6.9rem'))
    }});" 
          class="{{ 
            ($header && $header->show_contact_topbar && $check && $check->isInvestment() && $header && $header->show_investor_exclusives) ? 'with-contact-and-investor-bars' : 
            (($header && $header->show_contact_topbar) ? 'with-contact-bar' : 
            (($check && $check->isInvestment() && $header && $header->show_investor_exclusives) ? 'with-investor-bar' : ''))
        }}">
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
        <div id="rendered-page">
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
                @if($componentType === 'inner-section')
                    {{-- This is an actual inner-section component - use render-component for full functionality --}}
                    @include('page-components.render-component', [
                        'component' => $component, 
                        'componentId' => $componentId,
                        'isNested' => false
                    ])
                @else
                    {{-- This is an auto-wrapped component (1-column transparent inner-section) --}}
                    <div class="auto-wrapped-component" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
                        @include('page-components.render-component', [
                            'component' => $component, 
                            'componentId' => "auto-wrapped-{$index}",
                            'isNested' => false
                        ])
                    </div>
                @endif
            @endforeach
        </div>
        
        {{-- Demo Statistics Metric Component --}}
        {{-- <div style="margin: 4rem 0; padding: 2rem 0;">
            @include('page-components.render-component', [
                'component' => [
                    'type' => 'statistics-metric',
                    'statisticsData' => [
                        'metric' => '3X',
                        'description' => 'More lithium extracted than conventional methods',
                        'metricColor' => '#14B8A6',
                        'descriptionColor' => '#FFFFFF',
                        'backgroundColor' => '#1F2937',
                        'backgroundType' => 'color',
                        'borderRadius' => '12px',
                        'padding' => '3rem 2rem',
                        'textAlign' => 'center',
                        'metricFontSize' => '4rem',
                        'descriptionFontSize' => '1.25rem',
                        'maxWidth' => '400px'
                    ],
                    'style' => []
                ], 
                'componentId' => 'demo-statistics-metric',
                'isNested' => false
            ])
        </div> --}}
    </main>

@if ($data->is_main_site == 1)
    @include('layouts.main_footer')
    
@else
    {{-- Use the new dynamic footer for all website types --}}
    @if ($footer && $footer->status == 1)
        @include('layouts.new-footer')
    @endif
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

        // Auction Timer Functionality - from page-new.blade.php
        function initializeAuctionTimers() {
            // Find all auction timers on the page
            const timers = document.querySelectorAll('[id^="auction-timer-"]');
            
            timers.forEach(timer => {
                const timerData = timer.querySelector('.js-timer');
                if (!timerData) return;
                
                const deadline = timerData.getAttribute('data-deadline');
                const itemId = timer.id.replace('auction-timer-', '');
                
                if (!deadline) return;
                
                const deadlineTime = new Date(deadline).getTime();
                
                // Update timer every second
                const interval = setInterval(() => {
                    const now = new Date().getTime();
                    const timeLeft = deadlineTime - now;
                    
                    if (timeLeft <= 0) {
                        clearInterval(interval);
                        // Show expired state
                        document.getElementById(`days-${itemId}`).textContent = '0';
                        document.getElementById(`hours-${itemId}`).textContent = '0';
                        document.getElementById(`minutes-${itemId}`).textContent = '0';
                        return;
                    }
                    
                    // Calculate time units
                    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    
                    // Update display
                    document.getElementById(`days-${itemId}`).textContent = days;
                    document.getElementById(`hours-${itemId}`).textContent = hours;
                    document.getElementById(`minutes-${itemId}`).textContent = minutes;
                }, 1000);
            });
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
            
            // Enhanced Parallax Fix - Force CSS and add JavaScript fallback
            initParallaxFix();
            
            // Initialize auction timers - from page-new.blade.php
            initializeAuctionTimers();
            
            // Initialize form data storage for frontend users
            if (typeof initFormDataStorageForFrontend === 'function') {
                initFormDataStorageForFrontend();
            }
        });
        
        // Parallax Fix Function
        function initParallaxFix() {
            // Find all elements with background-attachment: fixed in their style
            const parallaxElements = document.querySelectorAll('[style*="background-attachment: fixed"], [style*="background-attachment:fixed"], [style*="scroll,fixed"]');
            
            parallaxElements.forEach(function(element) {
                // Check if it's multi-value syntax (scroll,fixed) 
                const styleAttr = element.getAttribute('style') || '';
                
                if (styleAttr.includes('scroll,fixed')) {
                    // Keep multi-value syntax for complex backgrounds
                    if (!element.style.backgroundAttachment.includes('scroll,fixed')) {
                        element.style.backgroundAttachment = 'scroll,fixed';
                    }
                    if (!element.style.backgroundPosition.includes('0 0,center center')) {
                        element.style.backgroundPosition = '0 0,center center';
                    }
                    if (!element.style.backgroundSize.includes('auto,cover')) {
                        element.style.backgroundSize = 'auto,cover';
                    }
                    if (!element.style.backgroundRepeat.includes('no-repeat,no-repeat')) {
                        element.style.backgroundRepeat = 'no-repeat,no-repeat';
                    }
                } else {
                    // Single value syntax for simple backgrounds
                    element.style.backgroundAttachment = 'fixed';
                    element.style.backgroundPosition = 'center center';
                    element.style.backgroundSize = 'cover';
                    element.style.backgroundRepeat = 'no-repeat';
                }
                
                // Add a class for easier targeting
                element.classList.add('parallax-element');
                
                // Debug log
                console.log('Parallax element found and fixed:', element, 'Style:', styleAttr);
            });
            
            // Also check after a slight delay in case elements are loaded dynamically
            setTimeout(function() {
                const newParallaxElements = document.querySelectorAll('[style*="background-attachment"][style*="background-image"]:not(.parallax-element), [style*="scroll,fixed"]:not(.parallax-element)');
                newParallaxElements.forEach(function(element) {
                    const styleAttr = element.getAttribute('style') || '';
                    
                    if (styleAttr.includes('scroll,fixed')) {
                        element.style.backgroundAttachment = 'scroll,fixed';
                        element.style.backgroundPosition = '0 0,center center';
                        element.style.backgroundSize = 'auto,cover';
                        element.style.backgroundRepeat = 'no-repeat,no-repeat';
                    } else {
                        element.style.backgroundAttachment = 'fixed';
                        element.style.backgroundPosition = 'center center';
                        element.style.backgroundSize = 'cover';
                        element.style.backgroundRepeat = 'no-repeat';
                    }
                    
                    element.classList.add('parallax-element');
                    console.log('Delayed parallax element fixed:', element, 'Style:', styleAttr);
                });
            }, 100);
            
            // Additional check for elements with the specific gradient+image pattern
            setTimeout(function() {
                const gradientElements = document.querySelectorAll('[style*="linear-gradient"][style*="url"]');
                gradientElements.forEach(function(element) {
                    if (!element.classList.contains('parallax-element')) {
                        const styleAttr = element.getAttribute('style') || '';
                        console.log('Found gradient+image element:', element, 'Style:', styleAttr);
                        
                        if (styleAttr.includes('scroll,fixed') || styleAttr.includes('background-attachment')) {
                            // Force the parallax styles
                            element.style.backgroundAttachment = 'scroll,fixed';
                            element.style.backgroundPosition = '0 0,center center';
                            element.style.backgroundSize = 'auto,cover';
                            element.style.backgroundRepeat = 'no-repeat,no-repeat';
                            element.classList.add('parallax-element');
                            console.log('Applied parallax fix to gradient element:', element);
                        }
                    }
                });
            }, 200);
        }
        
        // Form Data Storage for Frontend Users
        function initFormDataStorageForFrontend() {
            // Initialize form data storage system for frontend
            if (!window.formDataStorage) {
                window.formDataStorage = {
                    data: {},
                    
                    storeFormData: function(formId, fieldName, value) {
                        if (!this.data[formId]) {
                            this.data[formId] = {};
                        }
                        this.data[formId][fieldName] = value;
                        this.saveToStorage();
                    },
                    
                    getFormData: function(formId) {
                        return this.data[formId] || {};
                    },
                    
                    getAllData: function() {
                        return this.data;
                    },
                    
                    clearFormData: function(formId) {
                        if (formId) {
                            delete this.data[formId];
                        } else {
                            this.data = {};
                        }
                        this.saveToStorage();
                    },
                    
                    saveToStorage: function() {
                        try {
                            localStorage.setItem('investmentFormData', JSON.stringify(this.data));
                        } catch (e) {
                            console.warn('Could not save form data to localStorage:', e);
                        }
                    },
                    
                    loadFromStorage: function() {
                        try {
                            const stored = localStorage.getItem('investmentFormData');
                            if (stored) {
                                this.data = JSON.parse(stored);
                            }
                        } catch (e) {
                            console.warn('Could not load form data from localStorage:', e);
                            this.data = {};
                        }
                    }
                };
                
                // Load existing data
                window.formDataStorage.loadFromStorage();
            }
            
            // Capture form inputs
            document.addEventListener('input', function(e) {
                if (e.target.matches('input, select, textarea')) {
                    const form = e.target.closest('form');
                    if (form && !form.classList.contains('no-store')) {
                        const formId = form.id || form.className || 'default_form';
                        const fieldName = e.target.name || e.target.id || `field_${Date.now()}`;
                        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value;
                        
                        window.formDataStorage.storeFormData(formId, fieldName, value);
                        
                        // Show subtle save indicator
                        showDataSavedIndicator();
                    }
                }
            });
            
            document.addEventListener('change', function(e) {
                if (e.target.matches('input[type="radio"], input[type="checkbox"], select')) {
                    const form = e.target.closest('form');
                    if (form && !form.classList.contains('no-store')) {
                        const formId = form.id || form.className || 'default_form';
                        const fieldName = e.target.name || e.target.id || `field_${Date.now()}`;
                        const value = e.target.type === 'checkbox' ? e.target.checked : e.target.value;
                        
                        window.formDataStorage.storeFormData(formId, fieldName, value);
                        showDataSavedIndicator();
                    }
                }
            });
        }
        
        function showDataSavedIndicator() {
            // Remove existing indicator
            const existing = document.querySelector('.data-saved-indicator');
            if (existing) existing.remove();
            
            // Create new indicator
            const indicator = document.createElement('div');
            indicator.className = 'data-saved-indicator';
            indicator.innerHTML = '<i class="fas fa-check"></i> Saved';
            indicator.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: rgba(40, 167, 69, 0.9);
                color: white;
                padding: 8px 12px;
                border-radius: 20px;
                font-size: 12px;
                z-index: 10000;
                opacity: 0;
                transition: opacity 0.3s ease;
                display: flex;
                align-items: center;
                gap: 5px;
            `;
            
            document.body.appendChild(indicator);
            
            // Animate in and out
            setTimeout(() => indicator.style.opacity = '1', 10);
            setTimeout(() => {
                indicator.style.opacity = '0';
                setTimeout(() => indicator.remove(), 300);
            }, 1500);
        }
    </script>

    <!-- Sticky Bottom Investment CTA - Mobile Only (Investment Websites Only) -->
    {{-- @php
        dd($check);
    @endphp --}}
    @if($check && $check->isInvestment())
    <div id="sticky-investment-cta" class="d-block d-md-none" style="background-color: {{ $check->sticky_footer_bg_color }} !important;">
        <div class="sticky-cta-content">
            @if ($check->custom_sticky_button_text != null)
                <div class="share-price-section">
                    <div class="price-value" style="color: {{ $check->sticky_footer_text_color }}">{{ $check->custom_sticky_button_text}}</div>
                </div>
            @else
                <div class="share-price-section">
                    <div class="price-value" style="color: {{ $check->sticky_footer_text_color }}">${{ $check->share_price ?? '0.00' }}</div>
                    <div class="price-label" style="color: {{ $check->sticky_footer_text_color }}">Share Price</div>
                </div>
            @endif
            <div class="invest-button-section">
                <button class="invest-now-btn sssssttttt" onclick="window.location.href='/invest'" style="background-color: {{ $check->sticky_footer_button_bg }} !important; color: {{ $check->sticky_footer_button_text }} !important;">
                    {{ $header->invest_now_button_text ?? 'INVEST NOW' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    <style>
        /* Sticky Bottom Investment CTA Styles */
        #sticky-investment-cta {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: #000000;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            /* border-top: 1px solid #e0e0e0; */
        }

        .sticky-cta-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            max-width: 100%;
        }

        .share-price-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
            padding-left: 14%;

        }

        .price-value {
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
        }

        .price-label {
            color: #ffffff;
            font-size: 12px;
            font-weight: 400;
            line-height: 1.2;
            margin: 0;
            opacity: 0.8;
        }

        .invest-button-section {
            flex-shrink: 0;
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

        .sssssttttt{
            padding: 1.25rem 2.7rem !important;
            border-radius: 0px !important;
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

        strong {
            font-weight: bold;
        }

        /* Add bottom padding to body to prevent content overlap - Investment websites only */
        @if($check && $check->isInvestment())
        body {
            padding-bottom: 70px;
        }
        @endif

        /* Remove bottom padding on desktop */
        @media (min-width: 768px) {
            body {
                padding-bottom: 0;
            }
            
            #sticky-investment-cta {
                display: none !important;
            }

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
    </style>

    <!-- DataTables Initialization Script -->
    <script>
        // Global function to safely initialize DataTables
        function initStudentTable() {
            // Check if table exists
            if ($('#studentTable').length === 0) {
                return;
            }
            
            // Destroy existing DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#studentTable')) {
                $('#studentTable').DataTable().destroy();
            }
            
            // Initialize DataTable
            const table = $('#studentTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 25
            });

            // Link the custom search input to the DataTable search
            $('#search').on('keyup', function() {
                const value = $(this).val();
                table.search(value).draw();
            });
        }

        // Global function to safely initialize Donor DataTables
        function initDonorTable() {
            // Check if table exists
            if ($('#donorTable').length === 0) {
                return;
            }
            
            // Destroy existing DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#donorTable')) {
                $('#donorTable').DataTable().destroy();
            }
            
            // Initialize DataTable
            const table = $('#donorTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 25
            });
        }

        // Global function to reinitialize all DataTables
        function reinitializeAllDataTables() {
            initStudentTable();
            initDonorTable();
        }

        $(document).ready(function() {
            // Initialize all tables
            reinitializeAllDataTables();
            
            // Re-initialize when content changes
            $(document).on('DOMContentLoaded', function() {
                reinitializeAllDataTables();
            });
            
            // Observer for dynamically added content
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length > 0) {
                        // Check if any student or donor tables were added
                        const hasStudentTable = $(mutation.target).find('#studentTable').length > 0;
                        const hasDonorTable = $(mutation.target).find('#donorTable').length > 0;
                        
                        if (hasStudentTable || hasDonorTable) {
                            setTimeout(reinitializeAllDataTables, 100);
                        }
                    }
                });
            });
            
            // Start observing
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    </script>

    <script>
        // Global function to initialize tooltips for donation forms
function initializeDonationFormTooltips() {
    // Find all donation form components and initialize their tooltips
    const donationForms = document.querySelectorAll('.donation-form-block');
    donationForms.forEach(form => {
        const tooltips = form.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                // Dispose existing tooltip if any
                const existingTooltip = bootstrap.Tooltip.getInstance(tooltip);
                if (existingTooltip) {
                    existingTooltip.dispose();
                }
                // Create new tooltip
                new bootstrap.Tooltip(tooltip);
            }
        });
    });
}

setTimeout(() => {
                    initializeDonationFormTooltips();
                }, 100);

// Call initialization when the page is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize existing columns after a short delay to ensure DOM is ready
    setTimeout(() => {
        initializeDonationFormTooltips();
    }, 1000);
});
</script>

    
</body>
</html>
