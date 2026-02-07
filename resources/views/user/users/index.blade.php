@extends('user.main')

@section('page-title', 'Manage Users')

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

    .dt-buttons button span {
        color: #000 !important;
    }

    .paginate_buttons a {
        color: #000 !important;
    }
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xxl-12 mb-6 order-0">
                <div class="app-main__inner">
                    <div class="app-page-title mt-4" data-step="" data-title="" data-intro="">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">

                                <div class="page-title-icon">
                                    <i class="fas fa-id-card icon-gradient bg-arielle-smile"></i>
                                </div>

                                <div>
                                    <span class="text-capitalize">
                                        Users
                                    </span>
                                    <div class="page-title-subheading">
                                        Manage all Users.
                                    </div>
                                </div>

                            </div>
                            <div class="page-title-actions">
                                <a href="{{ route('users.manage-users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Create User
                                </a>
                            </div>
                        </div>

                        <div class="page-title-subheading opacity-10 mt-3"
                            style="white-space: nowrap; overflow-x: auto;">
                            <nav class="" aria-label="breadcrumb">
                                <ol class="breadcrumb">

                                    <li class="breadcrumb-item opacity-10">
                                        <a href="#">
                                            <i class="fas fa-home" role="img" aria-hidden="true"></i>
                                            <span class="visually-hidden">Home</span>
                                        </a>
                                        <i class="fas fa-chevron-right ms-1"></i>
                                    </li>

                                    <li class="breadcrumb-item ">
                                        Management
                                        <i class="fas fa-chevron-right ms-1"></i>
                                    </li>
                                    <li class="active breadcrumb-item" aria-current="page">
                                        Users
                                    </li>

                                </ol>
                            </nav>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg">
                            <div class="card-shadow-primary p-4 card-border text-white mb-3 card bg-primary" style="background: #fff !important;">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Website</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($users->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center">No users found.</td>
                                            </tr>
                                        @else
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ $user->id }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.user.profile', $user->id) }}" class="text-decoration-none fw-bold text-primary">
                                                            {{ $user->name }} {{ $user->last_name ?? '' }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->website->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($user->status == 1)
                                                            <span class="badge bg-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('users.manage-users.edit', $user->id) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if ($user->status != 1)
                                                            <a href="/admins/student/approve/{{ $user->id }}" class="btn btn-sm btn-success" title="Approve">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                        @endif
                                                    </td>
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
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
        <style>
            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                color: #000 !important;
            }
        </style>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

        <script>
            $(document).ready(function() {
                // Initialize DataTable with export/import buttons
                let table = new DataTable('.table', {
                    dom: 'Bfrtip',
                    pageLength: 25,
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            });
        </script>
    </div>
</div>
@endsection
