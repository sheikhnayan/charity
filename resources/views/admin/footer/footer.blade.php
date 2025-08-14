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
        @endsection
