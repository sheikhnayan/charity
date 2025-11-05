<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Donate to {{ $website->name }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2e4053;
            --accent-color: #28a745;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .qr-container {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .qr-header {
            background: white;
            border-radius: 20px 20px 0 0;
            padding: 30px 20px 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .qr-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid var(--primary-color);
        }
        
        .qr-title {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        
        .qr-campaign {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .qr-body {
            background: white;
            padding: 25px 20px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .amount-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .amount-btn {
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            font-size: 18px;
            font-weight: bold;
            color: var(--primary-color);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .amount-btn:hover, .amount-btn.active {
            border-color: var(--accent-color);
            background: var(--accent-color);
            color: white;
            transform: scale(1.05);
        }
        
        .custom-amount {
            position: relative;
            margin-bottom: 20px;
        }
        
        .custom-amount input {
            font-size: 28px;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            text-align: center;
        }
        
        .custom-amount .dollar-sign {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 28px;
            font-weight: bold;
            color: #666;
        }
        
        .form-control {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        .donate-btn {
            width: 100%;
            padding: 18px;
            font-size: 20px;
            font-weight: bold;
            background: linear-gradient(135deg, var(--accent-color) 0%, #20c997 100%);
            border: none;
            border-radius: 15px;
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
            transition: all 0.3s;
        }
        
        .donate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.6);
        }
        
        .secure-badge {
            text-align: center;
            margin-top: 15px;
            color: #666;
            font-size: 13px;
        }
        
        .secure-badge i {
            color: var(--accent-color);
            margin-right: 5px;
        }
        
        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .qr-footer {
            text-align: center;
            padding: 20px;
            color: white;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="qr-container">
        <div class="qr-header">
            @if($website->logo ?? null)
                <img src="{{ asset('uploads/' . $website->logo) }}" alt="{{ $website->name }}" class="qr-logo">
            @else
                <div class="qr-logo" style="background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-size: 32px;">
                    <i class="fas fa-heart"></i>
                </div>
            @endif
            <div class="qr-title">{{ $website->name }}</div>
            @if($campaignName)
                <div class="qr-campaign">
                    <i class="fas fa-tag"></i> {{ $campaignName }}
                </div>
            @endif
            <div class="badge bg-success">
                <i class="fas fa-mobile-alt"></i> QR Donation
            </div>
        </div>
        
        <div class="qr-body">
            <form action="{{ route('qr.donate.process') }}" method="POST" id="qrDonateForm">
                @csrf
                <input type="hidden" name="website_id" value="{{ $website->id }}">
                <input type="hidden" name="qr_identifier" value="{{ $qrIdentifier }}">
                <input type="hidden" name="campaign_name" value="{{ $campaignName }}">
                <input type="hidden" name="type" value="{{ $donationType }}">
                
                <!-- Quick Amount Selection -->
                <div class="text-center mb-3">
                    <small class="text-muted">Choose an amount or enter your own</small>
                </div>
                
                <div class="amount-buttons">
                    <button type="button" class="amount-btn" data-amount="25">$25</button>
                    <button type="button" class="amount-btn" data-amount="50">$50</button>
                    <button type="button" class="amount-btn" data-amount="100">$100</button>
                    <button type="button" class="amount-btn" data-amount="250">$250</button>
                    <button type="button" class="amount-btn" data-amount="500">$500</button>
                    <button type="button" class="amount-btn" data-amount="1000">$1K</button>
                </div>
                
                <!-- Custom Amount -->
                <div class="custom-amount">
                    <span class="dollar-sign">$</span>
                    <input type="number" 
                           class="form-control" 
                           id="donationAmount" 
                           name="amount" 
                           placeholder="Enter Amount" 
                           step="0.01" 
                           min="1"
                           value="{{ $presetAmount ?? '' }}"
                           required>
                </div>
                
                <!-- Personal Information -->
                <div class="row g-2">
                    <div class="col-6">
                        <input type="text" 
                               class="form-control" 
                               name="first_name" 
                               placeholder="First Name" 
                               required>
                    </div>
                    <div class="col-6">
                        <input type="text" 
                               class="form-control" 
                               name="last_name" 
                               placeholder="Last Name" 
                               required>
                    </div>
                </div>
                
                <input type="email" 
                       class="form-control" 
                       name="email" 
                       placeholder="Email Address" 
                       required>
                
                <input type="tel" 
                       class="form-control" 
                       name="phone" 
                       placeholder="Phone Number (Optional)">
                
                <!-- Optional Comment -->
                <textarea class="form-control" 
                          name="comment" 
                          rows="2" 
                          placeholder="Leave a message (Optional)"></textarea>
                
                <!-- Anonymous Option -->
                <div class="form-check mb-3">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="anonymous" 
                           name="anonymous_donation">
                    <label class="form-check-label" for="anonymous">
                        Make this donation anonymous
                    </label>
                </div>
                
                <!-- Tipping Component -->
                @include('components.tipping', [
                    'baseAmount' => $presetAmount ?? 0,
                    'primaryColor' => '#28a745'
                ])
                
                <!-- Submit Button -->
                <button type="submit" class="donate-btn">
                    <i class="fas fa-heart me-2"></i> Donate Now
                </button>
                
                <div class="secure-badge">
                    <i class="fas fa-lock"></i> Secure payment via Stripe & Authorize.Net
                </div>
            </form>
        </div>
        
        <div class="qr-footer">
            Powered by {{ config('app.name') }} • Tax-deductible donation
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Payment Funnel Tracking -->
    <script src="{{ asset('js/payment-funnel-tracking.js') }}"></script>
    
    <script>
        // Amount button selection
        document.querySelectorAll('.amount-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Set amount value
                const amount = this.getAttribute('data-amount');
                const amountValue = amount === '1000' ? 1000 : parseInt(amount);
                document.getElementById('donationAmount').value = amountValue;
            });
        });
        
        // Clear button selection when custom amount is entered
        document.getElementById('donationAmount').addEventListener('input', function() {
            document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
        });
        
        // Track form view
        if (typeof window.trackFunnelEvent === 'function') {
            window.trackFunnelEvent('form_view', {
                form_type: 'qr_donation',
                source: 'qr_code',
                campaign: '{{ $campaignName ?? '' }}'
            });
        }
        
        // Track amount entry
        document.getElementById('donationAmount').addEventListener('blur', function() {
            if (this.value && typeof window.trackFunnelEvent === 'function') {
                window.trackFunnelEvent('amount_entered', {
                    form_type: 'qr_donation',
                    amount: parseFloat(this.value)
                });
            }
        });
        
        // Preset amount if provided
        @if($presetAmount)
        document.getElementById('donationAmount').value = {{ $presetAmount }};
        // Find and activate matching button
        document.querySelectorAll('.amount-btn').forEach(btn => {
            if (parseInt(btn.getAttribute('data-amount')) === {{ $presetAmount }}) {
                btn.classList.add('active');
            }
        });
        @endif
    </script>
</body>
</html>
