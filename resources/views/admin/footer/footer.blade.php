@extends('admin.main')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                    <div class="card p-4">
                        <form action="{{ route('admin.footer.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <div class="row gy-3" bis_skin_checked="1">

                                <div class="col-md-6 col-lg-4" data-step="1" data-title="Header status"
                                    data-intro="To completely remove the header section from your website, change the status to disabled."
                                    bis_skin_checked="1">
                                    <label for="status" class="form-label">
                                        Status
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info  "
                                        data-title="Header status"
                                        data-description="To completely remove the header section from your website, change the status to disabled."></i>
                                    <select class="form-select" id="status" name="status">
                                        <option {{ $data->status == 1 ? 'selected' : '' }} value="1">
                                            Enabled
                                        </option>
                                        <option {{ $data->status == 0 ? 'selected' : '' }} value="0">
                                            Disabled
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-4" data-step="3" data-title="Header text color"
                                    data-intro="Choose a color for the header text." bis_skin_checked="1">
                                    <label for="text_color" class="form-label">
                                        Footer text color
                                    </label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="text_color_picker"
                                            value="{{ $data->color ?? '#000000' }}" title="Choose your color"
                                            style="max-width: 3rem;">
                                        <input type="text" class="form-control" id="text_color" name="color"
                                            value="{{ $data->color ?? '#000000' }}" placeholder="#000000 or color name">
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const colorInput = document.getElementById('text_color_picker');
                                            const textInput = document.getElementById('text_color');
                                            // Sync color picker to text
                                            colorInput.addEventListener('input', function() {
                                                textInput.value = colorInput.value;
                                            });
                                            // Sync text to color picker if valid hex
                                            textInput.addEventListener('input', function() {
                                                const val = textInput.value.trim();
                                                if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                    colorInput.value = val;
                                                }
                                            });
                                        });
                                    </script>
                                </div>

                                <div class="col-md-6 col-lg-4" data-step="4" data-title="Header background"
                                    data-intro="Choose a background color for the header of your website."
                                    bis_skin_checked="1">
                                    <label for="background_color" class="form-label">
                                        Footer background
                                    </label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="background_color_picker" value="{{ $data->background ?? '#ffffff' }}" title="Choose background color" style="max-width: 3rem;">
                                        <input type="text" class="form-control" id="background_color" name="background" value="{{ $data->background ?? '#ffffff' }}" placeholder="#ffffff or color name">
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const colorInput = document.getElementById('background_color_picker');
                                            const textInput = document.getElementById('background_color');
                                            colorInput.addEventListener('input', function() {
                                                textInput.value = colorInput.value;
                                            });
                                            textInput.addEventListener('input', function() {
                                                const val = textInput.value.trim();
                                                if (/^#([0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/.test(val)) {
                                                    colorInput.value = val;
                                                }
                                            });
                                        });
                                    </script>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Menu
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <select class="form-select" id="display_menu" name="menu">
                                        <option value="1" {{ $data->menu == 1 ? 'selected' : '' }}>
                                            Yes, display the menu
                                        </option>
                                        <option value="0" {{ $data->menu == 0 ? 'selected' : '' }}>
                                            No, hide the menu
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Message
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="message" value="{{ $data->message }}" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Copyright
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="copy_right" value="{{ $data->copy_right }}" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Privacy, Refund and Terms and services page link
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <select class="form-select" id="display_menu" name="privacy">
                                        <option value="1" {{ $data->privacy == 1 ? 'selected' : '' }}>
                                            Yes, display
                                        </option>
                                        <option value="0" {{ $data->privacy == 0 ? 'selected' : '' }}>
                                            No, hide
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Social
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <select class="form-select" id="display_menu" name="social">
                                        <option value="1" {{ $data->social == 1 ? 'selected' : '' }}>
                                            Yes
                                        </option>
                                        <option value="0" {{ $data->social == 0 ? 'selected' : '' }}>
                                            No
                                        </option>
                                    </select>
                                </div>

                                <!-- Investment Website Specific Fields -->
                                <div class="col-12 mt-4">
                                    <h5 class="mb-3">Investment Website Content</h5>
                                    <small class="text-muted">These fields are specifically for investment type websites</small>
                                </div>

                                <div class="col-12">
                                    <label for="disclaimer_text" class="form-label">
                                        Disclaimer Text
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info" 
                                        data-title="Disclaimer Text" 
                                        data-description="Rich text content for the disclaimer section of investment website footers."></i>
                                    <textarea name="disclaimer_text" id="disclaimer_text" class="form-control" rows="4">{{ $data->disclaimer_text ?? '' }}</textarea>
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="description_text" class="form-label">
                                        Description Text
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info" 
                                        data-title="Description Text" 
                                        data-description="Rich text content for the description section of investment website footers."></i>
                                    <textarea name="description_text" id="description_text" class="form-control" rows="4">{{ $data->description_text ?? '' }}</textarea>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="background_image_desktop" class="form-label">
                                        Background Image (Desktop)
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info" 
                                        data-title="Desktop Background" 
                                        data-description="Background image for footer on desktop devices."></i>
                                    <input type="file" name="background_image_desktop" class="form-control" accept="image/*">
                                    @if($data->background_image_desktop)
                                        <small class="text-muted mt-1">Current: {{ $data->background_image_desktop }}</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="background_image_mobile" class="form-label">
                                        Background Image (Mobile)
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info" 
                                        data-title="Mobile Background" 
                                        data-description="Background image for footer on mobile devices."></i>
                                    <input type="file" name="background_image_mobile" class="form-control" accept="image/*">
                                    @if($data->background_image_mobile)
                                        <small class="text-muted mt-1">Current: {{ $data->background_image_mobile }}</small>
                                    @endif
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Facebook
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="facebook" value="{{ $data->facebook }}" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Instagram
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="instagram" value="{{ $data->instagram }}" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        X
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="twitter" value="{{ $data->twitter }}" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Linkedin
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="linkedin" value="{{ $data->linkedin }}" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Youtube
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="youtube" value="{{ $data->youtube }}" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Pinterest
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="pinterest" value="{{ $data->pinterest }}" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        Tiktok
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="tiktok" value="{{ $data->tiktok }}" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="display_menu" class="form-label text-capitalize">
                                        BlueSky
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info  btn-modal-info"></i>
                                    <input type="text" name="blue_sky" value="{{ $data->blue_sky }}" class="form-control">
                                </div>
                            </div>
                            <div class="sticky-save-button-container mt-4" bis_skin_checked="1">
                                <div class="sticky-save-button-inner" bis_skin_checked="1">
                                    <button class="btn-hover-shine btn-wide btn btn-shadow btn-success btn-lg w-100 "
                                        type="submit" id="">
                                        Save
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- CKEditor Initialization -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize CKEditor for disclaimer text
                    ClassicEditor
                        .create(document.querySelector('#disclaimer_text'), {
                            toolbar: [
                                'heading', '|',
                                'bold', 'italic', 'link', '|',
                                'bulletedList', 'numberedList', '|',
                                'outdent', 'indent', '|',
                                'blockQuote', 'insertTable', '|',
                                'undo', 'redo', '|',
                                'sourceEditing'
                            ],
                            heading: {
                                options: [
                                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                                ]
                            }
                        })
                        .catch(error => {
                            console.error('CKEditor disclaimer_text error:', error);
                        });

                    // Initialize CKEditor for description text
                    ClassicEditor
                        .create(document.querySelector('#description_text'), {
                            toolbar: [
                                'heading', '|',
                                'bold', 'italic', 'link', '|',
                                'bulletedList', 'numberedList', '|',
                                'outdent', 'indent', '|',
                                'blockQuote', 'insertTable', '|',
                                'undo', 'redo', '|',
                                'sourceEditing'
                            ],
                            heading: {
                                options: [
                                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                                ]
                            }
                        })
                        .catch(error => {
                            console.error('CKEditor description_text error:', error);
                        });
                });
            </script>
        @endsection
