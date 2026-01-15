@php
    // Get website data based on current domain
    $url = url()->current();
    $domain = parse_url($url, PHP_URL_HOST);
    $check = \App\Models\Website::where('domain', $domain)->first();

    if ($check) {
        $user_id = $check->user_id;
        $setting = \App\Models\Setting::where('user_id', $user_id)->first();
        $header = \App\Models\Header::where('user_id', $user_id)->first();
        $footer = \App\Models\Footer::where('user_id', $user_id)->first();
        $website = $check;
        
        // Load custom fonts for dynamic font support
        $customFonts = \App\Models\CustomFont::get();
        
        // Get payment method from website settings
        $paymentMethod = $website->payment_method ?? 'stripe'; // stripe or authorize_net
    } else {
        $setting = null;
        $header = null;
        $footer = null;
        $website = null;
        $customFonts = collect();
        $paymentMethod = 'stripe';
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting && $setting->company_name ? $setting->company_name . ' | Checkout' : 'Checkout' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{background:#f9fafb;}</style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <link rel="stylesheet" href="{{ asset('checkout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom Fonts CSS -->
    <link href="{{ route('fonts.css') }}" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <style>
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .checkout-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .checkout-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 40px;
        }

        .checkout-summary {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .checkout-summary h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .order-items {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 12px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .item-info h4 {
            margin: 0 0 4px 0;
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }

        .item-amount {
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
        }

        .order-summary-divider {
            height: 1px;
            background: #e9ecef;
            margin: 20px 0;
        }

        .order-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-amount {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
        }

        .checkout-payment h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .form-section {
            border: none;
            padding: 0;
            margin-bottom: 30px;
        }

        .form-section h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .payment-form-section {
            margin-bottom: 24px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .checkout-terms {
            margin-bottom: 30px;
            padding: 16px;
            background: #ecf0f1;
            border-radius: 6px;
        }

        .checkout-actions {
            display: flex;
            gap: 16px;
            margin-bottom: 30px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
        }

        .btn-lg {
            padding: 14px 32px;
            font-size: 16px;
        }

        .security-notice {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .checkout-content {
                grid-template-columns: 1fr;
            }
            .checkout-summary {
                position: relative;
                top: auto;
            }
        }
    </style>
</head>

<body>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:0;min-height:100vh;margin:0;">
    <!-- Left Section: Order Items -->
    <div style="background:#fff;padding:40px;overflow-y:auto;">
        <div style="max-width:500px;">
            <h3 style="font-size:24px;font-weight:700;margin-bottom:30px;color:#2c3e50;">Order Summary</h3>
            
            <div style="border:1px solid #eee;border-radius:8px;padding:20px;margin-bottom:30px;">
                @foreach($items as $item)
                    <div style="margin-bottom:20px;">
                        <div style="display:grid;grid-template-columns:80px 1fr 120px;gap:15px;align-items:start;">
                            <div style="width:80px;height:80px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:40px;color:#ccc;">
                                <i class="fas fa-image"></i>
                            </div>
                            <div>
                                <h4 style="margin:0 0 8px 0;font-size:16px;font-weight:600;color:#2c3e50;">{{ $item['name'] }}</h4>
                                <p style="margin:0;font-size:13px;color:#95a5a6;">{{ ucfirst($item['type']) }}</p>
                                @if($item['quantity'] > 1)
                                    <p style="margin:4px 0 0 0;font-size:13px;color:#95a5a6;">Qty: {{ $item['quantity'] }}</p>
                                @endif
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:18px;font-weight:700;color:#667eea;">${{ number_format($item['total'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                    @if(!$loop->last)
                        <div style="height:1px;background:#eee;margin:20px 0;"></div>
                    @endif
                @endforeach
            </div>

            <!-- Pricing Summary -->
            <div style="border-top:2px solid #eee;padding-top:20px;">
                <div style="display:grid;grid-template-columns:1fr 120px;gap:15px;margin-bottom:12px;">
                    <span style="color:#2c3e50;font-weight:500;">Subtotal</span>
                    <span style="text-align:right;font-weight:600;color:#2c3e50;">${{ number_format($subtotal ?? $total, 2) }}</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 120px;gap:15px;margin-bottom:20px;">
                    <span style="color:#2c3e50;font-weight:500;">Tax</span>
                    <span style="text-align:right;font-weight:600;color:#2c3e50;">${{ number_format($tax ?? 0, 2) }}</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 120px;gap:15px;border-top:2px solid #eee;padding-top:15px;">
                    <span style="font-size:18px;font-weight:700;color:#2c3e50;">Total</span>
                    <span style="text-align:right;font-size:20px;font-weight:700;color:#667eea;">${{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Section: Payment Form -->
    <div style="background:#f9fafb;padding:40px;overflow-y:auto;">
        <div style="max-width:500px;">
            <h3 style="font-size:24px;font-weight:700;margin-bottom:30px;color:#2c3e50;">Payment Details</h3>

            <form id="checkoutForm" action="{{ route('checkout.process') }}" method="POST" style="display:flex;flex-direction:column;gap:0;">
                @csrf

                <!-- Personal Information -->
                <div style="margin-bottom:25px;">
                    <h4 style="font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:15px;color:#2c3e50;">Personal Information</h4>
                    
                    @if($requiresEmail)
                        <div style="margin-bottom:15px;">
                            <label style="display:block;font-size:13px;font-weight:500;margin-bottom:6px;color:#2c3e50;">Email Address *</label>
                            <input type="email" name="email" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;" required>
                        </div>
                    @else
                        <p style="font-size:13px;color:#95a5a6;margin-bottom:15px;">Logged in as: <strong>{{ Auth::user()->email }}</strong></p>
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                    @endif

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-bottom:15px;">
                        <div>
                            <label style="display:block;font-size:13px;font-weight:500;margin-bottom:6px;color:#2c3e50;">First Name *</label>
                            <input type="text" name="first_name" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;" required value="{{ Auth::user()->first_name ?? '' }}">
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:500;margin-bottom:6px;color:#2c3e50;">Last Name *</label>
                            <input type="text" name="last_name" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;" required value="{{ Auth::user()->last_name ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- Billing Address -->
                <div style="margin-bottom:25px;">
                    <h4 style="font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:15px;color:#2c3e50;">Billing Address</h4>
                    
                    <div style="margin-bottom:15px;">
                        <label style="display:block;font-size:13px;font-weight:500;margin-bottom:6px;color:#2c3e50;">Street Address</label>
                        <input type="text" name="address" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;margin-bottom:15px;">
                        <div>
                            <label style="display:block;font-size:13px;font-weight:500;margin-bottom:6px;color:#2c3e50;">City</label>
                            <input type="text" name="city" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;">
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:500;margin-bottom:6px;color:#2c3e50;">State</label>
                            <input type="text" name="state" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;">
                        </div>
                        <div>
                            <label style="display:block;font-size:13px;font-weight:500;margin-bottom:6px;color:#2c3e50;">ZIP Code</label>
                            <input type="text" name="zip" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;">
                        </div>
                    </div>

                    <div style="margin-bottom:15px;">
                        <label style="display:block;font-size:13px;font-weight:500;margin-bottom:6px;color:#2c3e50;">Country</label>
                        <input type="text" name="country" style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;box-sizing:border-box;">
                    </div>
                </div>

                <!-- Payment Method -->
                <div style="margin-bottom:25px;">
                    <h4 style="font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:15px;color:#2c3e50;">Payment Method</h4>
                    
                    <div id="paymentMethodContainer" style="display:grid;gap:15px;">
                        @if($paymentMethod === 'stripe')
                            <div style="padding:15px;border:2px solid #667eea;border-radius:8px;cursor:pointer;background:#f9fafb;">
                                <input type="radio" name="payment_method" value="stripe" checked style="margin-right:10px;">
                                <label style="font-weight:500;color:#2c3e50;cursor:pointer;display:inline;">Credit/Debit Card (Stripe)</label>
                            </div>
                        @elseif($paymentMethod === 'authorize_net')
                            <div style="padding:15px;border:2px solid #667eea;border-radius:8px;cursor:pointer;background:#f9fafb;">
                                <input type="radio" name="payment_method" value="authorize_net" checked style="margin-right:10px;">
                                <label style="font-weight:500;color:#2c3e50;cursor:pointer;display:inline;">Credit/Debit Card (Authorize.net)</label>
                            </div>
                        @else
                            <div style="padding:15px;border:2px solid #ddd;border-radius:8px;cursor:pointer;background:#f9fafb;">
                                <input type="radio" name="payment_method" value="stripe" checked style="margin-right:10px;">
                                <label style="font-weight:500;color:#2c3e50;cursor:pointer;display:inline;">Credit/Debit Card (Stripe)</label>
                            </div>
                            <div style="padding:15px;border:2px solid #ddd;border-radius:8px;cursor:pointer;background:#f9fafb;">
                                <input type="radio" name="payment_method" value="authorize_net" style="margin-right:10px;">
                                <label style="font-weight:500;color:#2c3e50;cursor:pointer;display:inline;">Credit/Debit Card (Authorize.net)</label>
                            </div>
                        @endif
                    </div>
                    
                    <input type="hidden" id="payment_token" name="payment_token" value="">
                </div>

                <!-- Terms -->
                <div style="margin-bottom:25px;padding:15px;background:#ecf0f1;border-radius:6px;">
                    <label style="display:flex;align-items:flex-start;gap:10px;font-size:13px;color:#2c3e50;cursor:pointer;">
                        <input type="checkbox" name="terms" required style="margin-top:3px;cursor:pointer;">
                        <span>I agree to the terms and conditions and privacy policy</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;border:none;padding:14px 32px;border-radius:6px;font-size:16px;font-weight:600;cursor:pointer;transition:all 0.3s;display:flex;align-items:center;justify-content:center;gap:8px;width:100%;">
                    <i class="fas fa-lock"></i> 
                    <span>Complete Purchase - ${{ number_format($total, 2) }}</span>
                </button>
            </form>

            <!-- Security Notice -->
            <div style="margin-top:20px;padding:15px;background:#d4edda;border:1px solid #c3e6cb;border-radius:6px;display:flex;align-items:flex-start;gap:10px;font-size:13px;color:#155724;">
                <i class="fas fa-shield-alt" style="margin-top:2px;font-size:16px;"></i>
                <p style="margin:0;">Your payment information is secure and encrypted. We never store credit card data.</p>
            </div>
        </div>
    </div>
</div>

<!-- Cart Script -->
<script src="{{ asset('js/cart.js') }}"></script>

<script>
    // Diagnostic logging for cart
    console.log('🔍 === CHECKOUT PAGE DIAGNOSTICS ===');
    console.log('🔍 jQuery loaded:', typeof jQuery !== 'undefined' ? '✅ YES' : '❌ NO');
    console.log('🔍 $ available:', typeof $ !== 'undefined' ? '✅ YES' : '❌ NO');
    console.log('🔍 window.ShoppingCart exists:', typeof window.ShoppingCart !== 'undefined' ? '✅ YES' : '❌ NO');
    console.log('🔍 document.body exists:', document.body !== null ? '✅ YES' : '❌ NO');
    console.log('🔍 document.readyState:', document.readyState);
    
    // Check if cart button exists
    setTimeout(() => {
        const cartBtn = document.getElementById('floatingCartButton');
        console.log('🔍 Cart button in DOM after 500ms:', cartBtn ? '✅ YES' : '❌ NO');
        if (cartBtn) {
            console.log('🔍 Cart button details:', {
                id: cartBtn.id,
                display: window.getComputedStyle(cartBtn).display,
                zIndex: window.getComputedStyle(cartBtn).zIndex,
                position: window.getComputedStyle(cartBtn).position
            });
        }
    }, 500);
</script>

<script>
    // Payment method selection handler
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Update border styling on method selection
            document.querySelectorAll('[style*="border:2px"]').forEach(div => {
                if (div.contains(radio)) {
                    div.style.borderColor = '#667eea';
                    div.style.background = '#f9fafb';
                } else if (div.querySelector('input[name="payment_method"]')) {
                    div.style.borderColor = '#ddd';
                }
            });
        });
    });

    // Form submission
    document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.6';
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        try {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const formData = new FormData(this);
            formData.set('payment_token', 'tok_visa'); // Placeholder

            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {'Accept': 'application/json'}
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert('Payment failed: ' + (data.message || 'Unknown error'));
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            alert('An error occurred: ' + error.message);
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.innerHTML = originalText;
        }
    });
</script>

</body>
</html>
