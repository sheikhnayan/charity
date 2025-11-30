<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting && $setting->company_name ? $setting->company_name . ' | Crypto Payment' : 'Crypto Payment' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .crypto-container {
            max-width: 700px;
            margin: 0 auto;
        }
        
        .crypto-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .crypto-header {
            background: linear-gradient(135deg, #1652f0 0%, #0052ff 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .crypto-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
        }
        
        .crypto-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .crypto-body {
            padding: 40px;
        }
        
        .payment-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .payment-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .payment-info-row:last-child {
            border-bottom: none;
            font-size: 1.3rem;
            font-weight: bold;
            color: #1652f0;
        }
        
        .payment-info-label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .payment-info-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .supported-currencies {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }
        
        .currency-badge {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px 10px;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
        }
        
        .currency-badge i {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 8px;
            color: #1652f0;
        }
        
        .pay-btn {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #1652f0 0%, #0052ff 100%);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .pay-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(22, 82, 240, 0.4);
        }
        
        .pay-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .loading-spinner {
            display: none;
            margin-right: 10px;
        }
        
        .loading-spinner.show {
            display: inline-block;
        }
        
        .info-section {
            background: #e8f4fd;
            border-left: 4px solid #0d6efd;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .info-section h4 {
            color: #0d6efd;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .info-section ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .info-section li {
            margin-bottom: 8px;
        }
        
        .back-btn {
            margin-top: 20px;
        }
        
        .coinbase-logo {
            height: 24px;
            vertical-align: middle;
            margin-right: 8px;
        }
        
        @media (max-width: 768px) {
            .crypto-header h1 {
                font-size: 1.5rem;
            }
            
            .crypto-body {
                padding: 20px;
            }
            
            .supported-currencies {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="crypto-container">
        <div class="crypto-card">
            <div class="crypto-header">
                <h1>
                    <i class="fab fa-bitcoin me-2"></i>
                    Cryptocurrency Payment
                </h1>
                <p>Secure payment powered by Coinbase Commerce</p>
            </div>
            
            <div class="crypto-body">
                <!-- Payment Information -->
                <div class="payment-info">
                    <h4 class="mb-3"><i class="fas fa-info-circle me-2"></i>Payment Details</h4>
                    <div class="payment-info-row">
                        <span class="payment-info-label">Payment Type</span>
                        <span class="payment-info-value">{{ ucfirst(request('type', 'donation')) }}</span>
                    </div>
                    <div class="payment-info-row">
                        <span class="payment-info-label">Reference ID</span>
                        <span class="payment-info-value">#{{ request('reference_id', request('donation_id', '000000')) }}</span>
                    </div>
                    <div class="payment-info-row">
                        <span class="payment-info-label">Amount to Pay</span>
                        <span class="payment-info-value">${{ number_format(request('amount', 0), 2) }}</span>
                    </div>
                </div>
                
                <!-- Supported Cryptocurrencies -->
                <h5 class="mb-3 text-center"><i class="fas fa-coins me-2"></i>Supported Cryptocurrencies</h5>
                <div class="supported-currencies">
                    <div class="currency-badge">
                        <i class="fab fa-bitcoin"></i>
                        Bitcoin
                    </div>
                    <div class="currency-badge">
                        <i class="fab fa-ethereum"></i>
                        Ethereum
                    </div>
                    <div class="currency-badge">
                        <i class="fas fa-circle"></i>
                        USDC
                    </div>
                    <div class="currency-badge">
                        <i class="fas fa-dollar-sign"></i>
                        USDT
                    </div>
                    <div class="currency-badge">
                        <i class="fas fa-gem"></i>
                        DAI
                    </div>
                    <div class="currency-badge">
                        <i class="fab fa-bitcoin"></i>
                        Litecoin
                    </div>
                </div>
                
                <!-- Pay Button -->
                <button id="payButton" class="pay-btn" onclick="initiateCryptoPayment()">
                    <span class="loading-spinner" id="loadingSpinner">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <i class="fas fa-shield-alt me-2"></i>
                    Continue to Coinbase Commerce
                </button>
                
                <!-- Info Section -->
                <div class="info-section">
                    <h4><i class="fas fa-shield-alt me-2"></i>Secure & Trusted</h4>
                    <ul>
                        <li>All transactions are processed securely through <strong>Coinbase Commerce</strong></li>
                        <li>No account needed - pay directly from your wallet</li>
                        <li>Instant confirmation once blockchain transaction is verified</li>
                        <li>Support for multiple cryptocurrencies</li>
                    </ul>
                </div>
                
                <!-- Back Button -->
                <div class="back-btn text-center">
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        function initiateCryptoPayment() {
            const payButton = document.getElementById('payButton');
            const spinner = document.getElementById('loadingSpinner');
            
            // Get payment data from URL
            const urlParams = new URLSearchParams(window.location.search);
            const paymentData = {
                type: urlParams.get('type') || 'donation',
                reference_id: urlParams.get('reference_id') || urlParams.get('donation_id'),
                amount: urlParams.get('amount'),
                website_id: urlParams.get('website_id'),
                user_id: urlParams.get('user_id') || null,
                session_id: urlParams.get('session_id') || null
            };
            
            // Validate required fields
            if (!paymentData.reference_id || !paymentData.amount) {
                alert('Missing payment information. Please try again.');
                return;
            }
            
            // Disable button and show loading
            payButton.disabled = true;
            spinner.classList.add('show');
            
            // Call API to create Coinbase charge
            fetch('/coinbase/create-charge', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(paymentData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.hosted_url) {
                    // Redirect to Coinbase Commerce hosted checkout
                    window.location.href = data.hosted_url;
                } else {
                    // Show error
                    alert(data.error || 'Failed to create payment. Please try again.');
                    payButton.disabled = false;
                    spinner.classList.remove('show');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                payButton.disabled = false;
                spinner.classList.remove('show');
            });
        }
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .crypto-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .crypto-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .crypto-header {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .crypto-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
        }
        
        .crypto-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .crypto-body {
            padding: 40px;
        }
        
        .payment-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .payment-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .payment-info-row:last-child {
            border-bottom: none;
            font-size: 1.3rem;
            font-weight: bold;
            color: #f39c12;
        }
        
        .payment-info-label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .payment-info-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .crypto-option {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .crypto-option:hover {
            border-color: #f39c12;
            box-shadow: 0 5px 20px rgba(243, 156, 18, 0.2);
            transform: translateY(-2px);
        }
        
        .crypto-option.selected {
            border-color: #f39c12;
            background: #fff9f0;
        }
        
        .crypto-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 20px;
        }
        
        .bitcoin-icon {
            background: #f7931a;
            color: white;
        }
        
        .ethereum-icon {
            background: #627eea;
            color: white;
        }
        
        .usdt-icon {
            background: #26a17b;
            color: white;
        }
        
        .wallet-address {
            background: #f8f9fa;
            border: 2px dashed #f39c12;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            display: none;
        }
        
        .wallet-address.show {
            display: block;
        }
        
        .address-box {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            word-break: break-all;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .copy-btn {
            background: #f39c12;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            margin-left: 10px;
        }
        
        .copy-btn:hover {
            background: #e67e22;
        }
        
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        
        .qr-code-box {
            background: white;
            border: 5px solid #f39c12;
            border-radius: 15px;
            padding: 20px;
            display: inline-block;
        }
        
        .instructions {
            background: #e8f4fd;
            border-left: 4px solid #0d6efd;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .instructions h4 {
            color: #0d6efd;
            margin-bottom: 15px;
        }
        
        .instructions ol {
            margin: 0;
            padding-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 10px;
        }
        
        .demo-badge {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .back-btn {
            margin-top: 30px;
        }
        
        @media (max-width: 768px) {
            .crypto-header h1 {
                font-size: 1.5rem;
            }
            
            .crypto-body {
                padding: 20px;
            }
            
            .address-box {
                flex-direction: column;
                gap: 10px;
            }
            
            .copy-btn {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="crypto-container">
        <div class="crypto-card">
            <div class="crypto-header">
                <h1>
                    <i class="fas fa-bitcoin-sign me-2"></i>
                    Cryptocurrency Payment
                    <span class="demo-badge">DEMO</span>
                </h1>
                <p>Complete your payment using cryptocurrency</p>
            </div>
            
            <div class="crypto-body">
                <!-- Payment Information -->
                <div class="payment-info">
                    <h4 class="mb-3"><i class="fas fa-info-circle me-2"></i>Payment Details</h4>
                    <div class="payment-info-row">
                        <span class="payment-info-label">Payment Type</span>
                        <span class="payment-info-value">{{ ucfirst(request('type', 'donation')) }}</span>
                    </div>
                    <div class="payment-info-row">
                        <span class="payment-info-label">Reference ID</span>
                        <span class="payment-info-value">#{{ request('donation_id', '000000') }}</span>
                    </div>
                    <div class="payment-info-row">
                        <span class="payment-info-label">Amount to Pay</span>
                        <span class="payment-info-value">${{ number_format(request('amount', 0), 2) }}</span>
                    </div>
                </div>
                
                <!-- Select Cryptocurrency -->
                <h4 class="mb-4"><i class="fas fa-coins me-2"></i>Select Cryptocurrency</h4>
                
                <div class="crypto-option" onclick="selectCrypto('bitcoin')">
                    <div class="d-flex align-items-center">
                        <div class="crypto-icon bitcoin-icon">
                            <i class="fab fa-bitcoin"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Bitcoin (BTC)</h5>
                            <small class="text-muted">The original cryptocurrency</small>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </div>
                </div>
                
                <div class="crypto-option" onclick="selectCrypto('ethereum')">
                    <div class="d-flex align-items-center">
                        <div class="crypto-icon ethereum-icon">
                            <i class="fab fa-ethereum"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Ethereum (ETH)</h5>
                            <small class="text-muted">Smart contract platform</small>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </div>
                </div>
                
                <div class="crypto-option" onclick="selectCrypto('usdt')">
                    <div class="d-flex align-items-center">
                        <div class="crypto-icon usdt-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Tether (USDT)</h5>
                            <small class="text-muted">Stablecoin pegged to USD</small>
                        </div>
                        <div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Wallet Address (Hidden initially) -->
                <div id="walletAddressSection" class="wallet-address">
                    <h4 class="mb-3"><i class="fas fa-wallet me-2"></i>Send <span id="cryptoName">Bitcoin</span> to this address</h4>
                    
                    <div class="qr-code">
                        <div class="qr-code-box">
                            <canvas id="qrcode"></canvas>
                            <p class="mt-2 mb-0 text-muted"><small>Scan QR Code</small></p>
                        </div>
                    </div>
                    
                    <p class="text-center mb-2"><strong>Or copy the address below:</strong></p>
                    <div class="address-box">
                        <span id="walletAddressText">1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa</span>
                        <button class="copy-btn" onclick="copyAddress()">
                            <i class="fas fa-copy me-2"></i>Copy
                        </button>
                    </div>
                    
                    <div class="alert alert-warning mt-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> Send exactly <strong>${{ number_format(request('amount', 0), 2) }}</strong> worth of <span id="cryptoNameAlert">Bitcoin</span> to complete the payment.
                    </div>
                </div>
                
                <!-- Instructions -->
                <div class="instructions">
                    <h4><i class="fas fa-clipboard-list me-2"></i>How to Complete Payment</h4>
                    <ol>
                        <li>Select your preferred cryptocurrency from the options above</li>
                        <li>Copy the wallet address or scan the QR code with your crypto wallet app</li>
                        <li>Send the exact amount shown in your cryptocurrency</li>
                        <li>Wait for blockchain confirmation (typically 10-30 minutes)</li>
                        <li>You will receive a confirmation email once payment is verified</li>
                    </ol>
                    
                    <div class="alert alert-info mt-3 mb-0" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> This is a demonstration page. No actual crypto payment processing is configured. In a live environment, you would integrate with a crypto payment gateway like Coinbase Commerce, BitPay, or similar services.
                    </div>
                </div>
                
                <!-- Back Button -->
                <div class="back-btn text-center">
                    <a href="javascript:history.back()" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Back to Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    
    <script>
        let currentCrypto = null;
        
        const walletAddresses = {
            bitcoin: '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
            ethereum: '0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb',
            usdt: 'TYASr5UV6HEcXatwdFQfmLVUqQQQMUxHLS'
        };
        
        const cryptoNames = {
            bitcoin: 'Bitcoin',
            ethereum: 'Ethereum',
            usdt: 'Tether (USDT)'
        };
        
        function selectCrypto(crypto) {
            // Remove selected class from all options
            document.querySelectorAll('.crypto-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.currentTarget.classList.add('selected');
            
            // Update current crypto
            currentCrypto = crypto;
            
            // Show wallet address section
            const walletSection = document.getElementById('walletAddressSection');
            walletSection.classList.add('show');
            
            // Update crypto name
            document.getElementById('cryptoName').textContent = cryptoNames[crypto];
            document.getElementById('cryptoNameAlert').textContent = cryptoNames[crypto];
            
            // Update wallet address
            const address = walletAddresses[crypto];
            document.getElementById('walletAddressText').textContent = address;
            
            // Generate QR code
            generateQRCode(address);
            
            // Scroll to wallet section
            setTimeout(() => {
                walletSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        }
        
        function generateQRCode(address) {
            const canvas = document.getElementById('qrcode');
            
            // Clear previous QR code
            const context = canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);
            
            // Generate new QR code
            QRCode.toCanvas(canvas, address, {
                width: 200,
                margin: 2,
                color: {
                    dark: '#000000',
                    light: '#ffffff'
                }
            }, function (error) {
                if (error) console.error(error);
            });
        }
        
        function copyAddress() {
            const addressText = document.getElementById('walletAddressText').textContent;
            
            // Copy to clipboard
            navigator.clipboard.writeText(addressText).then(() => {
                // Show success feedback
                const copyBtn = event.currentTarget;
                const originalHTML = copyBtn.innerHTML;
                
                copyBtn.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
                copyBtn.style.background = '#28a745';
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalHTML;
                    copyBtn.style.background = '#f39c12';
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Failed to copy address. Please copy manually.');
            });
        }
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
