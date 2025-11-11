@extends('admin.main')

@section('content')
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Quill Editor JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    
    <style>
    /* Custom Fonts @font-face declarations */
    @if(isset($customFonts) && $customFonts->count() > 0)
    @foreach($customFonts as $font)
    @font-face {
        font-family: '{{ $font->font_family }}';
        src: url('{{ asset('storage/' . $font->file_path) }}') format('{{ $font->file_format == 'ttf' ? 'truetype' : ($font->file_format == 'otf' ? 'opentype' : $font->file_format) }}');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }
    
    /* Quill font dropdown labels */
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="{{ $font->font_family }}"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="{{ $font->font_family }}"]::before {
        content: '{{ $font->font_name }}';
        font-family: '{{ $font->font_family }}', sans-serif;
    }
    
    /* Apply custom font classes */
    .ql-font-{{ $font->font_family }} {
        font-family: '{{ $font->font_family }}', sans-serif !important;
    }
    @endforeach
    @endif
    
    /* System font classes */
    .ql-font-arial { font-family: Arial, sans-serif !important; }
    .ql-font-helvetica { font-family: Helvetica, sans-serif !important; }
    .ql-font-times { font-family: 'Times New Roman', serif !important; }
    .ql-font-georgia { font-family: Georgia, serif !important; }
    .ql-font-verdana { font-family: Verdana, sans-serif !important; }
    .ql-font-courier { font-family: 'Courier New', monospace !important; }
    .ql-font-outfit { font-family: 'Outfit', sans-serif !important; }
    
    /* System font dropdown labels */
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="arial"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="arial"]::before {
        content: 'Arial';
        font-family: Arial, sans-serif;
    }
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="helvetica"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="helvetica"]::before {
        content: 'Helvetica';
        font-family: Helvetica, sans-serif;
    }
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="times"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="times"]::before {
        content: 'Times New Roman';
        font-family: 'Times New Roman', serif;
    }
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="georgia"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="georgia"]::before {
        content: 'Georgia';
        font-family: Georgia, serif;
    }
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="verdana"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="verdana"]::before {
        content: 'Verdana';
        font-family: Verdana, sans-serif;
    }
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="courier"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="courier"]::before {
        content: 'Courier New';
        font-family: 'Courier New', monospace;
    }
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="outfit"]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="outfit"]::before {
        content: 'Outfit';
        font-family: 'Outfit', sans-serif;
    }
    </style>

    <!-- Content wrapper -->
    <div class="content-wrapper">
```
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
                                    <label for="background_type" class="form-label">
                                        Footer Background Type
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info"
                                        data-title="Background Type"
                                        data-description="Choose whether to use a solid color or background images for the footer."></i>
                                    <select class="form-select" id="background_type" name="background_type">
                                        <option value="color" {{ ($data->background_type ?? 'color') == 'color' ? 'selected' : '' }}>
                                            Use Color
                                        </option>
                                        <option value="image" {{ ($data->background_type ?? 'color') == 'image' ? 'selected' : '' }}>
                                            Use Background Image
                                        </option>
                                    </select>
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
                                    <div id="disclaimer_editor" style="height: 200px;"></div>
                                    <input type="hidden" name="disclaimer_text" id="disclaimer_text" value="{!! htmlentities($data->disclaimer_text ?? '') !!}">
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="description_text" class="form-label">
                                        Description Text
                                    </label>
                                    <i role="button" class="fa-solid fa-circle-info text-info btn-modal-info" 
                                        data-title="Description Text" 
                                        data-description="Rich text content for the description section of investment website footers."></i>
                                    <div id="description_editor" style="height: 200px;"></div>
                                    <input type="hidden" name="description_text" id="description_text" value="{!! htmlentities($data->description_text ?? '') !!}">
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

            <!-- Custom Quill CSS Styles -->
            <style>
            /* Custom Quill styles for better font size support */
            .ql-snow .ql-picker.ql-size .ql-picker-label::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item::before {
              content: '14px';
            }
            .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="10px"]::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="10px"]::before {
              content: '10px';
            }
            .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="12px"]::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="12px"]::before {
              content: '12px';
            }
            .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="14px"]::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="14px"]::before {
              content: '14px';
            }
            .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="16px"]::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="16px"]::before {
              content: '16px';
            }
            .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="18px"]::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="18px"]::before {
              content: '18px';
            }
            .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="20px"]::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="20px"]::before {
              content: '20px';
            }
            .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="24px"]::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="24px"]::before {
              content: '24px';
            }
            .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="28px"]::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="28px"]::before {
              content: '28px';
            }
            .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="32px"]::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="32px"]::before {
              content: '32px';
            }
            .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="36px"]::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="36px"]::before {
              content: '36px';
            }
            .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="48px"]::before,
            .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="48px"]::before {
              content: '48px';
            }
            </style>

            <!-- Quill Editor Initialization -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Function to decode HTML entities
                    function decodeHtml(html) {
                        var txt = document.createElement("textarea");
                        txt.innerHTML = html;
                        return txt.value;
                    }

                    // Register custom font sizes using class attributor like page-builder
                    var SizeClass = Quill.import('attributors/class/size');
                    SizeClass.whitelist = ['10px', '12px', '14px', '16px', '18px', '20px', '24px', '28px', '32px', '36px', '48px'];
                    Quill.register(SizeClass, true);

                    // Register custom font families
                    var FontClass = Quill.import('attributors/class/font');
                    var defaultFonts = ['arial', 'helvetica', 'times', 'georgia', 'verdana', 'courier', 'outfit'];
                    
                    // Add custom uploaded fonts from database
                    @if(isset($customFonts) && $customFonts->count() > 0)
                    var customFonts = [
                        @foreach($customFonts as $font)
                        '{{ $font->font_family }}',
                        @endforeach
                    ];
                    @else
                    var customFonts = [];
                    @endif
                    
                    // Combine default and custom fonts
                    FontClass.whitelist = defaultFonts.concat(customFonts);
                    Quill.register(FontClass, true);

                    // Initialize Quill for disclaimer text
                    var disclaimerQuill = new Quill('#disclaimer_editor', {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                                [{ 'font': FontClass.whitelist }],
                                [{ 'size': SizeClass.whitelist }],
                                [{ 'color': [] }, { 'background': [] }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ 'align': [] }],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                [{ 'indent': '-1'}, { 'indent': '+1' }],
                                ['blockquote', 'code-block'],
                                ['link'],
                                ['clean']
                            ]
                        }
                    });

                    // Set initial content for disclaimer
                    var disclaimerContent = document.getElementById('disclaimer_text').value;
                    if (disclaimerContent) {
                        disclaimerQuill.root.innerHTML = decodeHtml(disclaimerContent);
                    }

                    // Update hidden input when content changes
                    disclaimerQuill.on('text-change', function() {
                        document.getElementById('disclaimer_text').value = disclaimerQuill.root.innerHTML;
                    });

                    // Initialize Quill for description text
                    var descriptionQuill = new Quill('#description_editor', {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                                [{ 'font': FontClass.whitelist }],
                                [{ 'size': SizeClass.whitelist }],
                                [{ 'color': [] }, { 'background': [] }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ 'align': [] }],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                [{ 'indent': '-1'}, { 'indent': '+1' }],
                                ['blockquote', 'code-block'],
                                ['link'],
                                ['clean']
                            ]
                        }
                    });

                    // Set initial content for description
                    var descriptionContent = document.getElementById('description_text').value;
                    if (descriptionContent) {
                        descriptionQuill.root.innerHTML = decodeHtml(descriptionContent);
                    }

                    // Update hidden input when content changes
                    descriptionQuill.on('text-change', function() {
                        document.getElementById('description_text').value = descriptionQuill.root.innerHTML;
                    });

                    // Ensure content is saved before form submission
                    document.querySelector('form').addEventListener('submit', function() {
                        document.getElementById('disclaimer_text').value = disclaimerQuill.root.innerHTML;
                        document.getElementById('description_text').value = descriptionQuill.root.innerHTML;
                    });


                });
            </script>
        @endsection
