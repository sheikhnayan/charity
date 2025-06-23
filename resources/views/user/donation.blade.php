@extends('user.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

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
                                                            alt="{{ Auth::user()->website->name }}"
                                                            src="https://myfunrun.nyc3.digitaloceanspaces.com/assets/site/607/-607.88843dac-5a2f-4a0d-9baf-fc7b9ea5270f.png">
                                                    </div>
                                                </div>

                                                <div class="widget-content-left">
                                                    <div class="widget-heading">
                                                        {{ Auth::user()->website->name }} </div>
                                                    <div class="fs-6 mt-2">
                                                        <i class="fas fa-link link-info me-1 btn-clipboard"
                                                            role="button" data-clipboard-text="{{ Auth::user()->website->domain }}"></i>
                                                        <a href="http://{{ Auth::user()->website->domain }}" class="link-info"
                                                            target="_blank">{{ Auth::user()->website->domain }}</a>
                                                    </div>
                                                </div>

                                                <div class="widget-content-right">
                                                    <div class="btn-group d-none d-md-inline-flex" role="group">
                                                        <a href="http://{{ Auth::user()->website->domain }}/student/139276-sheikh-nayan"
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
                                            Donation
                                        </span>
                                        <div class="page-title-subheading">
                                            View the received donations.
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
                                            Reports
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            donation
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary" style="background: #fff !important;">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Donor</th>
                                                <th>Entered</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (Auth::user()->donations->isEmpty())
                                                <tr>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                </tr>
                                            @else
                                                @foreach (Auth::user()->doantions as $item)
                                                    <tr>
                                                        <td>{{ $item->fist_name }} {{ $item->last_name }}</td>
                                                        <td>${{ $item->amount }}</td>
                                                        <td>
                                                            @if ($item->status == 1)
                                                                Approved
                                                            @else
                                                                Pending
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Content -->

            <!-- Include DataTables and jQuery CDN -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Initialize DataTable with default search disabled
                    let table = new DataTable('.table');
                });
            </script>
        @endsection
