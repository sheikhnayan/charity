@php
// Get website data based on current domain
$url = url()->current();
$domain = parse_url($url, PHP_URL_HOST);
$check = \App\Models\Website::where('domain', $domain)->first();

if ($check) {
    $user_id = $check->user_id;
    $setting = \App\Models\Setting::where('user_id', $user_id)->first();
    $header = \App\Models\Header::where('user_id', $user_id)->first();
    $footer = \App\Models\Footer::where('user_id', $user_id)->first();
} else {
    $setting = null;
    $header = null;
    $footer = null;
}
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting && $setting->company_name ? $setting->company_name . ' | Investment Checkout' : 'Investment Checkout' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{background:#f9fafb;}</style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Original invest page metadata -->
    <meta content="{{ $setting && $setting->company_name ? 'Invest in ' . $setting->company_name . ' and become part of our growing success story. Secure your shares today through our regulated investment platform.' : 'Secure investment platform offering regulated investment opportunities.' }}" name="description"/>
    <meta content="{{ $setting && $setting->company_name ? $setting->company_name . ' | Investment Checkout' : 'Investment Checkout' }}" property="og:title"/>
    <meta content="{{ $setting && $setting->company_name ? 'Invest in ' . $setting->company_name . ' and become part of our growing success story. Secure your shares today through our regulated investment platform.' : 'Secure investment platform offering regulated investment opportunities.' }}" property="og:description"/>
    <meta content="{{ $setting && $setting->logo ? asset('uploads/' . $setting->logo) : asset('investment/images/default-investment-image.jpg') }}" property="og:image"/>
    <meta content="{{ $setting && $setting->company_name ? $setting->company_name . ' | Investment Checkout' : 'Investment Checkout' }}" property="twitter:title"/>
    <meta content="{{ $setting && $setting->company_name ? 'Invest in ' . $setting->company_name . ' and become part of our growing success story. Secure your shares today through our regulated investment platform.' : 'Secure investment platform offering regulated investment opportunities.' }}" property="twitter:description"/>
    <meta content="{{ $setting && $setting->logo ? asset('uploads/' . $setting->logo) : asset('investment/images/default-investment-image.jpg') }}" property="twitter:image"/>
    <meta property="og:type" content="website"/>
    <meta content="summary_large_image" name="twitter:card"/>
    <meta content="noindex" name="robots"/>
    
    <!-- Investment page specific styles -->
    <link href="{{ asset('investment/css/main.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('investment/css/investment-utilities.css') }}" rel="stylesheet" type="text/css"/>
    <script src="{{ asset('investment/js/webfont-loader.js') }}" type="text/javascript"></script>
    <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
    
    @if($setting && $setting->favicon)
        <link href="{{ asset('uploads/' . $setting->favicon) }}" rel="shortcut icon" type="image/x-icon"/>
        <link href="{{ asset('uploads/' . $setting->favicon) }}" rel="apple-touch-icon"/>
    @endif
    <link href="{{ url()->current() }}" rel="canonical"/><!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-W7328S2C');</script>
<!-- End Google Tag Manager -->

<!-- Keep this css code to improve the font quality-->
<style>
  * {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  -o-font-smoothing: antialiased;
}

/* Custom Investment Form Styles */
.investment-form-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 30px;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  color: #ffffff !important;
}

.investment-form-title {
  font-size: 28px;
  font-weight: 700;
  color: #1a1a1a !important;
  text-align: center;
  margin-bottom: 30px;
}

.investment-step {
  transition: all 0.3s ease;
}

.investment-step.hidden {
  display: none;
}

.investment-step h3 {
  font-size: 22px;
  font-weight: 600;
  color: #333 !important;
  margin-bottom: 25px;
  text-align: center;
}

/* Amount Tiers */
.amount-tiers {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 15px;
  margin-bottom: 25px;
}

.tier-option {
  border: 2px solid #e5e5e5;
  border-radius: 8px;
  padding: 20px 15px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  background: #f9f9f9;
  color: #ffffff !important;
}

.tier-option:hover {
  border-color: #007bff;
  background: #f0f8ff;
  color: #ffffff !important;
}

.tier-option.selected {
  border-color: #007bff;
  background: #007bff;
  color: white !important;
}

.tier-amount {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 8px;
}

.tier-shares {
  font-size: 14px;
  opacity: 0.8;
}

.custom-amount-wrapper {
  grid-column: 1 / -1;
  margin-top: 20px;
  padding: 20px;
  border: 2px dashed #ddd;
  border-radius: 8px;
  background: #fafafa;
}

.custom-amount-wrapper label {
  display: block;
  font-weight: 600;
  color: #333 !important;
  margin-bottom: 10px;
}

.dmr-common-stock-2{
    color: #fff !important;
}

.custom-amount-wrapper input {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 16px;
  background: white !important;
  color: #ffffff !important;
}

.custom-shares-display {
  margin-top: 10px;
  font-size: 14px;
  color: #666 !important;
  font-weight: 500;
}

/* Form Styles */
.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-weight: 600;
  color: #333 !important;
  margin-bottom: 8px;
}

.form-group input,
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 16px;
  transition: border-color 0.3s ease;
  background: white !important;
  color: #ffffff !important;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
  background: white !important;
  color: #000 !important;
}

.checkbox-group label {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-weight: normal;
  line-height: 1.5;
  color: #ffffff !important;
}

.checkbox-group input[type="checkbox"] {
  width: auto;
  margin: 0;
  flex-shrink: 0;
}

/* Buttons */
.btn-continue,
.btn-submit,
.btn-back {
  padding: 15px 30px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-continue,
.btn-submit {
  background: #007bff;
  color: white;
  width: 100%;
}

.btn-continue:hover:not(:disabled),
.btn-submit:hover {
  background: #0056b3;
  transform: translateY(-2px);
}

.btn-continue:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.btn-back {
  background: #6c757d;
  color: white;
  margin-right: 15px;
}

.btn-back:hover {
  background: #545b62;
}

.form-actions {
  display: flex;
  align-items: center;
  margin-top: 30px;
}

/* Success Message */
.success-message {
  text-align: center;
  padding: 40px 20px;
}

.success-message h3 {
  color: #28a745 !important;
  font-size: 24px;
  margin-bottom: 15px;
}

.investment-summary {
  background: rgba(248, 249, 250, 0.95);
  border-radius: 8px;
  padding: 20px;
  margin-top: 25px;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #dee2e6;
}

.summary-item:last-child {
  border-bottom: none;
}

.summary-item .label {
  font-weight: 600;
  color: #495057 !important;
}

.summary-item .value {
  font-weight: 700;
  color: #007bff !important;
}

/* Override any inherited white text */
* {
  color: inherit;
}

.investment-form-container *,
.investment-form-wrapper *,
.page-wrapper * {
  color: #000 !important;
}

.investment-form-container input,
.investment-form-container select,
.investment-form-container textarea {
  background: white !important;
  color: #000 !important;
}

.tier-option.selected * {
  color: white !important;
}

/* Responsive */
@media (max-width: 768px) {
  .investment-form-container {
    margin: 20px;
    padding: 20px;
  }
  
  .amount-tiers {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .form-actions {
    flex-direction: column;
    gap: 15px;
  }
  
  .btn-back {
    margin-right: 0;
    width: 100%;
  }
}
</style>

<!-- Google Search Console -->
<meta name="google-site-verification" content="FogpITTz954DYojTNNL7jxXDuO_9x8i4D4ni0Wibtzg" />

<!-- begin Convert Experiences code
{{-- 
NOTE: External script removed - using local assets instead
<script type="text/javascript" src="//cdn-4.convertexperiments.com/js/1004905-100416832.js"></script>
--}}
end Convert Experiences code --><!-- Checkout Security Measure -->
<style>
#step1>div.opacity-100:first-child {
    display: none !important;
}
</style>
</head>
@php
    $url2 = url()->current();
    $domain2 = parse_url($url2, PHP_URL_HOST);
    $website = \App\Models\Website::where('domain', $domain2)->first();
@endphp


<body style="background-color: {{ $website && $website->background_color ? $website->background_color : ($setting && $setting->background_color ? $setting->background_color : '#f9fafb') }}; margin: 0; padding: 0; color: {{ $website && $website->text_color ? $website->text_color : ($setting && $setting->text_color ? $setting->text_color : '#ffffff') }};">
    @php
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $check = \App\Models\Website::where('domain', $domain)->first();
        
        if ($check) {
            $header = \App\Models\Header::where('website_id', $check->id)->first();
            $footer = \App\Models\Footer::where('website_id', $check->id)->first();
        }
        
        // Get dynamic background and text colors
        $pageBackgroundColor = $website->pages[0]->background_color;
        $pageTextColor = $website && $website->text_color ? $website->text_color : ($setting && $setting->text_color ? $setting->text_color : '#ffffff');
    @endphp
    
    @if ($header && $header->status == 1)
        @include('layouts.nav')
    @endif
    
    <main style="margin-top: 6.9rem; background-color: {{ $pageBackgroundColor }};">
        <div class="container-fluid" style="background-color: {{ $pageBackgroundColor }};">
            <div class="row justify-content-center">
                <div class="col-12">
                    <!-- Investment Form Container -->
                    <div class="investment-form-wrapper" style="min-height: calc(100vh - 6.9rem); background-color: {{ $pageBackgroundColor }};">

<div class="page-wrapper" style="background-color: {{ $pageBackgroundColor }}; color: {{ $pageTextColor }};"<div class="global-styles w-embed"><style>

/* Set color style to inherit */
.inherit-color * {
    color: inherit;
}

/* Focus state style for keyboard navigation for the focusable elements */
*[tabindex]:focus-visible,
  input[type="file"]:focus-visible {
   outline: 0.125rem solid #4d65ff;
   outline-offset: 0.125rem;
}

/* Get rid of top margin on first element in any rich text element */
.w-richtext > :not(div):first-child, .w-richtext > div:first-child > :first-child {
  margin-top: 0 !important;
}

/* Get rid of bottom margin on last element in any rich text element */
.w-richtext>:last-child, .w-richtext ol li:last-child, .w-richtext ul li:last-child {
	margin-bottom: 0 !important;
}

/* Prevent all click and hover interaction with an element */
.pointer-events-off {
	pointer-events: none;
}

/* Enables all click and hover interaction with an element */
.pointer-events-on {
  pointer-events: auto;
}

/* Create a class of .div-square which maintains a 1:1 dimension of a div */
.div-square::after {
	content: "";
	display: block;
	padding-bottom: 100%;
}

/* Make sure containers never lose their center alignment */
.container-medium,.container-small, .container-large {
	margin-right: auto !important;
  margin-left: auto !important;
}

/* 
Make the following elements inherit typography styles from the parent and not have hardcoded values. 
Important: You will not be able to style for example "All Links" in Designer with this CSS applied.
Uncomment this CSS to use it in the project. Leave this message for future hand-off.
*/
/*
a,
.w-input,
.w-select,
.w-tab-link,
.w-nav-link,
.w-dropdown-btn,
.w-dropdown-toggle,
.w-dropdown-link {
  color: inherit;
  text-decoration: inherit;
  font-size: inherit;
}
*/

/* Apply "..." after 3 lines of text */
.text-style-3lines {
	display: -webkit-box;
	overflow: hidden;
	-webkit-line-clamp: 3;
	-webkit-box-orient: vertical;
}

/* Apply "..." after 2 lines of text */
.text-style-2lines {
	display: -webkit-box;
	overflow: hidden;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
}

/* Adds inline flex display */
.display-inlineflex {
  display: inline-flex;
}

/* These classes are never overwritten */
.hide {
  display: none !important;
}

@media screen and (max-width: 991px) {
    .hide, .hide-tablet {
        display: none !important;
    }
}
  @media screen and (max-width: 767px) {
    .hide-mobile-landscape{
      display: none !important;
    }
}
  @media screen and (max-width: 479px) {
    .hide-mobile{
      display: none !important;
    }
}

/* NOTE: Utility classes moved to external CSS file to prevent conflicts */
/* See: /investment/css/investment-utilities.css */

/* Apply "..." at 100% width */
.truncate-width { 
		width: 100%; 
    white-space: nowrap; 
    overflow: hidden; 
    text-overflow: ellipsis; 
}
/* Removes native scrollbar */
.no-scrollbar {
    -ms-overflow-style: none;
    overflow: -moz-scrollbars-none; 
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

</style></div>
@if ($header && $header->status == 1)
    @include('layouts.nav')
@endif
<main class="main-wrapper" style="background-color: {{ $pageBackgroundColor }}"><header id="home" class="section_header3 checkout-hero"><div class="padding-global z-index-2"><div class="container-2"><div class="dmr-checkout-wrapper"><div><div class="hero-text-2 _2"><h1 class="heading-style-h1 is-checkout" style="color: #fff !important"><strong style="color: #fff !important">{{ $setting && $setting->company_name ? $setting->company_name : 'Investment' }}</strong> Investment Opportunity<br/></h1><div class="spacer-small"></div><div class="dmr-details-mobile-show"><div class="div-block-132"><div id="w-node-d76ce5db-1098-4f05-302a-51e614ef1974-4eda2ff4" class="div-block-155"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d5-a22a22d5" class="div-block-155"><div class="dmr-common-stock dmr-larger-t text-color-white"><strong style="color: #fff !important">Investment Details</strong></div><div class="div-block-132"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d9-a22a22d5" class="w-layout-layout quick-stack-5 wf-layout-layout"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22da-a22a22d5" class="w-layout-cell"><div class="div-block-67"><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small" style="color: #fff !important">SHARE PRICE</div><div class="dmr-common-stock-2 fixed-height"><strong style="color: #fff !important">${{ $website && $website->share_price ? number_format($website->share_price, 2) : ($setting && $setting->share_price ? number_format($setting->share_price, 2) : '1.00') }} USD</strong></div></div><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small" style="color: #fff !important">MIN INVESTMENT</div><div class="dmr-common-stock-2 fixed-height"><strong style="color: #fff !important">${{ $website && $website->min_investment ? number_format($website->min_investment, 2) : ($setting && $setting->min_investment ? number_format($setting->min_investment, 2) : '1000.00') }} USD</strong></div></div><div class="div-block-46 _3"><a href="#" class="close-2 w-inline-block"><div>X</div></a><div>{{ $website && $website->investment_note ? $website->investment_note : ($setting && $setting->investment_note ? $setting->investment_note : 'Minimum investment amount plus applicable transaction fees') }}</div></div></div></div><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22ee-a22a22d5" class="w-layout-cell"><div class="div-block-67"><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small" style="color: #fff !important">OFFERING TYPE</div><div class="dmr-common-stock-2 fixed-height"><strong style="color: #fff !important">{{ $website && $website->offering_type ? $website->offering_type : ($setting && $setting->offering_type ? $setting->offering_type : 'Equity') }}</strong></div></div><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small" style="color: #fff !important">ASSET TYPE</div><div class="dmr-common-stock-2 fixed-height" style="color: #fff !important"><strong>{{ $website && $website->asset_type ? $website->asset_type : ($setting && $setting->asset_type ? $setting->asset_type : 'Common Stock') }}</strong></div></div></div></div></div></div><div class="div-block-130">@if(($website && $website->investment_documents) || ($setting && $setting->investment_documents))<div class="investor_info_wrap text-size-xsmall link-light">@php $docs = $website && $website->investment_documents ? json_decode($website->investment_documents, true) : json_decode($setting->investment_documents, true); @endphp @if(is_array($docs)) @foreach($docs as $doc) <a aria-label="{{ $doc['name'] ?? 'Investment Document' }}" href="{{ $doc['url'] ?? '#' }}" target="_blank" class="investor_info_link text-link-inherit">{{ $doc['name'] ?? 'Document' }}</a> @endforeach @endif</div>@endif @if(($website && $website->investment_deadline) || ($setting && $setting->investment_deadline))<div class="countdown_checkout_wrapper"><div countdown-wrapper="1" class="countdown_wrapper is_checkout"><div class="countdown_title is_checkout"><strong>{{ $website && $website->deadline_text ? $website->deadline_text : ($setting && $setting->deadline_text ? $setting->deadline_text : 'Investment Deadline') }}</strong></div><div id="js-clock" class="timer_wrap"><div id="w-node-_20a0a116-730f-65a2-befe-802bf563b40a-a22a22d5" class="timer_cell"><div id="days" class="timer_number smaller">0</div><div class="timer_label _3">Days</div></div><div class="timer_cell"><div id="hours" class="timer_number smaller">0</div><div class="timer_label _3">Hours</div></div><div class="timer_cell last-m"><div id="minutes" class="timer_number smaller">0</div><div class="timer_label _3">Minutes</div></div><div class="timer_cell mobil-hide"><div id="seconds" class="timer_number smaller">0</div><div class="timer_label _3">Seconds</div></div></div></div></div>@endif</div></div></div></div></div></div><div class="w-layout-grid virtuix-checkout-grid">
    <!-- Custom Investment Form (replacing DealMaker React component) -->
    <div class="investment-form-container w-node-d76ce5db-1098-4f05-302a-51e614ef19a6-4eda2ff4">
        <div class="investment-form-wrapper">
            <h2 class="investment-form-title" style="color: #000 !important;">Complete Your Investment</h2>
            
            <!-- Investment Amount Selection -->
            <div class="investment-step" id="amount-step">
                <h3>Select Investment Amount</h3>
                <div class="amount-tiers">
                    @php
                        // Handle JSON format for investment tiers
                        $tiersData = null;
                        
                        // Debug: Check what we're working with
                        $investmentTiersRaw = null;
                        
                        if ($website && $website->investment_tiers) {
                            $investmentTiersRaw = $website->investment_tiers;
                            $tiersData = json_decode($website->investment_tiers, true);
                        } elseif ($setting && $setting->investment_tiers) {
                            $investmentTiersRaw = $setting->investment_tiers;
                            $tiersData = json_decode($setting->investment_tiers, true);
                        }
                        
                        // Debug output (remove this in production)
                        echo "<!-- DEBUG: Raw investment_tiers: " . htmlspecialchars($investmentTiersRaw) . " -->";
                        echo "<!-- DEBUG: Decoded tiersData: " . htmlspecialchars(print_r($tiersData, true)) . " -->";

                        
                        // Extract amounts from tier data or use defaults
                        $tiers = [1000, 2500, 5000, 10000]; // Default fallback
                        
                        if ($tiersData && is_array($tiersData)) {
                            $extractedTiers = [];
                            foreach ($tiersData as $tier) {
                                if (is_array($tier)) {
                                    // Handle object format: [{"amount": 1000}, {"amount": 2500}]
                                    if (isset($tier['amount'])) {
                                        $extractedTiers[] = (float)$tier['amount'];
                                    }
                                } elseif (is_numeric($tier)) {
                                    // Handle simple array format: [1000, 2500, 5000]
                                    $extractedTiers[] = (float)$tier;
                                }
                            }
                            if (!empty($extractedTiers)) {
                                $tiers = $extractedTiers;
                            }
                        } elseif ($investmentTiersRaw && is_string($investmentTiersRaw)) {
                            // Handle comma-separated string format: "1000,2500,5000,10000"
                            $stringTiers = explode(',', $investmentTiersRaw);
                            $extractedTiers = [];
                            foreach ($stringTiers as $tier) {
                                $tier = trim($tier);
                                if (is_numeric($tier)) {
                                    $extractedTiers[] = (float)$tier;
                                }
                            }
                            if (!empty($extractedTiers)) {
                                $tiers = $extractedTiers;
                            }
                        }
                        
                        $sharePrice = $website && $website->share_price ? (float)$website->share_price : 
                                     ($setting && $setting->share_price ? (float)$setting->share_price : 1.00);
                        $minInvestment = $website && $website->min_investment ? (float)$website->min_investment : 
                                        ($setting && $setting->min_investment ? (float)$setting->min_investment : 1000);
                    @endphp
                    
                    @foreach($tiers as $tier)
                        <div class="tier-option" data-amount="{{ $tier }}">
                            <div class="tier-amount">${{ $tier }}</div>
                            <div class="tier-shares">{{ number_format($tier / $sharePrice) }} shares</div>
                        </div>
                    @endforeach
                    
                    <div class="custom-amount-wrapper">
                        <label for="custom-amount">Custom Amount (Min: ${{ $minInvestment }})</label>
                        <input type="number" id="custom-amount" min="{{ $minInvestment }}" step="1" placeholder="Enter amount">
                        <div class="custom-shares-display"></div>
                    </div>
                </div>
                
                <button class="btn-continue" id="amount-continue" disabled>Continue</button>
            </div>
            
            <!-- Investor Information Step -->
            <div class="investment-step hidden" id="info-step">
                <h3>Investor Information</h3>
                <form id="investor-form">
                    <div class="form-group">
                        <label for="investor_type">Pick an investor type *</label>
                        <select id="investor_type" name="investor_type" required>
                            <option value="">Select investor type</option>
                            <option value="individual">Myself/an individual</option>
                            <option value="joint">Joint (more than one individual)</option>
                            <option value="corporation">Corporation</option>
                            <option value="trust">Trust</option>
                            <option value="ira">IRA</option>
                        </select>
                    </div>
                    
                    <!-- Individual Investor Fields -->
                    <div id="individual-fields" class="investor-type-fields" style="display: none;">
                        <div class="form-group">
                            <label for="investor_name">Full Name *</label>
                            <input type="text" id="investor_name" name="investor_name">
                        </div>
                        
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth *</label>
                            <input type="date" id="date_of_birth" name="date_of_birth">
                        </div>
                        
                        <div class="form-group">
                            <label for="ssn">Social Security Number *</label>
                            <input type="text" id="ssn" name="ssn" placeholder="XXX-XX-XXXX">
                        </div>
                    </div>
                    
                    <!-- Joint Account Fields -->
                    <div id="joint-fields" class="investor-type-fields" style="display: none;">
                        <div class="form-group">
                            <label for="primary_name">Primary Account Holder Name *</label>
                            <input type="text" id="primary_name" name="primary_name">
                        </div>
                        
                        <div class="form-group">
                            <label for="primary_dob">Primary Holder Date of Birth *</label>
                            <input type="date" id="primary_dob" name="primary_dob">
                        </div>
                        
                        <div class="form-group">
                            <label for="primary_ssn">Primary Holder SSN *</label>
                            <input type="text" id="primary_ssn" name="primary_ssn" placeholder="XXX-XX-XXXX">
                        </div>
                        
                        <div class="form-group">
                            <label for="secondary_name">Secondary Account Holder Name *</label>
                            <input type="text" id="secondary_name" name="secondary_name">
                        </div>
                        
                        <div class="form-group">
                            <label for="secondary_dob">Secondary Holder Date of Birth *</label>
                            <input type="date" id="secondary_dob" name="secondary_dob">
                        </div>
                        
                        <div class="form-group">
                            <label for="secondary_ssn">Secondary Holder SSN *</label>
                            <input type="text" id="secondary_ssn" name="secondary_ssn" placeholder="XXX-XX-XXXX">
                        </div>
                        
                        <div class="form-group">
                            <label for="joint_type">Joint Account Type *</label>
                            <select id="joint_type" name="joint_type">
                                <option value="">Select joint type</option>
                                <option value="jtwros">Joint Tenants with Rights of Survivorship</option>
                                <option value="tenants_common">Tenants in Common</option>
                                <option value="community_property">Community Property</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Corporation Fields -->
                    <div id="corporation-fields" class="investor-type-fields" style="display: none;">
                        <div class="form-group">
                            <label for="corporation_name">Corporation Name *</label>
                            <input type="text" id="corporation_name" name="corporation_name">
                        </div>
                        
                        <div class="form-group">
                            <label for="ein">Federal Tax ID (EIN) *</label>
                            <input type="text" id="ein" name="ein" placeholder="XX-XXXXXXX">
                        </div>
                        
                        <div class="form-group">
                            <label for="incorporation_state">State of Incorporation *</label>
                            <input type="text" id="incorporation_state" name="incorporation_state">
                        </div>
                        
                        <div class="form-group">
                            <label for="authorized_signatory">Authorized Signatory Name *</label>
                            <input type="text" id="authorized_signatory" name="authorized_signatory">
                        </div>
                        
                        <div class="form-group">
                            <label for="signatory_title">Signatory Title *</label>
                            <input type="text" id="signatory_title" name="signatory_title" placeholder="e.g., CEO, President">
                        </div>
                    </div>
                    
                    <!-- Trust Fields -->
                    <div id="trust-fields" class="investor-type-fields" style="display: none;">
                        <div class="form-group">
                            <label for="trust_name">Trust Name *</label>
                            <input type="text" id="trust_name" name="trust_name">
                        </div>
                        
                        <div class="form-group">
                            <label for="trust_date">Trust Date *</label>
                            <input type="date" id="trust_date" name="trust_date">
                        </div>
                        
                        <div class="form-group">
                            <label for="trustee_name">Trustee Name *</label>
                            <input type="text" id="trustee_name" name="trustee_name">
                        </div>
                        
                        <div class="form-group">
                            <label for="trustee_ssn">Trustee SSN/EIN *</label>
                            <input type="text" id="trustee_ssn" name="trustee_ssn" placeholder="XXX-XX-XXXX or XX-XXXXXXX">
                        </div>
                        
                        <div class="form-group">
                            <label for="trust_type">Trust Type *</label>
                            <select id="trust_type" name="trust_type">
                                <option value="">Select trust type</option>
                                <option value="revocable">Revocable Trust</option>
                                <option value="irrevocable">Irrevocable Trust</option>
                                <option value="charitable">Charitable Trust</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- IRA Fields -->
                    <div id="ira-fields" class="investor-type-fields" style="display: none;">
                        <div class="form-group">
                            <label for="ira_holder_name">IRA Account Holder Name *</label>
                            <input type="text" id="ira_holder_name" name="ira_holder_name">
                        </div>
                        
                        <div class="form-group">
                            <label for="ira_holder_dob">Account Holder Date of Birth *</label>
                            <input type="date" id="ira_holder_dob" name="ira_holder_dob">
                        </div>
                        
                        <div class="form-group">
                            <label for="ira_holder_ssn">Account Holder SSN *</label>
                            <input type="text" id="ira_holder_ssn" name="ira_holder_ssn" placeholder="XXX-XX-XXXX">
                        </div>
                        
                        <div class="form-group">
                            <label for="ira_type">IRA Type *</label>
                            <select id="ira_type" name="ira_type">
                                <option value="">Select IRA type</option>
                                <option value="traditional">Traditional IRA</option>
                                <option value="roth">Roth IRA</option>
                                <option value="sep">SEP IRA</option>
                                <option value="simple">SIMPLE IRA</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="custodian_name">IRA Custodian Name *</label>
                            <input type="text" id="custodian_name" name="custodian_name">
                        </div>
                        
                        <div class="form-group">
                            <label for="ira_account_number">IRA Account Number *</label>
                            <input type="text" id="ira_account_number" name="ira_account_number">
                        </div>
                    </div>
                    
                    <!-- Common Fields for All Types -->
                    <div class="common-fields">
                        <!-- Hidden field for main investor name (populated by JavaScript) -->
                        <input type="hidden" id="main_investor_name" name="investor_name">
                        
                        <div class="form-group">
                            <label for="investor_email">Email Address *</label>
                            <input type="email" id="investor_email" name="investor_email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="investor_phone">Phone Number</label>
                            <input type="tel" id="investor_phone" name="investor_phone">
                        </div>
                        
                        <div class="form-group">
                            <label for="investment_amount">Investment Amount *</label>
                            <input type="number" id="investment_amount" name="investment_amount" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="investor_address">Address</label>
                            <textarea id="investor_address" name="investor_address" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group checkbox-group">
                            <label style="color: #000 !important;">
                                <input type="checkbox" id="terms_accepted" name="terms_accepted" required>
                                I agree to the terms and conditions and consent to receiving investment communications
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-back" id="info-back">Back</button>
                        <button type="submit" class="btn-submit">Submit Investment</button>
                    </div>
                </form>
            </div>
            
            <!-- Success Step -->
            <div class="investment-step hidden" id="success-step">
                <div class="success-message">
                    <h3>Investment Submitted Successfully!</h3>
                    <p>Thank you for your investment. You will receive a confirmation email shortly.</p>
                    <div class="investment-summary">
                        <div class="summary-item">
                            <span class="label">Investment Amount:</span>
                            <span class="value" id="final-amount"></span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Number of Shares:</span>
                            <span class="value" id="final-shares"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="w-node-d76ce5db-1098-4f05-302a-51e614ef19a7-4eda2ff4" class="dmr-investment-details"><div class="w-layout-grid grid-35"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d5-a22a22d5" class="div-block-155"><div class="dmr-common-stock dmr-larger-t text-color-white"><strong style="color: #fff !important">Investment Details</strong></div><div class="div-block-132"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d9-a22a22d5" class="w-layout-layout quick-stack-5 wf-layout-layout"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22da-a22a22d5" class="w-layout-cell"><div class="div-block-67"><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small" style="color: #fff !important">SHARE PRICE</div><div class="dmr-common-stock-2 fixed-height"><strong style="color: #fff !important">${{ $website && $website->share_price ? number_format($website->share_price, 2) : ($setting && $setting->share_price ? number_format($setting->share_price, 2) : '1.00') }} USD</strong></div></div><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small" style="color: #fff !important">MIN INVESTMENT</div><div class="dmr-common-stock-2 fixed-height"><strong style="color: #fff !important">${{ $website && $website->min_investment ? number_format($website->min_investment, 2) : ($setting && $setting->min_investment ? number_format($setting->min_investment, 2) : '1000.00') }} USD</strong></div></div><div class="div-block-46 _3"><a href="#" class="close-2 w-inline-block"><div>X</div></a><div>{{ $website && $website->investment_note ? $website->investment_note : ($setting && $setting->investment_note ? $setting->investment_note : 'Minimum investment amount plus applicable transaction fees') }}</div></div></div></div><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22ee-a22a22d5" class="w-layout-cell"><div class="div-block-67"><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small" style="color: #fff !important">OFFERING TYPE</div><div class="dmr-common-stock-2 fixed-height"><strong style="color: #fff !important">{{ $website && $website->offering_type ? $website->offering_type : ($setting && $setting->offering_type ? $setting->offering_type : 'Equity') }}</strong></div></div><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small" style="color: #fff !important">ASSET TYPE</div><div class="dmr-common-stock-2 fixed-height"><strong style="color: #fff !important">{{ $website && $website->asset_type ? $website->asset_type : ($setting && $setting->asset_type ? $setting->asset_type : 'Common Stock') }}</strong></div></div></div></div></div></div><div class="div-block-130">@if(($website && $website->investment_documents) || ($setting && $setting->investment_documents))<div class="investor_info_wrap text-size-xsmall link-light">@php $docs = $website && $website->investment_documents ? json_decode($website->investment_documents, true) : json_decode($setting->investment_documents, true); @endphp @if(is_array($docs)) @foreach($docs as $doc) <a aria-label="{{ $doc['name'] ?? 'Investment Document' }}" href="{{ $doc['url'] ?? '#' }}" target="_blank" class="investor_info_link text-link-inherit">{{ $doc['name'] ?? 'Document' }}</a> @endforeach @endif</div>@endif @if(($website && $website->investment_deadline) || ($setting && $setting->investment_deadline))<div class="countdown_checkout_wrapper"><div countdown-wrapper="1" class="countdown_wrapper is_checkout"><div class="countdown_title is_checkout"><strong>{{ $website && $website->deadline_text ? $website->deadline_text : ($setting && $setting->deadline_text ? $setting->deadline_text : 'Investment Deadline') }}</strong></div><div id="js-clock" class="timer_wrap"><div id="w-node-_20a0a116-730f-65a2-befe-802bf563b40a-a22a22d5" class="timer_cell"><div id="days" class="timer_number smaller">0</div><div class="timer_label _3">Days</div></div><div class="timer_cell"><div id="hours" class="timer_number smaller">0</div><div class="timer_label _3">Hours</div></div><div class="timer_cell last-m"><div id="minutes" class="timer_number smaller">0</div><div class="timer_label _3">Minutes</div></div><div class="timer_cell mobil-hide"><div id="seconds" class="timer_number smaller">0</div><div class="timer_label _3">Seconds</div></div></div></div></div>@endif</div></div><div class="dmr-details-padding last-list"><div class="dmr-common-stock text-color-white"><strong style="color: #fff !important">Additional Information</strong></div><ul role="list" class="list-3"></ul><div class="disclaimer-dmr" style="color: #fff !important">{{ $website && $website->investment_disclaimer ? $website->investment_disclaimer : ($setting && $setting->investment_disclaimer ? $setting->investment_disclaimer : 'Investment details and disclosures are available in the offering documents.') }}</div></div></div></div></div></div></div></div></header></main>
    @if($footer)
        {!! $footer->content !!}
    @endif



<div class="w-layout-grid footer2_legal-list"><div class="footer2_credit-text"> ,   <a href="https://www.sec.gov/Archives/edgar/data/1748169/000168316825000119/ginluck_1aa1.htm" target="_blank" class="link-9">here</a>.<br/><br/>  These forward-looking statements are based on the beliefs of, assumptions made by, and information currently available to the company’s management. When used in the offering materials, the words “estimate,” “project,” “believe,” “anticipate,” “intend,” “expect” and similar expressions are intended to identify forward-looking statements. These statements reflect management’s current views with respect to future events and are subject to risks and uncertainties that could cause the company’s actual results to differ materially from those contained in the forward-looking statements. Investors are cautioned not to place undue reliance on these forward-looking statements, which speak only as of the date on which they are made. The company does not undertake any obligation to revise or update these forward-looking statements to reflect events or circumstances after such date or to reflect the occurrence of unanticipated events.</div><div class="w-layout-grid grid-4"><div class="w-layout-grid grid-2"><div id="w-node-_09590d8d-0d99-05d6-cbe7-c946f15c2b26-f15c2b06" class="footer-contact"><div class="w-embed"><svg xmlns="http://www.w3.org/2000/svg" height="32" width="32" viewBox="0 0 512 512"><!--!Font Awesome Pro 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2024 Fonticons, Inc.--><path fill="currentColor" d="M64 96c-17.7 0-32 14.3-32 32v39.9L227.6 311.3c16.9 12.4 39.9 12.4 56.8 0L480 167.9V128c0-17.7-14.3-32-32-32H64zM32 207.6V384c0 17.7 14.3 32 32 32H448c17.7 0 32-14.3 32-32V207.6L303.3 337.1c-28.2 20.6-66.5 20.6-94.6 0L32 207.6zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z"/></svg></div><div></div><div class="reason-text"><h3 class="heading-style-h5-2"><a href="#" aria-label="Mail {{ $setting && $setting->company_name ? $setting->company_name : 'Company' }}" class="link-2 text-color-white">{{ $setting && $setting->company_email ? $setting->company_email : 'invest@company.com' }}</a></h3></div></div></div><div id="w-node-_09590d8d-0d99-05d6-cbe7-c946f15c2b2e-f15c2b06" class="w-layout-grid footer4_social-list">@if($setting && $setting->facebook_url)
        <a aria-label="Go to Facebook" href="{{ $setting->facebook_url }}" target="_blank" class="footer4_social-link w-inline-block">
    @else
        <a aria-label="Go to Facebook" href="#" class="footer4_social-link w-inline-block" style="display: none;">
    @endif<div class="icon-embed-xsmall w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M22 12.0611C22 6.50451 17.5229 2 12 2C6.47715 2 2 6.50451 2 12.0611C2 17.0828 5.65684 21.2452 10.4375 22V14.9694H7.89844V12.0611H10.4375V9.84452C10.4375 7.32296 11.9305 5.93012 14.2146 5.93012C15.3088 5.93012 16.4531 6.12663 16.4531 6.12663V8.60261H15.1922C13.95 8.60261 13.5625 9.37822 13.5625 10.1739V12.0611H16.3359L15.8926 14.9694H13.5625V22C18.3432 21.2452 22 17.083 22 12.0611Z" fill="CurrentColor"/>
</svg></div></a>@if($setting && $setting->instagram_url)
        <a aria-label="Go to Instagram" href="{{ $setting->instagram_url }}" target="_blank" class="footer4_social-link w-inline-block">
    @else
        <a aria-label="Go to Instagram" href="#" class="footer4_social-link w-inline-block" style="display: none;">
    @endif<div class="icon-embed-xsmall w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M16 3H8C5.23858 3 3 5.23858 3 8V16C3 18.7614 5.23858 21 8 21H16C18.7614 21 21 18.7614 21 16V8C21 5.23858 18.7614 3 16 3ZM19.25 16C19.2445 17.7926 17.7926 19.2445 16 19.25H8C6.20735 19.2445 4.75549 17.7926 4.75 16V8C4.75549 6.20735 6.20735 4.75549 8 4.75H16C17.7926 4.75549 19.2445 6.20735 19.25 8V16ZM16.75 8.25C17.3023 8.25 17.75 7.80228 17.75 7.25C17.75 6.69772 17.3023 6.25 16.75 6.25C16.1977 6.25 15.75 6.69772 15.75 7.25C15.75 7.80228 16.1977 8.25 16.75 8.25ZM12 7.5C9.51472 7.5 7.5 9.51472 7.5 12C7.5 14.4853 9.51472 16.5 12 16.5C14.4853 16.5 16.5 14.4853 16.5 12C16.5027 10.8057 16.0294 9.65957 15.1849 8.81508C14.3404 7.97059 13.1943 7.49734 12 7.5ZM9.25 12C9.25 13.5188 10.4812 14.75 12 14.75C13.5188 14.75 14.75 13.5188 14.75 12C14.75 10.4812 13.5188 9.25 12 9.25C10.4812 9.25 9.25 10.4812 9.25 12Z" fill="CurrentColor"/>
</svg></div></a>@if($setting && $setting->twitter_url)
        <a aria-label="Go to X" href="{{ $setting->twitter_url }}" target="_blank" class="footer4_social-link w-inline-block">
    @else
        <a aria-label="Go to X" href="#" class="footer4_social-link w-inline-block" style="display: none;">
    @endif<div class="icon-embed-xsmall w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M17.1761 4H19.9362L13.9061 10.7774L21 20H15.4456L11.0951 14.4066L6.11723 20H3.35544L9.80517 12.7508L3 4H8.69545L12.6279 9.11262L17.1761 4ZM16.2073 18.3754H17.7368L7.86441 5.53928H6.2232L16.2073 18.3754Z" fill="CurrentColor"/>
</svg></div></a></div></div></div></div></div></div></div></div></footer></div>

{{-- Replace external scripts with local jQuery --}}
<script src="{{ asset('investment/js/jquery-3.5.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('investment/js/error-handler.js') }}" type="text/javascript"></script>
{{-- 
NOTE: External Death & Co scripts removed and replaced with local functionality
<script src="https://cdn.prod.website-files.com/65bbbcaef2927fbb7ef5844d/js/death-co-second-version.schunk.36b8fb49256177c8.js" type="text/javascript"></script>
<script src="https://cdn.prod.website-files.com/65bbbcaef2927fbb7ef5844d/js/death-co-second-version.schunk.6b1166717f10a265.js" type="text/javascript"></script>
<script src="https://cdn.prod.website-files.com/65bbbcaef2927fbb7ef5844d/js/death-co-second-version.schunk.05120ed059607970.js" type="text/javascript"></script>
<script src="https://cdn.prod.website-files.com/65bbbcaef2927fbb7ef5844d/js/death-co-second-version.131f10d9.67664f9d2286fe2e.js" type="text/javascript"></script>
--}}

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W7328S2C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<script>
// COUNTDOWN [UPDATED ON 07/2025]
// Set the date we're counting down to
const countDownDate = new Date("JULY 22, 2025 23:59:59 PDT").getTime();
const countdownWrappers = [...document.querySelectorAll('[countdown-wrapper]')];
  
countdownWrappers.forEach(wrapper => {

  // Update the count down every 1 second
  const x = setInterval(function() {

    // Get today's date and time
    const now = new Date().getTime();

    // Find the distance between now and the count down date
    const distance = countDownDate - now;

    // Time calculations for days, hours, minutes and seconds
    let days = Math.floor(distance / (1000 * 60 * 60 * 24));
    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // If the countdown is over
    if (distance < 0) {
      clearInterval(x);
      wrapper.querySelector("#ended") && (wrapper.querySelector("#ended").innerHTML = "Campaign Ended");
      days = 0;
      hours = 0;
      minutes = 0;
      seconds = 0;
      
      // Hide the wrapper when the countdown ends.
      wrapper.style.display = 'none';
      
    }

    // Output the result in an element with id="demo"

    wrapper.querySelector("#days").innerHTML = days;
    wrapper.querySelector("#hours").innerHTML = hours;
    wrapper.querySelector("#minutes").innerHTML = minutes;
    wrapper.querySelector("#seconds").innerHTML = seconds;

  }, 1000);
  
});  

</script>

{{-- 
NOTE: External DealMaker Utils script removed - functionality integrated locally
<script src="https://storage.googleapis.com/funf-magiclink/dealmaker-utils/v1/index19.js"
base_url = "https://app.dealmaker.tech/invitations/2a18f583-9da2-4938-b2f5-f200925290fd/view"
></script>
--}}

<!-- Custom Investment Form JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Investment form configuration
    const config = {
        sharePrice: {{ $website && $website->share_price ? $website->share_price : ($setting && $setting->share_price ? $setting->share_price : 1.00) }},
        minInvestment: {{ $website && $website->min_investment ? $website->min_investment : ($setting && $setting->min_investment ? $setting->min_investment : 1000) }},
        companyName: '{{ $setting && $setting->company_name ? addslashes($setting->company_name) : "Investment Platform" }}'
    };
    
    let selectedAmount = {{ isset($amount) && !empty($amount) ? $amount : 'null' }};
    
    // DOM elements
    const amountStep = document.getElementById('amount-step');
    const infoStep = document.getElementById('info-step');
    const successStep = document.getElementById('success-step');
    const tierOptions = document.querySelectorAll('.tier-option');
    const customAmountInput = document.getElementById('custom-amount');
    const customSharesDisplay = document.querySelector('.custom-shares-display');
    const amountContinueBtn = document.getElementById('amount-continue');
    const investmentAmountField = document.getElementById('investment_amount');
    const investorForm = document.getElementById('investor-form');
    const backBtn = document.getElementById('info-back');
    
    // Initialize with prefilled amount if available
    if (selectedAmount) {
        updateSelectedAmount(selectedAmount);
        showStep('info');
    }
    
    // Tier selection
    tierOptions.forEach(option => {
        option.addEventListener('click', function() {
            const amount = parseInt(this.dataset.amount);
            selectTier(this, amount);
        });
    });
    
    // Custom amount input
    customAmountInput.addEventListener('input', function() {
        const amount = parseFloat(this.value);
        if (amount >= config.minInvestment) {
            clearTierSelections();
            updateSelectedAmount(amount);
            updateCustomShares(amount);
        } else {
            selectedAmount = null;
            customSharesDisplay.textContent = '';
            amountContinueBtn.disabled = true;
        }
    });
    
    // Continue to info step
    amountContinueBtn.addEventListener('click', function() {
        if (selectedAmount) {
            showStep('info');
        }
    });
    
    // Back to amount step
    backBtn.addEventListener('click', function() {
        showStep('amount');
    });
    
    // Form submission
    investorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitInvestment();
    });
    
    // Investor type handling
    const investorTypeSelect = document.getElementById('investor_type');
    const investorTypeFields = document.querySelectorAll('.investor-type-fields');
    
    investorTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        
        // Hide all investor type fields first
        investorTypeFields.forEach(field => {
            field.style.display = 'none';
            // Clear required attributes from hidden fields
            const inputs = field.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.removeAttribute('required');
            });
        });
        
        // Show the selected investor type fields
        if (selectedType) {
            const selectedFields = document.getElementById(selectedType + '-fields');
            if (selectedFields) {
                selectedFields.style.display = 'block';
                // Add required attributes to visible fields
                const requiredInputs = selectedFields.querySelectorAll('input, select');
                requiredInputs.forEach(input => {
                    if (input.type !== 'hidden') {
                        input.setAttribute('required', 'required');
                    }
                });
            }
        }
        
        // Update the main investor_name field based on type
        updateInvestorNameField(selectedType);
    });
    
    function updateInvestorNameField(investorType) {
        // Set the main investor_name field based on the selected type and its specific fields
        const mainNameField = document.getElementById('main_investor_name');
        if (!mainNameField) return;
        
        switch(investorType) {
            case 'individual':
                const individualName = document.getElementById('investor_name');
                if (individualName && individualName.value) {
                    mainNameField.value = individualName.value;
                }
                break;
            case 'joint':
                const primaryName = document.getElementById('primary_name');
                const secondaryName = document.getElementById('secondary_name');
                if (primaryName && secondaryName) {
                    mainNameField.value = `${primaryName.value} & ${secondaryName.value}`;
                }
                break;
            case 'corporation':
                const corpName = document.getElementById('corporation_name');
                if (corpName && corpName.value) {
                    mainNameField.value = corpName.value;
                }
                break;
            case 'trust':
                const trustName = document.getElementById('trust_name');
                if (trustName && trustName.value) {
                    mainNameField.value = trustName.value;
                }
                break;
            case 'ira':
                const iraHolderName = document.getElementById('ira_holder_name');
                if (iraHolderName && iraHolderName.value) {
                    mainNameField.value = `${iraHolderName.value} (IRA)`;
                }
                break;
        }
    }
    
    // Add event listeners to update main name field when specific fields change
    document.addEventListener('input', function(e) {
        const selectedType = investorTypeSelect.value;
        if (!selectedType) return;
        
        const fieldId = e.target.id;
        const relevantFields = {
            'individual': ['investor_name'],
            'joint': ['primary_name', 'secondary_name'],
            'corporation': ['corporation_name'],
            'trust': ['trust_name'],
            'ira': ['ira_holder_name']
        };
        
        if (relevantFields[selectedType] && relevantFields[selectedType].includes(fieldId)) {
            updateInvestorNameField(selectedType);
        }
    });

    // Functions
    function selectTier(element, amount) {
        clearTierSelections();
        element.classList.add('selected');
        customAmountInput.value = '';
        customSharesDisplay.textContent = '';
        updateSelectedAmount(amount);
    }
    
    function clearTierSelections() {
        tierOptions.forEach(option => option.classList.remove('selected'));
    }
    
    function updateSelectedAmount(amount) {
        selectedAmount = amount;
        amountContinueBtn.disabled = false;
        
        // Update both the visible readonly field and ensure form data is set
        if (investmentAmountField) {
            investmentAmountField.value = amount;
        }
        
        // Also update any hidden fields or other amount inputs
        const allAmountFields = document.querySelectorAll('input[name="investment_amount"]');
        allAmountFields.forEach(field => {
            field.value = amount;
        });
        
        // Update button text
        const shares = Math.floor(amount / config.sharePrice);
        amountContinueBtn.textContent = `Continue with $${amount.toLocaleString()} (${shares.toLocaleString()} shares)`;
    }
    
    function updateCustomShares(amount) {
        const shares = Math.floor(amount / config.sharePrice);
        customSharesDisplay.textContent = `≈ ${shares.toLocaleString()} shares at $${config.sharePrice} per share`;
    }
    
    function showStep(step) {
        // Hide all steps
        amountStep.classList.add('hidden');
        infoStep.classList.add('hidden');
        successStep.classList.add('hidden');
        
        // Show selected step
        switch(step) {
            case 'amount':
                amountStep.classList.remove('hidden');
                break;
            case 'info':
                infoStep.classList.remove('hidden');
                break;
            case 'success':
                successStep.classList.remove('hidden');
                break;
        }
    }
    
    function submitInvestment() {
        const formData = new FormData(investorForm);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Add CSRF token
        formData.append('_token', csrfToken);
        
        // Show loading state
        const submitBtn = investorForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Processing...';
        submitBtn.disabled = true;
        
        // Create a regular form and submit it (like donations do)
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/invest/save-info';
        form.style.display = 'none';
        
        // Add all form data as hidden inputs
        for (let [key, value] of formData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
        
        // Submit the form
        document.body.appendChild(form);
        form.submit();
    }
    
    function showSuccessStep(data) {
        const shares = Math.floor(selectedAmount / config.sharePrice);
        document.getElementById('final-amount').textContent = `$${selectedAmount.toLocaleString()}`;
        document.getElementById('final-shares').textContent = `${shares.toLocaleString()} shares`;
    }
});
</script>

                    </div>
                </div>
            </div>
        </div>
    </main>

    @if($footer && $footer->status == 1)
        {!! $footer->content !!}
    @endif

    <!-- CSRF Test Button (for debugging) -->
    {{-- <div style="position: fixed; bottom: 10px; right: 10px; z-index: 9999;">
        <button id="csrf-test-btn" style="padding: 5px 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Test CSRF</button>
    </div> --}}

    <script>
        document.getElementById('csrf-test-btn').addEventListener('click', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            console.log('Testing CSRF with token:', csrfToken);
            
            fetch('/test-csrf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    test: 'data',
                    _token: csrfToken
                })
            }).then(response => {
                console.log('CSRF Test Response Status:', response.status);
                return response.json();
            }).then(data => {
                console.log('CSRF Test Response:', data);
                alert('CSRF Test: ' + data.message);
            }).catch(error => {
                console.error('CSRF Test Error:', error);
                alert('CSRF Test Failed: ' + error.message);
            });
        });
    </script>

</body></html>