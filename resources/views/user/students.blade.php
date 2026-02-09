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
                                                My Participants
                                            @else
                                                Participants
                                            @endif
                                        </span>
                                        <div class="page-title-subheading">
                                            @if(Auth::user()->role == 'parents')
                                                Manage your Participants.
                                            @else
                                                View all Participants.
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="page-title-actions">
                                    @if(Auth::user()->role == 'parents')
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                                            <i class="fas fa-plus me-2"></i>Add Participants
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
                                                <th>Parent/Guardian</th>
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
                                                    <td colspan="{{ Auth::user()->role == 'parents' ? '10' : '9' }}" class="text-center">No student found.</td>
                                                </tr>
                                            @else
                                                @foreach ($data as $item)
                                                    <tr>
                                                        <td>{{ $item->id }}</td>
                                                        <td>
                                                            <a href="/users/student/profile/{{ $item->id }}" class="text-decoration-none fw-bold text-primary">
                                                                {{ $item->name }} {{ $item->last_name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $item->email }}</td>
                                                        <td>
                                                            <span class="badge bg-info">{{ ucfirst($item->role) }}</span>
                                                        </td>
                                                        <td>{{ $item->teacher->name ?? 'N/A' }}</td>
                                                        <td>
                                                            @if($item->parent)
                                                                <a href="/users/profile" class="text-decoration-none text-primary fw-semibold">
                                                                    {{ $item->parent->name }} {{ $item->parent->last_name }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
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
                                                            <a href="/profile/{{ $item->id }}-{{ str_replace(' ', '-', $item->name) }}-{{ str_replace(' ', '-', $item->last_name) }}" class="btn btn-sm btn-info me-1" title="View Frontend Profile" target="_blank">
                                                                <i class="fas fa-external-link-alt"></i>
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
            <!-- Select2 JS -->
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
                
                // Initialize Select2 when modal is shown
                $('#addStudentModal').on('shown.bs.modal', function () {
                    if (!$('.teacher-select').hasClass('select2-hidden-accessible')) {
                        $('.teacher-select').select2({
                            placeholder: 'Search and select a teacher',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('#addStudentModal')
                        });
                    }
                });
                
                // Destroy Select2 when modal is hidden to prevent duplicates
                $('#addStudentModal').on('hidden.bs.modal', function () {
                    if ($('.teacher-select').hasClass('select2-hidden-accessible')) {
                        $('.teacher-select').select2('destroy');
                    }
                });
            </script>
            
            <!-- Add Student Modal -->
            @if(Auth::user()->role == 'parents')
            <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="addStudentForm" action="{{ route('parent.add-student') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="addStudentModalLabel">Add Participant</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
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
                                <div class="mb-3">
                                    <label for="modal_goal" class="form-label">Fundraising Goal</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="modal_goal" name="goal" min="0" step="0.01">
                                        <span class="input-group-text">.00 USD</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="modal_tshirt_size" class="form-label">T-Shirt Size</label>
                                    <select class="form-select" id="modal_tshirt_size" name="tshirt_size">
                                        <option value="">Select a size</option>
                                        <option value="Youth XS">Youth XS</option>
                                        <option value="Youth Small">Youth Small</option>
                                        <option value="Youth Medium">Youth Medium</option>
                                        <option value="Youth Large">Youth Large</option>
                                        <option value="Adult Small">Adult Small</option>
                                        <option value="Adult Medium">Adult Medium</option>
                                        <option value="Adult Large">Adult Large</option>
                                        <option value="Adult XL">Adult XL</option>
                                        <option value="Adult XXL">Adult XXL</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="modal_description" class="form-label">Profile Description</label>
                                    <textarea class="form-control" id="modal_description" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="modal_photo" class="form-label">Upload Photo</label>
                                    <input class="form-control @error('photo') is-invalid @enderror" type="file" id="modal_photo" name="photo" accept="image/png, image/gif, image/jpeg, image/jpg, image/pjpeg">
                                    <div class="form-text">Maximum file size: <strong>5MB</strong> | Accepted formats: <strong>JPG, JPEG, PNG, GIF</strong> | Recommended: Square format</div>
                                    <div class="invalid-feedback" id="modal_photo_error" style="@error('photo') display: block; @else display: none; @enderror">@error('photo'){{ $message }}@enderror</div>
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

            <script>
            $(document).ready(function() {
                const addStudentForm = document.getElementById('addStudentForm');
                const participantLoader = document.getElementById('participant-loader');
                const photoInput = document.getElementById('modal_photo');

                if (addStudentForm) {
                    addStudentForm.addEventListener('submit', function(e) {
                        // Check if photo has validation error before showing loader
                        if (photoInput && photoInput.classList.contains('is-invalid')) {
                            // Don't show loader if there's a validation error
                            return false;
                        }
                        
                        if (participantLoader) {
                            participantLoader.style.display = 'flex';
                        }
                        document.body.classList.add('page-locked');
                        window.onbeforeunload = function() {
                            return 'Please wait while the participant is being added.';
                        };
                    });
                }
                
                // Hide loader if there are backend validation errors
                @if($errors->any())
                    if (participantLoader) {
                        participantLoader.style.display = 'none';
                    }
                    document.body.classList.remove('page-locked');
                    window.onbeforeunload = null;
                @endif
            });
            
            document.addEventListener('DOMContentLoaded', function() {
                // Reopen modal if there are backend validation errors
                @if($errors->has('photo') || $errors->has('first_name') || $errors->has('last_name') || $errors->has('teacher_id'))
                    var addStudentModal = new bootstrap.Modal(document.getElementById('addStudentModal'));
                    addStudentModal.show();
                    
                    // Restore form values
                    @if(old('first_name'))
                        document.getElementById('first_name').value = "{{ old('first_name') }}";
                    @endif
                    @if(old('last_name'))
                        document.getElementById('last_name').value = "{{ old('last_name') }}";
                    @endif
                    @if(old('teacher_id'))
                        document.getElementById('teacher_id').value = "{{ old('teacher_id') }}";
                    @endif
                    @if(old('goal'))
                        document.getElementById('modal_goal').value = "{{ old('goal') }}";
                    @endif
                    @if(old('tshirt_size'))
                        document.getElementById('modal_tshirt_size').value = "{{ old('tshirt_size') }}";
                    @endif
                    @if(old('description'))
                        document.getElementById('modal_description').value = `{{ old('description') }}`;
                    @endif
                @endif
                
                const photoInput = document.getElementById('modal_photo');
                const photoError = document.getElementById('modal_photo_error');
                const form = document.getElementById('addStudentForm');
                
                if (photoInput && form) {
                    photoInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        
                        if (file) {
                            // Clear previous errors only when a new file is selected
                            photoInput.classList.remove('is-invalid');
                            photoError.style.display = 'none';
                            photoError.textContent = '';
                            
                            // Check file size (5MB max)
                            const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                            if (file.size > maxSize) {
                                photoInput.classList.add('is-invalid');
                                photoError.style.display = 'block';
                                photoError.textContent = 'File size exceeds 5MB. Please choose a smaller image.';
                                e.target.value = '';
                                return;
                            }
                            
                            // Get file extension
                            const fileName = file.name.toLowerCase();
                            const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                            const fileExtension = fileName.split('.').pop();
                            
                            // Check file extension first (catches HEIC, WEBP, etc.)
                            if (!allowedExtensions.includes(fileExtension)) {
                                photoInput.classList.add('is-invalid');
                                photoError.style.display = 'block';
                                photoError.textContent = `Unsupported file format (.${fileExtension}). Please upload JPG, JPEG, PNG, or GIF images only.`;
                                e.target.value = '';
                                return;
                            }
                            
                            // Check file type
                            const allowedTypes = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg'];
                            if (!allowedTypes.includes(file.type)) {
                                photoInput.classList.add('is-invalid');
                                photoError.style.display = 'block';
                                photoError.textContent = 'Invalid file type. Please upload an image file (PNG, JPG, GIF).';
                                e.target.value = '';
                                return;
                            }
                        }
                    });
                    
                    // Prevent form submission if there's a validation error
                    form.addEventListener('submit', function(e) {
                        if (photoInput.classList.contains('is-invalid')) {
                            e.preventDefault();
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            photoError.style.display = 'block';
                            photoError.textContent = photoError.textContent || 'Please fix the file upload error before submitting.';
                            photoInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return false;
                        }
                    });
                }
            });
            </script>
        @endsection