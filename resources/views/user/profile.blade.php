@extends('user.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('user/extra.css') }}">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        .forms-wizard li.done em::before,
        .lnr-checkmark-circle::before {
            content: "\e87f";
        }

        .forms-wizard li.done em::before {
            display: block;
            font-size: 1.2rem;
            height: 42px;
            line-height: 40px;
            text-align: center;
            width: 42px;
        }

        .forms-wizard li.done em {
            font-family: Linearicons-Free;
        }
    </style>
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                    <div class="app-main__inner">
                        <div class="app-site-information">
                            <div class="main-card card">
                                <div class="card-body">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">

                                                <div class="widget-content-left me-3 d-none d-md-block">
                                                    <div class="widget-content-left">
                                                        <img width="42" class="rounded" alt="The SHPS PTO Fundraiser 2025"
                                                            src="{{ asset('uploads/' . Auth::user()->website->setting->logo) }}">
                                                    </div>
                                                </div>

                                                <div class="widget-content-left">
                                                    <div class="widget-heading">
                                                        {{ Auth::user()->website->name }}
                                                    </div>
                                                    {{-- <div class="widget-subheading">
                                                        Peer to Peer
                                                        (Premium)
                                                    </div> --}}
                                                    <div class="fs-6 mt-2">
                                                        <i class="fas fa-link link-info me-1 btn-clipboard" role="button"
                                                            data-clipboard-text="http://{{ Auth::user()->website->domain }}"></i>
                                                        <a href="http://{{ Auth::user()->website->domain }}"
                                                            class="link-info"
                                                            target="_blank">{{ Auth::user()->website->domain }}</a>
                                                    </div>
                                                </div>

                                                <div class="widget-content-right">
                                                    <div class="btn-group d-none d-md-inline-flex" role="group">
                                                        <a href="/profile/{{ Auth::user()->id }}-{{ Auth::user()->name }}-{{ Auth::user()->last_name }}"
                                                            class="btn btn-info btn-hover-info" target="_blank">
                                                            <i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i>
                                                            <span>View</span>
                                                        </a>

                                                        <button type="button" class="btn btn-success btn-hover-info"
                                                            data-bs-toggle="modal" data-bs-target="#modal-share">
                                                            <i class="fa-solid fa-share-nodes fa-fw" aria-hidden="true"></i>
                                                            <span>Share</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="app-page-title mt-4" data-step="" data-title="" data-intro="">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">

                                    <div class="page-title-icon">
                                        <i class="fas fa-id-card icon-gradient bg-arielle-smile"></i>
                                    </div>

                                    <div>
                                        <span class="text-capitalize">
                                            profile
                                        </span>
                                        <div class="page-title-subheading">
                                            Manage your profile information.
                                        </div>
                                    </div>

                                </div>
                                <div class="page-title-actions">
                                </div>
                            </div>

                            <div class="page-title-subheading opacity-10 mt-3"
                                style="white-space: nowrap; overflow-x: auto;">
                                <nav class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb">

                                        <li class="breadcrumb-item opacity-10">
                                            <a href="/users">
                                                <i class="fas fa-home" role="img" aria-hidden="true"></i>
                                                <span class="visually-hidden">Home</span>
                                            </a>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>

                                        <li class="breadcrumb-item ">
                                            Information
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            profile
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <ul class="forms-wizard profile-progress-steps">
                            <li class="done">
                                <span>
                                    <em>1</em>
                                    <span>Profile</span>
                                </span>
                            </li>
                            <li class="done">
                                <span>
                                    <em>2</em>
                                    <span>Approved</span>
                                </span>
                            </li>
                        </ul>




                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary">

                                    {{-- <a class="btn-icon btn btn-light btn-sm position-absolute top-0 end-0 m-2"
                                        href="https://gmu-events.com/dash/profile?create=profile" role="button"
                                        style="z-index: 7; width: 150px">
                                        <i class="fa-solid fa-plus btn-icon-wrapper"></i>
                                        Create new profile
                                    </a> --}}

                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-primary">
                                            <div class="menu-header-content">
                                                <div class="avatar-icon-wrapper mb-3 avatar-icon-xl">
                                                    <div class="avatar-icon">
                                                        <div class="rounded-profile-picture fill" role="img"
                                                            aria-label="{{ Auth::user()->name }} {{ Auth::user()->last_name }}"
                                                            style="background-image: url({{ asset(Auth::user()->photo) }})">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5 class="menu-header-title">
                                                    <a href="{{ Auth::user()->website->domain }}/profile/139276-sheikh-nayan"
                                                        class="link-light">
                                                        {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                                                    </a>
                                                </h5>
                                                <h6 class="menu-header-subtitle text-capitalize">
                                                    {{ Auth::user()->role }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-center-fixed-width main-card mb-4 card">
                        <div class="card-body">
                            <form action="/users/profile" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row gy-3">
                                    <input type="hidden" name="isNew" value="1">

                                    <input type="hidden" name="site_uri" value="{{ Auth::user()->website->domain }}/profile/">


                                    <input type="hidden" name="participant_type" value="individual">
                                    <div class="col-12 tab-content fundraiser-tab-content">

                                        <div class="row gy-3 tab-pane profile-tab-individual show active" role="tabpanel">
                                            <div class="col-12">
                                                <label for="individual_goal" class="form-label">Your goal</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control" id="individual_goal"
                                                        name="goal" value="{{ Auth::user()->goal }}">
                                                    <span class="input-group-text">.00 USD</span>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="individual_url" class="form-label">
                                                    Your URL
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        {{ Auth::user()->website->domain }}/profile/
                                                    </span>
                                                    <input type="text" class="form-control" id="individual_url"
                                                        name="individual_url"
                                                        value="{{ Auth::user()->id }}-{{ Auth::user()->name }}-{{ Auth::user()->last_name }}">
                                                </div>
                                            </div>
                                        </div>


                                    <div class="col-6" style="order: -2;">
                                        <label for="first_name" class="form-label required">
                                            First name
                                        </label>


                                        <input type="text" class="form-control" id="first_name" name="name"
                                            value="{{ Auth::user()->name }}">
                                    </div>











                                    <div class="col-6" style="order: -1;">
                                        <label for="last_name" class="form-label required">
                                            Last name
                                        </label>


                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            value="{{ Auth::user()->last_name }}">
                                    </div>


                                    <div class="col-12">
                                        <label for="description" class="form-label ">
                                            Enter the text that will appear on your personal fundraising page.
                                        </label>


                                        <textarea class="form-control text-editor" id="description" name="description"
                                            rows="3" style="visibility: hidden;">
                                                    {!! Auth::user()->description !!}
                                                </textarea>
                                    </div>











                                    {{-- <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="show_send_button" name="show_send_button" value="1" checked="">
                                            <label class="form-check-label " for="show_send_button">
                                                Show send message button
                                            </label>
                                            <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                data-title="Show send message button" data-description="Checking this box will allow people to send an email message by clicking a button on the profile.
                    Email addresses will not be visible on the website."></i>
                                        </div>
                                    </div> --}}











                                    {{-- <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="show_amount_raised" name="show_amount_raised" value="1" checked="">
                                            <label class="form-check-label " for="show_amount_raised">
                                                Show amount raised
                                            </label>
                                            <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                data-title="Show amount raised"
                                                data-description="The amount you raise is displayed on your personal fundraising page and on the Leaderboard.
                                                        If you don't want to show the amount you raised, uncheck this box."></i>
                                        </div>
                                    </div> --}}










                                    {{--
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="receive_donation_notification" name="receive_donation_notification"
                                                value="1" checked="">
                                            <label class="form-check-label " for="receive_donation_notification">
                                                Receive notifications of donations
                                            </label>
                                            <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                data-title="Receive notifications of donations" data-description="If checked, you will receive email notifications of donations made on your personal fundraising
                                                    page."></i>
                                        </div>
                                    </div> --}}










                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            Image(s)
                                        </h5>
                                        <img src="{{ asset(Auth::user()->photo) }}" width="150px">
                                    </div>

                                    <div class="col-12">
                                        <label for="photo" class="form-label ">
                                            Profile Photo
                                        </label>


                                        <input class="form-control" type="file" id="photo-image-file" name="photo"
                                            accept="image/png, image/gif, image/jpeg, image/jpg">
                                        <div class="form-text">The recommended format for the profile picture should be
                                            a square.</div>
                                    </div>

                                </div>

                                <div class="sticky-save-button-container">
                                    <div class="sticky-save-button-inner">
                                        <button class="btn-hover-shine btn-wide btn btn-shadow btn-success btn-lg w-100 "
                                            type="submit" id="">
                                            Save
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->

        <script>
            ClassicEditor
                .create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });
        </script>
@endsection
