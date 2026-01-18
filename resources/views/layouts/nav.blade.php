<!-- Google Analytics -->
    @php
        // Get website by domain
        $currentDomain = request()->host();
        $website = \App\Models\Website::where('domain', $currentDomain)->first();
        // dd($website);
        $gaTrackingId = $website->google_analytics_id ?? null;
    @endphp
    @if($gaTrackingId)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaTrackingId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaTrackingId }}');
    </script>
    @endif
<!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
<!-- Navigation Bar -->
    <nav class="navbar navbar-expand-xl {{ $header->floating == 1 ? 'fixed-top' : 'non-float'}} bg-primary" style="background-color: {{ $header->background }} !important;">
        <div class="container invest-mobile">
            <a class="navbar-brand" href="https://{{ $check->domain }}">
                <img src="{{ asset('uploads/'.$setting->logo) }}" alt="Logo" 
                     width="{{ $header->logo_size ?? 100 }}" 
                     height="{{ $header->logo_height ?? 60 }}" 
                     class="d-inline-block align-text-top" 
                     style="width: {{ $header->logo_size ?? 100 }}px !important; height: {{ $header->logo_height ?? 60 }}px !important; object-fit: contain;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="color:{{ $header->color }} !important; border-color: transparent !important;">
                <i class="fa fa-bars" style="color: {{ $header->color }}; font-size: 1.5rem;"></i>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    @if ($check->type == 'investment')
                        {{-- Investment website: Use sections from page builder --}}
                        @if(isset($menuSections) && is_array($menuSections))
                            @foreach ($menuSections as $section)
                                <li class="nav-item">
                                    <a class="nav-link active scroll-to-section" aria-current="page" href="#{{ $section['sectionId'] }}" style="color:{{ $header->color }} !important; text-transform: uppercase; text-decoration: none;" data-section="{{ $section['sectionId'] }}">{{ $section['title'] }}</a>
                                </li>
                            @endforeach
                        @else
                                <li class="nav-item">
                                    <a class="nav-link active" href="/" style="color:{{ $header->color }} !important; text-transform: uppercase; text-decoration: none;">Home</a>
                                </li>
                        @endif
                    @else
                        {{-- Fundraiser website: Multi-page navigation (current behavior) --}}
                        @foreach ($check->pages->sortBy('position') as $item)
                            @if ($item->status == 1 && $item->show_in_menu)
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/page/{{ str_replace(' ', '-', strtolower($item->name)) }}" style="color:{{ $header->color }} !important;">{{ $item->name }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                    {{-- <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/auction" style="color:{{ $header->color }} !important">Auction</a>
                    </li> --}}
                </ul>
            </div>
            @if($check && $check->isInvestment())
                {{-- Investment website buttons --}}
                <div style="display: flex; align-items: center; gap: 10px;">
                    {{-- Login/Registration Button (if enabled) --}}
                    @if($header && $header->show_auth_button == 1)
                        @guest
                        <button class="invest-now-btn sssssttttt" 
                                onclick="openAuthModal()" 
                                style="background-color: {{ $header->auth_button_bg_color ?? '#007bff' }} !important; color: {{ $header->auth_button_text_color ?? '#ffffff' }} !important; padding: 0.6rem !important; font-size: 0.9rem;">
                            {{ $header->auth_button_text ?? 'Login / Register' }}
                        </button>
                        @endguest
                    @endif
                    
                    {{-- Dashboard/Invest Now Button --}}
                    @auth
                    <a class="navbar-brand close-on-mobile" href="/users/donation">
                        <div class="invest-button-section">
                        <button class="invest-now-btn sssssttttt" onclick="window.location.href='/users/donation'" style="background-color: {{ $check->sticky_footer_button_bg }} !important; color: {{ $check->sticky_footer_button_text }} !important; padding: 0.6rem !important;">
                            DASHBOARD
                        </button>
                    </div>
                    </a>
                    @else
                    <a class="navbar-brand close-on-mobile" href="/invest">
                        <div class="invest-button-section">
                        <button class="invest-now-btn sssssttttt" onclick="window.location.href='/invest'" style="background-color: {{ $check->sticky_footer_button_bg }} !important; color: {{ $check->sticky_footer_button_text }} !important; padding: 0.6rem !important;">
                            {{ $header->invest_now_button_text ?? 'INVEST NOW' }}
                        </button>
                    </div>
                    </a>
                    @endauth
                </div>
            @else
                {{-- Fundraiser website buttons --}}
                <div style="display: flex; align-items: center; gap: 10px;">
                    {{-- Login/Registration Button (if enabled) --}}
                    @if($header && $header->show_auth_button == 1)
                        @guest
                        <button class="invest-now-btn sssssttttt" 
                                onclick="openAuthModal()" 
                                style="background-color: {{ $header->auth_button_bg_color ?? '#007bff' }} !important; color: {{ $header->auth_button_text_color ?? '#ffffff' }} !important; padding: 0.6rem !important; font-size: 0.9rem;">
                            {{ $header->auth_button_text ?? 'Login / Register' }}
                        </button>
                        @endguest
                    @endif
                    
                    {{-- Dashboard Button (only for authenticated fundraiser users) --}}
                    @auth
                    <a class="navbar-brand close-on-mobile" href="/users/donation">
                        <div class="invest-button-section">
                        <button class="invest-now-btn sssssttttt" onclick="window.location.href='/users/donation'" style="background-color: {{ $check->sticky_footer_button_bg }} !important; color: {{ $check->sticky_footer_button_text }} !important; padding: 0.6rem !important;">
                            DASHBOARD
                        </button>
                    </div>
                    </a>
                    @endauth
                </div>
            @endif

        </div>
    </nav>

    @if ($check->type == 'investment')
    {{-- JavaScript for smooth scrolling to sections on investment websites --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all navigation links with scroll-to-section class
            const navLinks = document.querySelectorAll('.scroll-to-section');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetSection = this.getAttribute('data-section');
                    const targetElement = document.getElementById(targetSection);
                    
                    if (targetElement) {
                        // Calculate offset for fixed header if applicable
                        const headerOffset = {{ $header->floating == 1 ? '80' : '0' }};
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                        
                        // Update active nav link
                        navLinks.forEach(navLink => navLink.classList.remove('active-section'));
                        this.classList.add('active-section');
                    }
                });
            });
            
            // Optional: Update active nav link based on scroll position
            window.addEventListener('scroll', function() {
                let current = '';
                navLinks.forEach(link => {
                    const section = document.getElementById(link.getAttribute('data-section'));
                    if (section) {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.clientHeight;
                        if (pageYOffset >= (sectionTop - 200)) {
                            current = link.getAttribute('data-section');
                        }
                    }
                });
                
                navLinks.forEach(link => {
                    link.classList.remove('active-section');
                    if (link.getAttribute('data-section') === current) {
                        link.classList.add('active-section');
                    }
                });
            });
        });
    </script>
    
    {{-- Additional CSS for active section styling --}}
    <style>
        .scroll-to-section.active-section {
            /* font-weight: bold; */
            text-decoration: underline;
        }
    </style>


<script>
    $('.nav-link').on('click', function(){
        $('.navbar-toggler').addClass('collapsed');
        $('#navbarNav').removeClass('show');
    })
</script>
    @endif

    {{-- Auth Modal Styles - Exact copy from product.blade.php --}}
    <style>
        /* Ensure modals are hidden by default and positioned correctly */
        #authModal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 99999 !important;
        }

        #authModal.hidden {
            display: none;
        }

        #authModal:not(.hidden) {
            display: flex;
        }

        /* Ensure Bootstrap modal appears on top */
        .modal-backdrop {
            z-index: 99998 !important;
        }

        .modal {
            z-index: 99999 !important;
        }
    </style>

    {{-- Auth Modal for Header Login Button --}}
    @include('partials.ticket-auth-modal')
