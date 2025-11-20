@extends('admin.main')

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
                                            View all Users.
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
                                    <div class="mb-3">
                                        <label for="websiteFilter" class="form-label">Filter by Website:</label>
                                        <select id="websiteFilter" class="form-select" style="max-width:300px;">
                                            <option value="">All Websites</option>
                                            @foreach($websites as $website)
                                                <option value="{{ $website->name }}">{{ $website->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Website</th>
                                                {{-- <th>Last Name</th> --}}
                                                <th>Email</th>
                                                <th>Photo</th>
                                                <th>Goal</th>
                                                <th>Size</th>
                                                <th>Grade</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        {{-- @php
                                            dd($data);
                                        @endphp --}}
                                        <tbody>
                                            @if ($data->isEmpty())
                                                <tr>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                    <td colspan="1" class="text-center">No donations found.</td>
                                                </tr>
                                            @else
                                                @foreach ($data as $item)
                                                @if ($item->role != 'admin')
                                                    <tr>
                                                        <td>{{ $item->id }}</td>
                                                        <td>{{ $item->fist_name }} {{ $item->last_name }}</td>
                                                        <td>{{ $item->website->name }}</td>
                                                        <td>{{ $item->email }}</td>
                                                        <td>
                                                            <img src="{{ asset($item->photo ?? null) }}" width="200px">
                                                        </td>
                                                        <td>${{ $item->goal }}</td>
                                                        <td>{{ $item->size }}</td>
                                                        <td>{{ $item->grade }}</td>
                                                        <td>
                                                            @if ($item->status == 1)
                                                                <a href="#" class="btn btn-success">Approved</a>

                                                            @else

                                                                <a href="/admins/student/approve/{{ $item->id }}" class="btn btn-danger">Approve</a>

                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
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
                    // Initialize DataTable with export/import buttons
                    let table = new DataTable('.table', {
                        dom: 'Bfrtip',
                        pageLength: 25,
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                    });
                    $('#websiteFilter').on('change', function() {
                        let val = $(this).val();
                        if(val) {
                            table.column(3).search('^' + val + '$', true, false).draw();
                        } else {
                            table.column(3).search('').draw();
                        }
                    });
                });

            </script>
        @endsection
