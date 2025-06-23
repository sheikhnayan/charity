@extends('layouts.main')

@section('content')

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
</style>

    <main style="margin-top: 6.5rem">
        <div class="banner" style="background: url({{ asset('images/banner.png') }}); min-height: 480px;">
            <div class="client-banner-content">
                <h1 class="display-3 fw-semibold text-shadow">
                    <a href="/" class="text-light">
                        The SHPS PTO Fundraiser 2025
                    </a>
                </h1>
                <h2 class="text-light text-shadow mt-2">
                    Presented by Gear Me Up™
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mt-4 mb-4" style="font-size: 12px;">
                <div class="position-relative bg- p-4 rounded-3 shadow-sm border"
                    style="width: 100%; max-width: 930px; margin-inline: auto;">
                    <div class="row gy-3 ">
                        <div class="col-lg-3 d-flex align-items-center">
                            <div class="rounded-profile-picture border border-3 border-primary mx-auto"
                                style="border-radius: 50%; border-color: #2e4053 !important">
                                <img src="{{ asset($data->photo) }}"
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
                                style="height: 6px">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                    style="width: 100%">
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
                    <a href="{{ env('APP_URL') }}/student/{{ $data->id }}-{{ $data->name }}-{{ $data->last_name }}"
                        class="stretched-link" target="_blank"></a>
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
                    class="d-flex flex-column justify-content-center p-5 h-100 text-dark rounded-4 bg-light lead w-md-85 mx-auto break-all">
                    <p>Hi!! Thanks for your donation!</p>
                    <p>I am raising money for the Gear Me Up Fest Fun Run!</p>
                    <p>Deadline is Feb 21st!</p>
                </div>
            </div>
        </section>

        <section class="text- bg- section-border- " id="b2dd141f-e084-45c7-ba93-d8b6158d65af" data-section=""
                    style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
                    <div class="block-container container " id="block-086fc842-f2e9-4d56-af2e-be42317d11e7"
                        data-block="" data-template="7e729e7e3c534cbf918a45b5540afa84"
                        data-action="https://gmu-events.com/ajax/block/b2dd141f-e084-45c7-ba93-d8b6158d65af/086fc842-f2e9-4d56-af2e-be42317d11e7"
                        style="margin-top: 3rem;">


                        <form method="POST" action="/donation" class="donation-form-block" method="POST">
                            @csrf
                            <div class="col-12 col-md-10 col-lg-8 col-xl-6 mx-auto">
                                <div class="card border-primary shadow" style="border-width: 3px; border-color: #2e4053 !important;">
                                    <div class="card-header bg-primary border-primary rounded-0 text-center text-white fs-2"
                                        style="border-width: 3px; border-color: #2e4053 !important; background-color: #2e4053 !important;">
                                        Make a general donation
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
                                                    Donate To the SHPS PTO
                                                </label>
                                                <div></div>

                                                <div class="d-flex justify-content-center flex-wrap">
                                                    <input type="hidden" data-change-amount="1"
                                                        data-name="4e963109-9506-49a8-b609-a0929944c1b2" data-amount="500"
                                                        class="form-check btn-check select-amount"
                                                        name="user_id"
                                                        id="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b24479f3e5-aac8-4044-ac77-7c3192197e63"
                                                        value="{{ $data->id }}" autocomplete="off">
                                                    <label class="btn btn-outline-primary m-1"
                                                    style="color: #2e4053 !important; border-color: #2e4053 !important;"
                                                        for="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b24479f3e5-aac8-4044-ac77-7c3192197e63">Donate
                                                        to the PTO</label>
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
                                                            class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                            data-title="I elect to pay the fees"
                                                            data-description="By selecting this option, you elect to pay the credit card and transaction fees for this donation.The fees will be displayed in the next step."></i>
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
                                                <input type="text" class="form-control" id="email" name="email"
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
                                                        class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                        data-title="Anonymous"
                                                        data-description="Selecting this option will hide your name from everyone but the organizer."></i>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="leave_comment" class="form-label fw-semibold text-capitalize">
                                                    comment
                                                </label>
                                                <textarea class="form-control" id="leave_comment" name="leave_comment" rows="6"></textarea>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="hear_from_myevent_086fc842-f2e9-4d56-af2e-be42317d11e7"
                                                        name="hear_from_myevent">
                                                    <label class="form-check-label"
                                                        for="hear_from_myevent_086fc842-f2e9-4d56-af2e-be42317d11e7">Hear
                                                        from MyEvent</label>
                                                    <i role="button"
                                                        class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                                        data-title="Hear from MyEvent"
                                                        data-description="In compliance with the new Anti-Spam CASL legislation, we need your permission to continue communicating
with you. Please confirm your interest in hearing from MyEvent."></i>
                                                </div>
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
                    {{ $donations->count() }} donations have been made to this site
                </p>
            </div>
            <div class="col-8 mt-4">
                <table id="studentTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Grade</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donations->chunk(3) as $item)
                            <tr>
                                @foreach ($item as $i)
                                    <td>
                                        <div class="col-lg-12" style="font-size: 12px;">
                                            <div class="p-3 rounded text-center position-relative" style="background: #ebebeb">


                                                <h4 class="fw-semibold">
                                                    ${{ $i->amount }}
                                                </h4>

                                                <small class="d-block opacity-75 mt-2">
                                                    <span title="Donor">{{ $i->first_name }} {{ $i->last_name }}</span>
                                                                            <i class="fa-solid fa-arrow-right-long fa-fw mx-1 text-success" aria-hidden="true"></i>
                                                        <span title="Participant">{{ $i->user->name }} {{ $i->user->last_name }}</span>
                                                                    </small>


                                                <small class="d-block opacity-75 mt-3 p-2 rounded" style="backdrop-filter: brightness(1.5);">
                                                    <i class="fa-solid fa-calendar-days me-1" aria-hidden="true"></i>
                                                     {{ $i->created_at->diffForHumans() }}
                                                </small>

                                            </div>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    </main>

    <!-- Include DataTables and jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable with default search disabled
        const table = $('#studentTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
        });

        // Link the custom search input to the DataTable search
        $('#search').on('keyup', function() {
            const value = $(this).val();
            table.search(value).draw();
        });
    });
</script>
@endsection
