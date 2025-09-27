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
        $website = $check;
        $groups = \App\Models\User::where('website_id', $check->id)->where('role', 'group_leader')->get();
        $user = \App\Models\User::where('id', $check->user_id)->first();
    } else {
        $setting = null;
        $header = null;
        $footer = null;
        $website = null;
        $groups = collect();
        $user = null;
    }
@endphp

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting && $setting->company_name ? $setting->company_name . ' | Thank You!' : 'Thank You!' }}</title>
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400|Montserrat:700' rel='stylesheet' type='text/css'>
    <style>
        @import url(//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css);
        @import url(//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);
    </style>
    <link rel="stylesheet" href="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/default_thank_you.css">
    <script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/jquery-1.9.1.min.js"></script>
    <script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/html5shiv.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
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
    </style>
</head>

<body style="padding: 0px">
    @endphp
    @if ($header->status == 1)
        @include('layouts.nav')
    @endif
    <header class="site-header" id="header" style="padding-top: 
    @if($check->isInvestment())
    6rem
    @else
    3rem
    @endif
    ">
        <h1 class="site-header__title" data-lead-id="site-header-title" style="text-align: center;">THANK YOU!</h1>
    </header>

    <div class="main-content" style="text-align: center; padding-bottom: 4.3rem;">
        <i class="fa fa-check main-content__checkmark" id="checkmark"></i>
        <p class="main-content__body p-4" data-lead-id="main-content-body">Your payment was successful. A confirmation email
            with the transaction details has been sent to your provided email address. We truly appreciate your trust
            in us.</p>
        <p class="main-content__body p-4" data-lead-id="main-content-body">If you have any questions or need further
            assistance, feel free to contact our support team.</p>
    </div>



    @if ($check && $check->isInvestment() && $footer && $footer->status == 1)
        @include('layouts.new-footer')
    @elseif ($footer && $footer->status == 1)
            <footer class="standard-client-footer text-white bg-primary" data-footer="" style="
        background-color: {{ $footer->background }} !important;
        ">
                <div class="container">

                    <p class="lead text-center pt-4" style="color: {{ $footer->color }} !important">
                        {{ $footer->message }}
                    </p>
                    @if ($footer->menu == 1)
                        <div class="nav justify-content-center">
                            @foreach ($check->pages->sortBy('position') as $item)

                                @if($item->status == 1)

                                    <div class="nav-item">
                                        <a class="nav-link active" href="/page/{{ str_replace(' ', '-', strtolower($item->name)) }}"
                                            style="color:{{ $footer->color }} !important" aria-current="page">
                                            {{ $item->name }}
                                        </a>
                                    </div>
                                @endif

                            @endforeach
                        </div>
                    @endif

                    @if ($footer->social == 1)
                        <ul class="nav justify-content-center footer-socials mt-4 mb-4">
                            @if ($footer->facebook)
                                <li class="nav-item">
                                    <a href="{{ $footer->facebook }}" target="_blank">
                                        <i class="fa-brands fa-facebook fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">facebook</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->instagram)
                                <li class="nav-item">
                                    <a href="{{ $footer->instagram }}" target="_blank">
                                        <i class="fa-brands fa-instagram fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">instagram</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->linkedin)
                                <li class="nav-item">
                                    <a href="{{ $footer->linkedin }}" target="_blank">
                                        <i class="fa-brands fa-linkedin fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">linkedin</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->pinterest)
                                <li class="nav-item">
                                    <a href="{{ $footer->pinterest }}" target="_blank">
                                        <i class="fa-brands fa-pinterest fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">pinterest</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->x)
                                <li class="nav-item">
                                    <a href="{{ $footer->x }}" target="_blank">
                                        <i class="fa-brands fa-x-twitter fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">x</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->youtube)
                                <li class="nav-item">
                                    <a href="{{ $footer->youtube }}" target="_blank">
                                        <i class="fa-brands fa-youtube fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">youtube</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->blue_sky)
                                <li class="nav-item">
                                    <a href="{{ $footer->blue_sky }}" target="_blank">
                                        <i class="fa-solid fa-cloud fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">blue sky</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->tiktok)
                                <li class="nav-item">
                                    <a href="{{ $footer->tiktok }}" target="_blank">
                                        <i class="fa-brands fa-tiktok fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">tiktok</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif

                    @if ($footer->copy_right != null)
                        <p class="text-center" style="margin-bottom: 0px;">
                            <small style="color: {{ $footer->color }}">
                                {{ $footer->copy_right }}
                            </small>
                        </p>
                    @endif
                </div>
                @if ($footer->privacy == 1)
                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <ul
                                style="display: inline-flex; list-style: none; margin-left: 0px; margin-top: 20px; margin-bottom: 5px;">
                                <li style="margin-right: 1rem;">
                                    <a style="color: #1773b0; text-decoration: underline;"
                                        href="/page/{{ str_replace(' ', '-', strtolower($setting->refund ? $setting->refund_page->name : '#')) }}">Refund
                                        Policy</a>
                                </li>
                                <li style="margin-right: 1rem;">
                                    <a style="color: #1773b0; text-decoration: underline;"
                                        href="/page/{{ str_replace(' ', '-', strtolower($setting->privacy ? $setting->privacy_page->name : '#')) }}">Privacy
                                        Policy</a>
                                </li>
                                <li style="margin-right: 1rem;">
                                    <a style="color: #1773b0; text-decoration: underline;"
                                        href="/page/{{ str_replace(' ', '-', strtolower($setting->terms ? $setting->terms_page->name : '#')) }}">Terms
                                        of service</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </footer>
    @endif

</body>

</html>
