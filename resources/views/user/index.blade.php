@extends('user.main')

@section('content')

    <!-- Content wrapper -->
    <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        @if(Auth::user()->role == 'parents')
        <div class="row mb-4">
            <div class="col-xxl-12">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="fas fa-plus me-2"></i>Add Participants
                    </button>
                </div>
            </div>
        </div>
        @endif
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
                                Your personal fundraising page is: <a href="http://{{ Auth::user()->website->domain }}/profile/{{ Auth::user()->id }}-{{ Auth::user()->name }}-{{ Auth::user()->last_name }}">{{ Auth::user()->website->domain }}/profile/{{ Auth::user()->id }}-{{ Auth::user()->name }}-{{ Auth::user()->last_name }}</a>.
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

    <!-- Add Student Modal -->
    @if(Auth::user()->role == 'parents')
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('parent.add-student') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStudentModalLabel">Add Participant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                            <div class="form-text">Credentials are automatically generated for system use only and are not shared or tracked outside the fundraiser.</div>
                        </div>
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">Select Teacher <span class="text-danger">*</span></label>
                            <select class="form-select teacher-select" id="teacher_id" name="teacher_id" required>
                                <option value="">Choose a teacher</option>
                                @if(isset($teachers))
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- jQuery (Required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Wait for jQuery and Select2 to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Check if jQuery and Select2 are loaded
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                // Initialize Select2 for teacher select with search
                jQuery('.teacher-select').select2({
                    placeholder: 'Search and select a teacher',
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    </script>

@endsection


