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
                                            Page
                                        </span>
                                    </div>

                                </div>
                                <div class="page-title-actions">
                                </div>
                            </div>

                            <div class="page-title-subheading opacity-10 mt-3"
                                style="white-space: nowrap; overflow-x: auto;">
                                <nav class="" aria-label="breadcrumb">
                                    <ol class="breadcrumb" style="float: left">

                                        <li class="breadcrumb-item opacity-10">
                                            <a href="/admins">
                                                <i class="fas fa-home" role="img" aria-hidden="true"></i>
                                                <span class="visually-hidden">Home</span>
                                            </a>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>

                                        <li class="breadcrumb-item ">
                                            Setting
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            Page
                                        </li>

                                    </ol>

                                    <div class="btn-group" role="group" aria-label="Basic example" style="float: right">
                                        <a href="/admins/page/create" class="btn btn-primary">Add Website Page</a>
                                        <a href="/admins/page/create?main_site=1" class="btn btn-success">Add Main Site Page</a>
                                    </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg">
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-info-circle"></i> How Pages Work</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>🌐 Main Site Pages (fundconnects.com):</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Accessible only on main domain</li>
                                                <li>Independent of individual websites</li>
                                                <li>Example: /page/terms, /page/privacy</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>📄 Fundraiser Websites:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Each page is a separate URL</li>
                                                <li>Traditional multi-page navigation</li>
                                                <li>Example: /page/about, /page/services</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>💼 Investment Websites:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>All pages appear as sections on homepage</li>
                                                <li>Single-page with smooth scrolling navigation</li>
                                                <li>Menu clicks scroll to sections</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Main Site Pages Section --}}
                        @if(!empty($mainSitePages) && $mainSitePages->count() > 0)
                        <div class="row mb-4">
                            <div class="col-lg">
                                <h4><i class="fas fa-globe"></i> Main Site Pages (fundconnects.com)</h4>
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-success" style="background: #fff !important;">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>SI</th>
                                                <th>Name</th>
                                                <th>URL Preview</th>
                                                <th>Position/Order</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mainSitePages as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        {{ $item->name }}
                                                        @if($item->is_homepage)
                                                            <span class="badge bg-primary ms-1">
                                                                <i class="fas fa-home me-1"></i>Homepage
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item->is_homepage)
                                                            <code>fundconnects.com</code>
                                                            <br><small class="text-success">
                                                                <i class="fas fa-check-circle me-1"></i>Accessible via domain root
                                                            </small>
                                                        @else
                                                            <code>fundconnects.com/page/{{ str_replace(' ', '-', strtolower($item->name)) }}</code>
                                                            <br><small class="text-muted">Only accessible on main domain</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $item->position ?? 0 }}</span>
                                                        <small class="text-muted d-block">Menu order</small>
                                                    </td>
                                                    <td>
                                                        @if ($item->status == 1)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="/admins/page/show/{{ $item->id }}" class="btn btn-success btn-sm">Show</a>
                                                        <a href="/admins/page/edit/{{ $item->id }}" class="btn btn-primary btn-sm">Edit</a>
                                                        <a href="/admins/page/delete/{{ $item->id }}" class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Website Pages Section --}}
                        <div class="row">
                            <div class="col-lg">
                                <h4><i class="fas fa-desktop"></i> Website Pages</h4>
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary" style="background: #fff !important;">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>SI</th>
                                                <th>Name</th>
                                                <th>Website</th>
                                                <th>Website Type</th>
                                                <th>Position/Order</th>
                                                <th>Display Type</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($data->isEmpty())
                                                <tr>
                                                    <td colspan="8" class="text-center">No website pages found.</td>
                                                </tr>
                                            @else
                                                @foreach ($data->where('is_main_site', false) as $key => $item)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            {{ $item->name }}
                                                            @if($item->is_homepage)
                                                                <span class="badge bg-primary ms-1">
                                                                    <i class="fas fa-home me-1"></i>Homepage
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->website->name ?? 'Main Site'}}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $item->website->type ?? 'Main Site' == 'investment' ? 'primary' : 'success' }}">
                                                                {{ ucfirst($item->website->type ?? 'Main Site') }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">{{ $item->position ?? 0 }}</span>
                                                            @if ($item->website->type ?? 'Main Site' == 'investment')
                                                                <small class="text-muted d-block">Section order</small>
                                                            @else
                                                                <small class="text-muted d-block">Menu order</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->website->type ?? "Main Site" == 'investment')
                                                                <small class="text-muted">Section on homepage</small>
                                                            @else
                                                                <small class="text-muted">Separate page</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->status == 1)
                                                                <span class="badge bg-success">Active</span>
                                                            @else
                                                                <span class="badge bg-secondary">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="/admins/page/show/{{ $item->id }}" class="btn btn-success btn-sm">Show</a>
                                                            <a href="/admins/page/edit/{{ $item->id }}" class="btn btn-primary btn-sm">Edit</a>
                                                            <a href="/admins/page/delete/{{ $item->id }}" class="btn btn-danger btn-sm">Delete</a>
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
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Initialize DataTable with default search disabled
                    let table = new DataTable('.table', {
                        pageLength: 25
                    });
                });
            </script>
        @endsection
