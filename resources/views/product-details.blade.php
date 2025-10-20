<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{$ticket->name}}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    /* ---- Reset & Base ---- */
    :root{--accent:#0066cc;--muted:#6b7280;--bg:#f5f6f7;--card:#ffffff;--radius:12px;--page-max:1180px}
    *{box-sizing:border-box}
    html,body{height:100%}
    body{font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background:var(--bg); color:#111;margin:0;-webkit-font-smoothing:antialiased}
    a{color:inherit;text-decoration:none}
    img{display:block;max-width:100%}

    /* ---- Top global header (like ebay top navigation) ---- */
    .topbar{background:#fff;border-bottom:1px solid #e7e7ea;padding:10px 18px;display:flex;align-items:center;gap:14px}
    .brand{font-weight:700;color:var(--accent);font-size:20px}
    .top-search{flex:1;display:flex;gap:8px;align-items:center}
    .top-search input{flex:1;padding:10px 12px;border:1px solid #eaeaec;border-radius:8px}
    .top-actions{display:flex;gap:14px;align-items:center;color:var(--muted);font-size:14px}

    /* ---- Breadcrumb / utility row ---- */
    .utility{max-width:var(--page-max);margin:12px auto;padding:0 18px;display:flex;justify-content:space-between;align-items:center;font-size:13px;color:var(--muted)}

    /* ---- Main layout ---- */
    .container{max-width:var(--page-max);margin:0 auto;padding:0 18px}
    .grid{display:grid;grid-template-columns:1fr 360px;gap:28px;margin-top:12px}

    /* ---- Left column - gallery + product details ---- */
    .gallery-wrap{background:var(--card);border-radius:12px;padding:18px;border:1px solid #e9e9ea}
    .gallery-top{display:flex;gap:18px}
    .thumbs{width:84px;display:flex;flex-direction:column;gap:12px}
    .thumbs button{background:transparent;border:0;padding:0;cursor:pointer}
    .thumbs img{width:72px;height:72px;object-fit:cover;border-radius:8px;border:2px solid transparent}
    .thumbs img.active{border-color:var(--accent)}
    .main-media{flex:1;background:#fff;border-radius:10px;padding:18px;display:flex;align-items:center;justify-content:center;border:1px solid #f0f0f1}
    .main-media img{max-width:100%;max-height:540px;border-radius:8px}
    .media-controls{display:flex;align-items:center;gap:8px;margin-top:10px}
    .media-controls button{padding:8px 10px;border-radius:8px;border:1px solid #e6e6e8;background:#fff;cursor:pointer}

    /* ---- Right column - product panel ---- */
    .panel{background:var(--card);border-radius:12px;padding:18px;border:1px solid #e9e9ea;position:sticky;top:20px}
    .title{font-size:20px;font-weight:700;margin-bottom:6px}
    .subtitle{color:var(--muted);font-size:13px;margin-bottom:12px}
    .price{font-size:32px;color:#111;font-weight:800;margin-bottom:8px}
    .condition{font-size:13px;color:var(--muted);margin-bottom:10px}

    .qty-row{display:flex;gap:12px;align-items:center;margin-bottom:12px}
    .qty-row input{width:72px;padding:8px;border-radius:8px;border:1px solid #e6e6e8;text-align:center}

    .btn{display:inline-flex;align-items:center;justify-content:center;padding:12px 14px;border-radius:10px;font-weight:700;cursor:pointer}
    .btn.primary{background:var(--accent);color:#fff;border:0}
    .btn.ghost{background:#fff;border:1px solid #d9d9db;color:var(--accent)}

    .panel .small{font-size:13px;color:var(--muted);margin-top:10px}
    .payment-icons{display:flex;gap:8px;margin-top:8px}
    .payment-icons span{background:#fff;padding:6px 8px;border-radius:8px;border:1px solid #efeff0;font-size:12px}

    /* ---- Badge / stats row ---- */
    .stats{display:flex;gap:12px;align-items:center;margin-top:12px}
    .stat{background:#fbfbfc;padding:8px;border-radius:8px;border:1px solid #f0f0f1;font-size:13px}

    /* ---- Similar / explore sections ---- */
    .section{margin-top:20px}
    .section h3{font-size:16px;margin:0 0 12px}
    .cards{display:flex;gap:12px;overflow:auto;padding-bottom:6px}
    .card{background:#fff;padding:10px;border-radius:10px;min-width:200px;border:1px solid #eee}
    .card img{height:120px;object-fit:cover;border-radius:8px}
    .card .meta{padding-top:10px;font-size:13px;color:var(--muted)}

    /* ---- Item specifics ---- */
    .specs{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:18px}
    .spec{background:#fff;padding:14px;border-radius:8px;border:1px solid #efeff0}
    .spec strong{display:block;margin-bottom:6px}

    /* ---- Detailed description / long content ---- */
    .desc{background:#fff;padding:18px;border-radius:10px;border:1px solid #efeff0;margin-top:18px}
    .desc h4{margin-top:0}

    /* ---- Seller box + ratings ---- */
    .seller-panel{background:#fff;padding:14px;border-radius:10px;border:1px solid #efeff0}
    .seller-box{display:flex;gap:12px;align-items:center}
    .avatar{width:64px;height:64px;border-radius:999px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px}
    .seller-meta{font-size:14px}
    .rating-bars{margin-top:12px}
    .rating-row{display:flex;align-items:center;gap:8px;margin-bottom:6px}
    .rating-row .bar{height:8px;background:#f0f1f3;border-radius:8px;flex:1;overflow:hidden}
    .rating-row .bar .fill{height:100%;background:var(--accent);width:60%}
    .rating-row span{width:48px;text-align:right;font-size:13px;color:var(--muted)}

    /* ---- Extra banner / similar items from stores ---- */
    .promo{background:#fff;padding:18px;border-radius:12px;border:1px solid #efeff0;margin-top:18px;display:flex;align-items:center;justify-content:space-between}
    .promo .thumbs{display:flex;gap:8px}
    .promo img{width:84px;height:84px;object-fit:cover;border-radius:8px}

    /* ---- Footer ---- */
    footer{margin-top:26px;padding:22px 0;text-align:left;color:var(--muted);font-size:13px}

    /* ---- Utilities / responsive ---- */
    .muted{color:var(--muted)}
    .small{font-size:13px;color:var(--muted)}

    @media (max-width:1100px){.grid{grid-template-columns:1fr}.panel{position:static}}
    @media (max-width:520px){.thumbs{display:none}.main-media img{max-height:320px}}



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
<body>
  <!-- Topbar replicating eBay-like header (no logos) -->
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
                        <div class="col-6 col-md-auto">
                            <div class="contact-item me-4 mb-1">
                                <i class="fas fa-envelope me-2" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};"></i>
                                <a href="mailto:{{ $header->contact_email }}" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};">
                                    {{ $header->contact_email }}
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($header->contact_address)
                        <div class="col-3 col-md-auto">
                            <div class="contact-item mb-1">
                                <i class="fas fa-map-marker-alt me-2" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};"></i>
                                <span style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }}; text-decoration : underline !important;">
                                    {{ $header->contact_address }}
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

  <main class="container" style="margin-top: 14rem;">
    <div class="grid">
      <!-- LEFT: Gallery, similar, specifics, description -->
      <section>
        <div class="gallery-wrap" id="galleryWrap">
          <div class="gallery-top">
            <div class="thumbs" id="thumbsCol">
              <!-- thumbnails (use same source multiple times in demo) -->
              @foreach ($ticket->images as $item)
                  <button aria-label="thumbnail {{ $loop->index + 1 }}">
                      <img src="/{{ $item->image_path }}" data-full="/{{ $item->image_path }}" {{ $loop->index == 0 ? 'class=active' : '' }} alt="thumb{{ $loop->index + 1 }}">
                  </button>
              @endforeach
            </div>

            <div class="main-media" id="mainMedia">
              <img id="mainImg" src="/{{ $ticket->images[0]->image_path }}" alt="main product" />
            </div>
          </div>

          <div class="media-controls">
            <button id="zoomBtn">🔍 Zoom</button>
            <button id="prevBtn">◀</button>
            <button id="nextBtn">▶</button>
          </div>

          <!-- Similar items (carousel) -->
          <div class="section">
            <h3>Products from {{ $ticket->user->website->name }}</h3>
            <div class="cards" id="similarCards">
              <!-- example repeated cards to match screenshot -> in real page these would be separate images/text -->
              @php
                $similar = \App\Models\Ticket::where('user_id',$ticket->user->id)->where('type','product')->get();
              @endphp
              @foreach ($similar as $item)
              @if ($item->id != $ticket->id)
                  <a href="/product/{{ $item->id }}">
                    <div class="card"><img src="/{{ $item->image }}" alt="{{$item->name}}" style="width: 100%"><div class="meta">{{$item->name}}<br><strong>${{ number_format($item->price, 2) }}</strong></div></div>
                  </a>
              @endif
              @endforeach
            </div>
          </div>

          <!-- Explore related items (grid) -->
          {{-- <div class="section">
            <h3>Explore related items</h3>
            <div class="cards" style="gap:18px">
              <div class="card" style="min-width:160px"><img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png"><div class="meta">Gold Tone Ring<br><strong>$12.99</strong></div></div>
              <div class="card" style="min-width:160px"><img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png"><div class="meta">Blue Gem Ring<br><strong>$14.99</strong></div></div>
              <div class="card" style="min-width:160px"><img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png"><div class="meta">Rose Design<br><strong>$10.50</strong></div></div>
              <div class="card" style="min-width:160px"><img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png"><div class="meta">Vintage Leaf Ring<br><strong>$11.20</strong></div></div>
            </div>
          </div> --}}

          <!-- Item specifics table matching screenshot columns -->
          <div class="specs" aria-label="Item specifics">
            @foreach ($ticket->features as $item)
                <div class="spec"><strong>{{ $item->name }}</strong>{{ $item->value }}</div>
            @endforeach
            {{-- <div class="spec"><strong>Condition</strong>New without tags</div> --}}
          </div>

          <!-- Item description (long) -->
          <div class="desc" id="desc">
            <h4>Item description from the seller</h4>
            {!! $ticket->description !!}
          </div>

        </div>

        <!-- More long sections to match full page length: seller feedback, similar from stores, etc. -->
        <div style="height:18px"></div>

        {{-- <div class="section">
          <h3>Seller feedback</h3>
          <div class="seller-panel" style="margin-top:10px">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
              <div style="flex:1;min-width:220px">
                <div style="display:flex;align-items:center;gap:12px">
                  <div class="avatar">G</div>
                  <div>
                    <div style="font-weight:700">Gravity Standard</div>
                    <div class="small muted">Seller since: 2018 • Feedback score: 237,200</div>
                  </div>
                </div>
                <div style="margin-top:12px" class="small muted">Top-rated seller • Ships from: China</div>
              </div>

              <div style="min-width:260px">
                <div style="display:flex;gap:8px;align-items:center;justify-content:flex-end">
                  <div class="small muted">Detailed seller ratings</div>
                </div>
                <div class="rating-bars" style="margin-top:8px">
                  <div class="rating-row"><div class="small muted">Communication</div><div class="bar"><div class="fill" style="width:85%"></div></div><span class="small muted">4.8</span></div>
                  <div class="rating-row"><div class="small muted">Shipping time</div><div class="bar"><div class="fill" style="width:78%"></div></div><span class="small muted">4.6</span></div>
                  <div class="rating-row"><div class="small muted">Item as described</div><div class="bar"><div class="fill" style="width:82%"></div></div><span class="small muted">4.7</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="section">
          <h3>Similar items from eBay Stores</h3>
          <div class="cards" style="margin-top:12px">
            <div class="card" style="min-width:160px"><img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png"><div class="meta">Seller A Collection<br><strong>$9.99</strong></div></div>
            <div class="card" style="min-width:160px"><img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png"><div class="meta">Sparkle Gems<br><strong>$11.20</strong></div></div>
            <div class="card" style="min-width:160px"><img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png"><div class="meta">Ring Boutique<br><strong>$7.50</strong></div></div>
          </div>
        </div>

        <div class="promo">
          <div>
            <div style="font-weight:700;font-size:18px">Extra 8% off</div>
            <div class="small muted" style="margin-top:6px">Shop now for more ring styles</div>
          </div>
          <div style="display:flex;gap:8px;align-items:center">
            <img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png" alt="promo1"/>
            <img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png" alt="promo2"/>
            <img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png" alt="promo3"/>
          </div>
        </div> --}}

      </section>

      <!-- RIGHT: Product purchase panel + seller quick box -->
      <aside>
        <div class="panel" role="region" aria-label="purchase panel">
          <div class="title">{{$ticket->name}}</div>
          <div class="subtitle">Sold by <strong>{{$ticket->user->website->name}}</strong></div>
          <div class="price">US ${{ number_format($ticket->price, 2) }}</div>
          <div class="condition">Condition: <strong>New without tags</strong></div>

          <form action="/tickets" method="post">
          @csrf
          <div style="height:8px"></div>
          <input type="hidden" name="ticket[{{ $ticket->id }}][id]" value="{{ $ticket->id }}">
          <div class="qty-row">
            <label for="qty" class="small">Size</label>
            <div style="display:flex;align-items:center;gap:8px">
              @php
                $sizes = explode(',',$ticket->size);
                // dd($sizes);
              @endphp
              <button class="btn" id="dec"> </button>

              <select name="ticket[{{ $ticket->id }}][size]" id="" class="form-control" style="margin-left: 2rem; width: 4.5rem; text-align: center;">
                @foreach($sizes as $size)
                  <option value="{{ trim($size) }}">{{ trim($size) }}</option>
                @endforeach
              </select>
              {{-- <input id="qty" type="number" min="1" value="1" max="{{$ticket->quantity}}" aria-label="quantity" name="ticket[{{ $ticket->id }}][quantity]"> --}}
            </div>
          </div>
          <div class="qty-row">
            <label for="qty" class="small">Quantity</label>
            <div style="display:flex;align-items:center;gap:8px">
              <button class="btn" id="dec">-</button>
              <input id="qty" type="number" min="1" value="1" max="{{$ticket->quantity}}" aria-label="quantity" name="ticket[{{ $ticket->id }}][quantity]">
              <button class="btn" id="inc">+</button>
            </div>
          </div>
          
          <div style="display:flex;gap:8px;margin-bottom:10px">
            <button class="btn primary">Buy It Now</button>
            {{-- <button class="btn ghost">Add to cart</button> --}}
          </div>
        </form>

          {{-- <div class="small muted">People watch: 334 people are watching this.</div>
          <div style="height:10px"></div>

          <div class="small muted">Shipping: <strong>US $0.00</strong> • Estimated delivery: 7-18 Oct</div> --}}

          <div style="height:10px"></div>

          <div class="small muted">Return policy: 30-day returns. See details.</div>

          <div style="height:12px"></div>

          <div class="small muted">Payment methods</div>
          <div class="payment-icons">
            <span>VISA</span>
            <span>Mastercard</span>
            <span>PayPal</span>
            <span>Apple Pay</span>
          </div>

          {{-- <div style="height:12px;border-top:1px solid #f0f0f1;margin-top:12px;padding-top:12px">
            <div class="small muted">Delivery</div>
            <div class="small muted">Ships from: China • Import charges may apply</div>
          </div> --}}
        </div>

        <div class="section">
          <div class="seller-panel" style="margin-top:16px">
            <h3 style="margin:0 0 8px">About this seller</h3>
            <div class="seller-box">
              <div class="avatar"> <img src="/uploads/{{$ticket->user->website->setting->logo}}" alt="{{$ticket->user->website->name}}'s avatar"> </div>
              <div class="seller-meta">
                <div style="font-weight:700">{{$ticket->user->website->name}}</div>
                {{-- <div class="small muted">Feedback: {{$ticket->user->feedback_count}}</div> --}}
                {{-- <div style="margin-top:8px"><button class="btn ghost">Visit store</button></div> --}}
              </div>
            </div>
          </div>
        </div>

      </aside>
    </div>

  </main>
  @if ($footer && $footer->status == 1)
     @include('layouts.new-footer')
 @endif

  <script>
    // --- Gallery thumbnail interactions ---
    (function(){
      const thumbs = document.querySelectorAll('#thumbsCol img');
      const mainImg = document.getElementById('mainImg');
      let current = 0;
      thumbs.forEach((t,i)=>{
        t.addEventListener('click', ()=>{
          thumbs[current].classList.remove('active');
          t.classList.add('active');
          mainImg.src = t.dataset.full || t.src;
          current = i;
        });
      });

      document.getElementById('prevBtn').addEventListener('click', ()=>{
        const next = (current - 1 + thumbs.length) % thumbs.length;
        thumbs[next].click();
      });
      document.getElementById('nextBtn').addEventListener('click', ()=>{
        const next = (current + 1) % thumbs.length;
        thumbs[next].click();
      });

      document.getElementById('zoomBtn').addEventListener('click', ()=>{
        const url = mainImg.src;
        // open image in new tab for zoom demo
        window.open(url, '_blank');
      });
    })();

    // --- Quantity controls ---
    (function(){
      const inc = document.getElementById('inc');
      const dec = document.getElementById('dec');
      const qty  = document.getElementById('qty');
      inc.addEventListener('click', ()=>{qty.value = Math.max(1, parseInt(qty.value||1)+1)});
      dec.addEventListener('click', ()=>{qty.value = Math.max(1, parseInt(qty.value||1)-1)});
    })();

    // --- Similar cards keyboard nav ---
    (function(){
      document.addEventListener('keydown', (e)=>{
        const sc = document.getElementById('similarCards');
        if(!sc) return;
        if(e.key === 'ArrowLeft') sc.scrollBy({left:-220,behavior:'smooth'});
        if(e.key === 'ArrowRight') sc.scrollBy({left:220,behavior:'smooth'});
      });
    })();
  </script>
</body>
</html>
