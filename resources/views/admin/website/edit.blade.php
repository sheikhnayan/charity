@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

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
                                            Website
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
                                            Website
                                        </li>

                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg">
                                <div class="card-shadow-primary card-border text-white mb-3 card bg-primary" style="background: #fff !important;">
                                    <form action="{{ route('admin.website.update',[$data->id]) }}" method="post">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="first_name" class="form-label">First Name</label>
                                                        <input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name" value="{{ $data->user->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="last_name" class="form-label">Last Name</label>
                                                        <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Last Name" value="{{ $data->user->last_name ?? '' }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="type" class="form-label">Website Type</label>
                                                        <select name="type" class="form-control" id="type" required>
                                                            <option value="">Select Website Type</option>
                                                            <option value="fundraiser" {{ $data->type == 'fundraiser' ? 'selected' : '' }}>Fundraiser</option>
                                                            <option value="investment" {{ $data->type == 'investment' ? 'selected' : '' }}>Investment</option>
                                                        </select>
                                                        <small class="form-text text-muted">Choose whether this website is for fundraising or investment purposes.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ $data->user->email }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Password</label>
                                                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                                                        <small class="form-text text-muted">Leave blank to keep current password.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Website Name</label>
                                                        <input type="text" name="name" value="{{ $data->name }}" class="form-control" id="name" placeholder="Website Name" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Domain</label>
                                                        <input type="text" name="domain" value="{{ $data->domain }}" class="form-control" id="name" placeholder="Website Name" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select name="status" id="status" class="form-control">
                                                            <option {{ $data->status == 0 ? 'selected' : '' }} value="0">Deactive</option>
                                                            <option {{ $data->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Investment-specific fields -->
                                            <div class="row" id="investment-fields" style="display: {{ $data->type == 'investment' ? 'block' : 'none' }};">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="share_price" class="form-label">Share Price ($)</label>
                                                        <input type="number" name="share_price" class="form-control" id="share_price" placeholder="2.13" step="0.01" min="0.01" value="{{ $data->share_price ?? '' }}">
                                                        <small class="form-text text-muted">Price per share for investment calculations.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="min_investment" class="form-label">Minimum Investment ($)</label>
                                                        <input type="number" name="min_investment" class="form-control" id="min_investment" placeholder="1000" step="1" min="1" value="{{ $data->min_investment ?? '' }}">
                                                        <small class="form-text text-muted">Minimum amount required to invest.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="investment_tiers" class="form-label">Investment Tiers</label>
                                                        <input type="text" name="investment_tiers" class="form-control" id="investment_tiers" placeholder="1000,2500,5000,10000" value="{{ $data->investment_tiers ?? '' }}">
                                                        <small class="form-text text-muted">Comma-separated list of investment amounts to display as quick options.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="investment_disclaimer" class="form-label">Investment Disclaimer</label>
                                                        <div id="investment_disclaimer_editor" style="height: 200px;" data-content="{{ htmlspecialchars($data->investment_disclaimer ?? '', ENT_QUOTES, 'UTF-8') }}"></div>
                                                        <input type="hidden" name="investment_disclaimer" id="investment_disclaimer" value="{{ htmlspecialchars($data->investment_disclaimer ?? '', ENT_QUOTES, 'UTF-8') }}">
                                                        <small class="form-text text-muted">Legal disclaimer text with rich formatting options that will be displayed on the investment page.</small>
                                                        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="debugInvestmentDisclaimer()">Debug Content</button>
                                                    </div>
                                                </div>
                                                
                                                <!-- Sticky Footer Color Settings -->
                                                <div class="col-md-12">
                                                    <h5 class="mt-4 mb-3">Sticky Footer Button Colors</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sticky_footer_button_bg" class="form-label">Button Background Color</label>
                                                        <input type="color" name="sticky_footer_button_bg" class="form-control" id="sticky_footer_button_bg" value="{{ $data->sticky_footer_button_bg ?? '#007bff' }}">
                                                        <small class="form-text text-muted">Background color for the sticky Invest Now button.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sticky_footer_button_text" class="form-label">Button Text Color</label>
                                                        <input type="color" name="sticky_footer_button_text" class="form-control" id="sticky_footer_button_text" value="{{ $data->sticky_footer_button_text ?? '#ffffff' }}">
                                                        <small class="form-text text-muted">Text color for the sticky Invest Now button.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sticky_footer_text_color" class="form-label">Footer Text Color</label>
                                                        <input type="color" name="sticky_footer_text_color" class="form-control" id="sticky_footer_text_color" value="{{ $data->sticky_footer_text_color ?? '#333333' }}">
                                                        <small class="form-text text-muted">Color for text outside the button in the sticky footer.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sticky_footer_bg_color" class="form-label">Footer Background Color</label>
                                                        <input type="color" name="sticky_footer_bg_color" class="form-control" id="sticky_footer_bg_color" value="{{ $data->sticky_footer_bg_color ?? '#f8f9fa' }}">
                                                        <small class="form-text text-muted">Background color for the entire sticky footer section.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="preset_amounts" class="form-label">Preset Amounts</label>
                                                        <input type="text" name="preset_amounts" value="{{ $data->preset_amounts ?? '' }}" class="form-control" id="preset_amounts" placeholder="e.g. 100,500,1000">
                                                        <small class="form-text text-muted">Enter preset amounts separated by commas. These will be available for quick selection in auction/investment components.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="shares" class="form-label">Shares</label>
                                                        <input type="number" name="shares" value="{{ $data->shares ?? '' }}" class="form-control" id="shares" placeholder="Number of shares">
                                                        <small class="form-text text-muted">Specify the number of shares available for investment/auction.</small>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('admin.website.index') }}" class="btn btn-danger">Cancel</a>
                                            <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const websiteTypeSelect = document.getElementById('type');
                                                const investmentFields = document.getElementById('investment-fields');
                                                const sharePriceField = document.getElementById('share_price');
                                                const minInvestmentField = document.getElementById('min_investment');
                                                websiteTypeSelect.addEventListener('change', function() {
                                                    if (this.value === 'investment') {
                                                        investmentFields.style.display = 'block';
                                                        sharePriceField.required = true;
                                                        minInvestmentField.required = true;
                                                    } else {
                                                        investmentFields.style.display = 'none';
                                                        sharePriceField.required = false;
                                                        minInvestmentField.required = false;
                                                    }
                                                });
                                            });
                                            </script>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
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

            <!-- Quill Editor Initialization for Investment Disclaimer -->
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

                // Initialize Quill editor for investment disclaimer
                var investmentDisclaimerQuill = new Quill('#investment_disclaimer_editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
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

                // Function to decode HTML entities
                function decodeHtml(html) {
                    var txt = document.createElement("textarea");
                    txt.innerHTML = html;
                    return txt.value;
                }

                // Set initial content for investment disclaimer
                var investmentDisclaimerContent = document.getElementById('investment_disclaimer').value;
                console.log('Initial investment disclaimer content:', investmentDisclaimerContent);
                console.log('Raw data attribute:', document.getElementById('investment_disclaimer_editor').dataset.content);
                
                if (investmentDisclaimerContent && investmentDisclaimerContent.trim() !== '') {
                    try {
                        // First try direct assignment, then decoded if needed
                        if (investmentDisclaimerContent.includes('&')) {
                            var decodedContent = decodeHtml(investmentDisclaimerContent);
                            investmentDisclaimerQuill.root.innerHTML = decodedContent;
                        } else {
                            investmentDisclaimerQuill.root.innerHTML = investmentDisclaimerContent;
                        }
                        console.log('Loaded content into Quill editor');
                    } catch (error) {
                        console.error('Error loading content into Quill editor:', error);
                        // Fallback: try setting as plain text
                        investmentDisclaimerQuill.setText(investmentDisclaimerContent);
                    }
                }

                // Update hidden input when content changes
                investmentDisclaimerQuill.on('text-change', function() {
                    var content = investmentDisclaimerQuill.root.innerHTML;
                    document.getElementById('investment_disclaimer').value = content;
                    console.log('Content updated:', content);
                });

                // Ensure content is saved before form submission
                document.querySelector('form').addEventListener('submit', function(e) {
                    var content = investmentDisclaimerQuill.root.innerHTML;
                    document.getElementById('investment_disclaimer').value = content;
                    console.log('Form submission - saving content:', content);
                    console.log('Hidden input value:', document.getElementById('investment_disclaimer').value);
                });

                // Debug function
                window.debugInvestmentDisclaimer = function() {
                    console.log('=== INVESTMENT DISCLAIMER DEBUG ===');
                    console.log('Hidden input value:', document.getElementById('investment_disclaimer').value);
                    console.log('Quill content HTML:', investmentDisclaimerQuill.root.innerHTML);
                    console.log('Quill content text:', investmentDisclaimerQuill.getText());
                    console.log('Data attribute:', document.getElementById('investment_disclaimer_editor').dataset.content);
                    alert('Check browser console for debug information');
                };

                });
            </script>
@endsection
