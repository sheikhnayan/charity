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
    <meta charset="utf-8"/>
    <title>{{ $setting && $setting->company_name ? $setting->company_name . ' | Investment Checkout' : 'Investment Checkout' }}</title><meta content="{{ $setting && $setting->company_name ? 'Invest in ' . $setting->company_name . ' and become part of our growing success story. Secure your shares today through our regulated investment platform.' : 'Secure investment platform offering regulated investment opportunities.' }}" name="description"/>
    <meta content="{{ $setting && $setting->company_name ? $setting->company_name . ' | Investment Checkout' : 'Investment Checkout' }}" property="og:title"/>
    <meta content="{{ $setting && $setting->company_name ? 'Invest in ' . $setting->company_name . ' and become part of our growing success story. Secure your shares today through our regulated investment platform.' : 'Secure investment platform offering regulated investment opportunities.' }}" property="og:description"/>
    <meta content="{{ $setting && $setting->logo ? asset('uploads/' . $setting->logo) : asset('investment/images/default-investment-image.jpg') }}" property="og:image"/>
    <meta content="{{ $setting && $setting->company_name ? $setting->company_name . ' | Investment Checkout' : 'Investment Checkout' }}" property="twitter:title"/>
    <meta content="{{ $setting && $setting->company_name ? 'Invest in ' . $setting->company_name . ' and become part of our growing success story. Secure your shares today through our regulated investment platform.' : 'Secure investment platform offering regulated investment opportunities.' }}" property="twitter:description"/>
    <meta content="{{ $setting && $setting->logo ? asset('uploads/' . $setting->logo) : asset('investment/images/default-investment-image.jpg') }}" property="twitter:image"/><meta property="og:type" content="website"/><meta content="summary_large_image" name="twitter:card"/><meta content="width=device-width, initial-scale=1" name="viewport"/><meta content="noindex" name="robots"/><link href="{{ asset('investment/css/main.min.css') }}" rel="stylesheet" type="text/css"/>
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
</style></head><body style="background-color: {{ $setting && $setting->background_color ? $setting->background_color : '#ffffff' }};"><div class="page-wrapper"><div class="global-styles w-embed"><style>

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
 
.margin-0 {
  margin: 0rem !important;
}
  
.padding-0 {
  padding: 0rem !important;
}

.spacing-clean {
padding: 0rem !important;
margin: 0rem !important;
}

.margin-top {
  margin-right: 0rem !important;
  margin-bottom: 0rem !important;
  margin-left: 0rem !important;
}

.padding-top {
  padding-right: 0rem !important;
  padding-bottom: 0rem !important;
  padding-left: 0rem !important;
}
  
.margin-right {
  margin-top: 0rem !important;
  margin-bottom: 0rem !important;
  margin-left: 0rem !important;
}

.padding-right {
  padding-top: 0rem !important;
  padding-bottom: 0rem !important;
  padding-left: 0rem !important;
}

.margin-bottom {
  margin-top: 0rem !important;
  margin-right: 0rem !important;
  margin-left: 0rem !important;
}

.padding-bottom {
  padding-top: 0rem !important;
  padding-right: 0rem !important;
  padding-left: 0rem !important;
}

.margin-left {
  margin-top: 0rem !important;
  margin-right: 0rem !important;
  margin-bottom: 0rem !important;
}
  
.padding-left {
  padding-top: 0rem !important;
  padding-right: 0rem !important;
  padding-bottom: 0rem !important;
}
  
.margin-horizontal {
  margin-top: 0rem !important;
  margin-bottom: 0rem !important;
}

.padding-horizontal {
  padding-top: 0rem !important;
  padding-bottom: 0rem !important;
}

.margin-vertical {
  margin-right: 0rem !important;
  margin-left: 0rem !important;
}
  
.padding-vertical {
  padding-right: 0rem !important;
  padding-left: 0rem !important;
}

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
<main class="main-wrapper"><header id="home" class="section_header3 checkout-hero"><div class="padding-global z-index-2"><div class="container-2"><div class="dmr-checkout-wrapper"><div><div class="hero-text-2 _2"><h1 class="heading-style-h1 is-checkout"><strong>{{ $setting && $setting->company_name ? $setting->company_name : 'Investment' }}</strong> Investment Opportunity<br/></h1><div class="spacer-small"></div><div class="dmr-details-mobile-show"><div class="div-block-132"><div id="w-node-d76ce5db-1098-4f05-302a-51e614ef1974-4eda2ff4" class="div-block-155"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d5-a22a22d5" class="div-block-155"><div class="dmr-common-stock dmr-larger-t text-color-white"><strong>Investment Details</strong></div><div class="div-block-132"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d9-a22a22d5" class="w-layout-layout quick-stack-5 wf-layout-layout"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22da-a22a22d5" class="w-layout-cell"><div class="div-block-67"><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small">SHARE PRICE</div><div class="dmr-common-stock-2 fixed-height"><strong>$2.13 USD</strong></div></div><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small">MIN INVESTMENT</div><div class="dmr-common-stock-2 fixed-height"><strong>$1001.10 USD</strong></div></div><div class="div-block-46 _3"><a href="#" class="close-2 w-inline-block"><div>X</div></a><div>Minimum investment is $504 + 1.5% transaction fee</div></div></div></div><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22ee-a22a22d5" class="w-layout-cell"><div class="div-block-67"><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small">OFFERING TYPE</div><div class="dmr-common-stock-2 fixed-height"><strong>Equity</strong></div></div><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small">ASSET TYPE</div><div class="dmr-common-stock-2 fixed-height"><strong>Series C-1 Preferred Stock</strong></div></div></div></div></div></div><div class="div-block-130"><div class="investor_info_wrap text-size-xsmall link-light"><a aria-label="See full Form 1A about the offering." href="https://www.sec.gov/Archives/edgar/data/1748169/000168316825003758/0001683168-25-003758-index.htm" target="_blank" class="investor_info_link text-link-inherit">Form 1A</a><a href="https://www.sec.gov/Archives/edgar/data/1748169/000168316825006276/ginandluck_253g2.htm" aria-label="Investor Education" target="_blank" class="investor_info_link text-link-inherit">1A Supplement</a></div><div class="countdown_checkout_wrapper"><div countdown-wrapper="1" class="countdown_wrapper is_checkout"><div class="countdown_title is_checkout"><strong>FInal Day to Invest is 9/27</strong></div><div id="js-clock" class="timer_wrap"><div id="w-node-_20a0a116-730f-65a2-befe-802bf563b40a-a22a22d5" class="timer_cell"><div id="days" class="timer_number smaller">0</div><div class="timer_label _3">Days</div></div><div class="timer_cell"><div id="hours" class="timer_number smaller">0</div><div class="timer_label _3">Hours</div></div><div class="timer_cell last-m"><div id="minutes" class="timer_number smaller">0</div><div class="timer_label _3">Minutes</div></div><div class="timer_cell mobil-hide"><div id="seconds" class="timer_number smaller">0</div><div class="timer_label _3">Seconds</div></div></div></div></div></div></div></div></div></div></div><div class="w-layout-grid virtuix-checkout-grid"><div id="root" class="react-wrapper w-node-d76ce5db-1098-4f05-302a-51e614ef19a6-4eda2ff4"></div><div id="w-node-d76ce5db-1098-4f05-302a-51e614ef19a7-4eda2ff4" class="dmr-investment-details"><div class="w-layout-grid grid-35"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d5-a22a22d5" class="div-block-155"><div class="dmr-common-stock dmr-larger-t text-color-white"><strong>Investment Details</strong></div><div class="div-block-132"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d9-a22a22d5" class="w-layout-layout quick-stack-5 wf-layout-layout"><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22da-a22a22d5" class="w-layout-cell"><div class="div-block-67"><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small">SHARE PRICE</div><div class="dmr-common-stock-2 fixed-height"><strong>$2.13 USD</strong></div></div><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small">MIN INVESTMENT</div><div class="dmr-common-stock-2 fixed-height"><strong>$1001.10 USD</strong></div></div><div class="div-block-46 _3"><a href="#" class="close-2 w-inline-block"><div>X</div></a><div>Minimum investment is $504 + 1.5% transaction fee</div></div></div></div><div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22ee-a22a22d5" class="w-layout-cell"><div class="div-block-67"><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small">OFFERING TYPE</div><div class="dmr-common-stock-2 fixed-height"><strong>Equity</strong></div></div><div class="dmr-details-padding no-l"><div class="dmr-common-stock-2 small">ASSET TYPE</div><div class="dmr-common-stock-2 fixed-height"><strong>Series C-1 Preferred Stock</strong></div></div></div></div></div></div><div class="div-block-130"><div class="investor_info_wrap text-size-xsmall link-light"><a aria-label="See full Form 1A about the offering." href="https://www.sec.gov/Archives/edgar/data/1748169/000168316825003758/0001683168-25-003758-index.htm" target="_blank" class="investor_info_link text-link-inherit">Form 1A</a><a href="https://www.sec.gov/Archives/edgar/data/1748169/000168316825006276/ginandluck_253g2.htm" aria-label="Investor Education" target="_blank" class="investor_info_link text-link-inherit">1A Supplement</a></div><div class="countdown_checkout_wrapper"><div countdown-wrapper="1" class="countdown_wrapper is_checkout"><div class="countdown_title is_checkout"><strong>FInal Day to Invest is 9/27</strong></div><div id="js-clock" class="timer_wrap"><div id="w-node-_20a0a116-730f-65a2-befe-802bf563b40a-a22a22d5" class="timer_cell"><div id="days" class="timer_number smaller">0</div><div class="timer_label _3">Days</div></div><div class="timer_cell"><div id="hours" class="timer_number smaller">0</div><div class="timer_label _3">Hours</div></div><div class="timer_cell last-m"><div id="minutes" class="timer_number smaller">0</div><div class="timer_label _3">Minutes</div></div><div class="timer_cell mobil-hide"><div id="seconds" class="timer_number smaller">0</div><div class="timer_label _3">Seconds</div></div></div></div></div></div></div></div><div class="dmr-details-padding last-list"><div class="dmr-common-stock text-color-white"><strong>Additional Information</strong></div><ul role="list" class="list-3"></ul><div class="disclaimer-dmr">I consent to receiving reports, promotional emails and other commercial electronic messages from {{ $setting && $setting->company_name ? $setting->company_name : 'the Company' }} or from other service providers on behalf of {{ $setting && $setting->company_name ? $setting->company_name : 'the Company' }}.</div><div class="disclaimer-dmr">The amount of bonus shares will be represented in your Direct Registration Statement once shares are issued. The bonus shares will NOT be displayed in your DealMaker account dashboard.</div></div></div></div></div></div></div></div></header></main><footer class="footer_component"><div id="footer" class="padding-global"><div class="container-large"><div class="padding-vertical padding-xxlarge"><div class="div-block-169"><a href="/" dmr-utm-forward="1" aria-label="Go to Homepage" class="footer_logo w-nav-brand">
        @if($header && $header->logo)
            <img src="{{ asset('uploads/' . $header->logo) }}" loading="eager" width="250" alt="{{ $setting && $setting->company_name ? $setting->company_name : 'Company' }} logo." class="image-46"/>
        @else
            <div class="company-name" style="color: white; font-size: 24px; font-weight: bold;">{{ $setting && $setting->company_name ? $setting->company_name : 'Investment Platform' }}</div>
        @endif
    </a><p class="paragraph-3">[<a href="https://policies.google.com/privacy" aria-label="Go to PrivacyPolicy" target="_blank" class="link-3">Privacy Policy</a>]</p></div><div class="padding-top padding-medium"><div class="footer2_bottom-wrapper"><div class="w-layout-grid footer2_legal-list"><div class="footer2_credit-text">An offering statement regarding this offering has been filed with the SEC. The SEC has qualified that offering statement, which only means that the company may make sales of the securities described by the offering statement. The offering circular that is part of that offering statement is available <a href="https://www.sec.gov/Archives/edgar/data/1748169/000168316825000119/ginluck_1aa1.htm" target="_blank" class="link-9">here</a>.<br/><br/>This website may contain forward-looking statements and information relating to, among other things, the company, its business plan and strategy, and its industry. These forward-looking statements are based on the beliefs of, assumptions made by, and information currently available to the company’s management. When used in the offering materials, the words “estimate,” “project,” “believe,” “anticipate,” “intend,” “expect” and similar expressions are intended to identify forward-looking statements. These statements reflect management’s current views with respect to future events and are subject to risks and uncertainties that could cause the company’s actual results to differ materially from those contained in the forward-looking statements. Investors are cautioned not to place undue reliance on these forward-looking statements, which speak only as of the date on which they are made. The company does not undertake any obligation to revise or update these forward-looking statements to reflect events or circumstances after such date or to reflect the occurrence of unanticipated events.</div><div class="w-layout-grid grid-4"><div class="w-layout-grid grid-2"><div id="w-node-_09590d8d-0d99-05d6-cbe7-c946f15c2b26-f15c2b06" class="footer-contact"><div class="w-embed"><svg xmlns="http://www.w3.org/2000/svg" height="32" width="32" viewBox="0 0 512 512"><!--!Font Awesome Pro 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2024 Fonticons, Inc.--><path fill="currentColor" d="M64 96c-17.7 0-32 14.3-32 32v39.9L227.6 311.3c16.9 12.4 39.9 12.4 56.8 0L480 167.9V128c0-17.7-14.3-32-32-32H64zM32 207.6V384c0 17.7 14.3 32 32 32H448c17.7 0 32-14.3 32-32V207.6L303.3 337.1c-28.2 20.6-66.5 20.6-94.6 0L32 207.6zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z"/></svg></div><div>Investor Relations:</div><div class="reason-text"><h3 class="heading-style-h5-2"><a href="#" aria-label="Mail {{ $setting && $setting->company_name ? $setting->company_name : 'Company' }}" class="link-2 text-color-white">{{ $setting && $setting->company_email ? $setting->company_email : 'invest@company.com' }}</a></h3></div></div></div><div id="w-node-_09590d8d-0d99-05d6-cbe7-c946f15c2b2e-f15c2b06" class="w-layout-grid footer4_social-list">@if($setting && $setting->facebook_url)
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
</svg></div></a></div></div></div></div></div></div></div></div><div class="reach-template-2024--powered_by_dm_wrap"><a aria-label="Go to DealMaker" href="https://dealmaker.tech" target="_blank" class="reach-template-2024--powered_by_dm_link w-inline-block"><img width="160" height="51.5" alt="DealMaker logo" src="https://cdn.prod.website-files.com/65bbbcaef2927fbb7ef5844d/685d37052ca06fcc8c3d9788_60a781ab391de3142cd53e14d62ce0cc_dealmaker_logo-18.webp" loading="lazy" class="reach-template-2024--powered_by_dm_logo"/></a></div></footer></div><div id="tax-notice" class="tax-notice w-embed"><p><span style="font-size:14px"><span style="font-family:Arial,sans-serif"><span style="color:#000000"><strong>Government-required identity &amp; anti-fraud checks secure all transactions. Why Do We Need This?</strong></span></span></span></p>

<p>&nbsp;</p>

<p><span style="font-size:14px"><span style="font-family:Arial,sans-serif"><span style="color:#000000"><strong>Since this is a financial transaction we are required by regulators like the SEC &amp; US Department of Treasury to perform AML (Anti Money Laundering) &amp; KYC (Know Your Customer) verification in order to avoid money laundering, fraud, and identity theft.&nbsp;</strong></span></span></span></p>

<p>&nbsp;</p>

<p><span style="font-size:14px"><span style="font-family:Arial,sans-serif"><span style="color:#000000">Our broker-dealer, DealMaker Securities, LLC uses a Taxpayer Identification Number (TIN), for example Social Security Number (SSN), Employment Identification Number (EIN), Individual Tax Identification Number (ITIN) to fulfill its responsibilities with its Anti-Money Laundering (AML) Program as required by the Bank Secrecy Act (BSA) and its implementing regulations and FINRA Rule 3310 (AML Compliance Program) by requesting, reviewing, and verifying data and documentation provided during securities transactions, prior to acceptance.&nbsp;</span></span></span></p>

<p>&nbsp;</p>

<p><span style="font-size:14px"><span style="font-family:Arial,sans-serif"><span style="color:#000000">Here&rsquo;s why they are required for startup investments:</span></span></span></p>

<p>&nbsp;</p>

<p><span style="font-size:14px"><span style="font-family:Arial,sans-serif"><span style="color:#000000">1.</span></span></span></p>

<p><span style="font-size:14px"><span style="font-family:Arial,sans-serif"><span style="color:#000000">Preventing Illegal Activities: Money laundering involves the concealment or disguise of money derived from criminal origins by processing it through a single or series of transactions to make it appear as if it comes from a legal, legitimate source or constitute legitimate assets. Having a verification process, whereby investors are reviewed, checked against governmental databases, and all investment funds are evaluated, startups can feel confident they are protecting themselves from civil and criminal penalties and preventing terrorist financing, drug trafficking, tax evasion, corruption, fraud, and other financial crimes.</span></span></span></p>

<p>&nbsp;</p>

<p><span style="font-size:14px"><span style="font-family:Arial,sans-serif"><span style="color:#000000">2.</span></span></span></p>

<p><span style="font-size:14px"><span style="font-family:Arial,sans-serif"><span style="color:#000000">Identity Verification/Data: KYC processes help collect essential pieces of data and verify the identity and authority of the investors, ensuring that they are indeed who they claim to be and are authorized to process the transaction they seek to make. This protects against identity theft and fraud.</span></span></span></p>

<p>&nbsp;</p>

<p><span style="font-size:14px"><span style="font-family:Arial,sans-serif"><span style="color:#000000">3.</span></span></span></p>

<p><span style="font-size:14px"><span style="font-family:Arial,sans-serif"><span style="color:#000000">Regulatory Compliance: Compliance with AML and KYC requirements is mandatory in many jurisdictions. Failure to comply can lead to severe civil penalties, including heavy fines, and even criminal penalties.</span></span></span></p></div>

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

<!-- Checkout Settings -->
<script>  
  window.checkoutSettings = {
    dealId: {{ $setting && $setting->deal_id ? $setting->deal_id : 'null' }},
    disableRadioButtons: false,
    investmentTiers: {{ $setting && $setting->investment_tiers ? json_encode(explode(',', $setting->investment_tiers)) : '[1000, 2500, 5000, 10000]' }},
    sharePrice: {{ $setting && $setting->share_price ? $setting->share_price : '1.00' }},
    minInvestment: {{ $setting && $setting->min_investment ? $setting->min_investment : '1000.00' }},
    sharePriceMinFractionDigits: 2,
    sharePriceMaxFractionDigits: 2,
    disclaimer: '*All bonus shares (if any) will be issued after the completion or termination of this Offering, and therefore they will not be displayed under your investment amount.',
    resumeInvestmentText: 'Already started an investment in this round?',
    enableManaged: true,
    adjustScrollOfset: 90,
    wording: {
      price: 'Share price',
      perks: 'Shares',
      shares: 'Shares'
    },
    logoUrl: '{{ $header && $header->logo ? asset("uploads/" . $header->logo) : asset("investment/images/default-logo.png") }}',
    buttonColor: '{{ $setting && $setting->button_color ? $setting->button_color : "#007bff" }}',
    brandColor: '{{ $setting && $setting->brand_color ? $setting->brand_color : "#000" }}',
    companyName: '{{ $setting && $setting->company_name ? addslashes($setting->company_name) : "Investment Platform" }}',
    companyEmail: '{{ $setting && $setting->company_email ? $setting->company_email : "invest@company.com" }}'
  };
  
  // Debug logging to check for undefined values
  console.log('Checkout Settings:', window.checkoutSettings);
  
  // Ensure wording object is properly defined
  if (!window.checkoutSettings.wording) {
    window.checkoutSettings.wording = {
      price: 'Share price',
      perks: 'Shares', 
      shares: 'Shares'
    };
  }
</script>


<!-- DealMaker Checkout -->
<link rel="stylesheet" href="{{ asset('investment/css/dealmaker.css') }}"/>
<link rel="stylesheet" href="{{ asset('investment/css/investment-platform.css') }}"/>
<script src="{{ asset('investment/js/dealmaker.js') }}"></script>
<script src="{{ asset('investment/js/investment-platform.js') }}"></script>

<!-- Add CSRF token for AJAX requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- 
NOTE: The footer in this page is currently hardcoded but could be replaced with:
@if($footer)
    {!! $footer->content !!}
@endif
--}}

</body></html>