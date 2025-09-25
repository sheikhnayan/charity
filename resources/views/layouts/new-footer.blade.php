{{-- <link rel="stylesheet" href="{{ asset('css/new-footer.css') }}"> --}}

{{-- <style> --}}

@endif

@php
    $url = request()->getHost();
    // Fetch footer based on domain
    $website = \App\Models\Website::where('domain', $url)->first(); 
    $footer = \App\Models\Footer::where('website_id', $website ? $website->id : null)->first();
    $setting = \App\Models\Setting::where('user_id', $website ? $website->user_id : null)->first();

    // dd($setting);

@endphp

<footer id="footer" class="footer_component no-border">
    <div class="footer_container u-container z-index-1">
        <div class="footer_top_wrapper">
            <div class="footer_content_wrap _1"><a
                    href="https://{{ $website->domain }}"
                    dmr-utm-forward="1" aria-label="Go to EnergyX's homepage"
                    id="w-node-_1a8f52e2-9bf4-f242-e723-3b1fe0e365c7-e0e365c4" target="_blank"
                    class="footer_logo_link w-nav-brand"><img width="Auto" loading="lazy" alt="{{ $website->name }}"
                        src="{{ asset('/uploads/' . $setting->logo) }}"
                        class="footer_logo"></a>
                {{-- <div class="text-style-eyebrow text-color-teal" style="color: {{ $footer->color ?? '#ffffff' }} !important; margin-top: 40px;">Powering the Future</div> --}}
                <div style="color: {{ $footer->color ?? '#ffffff' }} !important; margin-top: 40px !important;">
                    {!! $footer->disclaimer_text ?? '<p>Energy Exploration Technologies has a mission to become a worldwide leader in the global transition to sustainable energy.</p>' !!}
                </div>
                <div class="spacer-small"></div>
                @if ($footer->social == 1)
                    <ul id="w-node-_1a8f52e2-9bf4-f242-e723-3b1fe0e365c9-e0e365c4" role="list" class="footer_link_list">
                        @if($footer && $footer->facebook != '#')
                        <li class="footer_link_item"><a aria-label="Facebook"
                                href="{{ $footer->facebook }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M22 12.0611C22 6.50451 17.5229 2 12 2C6.47715 2 2 6.50451 2 12.0611C2 17.0828 5.65684 21.2452 10.4375 22V14.9694H7.89844V12.0611H10.4375V9.84452C10.4375 7.32296 11.9305 5.93012 14.2146 5.93012C15.3088 5.93012 16.4531 6.12663 16.4531 6.12663V8.60261H15.1922C13.95 8.60261 13.5625 9.37822 13.5625 10.1739V12.0611H16.3359L15.8926 14.9694H13.5625V22C18.3432 21.2452 22 17.083 22 12.0611Z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->twitter != '#')
                        <li class="footer_link_item"><a aria-label="X (former twitter)" href="{{ $footer->twitter }}"
                                target="_blank" class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.1761 4H19.9362L13.9061 10.7774L21 20H15.4456L11.0951 14.4066L6.11723 20H3.35544L9.80517 12.7508L3 4H8.69545L12.6279 9.11262L17.1761 4ZM16.2073 18.3754H17.7368L7.86441 5.53928H6.2232L16.2073 18.3754Z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->instagram != '#')
                        <li class="footer_link_item"><a aria-label="Instagram" href="{{ $footer->instagram }}"
                                target="_blank" class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M16 3H8C5.23858 3 3 5.23858 3 8V16C3 18.7614 5.23858 21 8 21H16C18.7614 21 21 18.7614 21 16V8C21 5.23858 18.7614 3 16 3ZM19.25 16C19.2445 17.7926 17.7926 19.2445 16 19.25H8C6.20735 19.2445 4.75549 17.7926 4.75 16V8C4.75549 6.20735 6.20735 4.75549 8 4.75H16C17.7926 4.75549 19.2445 6.20735 19.25 8V16ZM16.75 8.25C17.3023 8.25 17.75 7.80228 17.75 7.25C17.75 6.69772 17.3023 6.25 16.75 6.25C16.1977 6.25 15.75 6.69772 15.75 7.25C15.75 7.80228 16.1977 8.25 16.75 8.25ZM12 7.5C9.51472 7.5 7.5 9.51472 7.5 12C7.5 14.4853 9.51472 16.5 12 16.5C14.4853 16.5 16.5 14.4853 16.5 12C16.5027 10.8057 16.0294 9.65957 15.1849 8.81508C14.3404 7.97059 13.1943 7.49734 12 7.5ZM9.25 12C9.25 13.5188 10.4812 14.75 12 14.75C13.5188 14.75 14.75 13.5188 14.75 12C14.75 10.4812 13.5188 9.25 12 9.25C10.4812 9.25 9.25 10.4812 9.25 12Z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->linkedin != '#')
                        <li class="footer_link_item"><a aria-label="Linkedin"
                                href="{{ $footer->linkedin }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.5 3C3.67157 3 3 3.67157 3 4.5V19.5C3 20.3284 3.67157 21 4.5 21H19.5C20.3284 21 21 20.3284 21 19.5V4.5C21 3.67157 20.3284 3 19.5 3H4.5ZM8.52076 7.00272C8.52639 7.95897 7.81061 8.54819 6.96123 8.54397C6.16107 8.53975 5.46357 7.90272 5.46779 7.00413C5.47201 6.15897 6.13998 5.47975 7.00764 5.49944C7.88795 5.51913 8.52639 6.1646 8.52076 7.00272ZM12.2797 9.76176H9.75971H9.7583V18.3216H12.4217V18.1219C12.4217 17.742 12.4214 17.362 12.4211 16.9819V16.9818V16.9816V16.9815V16.9812C12.4203 15.9674 12.4194 14.9532 12.4246 13.9397C12.426 13.6936 12.4372 13.4377 12.5005 13.2028C12.7381 12.3253 13.5271 11.7586 14.4074 11.8979C14.9727 11.9864 15.3467 12.3141 15.5042 12.8471C15.6013 13.1803 15.6449 13.5389 15.6491 13.8863C15.6605 14.9339 15.6589 15.9815 15.6573 17.0292V17.0294C15.6567 17.3992 15.6561 17.769 15.6561 18.1388V18.3202H18.328V18.1149C18.328 17.6629 18.3278 17.211 18.3275 16.7591V16.759V16.7588C18.327 15.6293 18.3264 14.5001 18.3294 13.3702C18.3308 12.8597 18.276 12.3563 18.1508 11.8627C17.9638 11.1286 17.5771 10.5211 16.9485 10.0824C16.5027 9.77019 16.0133 9.5691 15.4663 9.5466C15.404 9.54401 15.3412 9.54062 15.2781 9.53721L15.2781 9.53721L15.2781 9.53721C14.9984 9.52209 14.7141 9.50673 14.4467 9.56066C13.6817 9.71394 13.0096 10.0641 12.5019 10.6814C12.4429 10.7522 12.3852 10.8241 12.2991 10.9314L12.2991 10.9315L12.2797 10.9557V9.76176ZM5.68164 18.3244H8.33242V9.76733H5.68164V18.3244Z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->youtube != '#')
                        <li class="footer_link_item"><a aria-label="YouTube"
                                href="{{ $footer->youtube }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->tiktok != '#')
                        <li class="footer_link_item"><a aria-label="TikTok"
                                href="{{ $footer->tiktok }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-.88-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->pinterest != '#')
                        <li class="footer_link_item"><a aria-label="Pinterest"
                                href="{{ $footer->pinterest }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.219-.359-1.219c0-1.142.662-1.997 1.482-1.997.699 0 1.037.219 1.037 1.142 0 .695-.219 1.735-.359 2.692-.199.937.219 1.697 1.142 1.697 1.367 0 2.426-1.459 2.426-3.561 0-1.855-1.337-3.158-3.244-3.158-2.219 0-3.518 1.657-3.518 3.378 0 .662.255 1.378.574 1.774.062.074.07.139.052.215-.057.239-.184.749-.209.854-.033.139-.107.169-.246.102-1.268-.588-2.07-2.426-2.07-3.913 0-2.447 1.774-4.692 5.11-4.692 2.686 0 4.774 1.915 4.774 4.468 0 2.665-1.678 4.81-4.009 4.81-.784 0-1.522-.408-1.774-.896 0 0-.388 1.478-.483 1.84-.175.675-.647 1.522-.963 2.035.726.225 1.497.345 2.292.345 6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->blue_sky != '#')
                        <li class="footer_link_item"><a aria-label="BlueSky"
                                href="{{ $footer->blue_sky }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.4 5.2C4.8 2.8 7.2 1.6 9.6 2.4c2.4.8 3.6 2.8 3.6 5.2 0 2.4-1.2 4.4-3.6 5.2-2.4.8-4.8-.4-7.2-2.8zM21.6 5.2c-2.4-2.4-4.8-3.6-7.2-2.8-2.4.8-3.6 2.8-3.6 5.2 0 2.4 1.2 4.4 3.6 5.2 2.4.8 4.8-.4 7.2-2.8z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                    </ul>
                @endif
            </div>
            <div class="footer_content_wrap _2">
                <div class="footer_content_heading">
                    <div class="text-style-eyebrow" style="color: {{ $footer->color ?? '#ffffff' }} !important;">Contact Us</div>
                </div><a aria-label="Email {{ $website->name }}" href="mailto:{{$website->user->email}}"
                    class="link_wrap not-allcaps w-inline-block">
                    <div class="link_icon icon-embed-xxsmall w-embed"><svg xmlns="http://www.w3.org/2000/svg"
                            height="1em" viewBox="0 0 512 512">
                            <path style="color: {{ $footer->color ?? '#ffffff' }} !important;"
                                d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"
                                fill="currentColor"></path>
                        </svg></div>
                    <div style="color: {{ $footer->color ?? '#ffffff' }} !important;">{{$website->user->email}}</div>
                </a>
            </div>
        </div>
        <div class="footer_line_divider"></div>
        {{-- <div class="sources_wrap">
            <p class="sources_text"><span class="text-style-eyebrow">Sources:</span><br><br>*Estimates are based on the
                Preliminary Feasibility Study (PFS) conducted for EnergyX's lithium project. <a
                    href="https://energyx.docsend.com/view/qi436qnghtzwmae6"
                    aria-label="Go to EnergyX 2025 presentation." target="_blank" class="text-style-link">View the full
                    PFS summary here.</a></p>
            <div class="spacer-xsmall"></div>
            <div class="rich_content w-richtext">
                <ol start="" role="list" class="sources_list">
                    <li class="sources_list_item"><a
                            href="https://www.morningstar.com/stocks/we-forecast-40-global-ev-adoption-rate-by-2030-up-10-2022"
                            class="text-style-link">Morningstar EV Adoption Rate Forecast</a></li>
                    <li class="sources_list_item"><a
                            href="https://www.renewableenergyworld.com/storage/global-energy-storage-market-could-grow-to-546b-says-analyst/#gref"
                            aria-label="Renewable Energy World" class="text-style-link">Renewable Energy World</a>
                    </li>
                    <li class="sources_list_item"><a
                            href="https://www.iea.org/data-and-statistics/charts/total-lithium-demand-by-sector-and-scenario-2020-2040"
                            aria-label="International Energy Agency" class="text-style-link">International Energy
                            Agency</a></li>
                    <li class="sources_list_item"><a
                            href="https://dimensionmarketresearch.com/report/energy-storage-market/"
                            class="text-style-link">Energy Storage Market</a></li>
                </ol>
            </div>
        </div> --}}
        {{-- <div class="footer_line_divider"></div> --}}
        <div class="disclaimer_wrap text-size-tiny text-color-secondary">
           {!! $footer->description_text !!}
        </div>
        <div class="footer_line_divider"></div>
        <div class="footer_bottom_wrapper">
            <ul id="w-node-_1a8f52e2-9bf4-f242-e723-3b1fe0e36600-e0e365c4" role="list" class="footer_link_list">
                @if ($setting->refund)
                    <li class="footer_link_item"><a href="/page/{{ str_replace(' ', '-', strtolower($setting->refund ? $setting->refund_page->name : '#')) }}"
                            aria-label="Read privacy policy" target="_blank" class="footer_legal_link">Refund policy</a>
                    </li>
                @endif
                @if ($setting->privacy)
                    <li class="footer_link_item"><a href="/page/{{ str_replace(' ', '-', strtolower($setting->privacy ? $setting->privacy_page->name : '#')) }}"
                            aria-label="Read privacy policy" target="_blank" class="footer_legal_link">Privacy policy</a>
                    </li>
                @endif
                @if ($setting->terms)
                    <li class="footer_link_item"><a href="/page/{{ str_replace(' ', '-', strtolower($setting->terms ? $setting->terms_page->name : '#')) }}"
                            aria-label="Read privacy policy" target="_blank" class="footer_legal_link">Terms of service</a>
                    </li>
                @endif
            </ul>
            <div id="w-node-_1a8f52e2-9bf4-f242-e723-3b1fe0e365fe-e0e365c4" class="footer_credit_text" style="color: {{ $footer->color ?? '#ffffff' }} !important;">{{$website->investment_disclaimer}}</div>
        </div>
    </div>
    <div class="footer_bg_image_wrap"><img width="1920" sizes="(max-width: 1920px) 100vw, 1920px"
            alt="Footer background image for desktop"
            src="{{ $footer->background_image_desktop ? asset('uploads/' . $footer->background_image_desktop) : 'https://cdn.prod.website-files.com/615c7704bf83fe0f0bb27c0b/685affc19009a2f3c9ecd550_iStock-520365936_a.webp' }}"
            loading="lazy"
            class="footer_bg_image_desktop"><img width="720" sizes="(max-width: 720px) 100vw, 720px"
            alt="Footer background image for mobile"
            src="{{ $footer->background_image_mobile ? asset('uploads/' . $footer->background_image_mobile) : 'https://cdn.prod.website-files.com/615c7704bf83fe0f0bb27c0b/688060eaaffeb257c1461eca_Sky-Mobile.webp' }}"
            loading="lazy"
            class="footer_bg_image_mobile">
        <div class="footer_bg_overlay"></div>
    </div>
    <div data-wf--powered-by-dealmaker--variant="homepage" class="powered_by_dm_wrap"><a aria-label="Go to DealMaker"
            href="https://{{ $website->domain }}" target="_blank" class="powered_by_dm_link w-inline-block"><img
                width="160" height="51.5" alt="{{ $website->name }} logo"
                src="{{ asset('/uploads/' . $setting->logo) }}"
                loading="lazy" class="powered_by_dm_logo"></a></div>
</footer>
