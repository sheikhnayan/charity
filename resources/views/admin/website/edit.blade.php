@extends('admin.main')

@section('content')
<link rel="stylesheet" href="{{ asset('user/extra.css') }}">
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
@endsection
