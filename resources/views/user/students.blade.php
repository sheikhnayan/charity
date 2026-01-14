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
                                            @if(Auth::user()->role == 'parents')
                                                My Students
                                            @else
                                                Users
                                            @endif
                                        </span>
                                        <div class="page-title-subheading">
                                            @if(Auth::user()->role == 'parents')
                                                Manage your students/children.
                                            @else
                                                View all Users.
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="page-title-actions">
                                    @if(Auth::user()->role == 'parents')
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                            <i class="fas fa-plus me-2"></i>Add Student
                                        </button>
                                    @endif
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
                                            Reports
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            users
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary p-4 card-border text-white mb-3 card bg-primary" style="background: #fff !important;">

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Teacher</th>
                                                <th>Goal</th>
                                                @if(Auth::user()->role == 'parents')
                                                <th>Raised</th>
                                                @endif
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($data->isEmpty())
                                                <tr>
                                                    <td colspan="{{ Auth::user()->role == 'parents' ? '9' : '8' }}" class="text-center">No student found.</td>
                                                </tr>
                                            @else
                                                @foreach ($data as $item)
                                                    <tr>
                                                        <td>{{ $item->id }}</td>
                                                        <td>
                                                            <a href="/users/student/profile/{{ $item->id }}" class="text-decoration-none fw-bold text-primary">
                                                                {{ $item->fist_name }} {{ $item->last_name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $item->email }}</td>
                                                        <td>
                                                            <span class="badge bg-info">{{ ucfirst($item->role) }}</span>
                                                        </td>
                                                        <td>{{ $item->teacher->name ?? 'N/A' }}</td>
                                                        <td>${{ number_format($item->goal ?? 0, 2) }}</td>
                                                        @if(Auth::user()->role == 'parents')
                                                        <td>
                                                            @php
                                                                // Calculate total raised from approved donations for this student
                                                                $totalRaised = \App\Models\Donation::where('user_id', $item->id)
                                                                    ->where('status', 1)
                                                                    ->sum('amount');
                                                            @endphp
                                                            ${{ number_format($totalRaised, 2) }}
                                                        </td>
                                                        @endif
                                                        <td>
                                                            @if ($item->status == 1)
                                                                <span class="badge bg-success">Approved</span>
                                                            @else
                                                                <span class="badge bg-warning">Pending</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="/users/student/profile/{{ $item->id }}" class="btn btn-sm btn-primary me-1" title="Edit Profile">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if(Auth::user()->role == 'parents' && $item->status != 1)
                                                                <a href="/admins/student/approve/{{ $item->id }}" class="btn btn-sm btn-success" title="Approve">
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
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Only initialize DataTable if there are rows with data
                    @if (!$data->isEmpty())
                    let table = new DataTable('.table', {
                        dom: 'Bfrtip',
                        pageLength: 25,
                        buttons: [
                            {
                                extend: 'csv',
                                text: 'Export CSV',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true; // export all if none checked
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)' // Exclude checkbox and action columns
                                }
                            },
                            {
                                extend: 'excel',
                                text: 'Export Excel',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true;
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)'
                                }
                            },
                            {
                                extend: 'pdf',
                                text: 'Export PDF',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true;
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)'
                                }
                            },
                            {
                                extend: 'print',
                                text: 'Print',
                                exportOptions: {
                                    rows: function(idx, data, node) {
                                        let checked = $('.row-check:checked');
                                        if (checked.length === 0) return true;
                                        return $(node).find('.row-check').prop('checked');
                                    },
                                    columns: ':visible:not(:first-child):not(:last-child)'
                                }
                            }
                        ]
                    });
                    @endif
                });
            </script>
            
            <!-- Add Student Modal -->
            @if(Auth::user()->role == 'parents')
            <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('parent.add-student') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="addStudentModalLabel">Add Student/Child</h5>
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
                                    <div class="form-text">Email and password will be automatically generated</div>
                                </div>
                                <div class="mb-3">
                                    <label for="teacher_id" class="form-label">Select Teacher <span class="text-danger">*</span></label>
                                    <select class="form-select" id="teacher_id" name="teacher_id" required>
                                        <option value="">Choose a teacher</option>
                                        @if(isset($teachers))
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                {{-- <div class="mb-3">
                                    <label for="goal" class="form-label">Fundraising Goal ($)</label>
                                    <input type="number" class="form-control" id="goal" name="goal" min="0" step="0.01">
                                </div> --}}
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
        @endsection
