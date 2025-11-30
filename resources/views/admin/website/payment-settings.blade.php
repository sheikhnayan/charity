@extends('admin.main')

@section('content')
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div>Payment Settings - {{ $website->name }}
                    <div class="page-title-subheading">Configure payment gateway credentials for this website</div>
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('admin.website.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Websites
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <i class="header-icon lnr-cog icon-gradient bg-plum-plate"></i>
                    Payment Gateway Configuration
                    <div class="btn-actions-pane-right">
                        @if($paymentSettings)
                            <button type="button" class="btn btn-primary btn-sm" onclick="testConnection()">
                                <i class="fa fa-plug"></i> Test Connection
                            </button>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.websites.payment.update', $website) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="form-group">
                            <label>Payment Gateway</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" 
                                       {{ old('payment_method', $paymentSettings->payment_method ?? 'authorize') === 'stripe' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stripe">
                                    <strong>Stripe</strong> - Credit card processing
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="authorize" value="authorize" 
                                       {{ old('payment_method', $paymentSettings->payment_method ?? 'authorize') === 'authorize' ? 'checked' : '' }}>
                                <label class="form-check-label" for="authorize">
                                    <strong>Authorize.net</strong> - Payment processing
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="coinbase" value="coinbase" 
                                       {{ old('payment_method', $paymentSettings->payment_method ?? 'authorize') === 'coinbase' ? 'checked' : '' }}>
                                <label class="form-check-label" for="coinbase">
                                    <strong>Coinbase Commerce</strong> - Cryptocurrency payments (BTC, ETH, USDC, etc.)
                                </label>
                            </div>
                        </div>

                        <!-- Stripe Settings -->
                        <div id="stripe-settings" class="payment-settings" style="display: none;">
                            <h5 class="card-title"><i class="fab fa-stripe"></i> Stripe Configuration</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Publishable Key</label>
                                        <input type="text" class="form-control" name="stripe_publishable_key" 
                                               value="{{ old('stripe_publishable_key', $paymentSettings->stripe_publishable_key ?? '') }}"
                                               placeholder="pk_test_... or pk_live_...">
                                        <small class="form-text text-muted">Your Stripe publishable key</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Secret Key</label>
                                        <input type="password" class="form-control" name="stripe_secret_key" 
                                               value="{{ old('stripe_secret_key', $paymentSettings->stripe_secret_key ?? '') }}"
                                               placeholder="sk_test_... or sk_live_...">
                                        <small class="form-text text-muted">Your Stripe secret key</small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Webhook Secret (Optional)</label>
                                <input type="password" class="form-control" name="stripe_webhook_secret" 
                                       value="{{ old('stripe_webhook_secret', $paymentSettings->stripe_webhook_secret ?? '') }}"
                                       placeholder="whsec_...">
                                <small class="form-text text-muted">Webhook endpoint secret for Stripe events</small>
                            </div>
                        </div>

                        <!-- Authorize.net Settings -->
                        <div id="authorize-settings" class="payment-settings" style="display: none;">
                            <h5 class="card-title"><i class="fa fa-shield-alt"></i> Authorize.net Configuration</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>API Login ID</label>
                                        <input type="text" class="form-control" name="authorize_login_id" 
                                               value="{{ old('authorize_login_id', $paymentSettings->authorize_login_id ?? '') }}"
                                               placeholder="Your API Login ID">
                                        <small class="form-text text-muted">Authorize.net API Login ID</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Transaction Key</label>
                                        <input type="password" class="form-control" name="authorize_transaction_key" 
                                               value="{{ old('authorize_transaction_key', $paymentSettings->authorize_transaction_key ?? '') }}"
                                               placeholder="Your Transaction Key">
                                        <small class="form-text text-muted">Authorize.net Transaction Key</small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="authorize_sandbox" id="authorize_sandbox" value="1"
                                           {{ old('authorize_sandbox', $paymentSettings->authorize_sandbox ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="authorize_sandbox">
                                        Use Sandbox Mode (Test Environment)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Coinbase Commerce Settings -->
                        <div id="coinbase-settings" class="payment-settings" style="display: none;">
                            <h5 class="card-title"><i class="fab fa-bitcoin"></i> Coinbase Commerce Configuration</h5>
                            <div class="form-group">
                                <label>API Key</label>
                                <input type="password" class="form-control" name="coinbase_api_key" 
                                       value="{{ old('coinbase_api_key', $paymentSettings->coinbase_api_key ?? '') }}"
                                       placeholder="Your Coinbase Commerce API Key">
                                <small class="form-text text-muted">Get this from your Coinbase Commerce dashboard → Settings → API Keys</small>
                            </div>
                            <div class="form-group">
                                <label>Webhook Secret</label>
                                <input type="password" class="form-control" name="coinbase_webhook_secret" 
                                       value="{{ old('coinbase_webhook_secret', $paymentSettings->coinbase_webhook_secret ?? '') }}"
                                       placeholder="Your Webhook Shared Secret">
                                <small class="form-text text-muted">Get this from Coinbase Commerce → Settings → Webhook subscriptions</small>
                            </div>
                            <div class="alert alert-info">
                                <strong><i class="fa fa-info-circle"></i> Webhook URL:</strong><br>
                                <code>{{ url('/webhook/coinbase') }}</code><br>
                                <small>Configure this URL in your Coinbase Commerce dashboard</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                       {{ old('is_active', $paymentSettings->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Enable payment processing for this website</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save Payment Settings
                        </button>
                        @if($paymentSettings)
                            <button type="button" class="btn btn-danger" onclick="deleteSettings()">
                                <i class="fa fa-trash"></i> Delete Settings
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <i class="header-icon lnr-question-circle icon-gradient bg-happy-itmeo"></i>
                    Setup Guide
                </div>
                <div class="card-body">
                    <div id="stripe-guide" class="setup-guide" style="display: none;">
                        <h6><i class="fab fa-stripe"></i> Stripe Setup</h6>
                        <ol>
                            <li>Log in to your <a href="https://dashboard.stripe.com" target="_blank">Stripe Dashboard</a></li>
                            <li>Go to Developers → API keys</li>
                            <li>Copy your Publishable key and Secret key</li>
                            <li>For webhooks, create an endpoint in Developers → Webhooks</li>
                        </ol>
                        <div class="alert alert-info">
                            <small><strong>Tip:</strong> Test keys start with "pk_test_" and "sk_test_", live keys start with "pk_live_" and "sk_live_"</small>
                        </div>
                    </div>

                    <div id="authorize-guide" class="setup-guide" style="display: none;">
                        <h6><i class="fa fa-shield-alt"></i> Authorize.net Setup</h6>
                        <ol>
                            <li>Log in to your <a href="https://account.authorize.net" target="_blank">Authorize.net Account</a></li>
                            <li>Go to Account → Settings → Security Settings → General Security Settings</li>
                            <li>Generate an API Login ID and Transaction Key</li>
                            <li>For production, uncheck "Sandbox Mode"</li>
                        </ol>
                        <div class="alert alert-warning">
                            <small><strong>Note:</strong> Always test in sandbox mode before going live</small>
                        </div>
                    </div>

                    <div id="coinbase-guide" class="setup-guide" style="display: none;">
                        <h6><i class="fab fa-bitcoin"></i> Coinbase Commerce Setup</h6>
                        <ol>
                            <li>Sign up at <a href="https://commerce.coinbase.com" target="_blank">Coinbase Commerce</a></li>
                            <li>Go to Settings → API Keys</li>
                            <li>Click "Create an API Key" and copy it</li>
                            <li>Go to Settings → Webhook subscriptions</li>
                            <li>Copy the "Webhook Shared Secret"</li>
                            <li>Add webhook endpoint: <code>{{ url('/webhook/coinbase') }}</code></li>
                        </ol>
                        <div class="alert alert-success">
                            <small><strong>Supported Currencies:</strong> Bitcoin (BTC), Ethereum (ETH), USDC, USDT, DAI, Litecoin (LTC)</small>
                        </div>
                    </div>
                </div>
            </div>

            @if($paymentSettings)
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <i class="header-icon lnr-checkmark-circle icon-gradient bg-happy-green"></i>
                    Connection Status
                </div>
                <div class="card-body">
                    <div id="connection-status">
                        <p class="text-muted">Click "Test Connection" to verify your settings</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the payment settings for this website?</p>
                <p class="text-danger"><strong>This will disable payment processing until new settings are configured.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.websites.payment.destroy', $website) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle payment method toggle
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    const stripeSettings = document.getElementById('stripe-settings');
    const authorizeSettings = document.getElementById('authorize-settings');
    const coinbaseSettings = document.getElementById('coinbase-settings');
    const stripeGuide = document.getElementById('stripe-guide');
    const authorizeGuide = document.getElementById('authorize-guide');
    const coinbaseGuide = document.getElementById('coinbase-guide');

    function toggleSettings() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        // Hide all settings
        stripeSettings.style.display = 'none';
        authorizeSettings.style.display = 'none';
        coinbaseSettings.style.display = 'none';
        stripeGuide.style.display = 'none';
        authorizeGuide.style.display = 'none';
        coinbaseGuide.style.display = 'none';
        
        // Show selected method settings
        if (selectedMethod === 'stripe') {
            stripeSettings.style.display = 'block';
            stripeGuide.style.display = 'block';
        } else if (selectedMethod === 'coinbase') {
            coinbaseSettings.style.display = 'block';
            coinbaseGuide.style.display = 'block';
        } else {
            authorizeSettings.style.display = 'block';
            authorizeGuide.style.display = 'block';
        }
    }

    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', toggleSettings);
    });

    // Initialize on page load
    toggleSettings();
});

function testConnection() {
    const statusDiv = document.getElementById('connection-status');
    statusDiv.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Testing connection...';
    
    fetch(`{{ route('admin.websites.payment.test', $website) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusDiv.innerHTML = `
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i> ${data.message}
                    ${data.details && Object.keys(data.details).length > 0 ? 
                        '<br><small>' + Object.entries(data.details).map(([key, value]) => `${key}: ${value}`).join('<br>') + '</small>' 
                        : ''}
                </div>
            `;
        } else {
            statusDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i> ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        statusDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i> Error testing connection: ${error.message}
            </div>
        `;
    });
}

function deleteSettings() {
    $('#deleteModal').modal('show');
}
</script>

@endsection