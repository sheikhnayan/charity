@extends('user.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('user/extra.css') }}">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        .forms-wizard li.done em::before,
        .lnr-checkmark-circle::before {
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
                                                        <img width="42" class="rounded" alt="{{ $currentWebsite->name }}"
                                                            src="{{ asset('uploads/' . $currentWebsite->setting->logo) }}">
                                                    </div>
                                                </div>

                                                <div class="widget-content-left">
                                                    <div class="widget-heading">
                                                        {{ $currentWebsite->name }}
                                                    </div>
                                                    <div class="fs-6 mt-2">
                                                        <i class="fas fa-link link-info me-1 btn-clipboard" role="button"
                                                            data-clipboard-text="http://{{ $currentWebsite->domain }}/profile/{{ $user->id }}-{{ $user->name }}-{{ $user->last_name }}"></i>
                                                        <a href="http://{{ $currentWebsite->domain }}/profile/{{ $user->id }}-{{ $user->name }}-{{ $user->last_name }}"
                                                            class="link-info"
                                                            target="_blank">{{ $currentWebsite->domain }}/profile/{{ $user->id }}-{{ $user->name }}-{{ $user->last_name }}</a>
                                                    </div>
                                                </div>

                                                <div class="widget-content-right">
                                                    <div class="btn-group d-none d-md-inline-flex me-2" role="group">
                                                        <a href="/profile/{{ $user->id }}-{{ $user->name }}-{{ $user->last_name }}"
                                                            class="btn btn-info btn-hover-info" target="_blank">
                                                            <i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i>
                                                            <span>View</span>
                                                        </a>

                                                        <button type="button" class="btn btn-success btn-hover-info"
                                                            data-bs-toggle="modal" data-bs-target="#modal-share">
                                                            <i class="fa-solid fa-share-nodes fa-fw" aria-hidden="true"></i>
                                                            <span>Share</span>
                                                        </button>
                                                        
                                                        <button type="button" class="btn btn-primary btn-hover-info" onclick="copyProfileUrl()">
                                                            <i class="fa-solid fa-copy fa-fw" aria-hidden="true"></i>
                                                            <span>Copy URL</span>
                                                        </button>
                                                    </div>
                                                    
                                                    <a href="/users/student" class="btn btn-secondary">
                                                        <i class="fa-solid fa-arrow-left fa-fw" aria-hidden="true"></i>
                                                        <span>Back to Students</span>
                                                    </a>
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
                                            Student Profile
                                        </span>
                                        <div class="page-title-subheading">
                                            Edit profile information.
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
                                            <a href="/users/student">My Students</a>
                                            <i class="fas fa-chevron-right ms-1"></i>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                            Edit Profile
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
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-primary">
                                            <div>
                                                <h5 class="menu-header-title">
                                                    <a href="{{ $user->website->domain }}/profile/{{ $user->id }}-{{ $user->name }}-{{ $user->last_name }}"
                                                        class="link-light">
                                                        {{ $user->fist_name }} {{ $user->last_name }}
                                                    </a>
                                                </h5>
                                                <h6 class="menu-header-subtitle text-capitalize">
                                                    {{ $user->role }}
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
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            <form action="{{ route('parent.update-student', $user->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row gy-3">

                                    <div class="col-12">
                                        <label for="goal" class="form-label">Fundraising Goal</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="goal"
                                                name="goal" value="{{ $user->goal }}">
                                            <span class="input-group-text">.00 USD</span>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="name" class="form-label required">
                                            Full Name
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $user->name }}" required>
                                    </div>

                                    {{-- <div class="col-12">
                                        <label for="email" class="form-label required">
                                            Email
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $user->email }}" readonly>
                                        <div class="form-text">Email cannot be changed</div>
                                    </div> --}}

                                    <div class="col-12">
                                        <label for="description" class="form-label ">
                                            Profile Description
                                        </label>
                                        <textarea class="form-control text-editor" id="description" name="description"
                                            rows="3" style="visibility: hidden;">
                                            {!! $user->description !!}
                                        </textarea>
                                    </div>

                                    <div class="col-12">
                                        <h5 class="text-primary">
                                            Profile Photo
                                        </h5>
                                        @if($user->photo)
                                            <img src="{{ asset($user->photo) }}" width="150px" class="mb-3">
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <label for="photo" class="form-label ">
                                            Upload New Photo
                                        </label>
                                        <input class="form-control" type="file" id="photo-image-file" name="photo"
                                            accept="image/png, image/gif, image/jpeg, image/jpg">
                                        <div class="form-text">The recommended format for the profile picture should be
                                            a square.</div>
                                    </div>

                                </div>

                                <div class="sticky-save-button-container">
                                    <div class="sticky-save-button-inner">
                                        <button class="btn-hover-shine btn-wide btn btn-shadow btn-success btn-lg w-100 "
                                            type="submit" id="">
                                            Save Changes
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
        function copyProfileUrl() {
            const profileUrl = window.location.origin + '/profile/{{ $user->id }}-{{ $user->name }}-{{ $user->last_name }}';
            
            // Create temporary textarea
            const textarea = document.createElement('textarea');
            textarea.value = profileUrl;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                document.execCommand('copy');
                // Show success message
                const btn = event.target.closest('button');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check fa-fw"></i><span>Copied!</span>';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-primary');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-primary');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy:', err);
                alert('Failed to copy URL. Please copy manually: ' + profileUrl);
            }
            
            document.body.removeChild(textarea);
        }
        </script>

        <script>
            ClassicEditor
                .create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });
        </script>
@endsection
