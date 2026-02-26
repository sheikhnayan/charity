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

    #usersTable_filter label {
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
                                {{-- <a href="{{ route('users.manage-users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Create User
                                </a> --}}
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

                                @php
                                    $isRoleUser = auth()->user() && auth()->user()->role === 'user';
                                @endphp

                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <span class="text-dark fw-semibold">Filter:</span>
                                        <a href="{{ route('users.manage-users.index') }}"
                                           class="btn btn-sm {{ empty($filterType) ? 'btn-primary' : 'btn-outline-primary' }}">
                                            All
                                        </a>
                                        <a href="{{ route('users.manage-users.index', ['type' => 'participant']) }}"
                                           class="btn btn-sm {{ ($filterType ?? null) === 'participant' ? 'btn-primary' : 'btn-outline-primary' }}">
                                            Participants
                                        </a>
                                        <a href="{{ route('users.manage-users.index', ['type' => 'parent']) }}"
                                           class="btn btn-sm {{ ($filterType ?? null) === 'parent' ? 'btn-primary' : 'btn-outline-primary' }}">
                                            Parents
                                        </a>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-success" id="exportSelectedBtn" disabled>
                                            <i class="fas fa-file-excel me-1"></i> Export Selected
                                        </button>
                                    </div>
                                </div>

                                @if($isRoleUser)
                                <div class="row g-3 mb-3">
                                    <div class="col-md-3">
                                        <label for="teacherFilter" class="form-label text-dark fw-semibold">Filter by Teacher:</label>
                                        <select id="teacherFilter" class="form-select form-select-sm">
                                            <option value="">All Teachers</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ trim($teacher->name . ' ' . ($teacher->last_name ?? '')) }}">
                                                    {{ trim($teacher->name . ' ' . ($teacher->last_name ?? '')) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="parentFilter" class="form-label text-dark fw-semibold">Filter by Parent:</label>
                                        <select id="parentFilter" class="form-select form-select-sm">
                                            <option value="">All Parents</option>
                                            @foreach($parents as $parent)
                                                <option value="{{ $parent->email }}">
                                                    {{ trim($parent->name . ' ' . ($parent->last_name ?? '')) }} ({{ $parent->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="dateFrom" class="form-label text-dark fw-semibold">Date From:</label>
                                        <input type="date" id="dateFrom" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="dateTo" class="form-label text-dark fw-semibold">Date To:</label>
                                        <input type="date" id="dateTo" class="form-control form-control-sm">
                                    </div>
                                </div>
                                @endif
                                
                                <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                                <table class="table table-striped" id="usersTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px;">
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                            </th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Website</th>
                                            @if($isRoleUser)
                                                <th style="display: none;">Role</th>
                                                <th>Parent Email</th>
                                                <th>Teacher</th>
                                                <th>Shirt Size</th>
                                                <th>Amount Raised</th>
                                                <th>Goal</th>
                                            @else
                                                <th>Role</th>
                                            @endif
                                            <th>Registration Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($users->isEmpty())
                                            <tr>
                                                <td colspan="{{ $isRoleUser ? 15 : 9 }}" class="text-center">No users found.</td>
                                            </tr>
                                        @else
                                            @foreach ($users as $user)
                                                <tr data-user-id="{{ $user->id }}">
                                                    <td>
                                                        <input type="checkbox" class="form-check-input row-checkbox" value="{{ $user->id }}">
                                                    </td>
                                                    <td>{{ $user->id }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.user.profile', $user->id) }}" class="text-decoration-none fw-bold text-primary">
                                                            {{ $user->name }} {{ $user->last_name ?? '' }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->website->name ?? 'N/A' }}</td>
                                                    @if($isRoleUser)
                                                        <td style="display: none;">
                                                            <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                                                        </td>
                                                        <td>{{ $user->parent->email ?? 'N/A' }}</td>
                                                        <td>{{ trim(($user->teacher->name ?? '') . ' ' . ($user->teacher->last_name ?? '')) ?: 'N/A' }}</td>
                                                        <td>{{ $user->tshirt_size ?? 'N/A' }}</td>
                                                        <td>${{ number_format($user->donations->sum('amount'), 2) }}</td>
                                                        <td>
                                                            @if ($user->role == 'user')
                                                            ${{ number_format($user->website->setting->goal ?? 0, 2) }}
                                                            @else
                                                            ${{ number_format($user->goal ?? 0, 2) }}
                                                            @endif
                                                        </td>
                                                    @else
                                                        <td>
                                                            <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                                                        </td>
                                                    @endif
                                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d h:i A') }}</td>
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
                                                            <a href="/admins/student/approve/{{ $user->id }}" class="btn btn-sm btn-success me-1" title="Approve">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                        @endif
                                                        @if(auth()->user()->role === 'user' && in_array($user->role, ['individual', 'parents']))
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
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
        </div>
        <!-- / Content -->

        {{-- Delete User Modals --}}
        @foreach ($users as $user)
            @if(auth()->user()->role === 'user' && in_array($user->role, ['individual', 'parents']))
            <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">
                                <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Warning:</strong> This action cannot be undone!
                            </div>
                            
                            @if($user->role === 'parents')
                                <p class="mb-3"><strong>Deleting this parent will:</strong></p>
                                <ul class="mb-3">
                                    <li>Delete all students (participants) under this parent</li>
                                    <li>Delete all donations received by those students</li>
                                    <li>Delete all transactions related to those donations</li>
                                    <li>Delete all donations and transactions for this parent</li>
                                </ul>
                            @else
                                <p class="mb-3"><strong>Deleting this student will:</strong></p>
                                <ul class="mb-3">
                                    <li>Delete all donations received by this student</li>
                                    <li>Delete all transactions related to those donations</li>
                                </ul>
                            @endif
                            
                            @if($user->donations->count() > 0)
                                <div class="alert alert-info">
                                    <strong>{{ $user->donations->count() }} donation record(s) will be deleted</strong>
                                    <br>
                                    <small>Total amount: ${{ number_format($user->donations->sum('amount') + $user->donations->sum('tip_amount'), 2) }}</small>
                                </div>
                            @endif
                            
                            <p class="mb-0">Are you sure you want to delete <strong>{{ $user->name }} {{ $user->last_name }}</strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i>Yes, Delete Permanently
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

        <style>
            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                color: #000 !important;
            }
        </style>

        <script>
            $(document).ready(function() {
                @if($isRoleUser)
                // Initialize DataTable with custom filtering
                var table = $('#usersTable').DataTable({
                    pageLength: 25,
                    scrollX: true,
                    columnDefs: [
                        { orderable: false, targets: 0 }
                    ]
                });

                // Custom search function for filtering
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        const teacherFilter = $('#teacherFilter').val().toLowerCase();
                        const parentFilter = $('#parentFilter').val().toLowerCase();
                        const dateFrom = $('#dateFrom').val();
                        const dateTo = $('#dateTo').val();

                        // Data indices: 6=Parent Email, 7=Teacher, 11=Registration Date
                        const parentEmail = data[6].toLowerCase();
                        const teacher = data[7].toLowerCase();
                        const dateStr = data[11]; // Format: "YYYY-MM-DD HH:MM AM/PM"

                        // Filter by teacher
                        if (teacherFilter && teacher.indexOf(teacherFilter) === -1) {
                            return false;
                        }

                        // Filter by parent
                        if (parentFilter && parentEmail.indexOf(parentFilter) === -1) {
                            return false;
                        }

                        // Filter by date range
                        if (dateFrom || dateTo) {
                            // Extract date part from "YYYY-MM-DD HH:MM AM/PM"
                            const rowDate = dateStr.substring(0, 10); // Get first 10 chars (YYYY-MM-DD)
                            
                            if (dateFrom && rowDate < dateFrom) {
                                return false;
                            }
                            if (dateTo && rowDate > dateTo) {
                                return false;
                            }
                        }

                        return true;
                    }
                );

                // Trigger table redraw when filters change
                $('#teacherFilter, #parentFilter, #dateFrom, #dateTo').on('change', function() {
                    table.draw();
                });
                @else
                // Basic DataTable for non-role users
                $('#usersTable').DataTable({
                    pageLength: 25,
                    scrollX: true,
                    columnDefs: [
                        { orderable: false, targets: 0 }
                    ]
                });
                @endif

                // Select All functionality
                $('#selectAll').on('click', function() {
                    const isChecked = $(this).prop('checked');
                    $('#usersTable tbody tr:visible').find('.row-checkbox').prop('checked', isChecked);
                    updateExportButton();
                });

                // Individual checkbox change
                $(document).on('change', '.row-checkbox', function() {
                    updateSelectAll();
                    updateExportButton();
                });

                // Update Select All checkbox state
                function updateSelectAll() {
                    const totalVisible = $('#usersTable tbody tr:visible').length;
                    const totalChecked = $('#usersTable tbody tr:visible').find('.row-checkbox:checked').length;
                    $('#selectAll').prop('checked', totalVisible > 0 && totalVisible === totalChecked);
                }

                // Update export button state
                function updateExportButton() {
                    const checkedCount = $('.row-checkbox:checked').length;
                    $('#exportSelectedBtn').prop('disabled', checkedCount === 0);
                    if (checkedCount > 0) {
                        $('#exportSelectedBtn').html('<i class="fas fa-file-excel me-1"></i> Export Selected (' + checkedCount + ')');
                    } else {
                        $('#exportSelectedBtn').html('<i class="fas fa-file-excel me-1"></i> Export Selected');
                    }
                }

                // Export selected rows
                $('#exportSelectedBtn').on('click', function() {
                    const selectedIds = [];
                    $('.row-checkbox:checked').each(function() {
                        if ($(this).closest('tr').is(':visible')) {
                            selectedIds.push($(this).val());
                        }
                    });

                    if (selectedIds.length === 0) {
                        alert('Please select at least one user from the visible/filtered results to export.');
                        return;
                    }

                    // Create CSV content
                    let csvContent = 'ID,Name,Email,Website,Role,Status';
                    @if($isRoleUser)
                        csvContent = 'ID,Name,Email,Website,Parent Email,Teacher,Shirt Size,Amount Raised,Goal,Status';
                    @endif
                    csvContent += '\n';

                    selectedIds.forEach(function(id) {
                        const row = $('tr[data-user-id="' + id + '"]');
                        const cells = row.find('td');
                        
                        const rowData = [];
                        for (let i = 1; i < cells.length - 1; i++) {
                            let cellText = $(cells[i]).text().trim();
                            cellText = cellText.replace(/"/g, '""');
                            if (cellText.includes(',') || cellText.includes('"') || cellText.includes('\n')) {
                                cellText = '"' + cellText + '"';
                            }
                            rowData.push(cellText);
                        }
                        csvContent += rowData.join(',') + '\n';
                    });

                    // Create download link
                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', 'selected_users_' + new Date().getTime() + '.csv');
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            });
        </script>
    </div>
</div>
@endsection
