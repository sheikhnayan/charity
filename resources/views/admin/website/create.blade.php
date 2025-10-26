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
                                    <form action="{{ route('admin.website.store') }}" method="post">
                                        @csrf

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="first_name" class="form-label">First Name</label>
                                                        <input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="last_name" class="form-label">Last Name</label>
                                                        <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Last Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Password</label>
                                                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Website Name</label>
                                                        <input type="text" name="name" class="form-control" id="name" placeholder="Website Name" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="type" class="form-label">Website Type</label>
                                                        <select name="type" class="form-control" id="type" required>
                                                            <option value="">Select Website Type</option>
                                                            <option value="fundraiser">Fundraiser</option>
                                                            <option value="investment">Investment</option>
                                                        </select>
                                                        <small class="form-text text-muted">Choose whether this website is for fundraising or investment purposes.</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Investment-specific fields -->
                                            <div class="row" id="investment-fields" style="display: none;">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="share_price" class="form-label">Share Price ($)</label>
                                                        <input type="number" name="share_price" class="form-control" id="share_price" placeholder="2.13" step="0.01" min="0.01">
                                                        <small class="form-text text-muted">Price per share for investment calculations.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="min_investment" class="form-label">Minimum Investment ($)</label>
                                                        <input type="number" name="min_investment" class="form-control" id="min_investment" placeholder="1000" step="1" min="1">
                                                        <small class="form-text text-muted">Minimum amount required to invest.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="investment_tiers" class="form-label">Investment Tiers</label>
                                                        <input type="text" name="investment_tiers" class="form-control" id="investment_tiers" placeholder="1000,2500,5000,10000">
                                                        <small class="form-text text-muted">Comma-separated list of investment amounts to display as quick options.</small>
                                                    </div>
                                                </div>
                                                
                                                <!-- Investment Page Text Settings -->
                                                <div class="col-md-12">
                                                    <h5 class="mt-4 mb-3">Investment Page Text Labels</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="invest_page_title" class="form-label">Investment Page Title</label>
                                                        <input type="text" name="invest_page_title" class="form-control" id="invest_page_title" placeholder="Complete Your Investment" value="Complete Your Investment">
                                                        <small class="form-text text-muted">The main title displayed on the investment form page.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="invest_amount_title" class="form-label">Investment Amount Section Title</label>
                                                        <input type="text" name="invest_amount_title" class="form-control" id="invest_amount_title" placeholder="Select Investment Amount" value="Select Investment Amount">
                                                        <small class="form-text text-muted">The title for the investment amount selection section.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="share_price_label" class="form-label">Share Price Label</label>
                                                        <input type="text" name="share_price_label" class="form-control" id="share_price_label" placeholder="SHARE PRICE" value="SHARE PRICE">
                                                        <small class="form-text text-muted">The label displayed above the share price value.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="minimum_investment_label" class="form-label">Minimum Investment Label</label>
                                                        <input type="text" name="minimum_investment_label" class="form-control" id="minimum_investment_label" placeholder="MINIMUM INVESTMENT" value="MINIMUM INVESTMENT">
                                                        <small class="form-text text-muted">The label displayed above the minimum investment value.</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="custom_sticky_button_text" class="form-label">Custom Sticky Button Verbiage</label>
                                                        <input type="text" name="custom_sticky_button_text" class="form-control" id="custom_sticky_button_text" placeholder="Custom verbiage for sticky invest now button" value="Custom verbiage for sticky invest now button">
                                                        <small class="form-text text-muted">Custom Verbiage for Sticky Invest Now Button.</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Footer Disclaimer - Available for all website types -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="investment_disclaimer" class="form-label">Footer Disclaimer</label>
                                                        <textarea name="investment_disclaimer" class="form-control" id="investment_disclaimer" rows="3" placeholder="Enter footer disclaimer text"></textarea>
                                                        <small class="form-text text-muted">Legal disclaimer text that will be displayed in the footer for all website types.</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Domain</label>
                                                        <input type="text" name="domain" class="form-control" id="name" placeholder="Enter Domain" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('admin.website.index') }}" class="btn btn-danger">Cancel</a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<script>
// Handle website type change to show/hide investment fields
function toggleInvestmentFields() {
    const websiteType = document.getElementById('type').value;
    const investmentFields = document.getElementById('investment-fields');
    
    if (websiteType === 'investment') {
        investmentFields.style.display = 'block';
    } else {
        investmentFields.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleInvestmentFields();
    
    // Add event listener to type dropdown
    document.getElementById('type').addEventListener('change', toggleInvestmentFields);
});
</script>

@endsection
