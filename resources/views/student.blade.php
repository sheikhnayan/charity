@php
    $url = url()->current();
    $doamin = parse_url($url, PHP_URL_HOST);
    $check = \App\Models\Website::where('domain', $doamin)->first();
    $header = \App\Models\Header::where('website_id', $check->id)->first();
    $footer = \App\Models\Footer::where('website_id', $check->id)->first();
    $setting = \App\Models\Setting::where('user_id', $check->user_id)->first();

@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $check->name ?? 'Page' }}</title>
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
    #studentTable {
        background-color: #fff !important; /* Set the table background to white */
        border: none !important; /* Remove the table border */
    }

    #studentTable th, #studentTable td {
        background-color: #fff !important; /* Set the background of table cells to white */
        border: none !important; /* Remove borders from table cells */
    }

    #studentTable tbody tr {
        background-color: #fff !important; /* Set the background of table rows to white */
    }

    #studentTable_filter {
        display: none;
    }

    #studentTable_length {
        display: none;
    }

    #studentTable thead {
        display: none; /* Hide the table header */
    }

    .non-float{
        margin-bottom: -111px;
    }

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
</style>
</head>
<body>
    @if ($header->status == 1)
        @include('layouts.nav')
    @endif

    <main style="margin-top: 6.5rem">
        <div class="banner" style="background: url({{ asset('/uploads/'.$check->user->setting->banner) }}); background-size: cover; min-height: 480px;">
            <div class="client-banner-content">
                <h1 class="display-3 fw-semibold text-shadow">
                    <a href="/" class="text-light">
                        {{ $check->user->setting->title }}
                    </a>
                </h1>
                <h2 class="text-light text-shadow mt-2">
                    {{ $check->user->setting->sub_title }}
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mt-4 mb-4" style="font-size: 12px; padding-left: 20px; padding-right: 20px;">
                <div class="position-relative bg- p-4 rounded-3 shadow-sm border"
                    style="width: 100%; max-width: 930px; margin-inline: auto;">
                    <div class="row gy-3 ">
                        <div class="col-lg-3 d-flex align-items-center">
                            <div class="rounded-profile-picture border border-3 border-primary mx-auto"
                                style="border-radius: 50%; border-color: #2e4053 !important; overflow: hidden;">
                                <img src="{{ asset($data->photo ?? null) }}"
                                    style="width: 80px; min-width: 80px; height: 80px; min-height: 80px;">
                            </div>
                        </div>

                        <div class="col-lg-9 d-flex flex-column justify-content-center">
                            <h2 class="fs-1.25 fw-semibold text-center text-lg-start break-all" style="font-size: 1.25rem;">
                                {{ $data->name }}
                            </h2>
                            <span class="opacity-75 text-center text-lg-start mt-2"></span>
                            <div class="progress mt-3" role="progressbar"
                                aria-valuenow="{{ $data->donations->sum('amount') }}" aria-valuemin="0"
                                aria-valuemax="{{ $data->goal }}" data-primary-color="#2e4053"
                                data-secondary-color="#b7bcc4" data-duration="5" data-goal-reached="true"
                                style="height: 14px">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                    style="width: @if($data->goal > 0){{ ($data->donations->sum('amount') / $data->goal)*100 }}@else 1 @endif%">
                                    <span style="font-size: 13px; font-weight: bold; margin-top: -2px;"> @if($data->goal > 0){{ round(($data->donations->sum('amount') / $data->goal)*100) }}@else 1 @endif% </span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block text-center mt-2">
                                @php
                                    $to = $data->donations->sum('amount');
                                @endphp
                                ${{ $to }} <small class="opacity-75 fw-light">of</small> ${{ $data->goal ?? 0 }}
                                <small class="opacity-75 fw-light">raised</small>
                            </span>
                        </div>
                    </div>
                    <span class="position-absolute top-0 end-0 m-2 opacity-50 small">
                        Last updated {{ $data->updated_at->diffForHumans() }}
                    </span>
                    <a href="/profile/{{ $data->id }}-{{ $data->name }}-{{ $data->last_name }}"
                        class="" target="_blank"></a>
                </div>
            </div>
        </div>

        <section>
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-10">
                        <div class="row justify-content-center gy-3">

                            <div class="col-6 col-md-3 text-center position-relative">
                                <i class="fas fa-hand-holding-usd fs-4" role="img" aria-hidden="true" style="font-size: 4rem !important;"></i>
                                <a href="#profile-donation-form" class="stretched-link d-block text-center mt-4"
                                    style="white-space:nowrap; color: #2e4053">
                                    Donate
                                    <i class="fas fa-arrow-down ms-1" role="img" aria-hidden="true"></i>
                                </a>
                            </div>

                            <div class="col-6 col-md-3 text-center position-relative">
                                <i class="fas fa-comments fs-4" role="img" aria-hidden="true" style="font-size: 4rem !important;"></i>
                                <button type="button"
                                    class="btn btn-link btn-modal stretched-link d-block mx-auto p-0 mt-4"
                                    data-action="https://gmu-events.com/ajax/profile/8100cb02-93d5-4e06-90a3-44f990caf61e/edit"
                                    style="white-space:nowrap; color: #2e4053">
                                    Send message
                                    <i class="fas fa-arrow-down ms-1" role="img" aria-hidden="true"></i>
                                </button>
                            </div>




                        </div>
                    </div>
                </div>
                <div
                    class="d-flex flex-column justify-content-center text-center p-5 h-100 text-dark rounded-4 bg-light lead w-md-85 mx-auto break-all" style="background-color: #ebebeb !important;">
                    {!! $data->description !!}
                </div>
            </div>
        </section>

        <section class="text- bg- section-border- " id="b2dd141f-e084-45c7-ba93-d8b6158d65af" data-section=""
                    style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
                    <div class="block-container container " id="block-086fc842-f2e9-4d56-af2e-be42317d11e7"
                        data-block="" data-template="7e729e7e3c534cbf918a45b5540afa84"
                        data-action=""
                        style="margin-top: 3rem;">


                        <form method="POST" action="/donations" class="donation-form-block" method="POST">
                            @csrf
                            <div class="col-12 col-md-10 col-lg-8 col-xl-6 mx-auto">
                                <div class="card border-primary shadow" style="border-width: 3px; border-color: #2e4053 !important;">
                                    <div class="card-header bg-primary border-primary rounded-0 text-center text-white fs-2"
                                        style="border-width: 3px; border-color: #2e4053 !important; background-color: #2e4053 !important;">
                                        Make a donation
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="profile_uuid" value="">

                                        <input type="hidden" name="team_uuid" value="">

                                        <div class="row gy-3">
                                            <div
                                                class="col-12 d-flex flex-column justify-content-center align-items-center">
                                                <label
                                                    for="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b2"
                                                    class="form-label " style="color: #000; font-weight: bold;">
                                                    Donate To {{$data->name}}
                                                </label>
                                                <div></div>

                                                <div class="d-flex justify-content-center flex-wrap">
                                                    <input type="hidden" data-change-amount="1"
                                                        data-name="4e963109-9506-49a8-b609-a0929944c1b2" data-amount="500"
                                                        class="form-check btn-check select-amount"
                                                        name="user_id"
                                                        id="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b24479f3e5-aac8-4044-ac77-7c3192197e63"
                                                        value="{{ $data->id }}" autocomplete="off">
                                                    {{-- <label class="btn btn-outline-primary m-1"
                                                    style="color: #2e4053 !important; border-color: #2e4053 !important;"
                                                        for="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b24479f3e5-aac8-4044-ac77-7c3192197e63">Donate
                                                        to the PTO</label> --}}
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                                        style="border-width: 2px; border-right-width: 0; border-color: #2e4053 !important;">$</span>
                                                    <input type="number" placeholder="0"
                                                        class="form-control fs-2 fs-lg-4 text-center border-primary"
                                                        style="border-width: 2px; border-color: #2e4053 !important;" name="donation_amount" value="">
                                                    <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                                        style="border-width: 2px; border-left-width: 0; border-color: #2e4053 !important;">.00</span>
                                                </div>
                                                <input type="hidden" name="amount" value="">
                                                <div class="text-center">
                                                    <small class="form-text text-muted">
                                                        * The minimum donation amount is 8.
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-center align-items-center">
                                                <div class="card border-primary shadow p-2" style="border-width: 2px; border-color: #2e4053 !important;">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="pay_fees" name="pay_fees" checked="">
                                                        <label class="form-check-label fw-semibold" for="pay_fees">
                                                            I elect to pay the fees
                                                        </label>
                                                        <i role="button"
                                                class="fa-solid fa-circle-info text-info btn-modal-info"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="By selecting this option, you elect to pay the credit card and transaction fees for this donation. The fees will be displayed in the next step."></i>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-12">
                                                <label for="first_name" class="form-label fw-semibold required">
                                                    First name
                                                </label>
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" value="">
                                            </div>

                                            <div class="col-12">
                                                <label for="last_name" class="form-label fw-semibold required">
                                                    Last name
                                                </label>
                                                <input type="text" class="form-control" id="last_name"
                                                    name="last_name" value="">
                                            </div>


                                            <div class="col-12">
                                                <label for="email" class="form-label fw-semibold required">
                                                    Email address
                                                </label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="">
                                            </div>

                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="anonymous_donation" name="anonymous_donation">
                                                    <label class="form-check-label fw-semibold" for="anonymous_donation">
                                                        Anonymous
                                                    </label>
                                                    <i role="button"
                                            class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Selecting this option will hide your name from everyone but the organizer."></i>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="leave_comment" class="form-label fw-semibold text-capitalize">
                                                    comment
                                                </label>
                                                <textarea class="form-control" id="leave_comment" name="leave_comment" rows="6"></textarea>
                                            </div>



                                            <input type="hidden" name="template"
                                                value="7e729e7e3c534cbf918a45b5540afa84">

                                            <div class="col-12">
                                                <small class="text-muted">This form is protected by reCAPTCHA and the
                                                    Google <a href="https://policies.google.com/privacy">Privacy Policy</a>
                                                    and <a href="https://policies.google.com/terms">Terms of Service</a>
                                                    apply.</small>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer bg-primary border-primary rounded-0 p-0"
                                        style="border-width: 3px; border-color: #2e4053 !important;">
                                        <button type="submit"
                                            class="btn btn-primary btn-lg w-100 h-100 text-white rounded-0 shadow-none" style="background: #2e4053 !important; border-color: #2e4053 !important;">
                                            Donate
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
        </section>

        <div class="row justify-content-center">
            <div class="col-md-8 mt-4">
                <p class="lead text-center mt-3">
                    {{ $donations->count() }} donations have been made to this User
                </p>
            </div>
            <div class="col-8 mt-4">
                <div class="row">
                    @foreach ($donations as $item)
                        <div class="col-lg-4 mt-2" style="font-size: 12px;">
                            <div class="p-3 rounded text-center position-relative" style="background: #ebebeb">
                                <h4 class="fw-semibold">
                                    ${{ $item->amount }}
                                </h4>

                                <small class="d-block opacity-75 mt-2">
                                    @if ($item->hide != 1)
                                    <span title="Donor">{{ $item->first_name }} {{ $item->last_name }}</span>
                                    @endif
                                    <i class="fa-solid fa-arrow-right-long fa-fw mx-1 text-success" aria-hidden="true"></i>
                                    <span title="Participant">{{ $item->user->name }} {{ $item->user->last_name }}</span>
                                </small>

                                @if ($item->comment)
                                    <span style="position: absolute; top: 10px; right: 10px; font-size: 17px; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#donationMessageModal-{{ $item->id }}">
                                        <i style="color: #000 !important" class="fa-solid fa-message fa-fw mx-1 text-primary" aria-hidden="true" title="Message"></i>
                                    </span>
                                    <!-- Modal for donation message -->
                                    <div class="modal" id="donationMessageModal-{{ $item->id }}" tabindex="-1" aria-labelledby="donationMessageModalLabel-{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="donationMessageModalLabel-{{ $item->id }}">
                                            {{ $item->first_name }} {{ $item->last_name }} - ${{ number_format($item->amount, 2) }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h5>{{ $item->comment ?? 'No message.' }}</h5>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                @endif


                                <small class="d-block opacity-75 mt-3 p-2 rounded" style="backdrop-filter: brightness(1.5);">
                                    <i class="fa-solid fa-calendar-days me-1" aria-hidden="true"></i>
                                        {{ $item->created_at->diffForHumans() }}
                                </small>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


    </main>

    @if ($footer->status == 1)
<footer class="standard-client-footer text-white bg-primary" data-footer="" style="
background-color: {{ $footer->background }} !important;
max-width: 100%;
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
                                <a class="nav-link active" href="/page/{{ str_replace(' ', '-', strtolower($item->name)) }}" style="color:{{ $header->color }} !important" aria-current="page">
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
                                        <i class="fa-brands fa-facebook fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">facebook</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->instagram)
                                <li class="nav-item">
                                    <a href="{{ $footer->instagram }}" target="_blank">
                                        <i class="fa-brands fa-instagram fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">instagram</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->linkedin)
                                <li class="nav-item">
                                    <a href="{{ $footer->linkedin }}" target="_blank">
                                        <i class="fa-brands fa-linkedin fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">linkedin</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->pinterest)
                                <li class="nav-item">
                                    <a href="{{ $footer->pinterest }}" target="_blank">
                                        <i class="fa-brands fa-pinterest fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">pinterest</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->x)
                                <li class="nav-item">
                                    <a href="{{ $footer->x }}" target="_blank">
                                        <i class="fa-brands fa-x-twitter fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">x</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->youtube)
                                <li class="nav-item">
                                    <a href="{{ $footer->youtube }}" target="_blank">
                                        <i class="fa-brands fa-youtube fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">youtube</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->blue_sky)
                                <li class="nav-item">
                                    <a href="{{ $footer->blue_sky }}" target="_blank">
                                        <i class="fa-solid fa-cloud fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">blue sky</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->tiktok)
                                <li class="nav-item">
                                    <a href="{{ $footer->tiktok }}" target="_blank">
                                        <i class="fa-brands fa-tiktok fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">tiktok</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif

                @if ($footer->copy_right != null)
                    <p class="text-center">
                        <small style="color: {{ $footer->color }}">
                            {{ $footer->copy_right }}
                        </small>
                    </p>
                @endif

    </div>
    @if ($footer->privacy == 1)
        <div class="row">
            <div class="col-md-12 text-center">
                <ul style="display: inline-flex; list-style: none; margin-left: 0px; margin-top: 20px; margin-bottom: 5px;">
                        <li style="margin-right: 1rem;">
                            <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->refund ? $setting->refund_page->name : '#')) }}">Refund Policy</a>
                        </li>
                        <li style="margin-right: 1rem;">
                            <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->privacy ? $setting->privacy_page->name : '#')) }}">Privacy Policy</a>
                        </li>
                        <li style="margin-right: 1rem;">
                            <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->terms ? $setting->terms_page->name : '#')) }}">Terms of service</a>
                        </li>
                    </ul>
            </div>
        </div>
    @endif
</footer>
@endif

    <!-- Include DataTables and jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Payment Funnel Tracking -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/payment-funnel-tracking.js') }}"></script>

<script>
    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'none';
        // Initialize DataTable with default search disabled
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
    });
</script>

