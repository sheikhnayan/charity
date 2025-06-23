@extends('user.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<style>
    .forms-wizard li.done em::before, .lnr-checkmark-circle::before {
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
                                                        <img width="42" class="rounded"
                                                            alt="The SHPS PTO Fundraiser 2025"
                                                            src="{{ asset('uploads/'.Auth::user()->website->setting->logo) }}">
                                                    </div>
                                                </div>

                                                <div class="widget-content-left">
                                                    <div class="widget-heading">
                                                        {{ Auth::user()->website->name }} </div>
                                                    {{-- <div class="widget-subheading">
                                                        Peer to Peer
                                                        (Premium)
                                                    </div> --}}
                                                    <div class="fs-6 mt-2">
                                                        <i class="fas fa-link link-info me-1 btn-clipboard"
                                                            role="button" data-clipboard-text="http://{{ Auth::user()->website->domain }}"></i>
                                                        <a href="http://{{ Auth::user()->website->domain }}" class="link-info"
                                                            target="_blank">{{ Auth::user()->website->domain }}</a>
                                                    </div>
                                                </div>

                                                <div class="widget-content-right">
                                                    <div class="btn-group d-none d-md-inline-flex" role="group">
                                                        <a href="{{ Auth::user()->website->domain }}/student/{{ Auth::user()->id }}-{{ Auth::user()->name }}-{{ Auth::user()->last_name }}"
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
                                                            style="background: url('{{ asset(Auth::user()->photo) }}');"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5 class="menu-header-title">
                                                        <a href="{{ Auth::user()->website->domain }}/student/139276-sheikh-nayan"
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

                                        <input type="hidden" name="site_uri" value="{{ env('APP_URL') }}/student/">


                                        <input type="hidden" name="participant_type" value="individual">
                                        <div class="col-12 tab-content fundraiser-tab-content">

                                            <div class="row gy-3 tab-pane profile-tab-individual show active"
                                                role="tabpanel">
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
                                                        Your URI
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            {{ env('APP_URL') }}/student/
                                                        </span>
                                                        <input type="text" class="form-control" id="individual_url"
                                                            name="individual_url" value="{{ Auth::user()->id }}-{{ Auth::user()->name }}-{{ Auth::user()->last_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- <div class="row gy-3 tab-pane profile-tab-join_team " role="tabpanel">
                                                <div class="col-12">
                                                    <label for="select_team" class="form-label text-capitalize required">

                                                        Team

                                                    </label>
                                                    <select class="form-select profile_select_team" id="select_team"
                                                        name="select_team">
                                                        <option value="">Open this select menu</option>
                                                        <option value="11640"
                                                            data-uri="https://gmu-events.com/student/katz/">
                                                            Mr. Katz
                                                        </option>
                                                        <option value="11638"
                                                            data-uri="https://gmu-events.com/student/ouassini/">
                                                            Mr. Ouassini
                                                        </option>
                                                        <option value="11628"
                                                            data-uri="https://gmu-events.com/student/hadama/">
                                                            Mrs. Hadama
                                                        </option>
                                                        <option value="11289"
                                                            data-uri="https://gmu-events.com/student/pledger/">
                                                            Mrs. Pledger
                                                        </option>
                                                        <option value="11634"
                                                            data-uri="https://gmu-events.com/student/smith/">
                                                            Mrs. Smith
                                                        </option>
                                                        <option value="11652"
                                                            data-uri="https://gmu-events.com/student/Amanda/">
                                                            Ms. Amanda
                                                        </option>
                                                        <option value="11629"
                                                            data-uri="https://gmu-events.com/student/Ballard/">
                                                            Ms. Ballard
                                                        </option>
                                                        <option value="11645"
                                                            data-uri="https://gmu-events.com/student/cgj/">
                                                            Ms. Cheynna, Ms. Guadalupe, Ms. Julianne
                                                        </option>
                                                        <option value="11631"
                                                            data-uri="https://gmu-events.com/student/cinadr/">
                                                            Ms. Cinadr
                                                        </option>
                                                        <option value="11627"
                                                            data-uri="https://gmu-events.com/student/comfort/">
                                                            Ms. Comfort
                                                        </option>
                                                        <option value="11635"
                                                            data-uri="https://gmu-events.com/student/Coulter/">
                                                            Ms. Coulter
                                                        </option>
                                                        <option value="11651"
                                                            data-uri="https://gmu-events.com/student/Danielle/">
                                                            Ms. Danielle
                                                        </option>
                                                        <option value="11637"
                                                            data-uri="https://gmu-events.com/student/dozier/">
                                                            Ms. Dozier
                                                        </option>
                                                        <option value="11641"
                                                            data-uri="https://gmu-events.com/student/freisleben/">
                                                            Ms. Freisleben
                                                        </option>
                                                        <option value="11647"
                                                            data-uri="https://gmu-events.com/student/Hailey/">
                                                            Ms. Hailey
                                                        </option>
                                                        <option value="11632"
                                                            data-uri="https://gmu-events.com/student/hunter/">
                                                            Ms. Hunter
                                                        </option>
                                                        <option value="11642"
                                                            data-uri="https://gmu-events.com/student/iglesias/">
                                                            Ms. Iglesias
                                                        </option>
                                                        <option value="11644"
                                                            data-uri="https://gmu-events.com/student/colony/">
                                                            Ms. Kolodny
                                                        </option>
                                                        <option value="11625"
                                                            data-uri="https://gmu-events.com/student/Lambert/">
                                                            Ms. Lambert
                                                        </option>
                                                        <option value="11649"
                                                            data-uri="https://gmu-events.com/student/lucia/">
                                                            Ms. Lucia
                                                        </option>
                                                        <option value="11639"
                                                            data-uri="https://gmu-events.com/student/price/">
                                                            Ms. Price
                                                        </option>
                                                        <option value="11626"
                                                            data-uri="https://gmu-events.com/student/Robinson/">
                                                            Ms. Robinson
                                                        </option>
                                                        <option value="11653"
                                                            data-uri="https://gmu-events.com/student/rupwanti/">
                                                            Ms. Rupwanti
                                                        </option>
                                                        <option value="11648"
                                                            data-uri="https://gmu-events.com/student/Skylar/">
                                                            Ms. Skylar
                                                        </option>
                                                        <option value="11646"
                                                            data-uri="https://gmu-events.com/student/sunny/">
                                                            Ms. Sunny
                                                        </option>
                                                        <option value="11650"
                                                            data-uri="https://gmu-events.com/student/toni/">
                                                            Ms. Toni
                                                        </option>
                                                        <option value="11633"
                                                            data-uri="https://gmu-events.com/student/tran/">
                                                            Ms. Tran
                                                        </option>
                                                        <option value="11630"
                                                            data-uri="https://gmu-events.com/student/williams/">
                                                            Ms. Williams
                                                        </option>
                                                        <option value="11643"
                                                            data-uri="https://gmu-events.com/student/wunderlich/">
                                                            Ms. Wunderlich
                                                        </option>
                                                        <option value="11636"
                                                            data-uri="https://gmu-events.com/student/tingling/">
                                                            Ms. Yingling
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-12">
                                                    <label for="join_goal" class="form-label">Your goal</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" class="form-control" id="join_goal"
                                                            name="goal" value="{{ Auth::user()->goal }}">
                                                        <span class="input-group-text">.00 USD</span>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="join_url" class="form-label">
                                                        Your URI

                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text join_team_url">
                                                            {{ env('APP_URL') }}/student/
                                                        </span>
                                                        <input type="text" class="form-control" id="join_url"
                                                            name="url" value="{{ Auth::user()->id }}-{{ Auth::user()->name }}-{{ Auth::user()->last_name }}">
                                                    </div>
                                                </div>
                                            </div> --}}


                                        </div>













                                        <div class="col-12">
                                            <h5 class="text-primary">
                                                Personal
                                            </h5>
                                        </div>

                                        <div class="col-6" style="order: -2;">
                                            <label for="first_name" class="form-label required">
                                                Student's First name
                                            </label>


                                            <input type="text" class="form-control" id="first_name" name="name"
                                                value="{{ Auth::user()->name }}">
                                        </div>











                                        <div class="col-6" style="order: -1;">
                                            <label for="last_name" class="form-label required">
                                                Student's Last name
                                            </label>


                                            <input type="text" class="form-control" id="last_name" name="last_name"
                                                value="{{ Auth::user()->last_name }}">
                                        </div>











                                        <div class="col-12">
                                        </div>











                                        <div class="col-12">
                                            <label for="description" class="form-label ">
                                                Enter the text that will appear on your personal fundraising page.
                                            </label>


                                            <textarea class="form-control text-editor" id="description" name="description" rows="3"
                                                style="visibility: hidden;">
                                                {!! Auth::user()->description !!}
                                            </textarea>
                                        </div>











                                        <div class="col-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="show_send_button" name="show_send_button" value="1"
                                                    checked="">
                                                <label class="form-check-label " for="show_send_button">
                                                    Show send message button
                                                </label>
                                                <i role="button"
                                                    class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                    data-title="Show send message button"
                                                    data-description="Checking this box will allow people to send an email message by clicking a button on the profile.
                Email addresses will not be visible on the website."></i>
                                            </div>
                                        </div>











                                        <div class="col-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="show_amount_raised" name="show_amount_raised" value="1"
                                                    checked="">
                                                <label class="form-check-label " for="show_amount_raised">
                                                    Show amount raised
                                                </label>
                                                <i role="button"
                                                    class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                    data-title="Show amount raised"
                                                    data-description="The amount you raise is displayed on your personal fundraising page and on the Leaderboard.
                                                    If you don't want to show the amount you raised, uncheck this box."></i>
                                            </div>
                                        </div>











                                        <div class="col-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="receive_donation_notification"
                                                    name="receive_donation_notification" value="1" checked="">
                                                <label class="form-check-label " for="receive_donation_notification">
                                                    Receive notifications of donations
                                                </label>
                                                <i role="button"
                                                    class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                    data-title="Receive notifications of donations"
                                                    data-description="If checked, you will receive email notifications of donations made on your personal fundraising
                                                page."></i>
                                            </div>
                                        </div>










                                        <div class="col-12">
                                            <h5 class="text-primary">
                                                Image(s)
                                            </h5>
                                            <img src="{{ asset(Auth::user()->photo) }}" width="150px">
                                        </div>

                                        <div class="col-12">
                                            <label for="photo" class="form-label ">
                                                Student's Profile Photo
                                            </label>


                                            <input class="form-control" type="file" id="photo-image-file"
                                                name="photo" accept="image/png, image/gif, image/jpeg, image/jpg">
                                            <div class="form-text">The recommended format for the profile picture should be
                                                a square.</div>
                                        </div>


{{--
                                        <div class="col-12">
                                            <span class="form-heading">
                                                Custom question
                                            </span>
                                        </div>

                                        <div class="col-12">
                                            <label for="4565fbe1-dfb5-4527-9c53-2840a65f8c6f" class="form-label required">
                                                Student's Teacher Name
                                            </label>

                                            <select class="form-select" id="4565fbe1-dfb5-4527-9c53-2840a65f8c6f"
                                                name="teacher_id">
                                                <option value="">Open this select menu</option>
                                                <option value="89e8523e-4d76-4e19-98ea-a5c86c56c0fa">
                                                    Mr. Katz
                                                </option>
                                                <option value="275bc7d8-29ae-4281-8f63-ad30e1fba5e3">
                                                    Ms. Rupwanti
                                                </option>
                                                <option value="08a25ba3-16a7-435f-a175-c5149c63faa0">
                                                    Ms. Amanda
                                                </option>
                                                <option value="49ca1dfc-0b00-4075-9a74-8911f7985953">
                                                    Ms. Danielle
                                                </option>
                                                <option value="74b0c518-49ed-450f-bbef-febe4e7de153">
                                                    Ms. Toni
                                                </option>
                                                <option value="f7211a46-0573-4e60-bb4a-3f96e89fe976">
                                                    Ms. Lucia
                                                </option>
                                                <option value="72960a55-0e5e-4528-8afb-61f8a5b81b83">
                                                    Ms. Skylar
                                                </option>
                                                <option value="2980a6e8-525c-4e3a-be68-f8158b5ae51c">
                                                    Ms. Hailey
                                                </option>
                                                <option value="6e4ff63f-3e81-4260-b5ae-12546b415d7a">
                                                    Ms. Sunny
                                                </option>
                                                <option value="f432a97a-cf82-457c-88b9-213de9ee1c71">
                                                    Ms. Julianne
                                                </option>
                                                <option value="356f7740-a8c3-432e-be55-f1c2a51eacba">
                                                    Ms. Guadalupe
                                                </option>
                                                <option value="7aa36a95-b2c6-4277-803a-9d151ca3bf29">
                                                    Ms. Cheynna
                                                </option>
                                                <option value="52dd694d-6e55-48cf-95e5-e6fdfccea6e7">
                                                    Ms. Kolodny
                                                </option>
                                                <option value="1b3504fe-b444-45dd-b20a-500a06dae392" selected="">
                                                    Ms. Wunderlich
                                                </option>
                                                <option value="26e1df67-91f1-4473-a726-2efa3f87873b">
                                                    Ms. Iglesias
                                                </option>
                                                <option value="c1cf2d17-6e05-4481-a8b3-d2dcf9b9eb9b">
                                                    Ms. Frieisleben
                                                </option>
                                                <option value="de406354-3827-4ecf-ad53-75fb11c51687">
                                                    Ms. Lambert
                                                </option>
                                                <option value="6d7786c6-8ba7-4143-8633-b6984cc6e43d">
                                                    Ms. Price
                                                </option>
                                                <option value="785159cc-a0ed-4523-87b8-2ca398a64196">
                                                    Mr. Ouassini
                                                </option>
                                                <option value="0197f860-72e0-4b1e-a64f-689bb3e0f374">
                                                    Ms. Dozier
                                                </option>
                                                <option value="60c34c7b-19dd-4f5b-b39c-b4b429cc8213">
                                                    Ms. Yingling
                                                </option>
                                                <option value="2bcdd6dd-6aee-4001-8e98-c2a44b93c681">
                                                    Ms. Coulter
                                                </option>
                                                <option value="c64c5d04-966a-469d-afd8-26c3acf60b34">
                                                    Mrs. Smith
                                                </option>
                                                <option value="a9cdd774-39eb-4a10-8512-63ca374df9bf">
                                                    Ms. Tran
                                                </option>
                                                <option value="a51ed51c-e020-4137-9003-98632d63ade7">
                                                    Ms. Hunter
                                                </option>
                                                <option value="9382091d-bffd-4b99-a6b1-a1c9b279c1b1">
                                                    Ms. Cinadr
                                                </option>
                                                <option value="51c56d69-d620-4d4c-b50c-586fe7b32f2b">
                                                    Ms. Williams
                                                </option>
                                                <option value="f0f41a86-5588-4ec5-89a8-a08c7c9f7f81">
                                                    Ms. Pledger
                                                </option>
                                                <option value="493bc37b-bddf-417b-ad6a-7d2ab70320f7">
                                                    Ms. Ballard
                                                </option>
                                                <option value="abc8c91e-a7af-4364-a11d-9f8b7f3284d0">
                                                    Mrs. Hadama
                                                </option>
                                                <option value="84b333b3-37ac-49ff-97b9-ee371d9dcb95">
                                                    Ms. Comfort
                                                </option>
                                                <option value="2799cc8f-f463-4600-8b57-2b060e976cb0">
                                                    Ms. Robinson
                                                </option>
                                                <option value="fbb03784-e1cc-4287-8be9-0c2be5007b34">
                                                    Ms. Largent
                                                </option>
                                            </select>

                                        </div>
                                        <div class="col-12">
                                            <label for="2d591b71-34c9-4f2a-9e88-563593db6ac4" class="form-label required">
                                                Student's T-shirt size
                                            </label>

                                            <select class="form-select" id="2d591b71-34c9-4f2a-9e88-563593db6ac4"
                                                name="size">
                                                <option value="">Open this select menu</option>
                                                <option value="b21386f1-e4f5-413c-b7e6-ffe3be386359">
                                                    XS- Youth
                                                </option>
                                                <option value="d98290ed-cd38-4384-88b1-a87232d42ea2">
                                                    Small - Youth
                                                </option>
                                                <option value="d7018b91-faa7-4767-b153-f5126a2751b8">
                                                    Medium- Youth
                                                </option>
                                                <option value="a9dca94f-75a0-4c6f-a710-70d0ac1fb9ef">
                                                    Large- Youth
                                                </option>
                                                <option value="3f4965f9-74f2-46cc-93de-ac6c550a1efe">
                                                    Small
                                                </option>
                                                <option value="8038121e-98bc-4cd8-927d-558add56143c" selected="">
                                                    Medium
                                                </option>
                                                <option value="08d13deb-d723-4cf8-91a5-c48a4569b6b0">
                                                    Large
                                                </option>
                                                <option value="b625b1d9-20e1-464b-84fc-a003c59d068b">
                                                    X-Large
                                                </option>
                                                <option value="d8852532-4de4-4183-8f7a-e2b0142adf3f">
                                                    2XL
                                                </option>
                                                <option value="8cba3d65-17dc-4851-b652-7d4b7fef7909">
                                                    3XL
                                                </option>
                                            </select>

                                        </div>
                                        <div class="col-12">
                                            <label for="d6654a66-2142-40cf-80d5-87fe2c22217b" class="form-label required">
                                                Student's Grade
                                            </label>

                                            <input type="text" class="form-control"
                                                id="d6654a66-2142-40cf-80d5-87fe2c22217b"
                                                name="grade" value="4">

                                        </div> --}}
                                    </div>

                                    <div class="sticky-save-button-container">
                                        <div class="sticky-save-button-inner">
                                            <button
                                                class="btn-hover-shine btn-wide btn btn-shadow btn-success btn-lg w-100 "
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
