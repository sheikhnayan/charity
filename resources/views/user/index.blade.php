@extends('user.main')

@section('content')

    <!-- Content wrapper -->
    <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
        <div class="col-xxl-12 mb-6 order-0">
            <div class="card">
            <div class="d-flex align-items-start row">
                <div class="col-sm-7">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">{{ Auth::user()->website->name }} </h5>
                    {{-- <p class="mb-6">
                        Peer to Peer (Premium)
                    </p> --}}

                    <a href="http://{{ Auth::user()->website->domain }}" target="_blank" class="btn btn-sm btn-outline-primary">{{ Auth::user()->website->domain }}</a>
                </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                <div class="card-body pb-0 px-0 px-md-6">
                    <img
                    src="{{ asset('uploads/'.Auth::user()->website->setting->logo) }}"
                    height="175"
                    alt="View Badge User" />
                </div>
                </div>
            </div>
            </div>

            <div class="main-card mb-4 card mt-4">
                <div class="card-body steps-to-success-container">
                    <div class="jumbotron mb-0">
                        <h1 class="display-4">
                            Hello, {{ Auth::user()->name }} {{ Auth::user()->last_name }} !
                        </h1>

                        <p class="lead">
                            This is your dashboard, where you can manage your profile and view reports on your fundraising progress.
                        </p>

                                        <hr class="my-4">

                            <p class="mb-0">
                                Your personal fundraising page is: <a href="http://{{ Auth::user()->website->domain }}/student/{{ Auth::user()->id }}-{{ Auth::user()->name }}-{{ Auth::user()->last_name }}">{{ Auth::user()->website->domain }}/student/{{ Auth::user()->id }}-{{ Auth::user()->name }}-{{ Auth::user()->last_name }}</a>.
                            </p>


                            <p class="mt-3">
                                Copy and paste your fundraising link above and text it to your family and friends
                            </p>
                                </div>

                    <div class="vertical-time-icons vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                        <div class="vertical-timeline-item vertical-timeline-element">
                            <div>
                                <div class="vertical-timeline-element-icon bounce-in">
                                    <div class="timeline-icon border-info bg-info">
                                        <i class="fa-solid fa-id-card-clip text-white" role="img" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="vertical-timeline-element-content bounce-in">
                                    <h4 class="timeline-title">
                                        <a href="/users/profile" class="link-info">
                                            Setup your profile
                                        </a>
                                    </h4>
                                    <p>
                                        Review your profile page and make sure all your details are correct
                                    </p>
                                </div>
                            </div>
                        </div>

                                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->


@endsection


