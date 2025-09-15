@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    .form-section {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .section-header {
        background: #007bff;
        color: white;
        padding: 10px 15px;
        margin: -20px -20px 20px -20px;
        border-radius: 8px 8px 0 0;
        font-weight: bold;
    }
    .logo-item {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }
    .btn-toggle {
        min-width: 120px;
    }
    .preview-link {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }
    .json-editor {
        font-family: 'Courier New', monospace;
        min-height: 100px;
    }
</style>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Preview Link -->
        <a href="/dealmaker-demo" target="_blank" class="btn btn-primary preview-link">
            <i class="fas fa-external-link-alt me-2"></i>Preview Homepage
        </a>

        <div class="row">
            <div class="col-12">
                <div class="app-main__inner">
                    <div class="app-page-title mt-4">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="fas fa-home icon-gradient bg-arielle-smile"></i>
                                </div>
                                <div>
                                    <span class="text-capitalize">DealMaker Homepage Settings</span>
                                    <div class="page-title-subheading">
                                        Manage all dynamic content for the DealMaker homepage from this admin panel.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('dealmaker.admin.update') }}" method="POST">
                        @csrf

                        <!-- Meta Tags Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-tags me-2"></i>SEO & Meta Tags
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Page Title</label>
                                    <input type="text" class="form-control" name="meta_title" 
                                           value="{{ $setting->meta_title ?? '' }}" 
                                           placeholder="DealMaker | Raise Capital Online">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">OG Image URL</label>
                                    <input type="url" class="form-control" name="og_image" 
                                           value="{{ $setting->og_image ?? '' }}" 
                                           placeholder="https://example.com/image.jpg">
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" rows="2" 
                                              placeholder="Brief description for search engines">{{ $setting->meta_description ?? '' }}</textarea>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords" 
                                           value="{{ $setting->meta_keywords ?? '' }}" 
                                           placeholder="capital raising, investment, funding">
                                </div>
                            </div>
                        </div>

                        <!-- Hero Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-star me-2"></i>Hero Section
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Hero Title</label>
                                    <input type="text" class="form-control" name="hero_title" 
                                           value="{{ $setting->hero_title ?? '' }}" 
                                           placeholder="The Future Of Retail Capital">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Hero Subtitle</label>
                                    <input type="text" class="form-control" name="hero_subtitle" 
                                           value="{{ $setting->hero_subtitle ?? '' }}" 
                                           placeholder="Raise Boldly">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">CTA Button Text</label>
                                    <input type="text" class="form-control" name="hero_cta_text" 
                                           value="{{ $setting->hero_cta_text ?? '' }}" 
                                           placeholder="Get Started">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">CTA Button URL</label>
                                    <input type="text" class="form-control" name="hero_cta_url" 
                                           value="{{ $setting->hero_cta_url ?? '' }}" 
                                           placeholder="/connect">
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-label">Background Video URL</label>
                                    <input type="url" class="form-control" name="hero_background_video" 
                                           value="{{ $setting->hero_background_video ?? '' }}" 
                                           placeholder="https://example.com/video.mp4">
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-chart-bar me-2"></i>Statistics Section
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Statistic 1 Number</label>
                                    <input type="text" class="form-control" name="stat_1_number" 
                                           value="{{ $setting->stat_1_number ?? '' }}" 
                                           placeholder="$2B+">
                                    <label class="form-label mt-2">Statistic 1 Text</label>
                                    <input type="text" class="form-control" name="stat_1_text" 
                                           value="{{ $setting->stat_1_text ?? '' }}" 
                                           placeholder="Raised by customers">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Statistic 2 Number</label>
                                    <input type="text" class="form-control" name="stat_2_number" 
                                           value="{{ $setting->stat_2_number ?? '' }}" 
                                           placeholder="1.5B+">
                                    <label class="form-label mt-2">Statistic 2 Text</label>
                                    <input type="text" class="form-control" name="stat_2_text" 
                                           value="{{ $setting->stat_2_text ?? '' }}" 
                                           placeholder="Investments processed">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Statistic 3 Number</label>
                                    <input type="text" class="form-control" name="stat_3_number" 
                                           value="{{ $setting->stat_3_number ?? '' }}" 
                                           placeholder="900+">
                                    <label class="form-label mt-2">Statistic 3 Text</label>
                                    <input type="text" class="form-control" name="stat_3_text" 
                                           value="{{ $setting->stat_3_text ?? '' }}" 
                                           placeholder="Offerings">
                                </div>
                            </div>
                        </div>

                        <!-- Branding Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-image me-2"></i>Branding & Logo
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Site Logo URL</label>
                                    <input type="url" class="form-control" name="site_logo" 
                                           value="{{ $setting->site_logo ?? '' }}" 
                                           placeholder="https://example.com/logo.svg">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Site Tagline</label>
                                    <input type="text" class="form-control" name="site_tagline" 
                                           value="{{ $setting->site_tagline ?? '' }}" 
                                           placeholder="DealMaker Logo">
                                </div>
                            </div>
                        </div>

                        <!-- Client Logos Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-building me-2"></i>Client Logos
                            </div>
                            <div id="client-logos-container">
                                @if($setting && $setting->client_logos)
                                    @php $logos = json_decode($setting->client_logos, true) ?? []; @endphp
                                    @foreach($logos as $index => $logo)
                                        <div class="logo-item" data-index="{{ $index }}">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control" name="client_logos[{{ $index }}][name]" 
                                                           value="{{ $logo['name'] }}" placeholder="Company Name">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="url" class="form-control" name="client_logos[{{ $index }}][image]" 
                                                           value="{{ $logo['image'] }}" placeholder="Logo Image URL">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="url" class="form-control" name="client_logos[{{ $index }}][url]" 
                                                           value="{{ $logo['url'] ?? '' }}" placeholder="Company Website URL (optional)">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger btn-sm remove-logo">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-logo" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Add New Logo
                            </button>
                        </div>

                        <!-- Phone Slider Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-mobile-alt me-2"></i>Phone Slider Content
                            </div>
                            <div id="slider-images-container">
                                @if($setting && $setting->slider_images)
                                    @php $slides = json_decode($setting->slider_images, true) ?? []; @endphp
                                    @foreach($slides as $index => $slide)
                                        <div class="logo-item" data-index="{{ $index }}">
                                            <h6>Slide {{ $index + 1 }}</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Phone Image URL</label>
                                                    <input type="url" class="form-control" name="slider_images[{{ $index }}][image]" 
                                                           value="{{ $slide['image'] }}" placeholder="Phone mockup image URL">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Slide Title</label>
                                                    <input type="text" class="form-control" name="slider_images[{{ $index }}][title]" 
                                                           value="{{ $slide['title'] }}" placeholder="Slide title">
                                                </div>
                                                <div class="col-md-8 mt-2">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" name="slider_images[{{ $index }}][description]" rows="2" 
                                                              placeholder="Slide description">{{ $slide['description'] }}</textarea>
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <label class="form-label">CTA Text</label>
                                                    <input type="text" class="form-control" name="slider_images[{{ $index }}][cta_text]" 
                                                           value="{{ $slide['cta_text'] ?? 'Start Now' }}" placeholder="Button text">
                                                </div>
                                                <div class="col-md-2 mt-2">
                                                    <label class="form-label">CTA URL</label>
                                                    <input type="text" class="form-control" name="slider_images[{{ $index }}][cta_url]" 
                                                           value="{{ $slide['cta_url'] ?? '/connect' }}" placeholder="Button link">
                                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-slide">
                                                        <i class="fas fa-trash"></i> Remove Slide
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-slide" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Add New Slide
                            </button>
                        </div>

                        <!-- Section Visibility Controls -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-eye me-2"></i>Section Visibility Controls
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_hero" id="show_hero" 
                                               {{ ($setting->show_hero ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_hero">Show Hero Section</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_stats" id="show_stats" 
                                               {{ ($setting->show_stats ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_stats">Show Statistics</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_about" id="show_about" 
                                               {{ ($setting->show_about ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_about">Show About Section</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_services" id="show_services" 
                                               {{ ($setting->show_services ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_services">Show Services</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_testimonials" id="show_testimonials" 
                                               {{ ($setting->show_testimonials ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_testimonials">Show Testimonials</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_contact" id="show_contact" 
                                               {{ ($setting->show_contact ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_contact">Show Contact</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Code Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-code me-2"></i>Custom Code Injection
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Custom CSS</label>
                                    <textarea class="form-control json-editor" name="custom_css" rows="8" 
                                              placeholder="/* Custom CSS styles */">{{ $setting->custom_css ?? '' }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Custom JavaScript</label>
                                    <textarea class="form-control json-editor" name="custom_js" rows="8" 
                                              placeholder="/* Custom JavaScript code */">{{ $setting->custom_js ?? '' }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Custom Head Code</label>
                                    <textarea class="form-control json-editor" name="custom_head_code" rows="8" 
                                              placeholder="<!-- Custom HTML for <head> section -->">{{ $setting->custom_head_code ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Save All Settings
                            </button>
                            <a href="/dealmaker-demo" target="_blank" class="btn btn-success btn-lg ms-3">
                                <i class="fas fa-external-link-alt me-2"></i>Preview Changes
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for dynamic logo management -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let logoIndex = {{ $setting && $setting->client_logos ? count(json_decode($setting->client_logos, true) ?? []) : 0 }};
    let slideIndex = {{ $setting && $setting->slider_images ? count(json_decode($setting->slider_images, true) ?? []) : 0 }};

    // Add new logo
    document.getElementById('add-logo').addEventListener('click', function() {
        const container = document.getElementById('client-logos-container');
        const logoHtml = `
            <div class="logo-item" data-index="${logoIndex}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="client_logos[${logoIndex}][name]" 
                               placeholder="Company Name">
                    </div>
                    <div class="col-md-4">
                        <input type="url" class="form-control" name="client_logos[${logoIndex}][image]" 
                               placeholder="Logo Image URL">
                    </div>
                    <div class="col-md-3">
                        <input type="url" class="form-control" name="client_logos[${logoIndex}][url]" 
                               placeholder="Company Website URL (optional)">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-logo">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', logoHtml);
        logoIndex++;
    });

    // Add new slide
    document.getElementById('add-slide').addEventListener('click', function() {
        const container = document.getElementById('slider-images-container');
        const slideHtml = `
            <div class="logo-item" data-index="${slideIndex}">
                <h6>Slide ${slideIndex + 1}</h6>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Phone Image URL</label>
                        <input type="url" class="form-control" name="slider_images[${slideIndex}][image]" 
                               placeholder="Phone mockup image URL">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slide Title</label>
                        <input type="text" class="form-control" name="slider_images[${slideIndex}][title]" 
                               placeholder="Slide title">
                    </div>
                    <div class="col-md-8 mt-2">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="slider_images[${slideIndex}][description]" rows="2" 
                                  placeholder="Slide description"></textarea>
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label">CTA Text</label>
                        <input type="text" class="form-control" name="slider_images[${slideIndex}][cta_text]" 
                               value="Start Now" placeholder="Button text">
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label">CTA URL</label>
                        <input type="text" class="form-control" name="slider_images[${slideIndex}][cta_url]" 
                               value="/connect" placeholder="Button link">
                        <button type="button" class="btn btn-danger btn-sm mt-2 remove-slide">
                            <i class="fas fa-trash"></i> Remove Slide
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', slideHtml);
        slideIndex++;
    });

    // Remove logo
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-logo') || e.target.parentElement.classList.contains('remove-logo')) {
            const logoItem = e.target.closest('.logo-item');
            if (logoItem) {
                logoItem.remove();
            }
        }
    });

    // Remove slide
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-slide') || e.target.parentElement.classList.contains('remove-slide')) {
            const slideItem = e.target.closest('.logo-item');
            if (slideItem) {
                slideItem.remove();
            }
        }
    });
});
</script>
@endsection