<!-- Navigation Bar -->
    <nav class="navbar navbar-expand-xl {{ $header->floating == 1 ? 'fixed-top' : 'non-float'}} bg-primary" style="background-color: {{ $header->background }} !important;">
        <div class="container">
            <a class="navbar-brand" href="https://{{ $check->domain }}">
                <img src="{{ asset('uploads/'.$setting->logo) }}" alt="Logo" width="{{ $header->logo_size }}" height="{{ $header->logo_size }}" class="d-inline-block align-text-top" style="width: {{ $header->logo_size }}px !important; height: {{ $header->logo_size }}px !important;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="color:{{ $header->color }} !important; border-color: {{ $header->color }} !important;">
                <i class="fa fa-bars" style="color: {{ $header->color }}; font-size: 1.5rem;"></i>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    @if ($check->type == 'investment')
                        {{-- Investment website: Use sections from page builder --}}
                        @if(isset($menuSections) && is_array($menuSections))
                            @foreach ($menuSections as $section)
                                <li class="nav-item">
                                    <a class="nav-link active scroll-to-section" aria-current="page" href="#{{ $section['sectionId'] }}" style="color:{{ $header->color }} !important;" data-section="{{ $section['sectionId'] }}">{{ $section['title'] }}</a>
                                </li>
                            @endforeach
                        @endif
                    @else
                        {{-- Fundraiser website: Multi-page navigation (current behavior) --}}
                        @foreach ($check->pages->sortBy('position') as $item)
                            @if ($item->status == 1)
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
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
    @endif
