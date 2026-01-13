<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $website->name }} - Donate via QR</title>
    
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
        
        .qr-type-badge {
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
        
        .type-selector {
            margin-bottom: 25px;
        }
        
        .type-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .type-tab {
            flex: 1;
            padding: 12px;
            border: none;
            background: none;
            font-size: 14px;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .type-tab.active {
            color: var(--accent-color);
            border-bottom-color: var(--accent-color);
        }
        
        .type-content {
            display: none;
        }
        
        .type-content.active {
            display: block;
        }
        
        .selection-list {
            max-height: 250px;
            overflow-y: auto;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .selection-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }
        
        .selection-item:last-child {
            border-bottom: none;
        }
        
        .selection-item:hover {
            background-color: #f8f9fa;
            transform: translateX(2px);
        }
        
        .selection-item.selected {
            background-color: var(--accent-color);
            color: white;
        }
        
        .selection-item-radio {
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 50%;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .selection-item.selected .selection-item-radio {
            border-color: white;
            background-color: white;
        }
        
        .selection-item.selected .selection-item-radio::after {
            content: '✓';
            color: var(--accent-color);
            font-weight: bold;
        }
        
        .selection-item-label {
            flex: 1;
        }
        
        .selection-item-sublabel {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 2px;
        }
        
        .selection-item.selected .selection-item-sublabel {
            opacity: 0.9;
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
        
        .donate-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
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

        .required-label {
            color: #dc3545;
        }

        .hidden-input {
            display: none;
        }

        .selection-required {
            display: none;
            color: #dc3545;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 10px;
        }

        .selection-required.show {
            display: block;
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
            <div class="qr-type-badge">
                <i class="fas fa-mobile-alt"></i> Secure QR Donation
            </div>
        </div>
        
        <div class="qr-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form action="{{ route('qr.donate.process') }}" method="POST" id="qrDonateForm">
                @csrf
                <input type="hidden" name="website_id" value="{{ $website->id }}">
                <input type="hidden" name="qr_identifier" value="{{ $qrIdentifier }}">
                <input type="hidden" name="type" id="typeInput" value="{{ $type }}">
                <input type="hidden" name="auction_id" id="auctionIdInput" class="hidden-input">
                <input type="hidden" name="ticket_id" id="ticketIdInput" class="hidden-input">
                <input type="hidden" name="student_id" id="studentIdInput" class="hidden-input">
                
                <!-- Type Selection -->
                <div class="type-selector">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-cubes me-1"></i> What would you like to donate for? <span class="required-label">*</span>
                    </label>
                    <div class="type-tabs">
                        <button type="button" class="type-tab {{ $type === 'donation' ? 'active' : '' }}" data-type="donation">
                            <i class="fas fa-heart me-1"></i> Donation
                        </button>
                        <button type="button" class="type-tab {{ $type === 'auction' ? 'active' : '' }}" data-type="auction">
                            <i class="fas fa-gavel me-1"></i> Auction
                        </button>
                        <button type="button" class="type-tab {{ $type === 'sales' ? 'active' : '' }}" data-type="sales">
                            <i class="fas fa-ticket me-1"></i> Tickets
                        </button>
                    </div>
                </div>
                
                <!-- Donation Type Content -->
                <div class="type-content {{ $type === 'donation' ? 'active' : '' }}" data-type-content="donation">
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
                </div>
                
                <!-- Auction Type Content -->
                <div class="type-content {{ $type === 'auction' ? 'active' : '' }}" data-type-content="auction">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-gavel me-1"></i> Select Auction <span class="required-label">*</span>
                    </label>
                    <div class="selection-required auction-required">Please select an auction</div>
                    <div class="selection-list" id="auctionList">
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-spinner fa-spin"></i> Loading auctions...
                        </div>
                    </div>
                    <input type="hidden" name="auction_id_temp" id="auctionSelected" value="{{ $selectedId ?? '' }}">
                </div>
                
                <!-- Sales/Tickets Type Content -->
                <div class="type-content {{ $type === 'sales' ? 'active' : '' }}" data-type-content="sales">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-ticket me-1"></i> Select Ticket <span class="required-label">*</span>
                    </label>
                    <div class="selection-required sales-required">Please select a ticket</div>
                    <div class="selection-list" id="ticketList">
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-spinner fa-spin"></i> Loading tickets...
                        </div>
                    </div>
                    <input type="hidden" name="ticket_id_temp" id="ticketSelected" value="{{ $selectedId ?? '' }}">
                </div>

                <!-- Student/Donation Beneficiary Content -->
                <div class="type-content {{ $type === 'donation' ? 'active' : '' }}" data-type-content="donation-student" style="display: none;">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-user me-1"></i> Select Student Beneficiary <span class="required-label">*</span>
                    </label>
                    <div class="selection-required donation-student-required">Please select a student</div>
                    <div class="selection-list" id="studentList">
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-spinner fa-spin"></i> Loading students...
                        </div>
                    </div>
                    <input type="hidden" name="student_id_temp" id="studentSelected" value="">
                </div>
                
                <!-- Personal Information -->
                <label class="form-label fw-semibold mb-3 mt-4">
                    <i class="fas fa-user-circle me-1"></i> Your Information <span class="required-label">*</span>
                </label>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="text" 
                               class="form-control" 
                               name="first_name" 
                               placeholder="First Name"
                               value="{{ old('first_name') }}"
                               required>
                    </div>
                    <div class="col-6">
                        <input type="text" 
                               class="form-control" 
                               name="last_name" 
                               placeholder="Last Name"
                               value="{{ old('last_name') }}"
                               required>
                    </div>
                </div>
                
                <input type="email" 
                       class="form-control" 
                       name="email" 
                       placeholder="Email Address"
                       value="{{ old('email') }}"
                       required>
                
                <input type="tel" 
                       class="form-control" 
                       name="phone" 
                       placeholder="Phone Number (Optional)"
                       value="{{ old('phone') }}">
                
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
                
                <!-- Tipping Component (Donation Type Only) -->
                <div id="tippingContainer" style="display: none;">
                    @include('components.tipping', [
                        'baseAmount' => 0,
                        'primaryColor' => '#28a745'
                    ])
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="donate-btn" id="submitBtn">
                    <i class="fas fa-heart me-2"></i> <span id="submitBtnText">Continue to Payment</span>
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
        const websiteId = {{ $website->id }};
        const currentType = '{{ $type }}';
        const selectedIdFromUrl = '{{ $selectedId ?? "" }}';
        const presetAmount = '{{ $presetAmount ?? "" }}';
        const isQRScanned = selectedIdFromUrl !== ''; // True if QR was scanned with pre-selection
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // If QR was scanned, hide type tabs and show only selected content
            if (isQRScanned) {
                hideTypeSelectionTabs();
            }
            
            setupTypeSelection();
            loadDataForType(currentType);
            setupAmountButtons();
            
            // Set preset amount if provided
            if (presetAmount && currentType === 'donation') {
                document.getElementById('donationAmount').value = parseFloat(presetAmount);
            }
            
            // Auto-select 10% tip for donations
            if (currentType === 'donation') {
                autoSelectTip();
            }
            
            trackFormView();
        });

        // Hide type selection tabs when QR code pre-selected item
        function hideTypeSelectionTabs() {
            const typeSelector = document.querySelector('.type-selector');
            const typeTabs = document.querySelector('.type-tabs');
            
            if (typeTabs) {
                typeTabs.style.display = 'none';
            }
            
            // Add info banner instead
            if (typeSelector && !document.getElementById('qrModeInfo')) {
                const infoBanner = document.createElement('div');
                infoBanner.id = 'qrModeInfo';
                infoBanner.className = 'alert alert-info mb-3';
                infoBanner.innerHTML = `<i class="fas fa-qrcode me-2"></i> <strong>QR Mode:</strong> Showing selected ${currentType}`;
                typeSelector.insertBefore(infoBanner, typeSelector.firstChild);
            }
        }

        // Setup type selection tabs
        function setupTypeSelection() {
            document.querySelectorAll('.type-tab').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Don't allow type switching if QR scanned
                    if (isQRScanned) {
                        return;
                    }
                    const newType = this.getAttribute('data-type');
                    switchType(newType);
                });
            });
        }

        // Auto-select 10% tip
        function autoSelectTip() {
            setTimeout(() => {
                // Find 10% button in tipping component (usually second button after default)
                const tipButtons = document.querySelectorAll('[data-tip-percentage="10"]');
                if (tipButtons.length > 0) {
                    tipButtons[0].click();
                }
            }, 300);
        }

        // Switch between types
        function switchType(type) {
            // Update hidden input
            document.getElementById('typeInput').value = type;
            
            // Update active tab
            document.querySelectorAll('.type-tab').forEach(tab => {
                tab.classList.remove('active');
                if (tab.getAttribute('data-type') === type) {
                    tab.classList.add('active');
                }
            });
            
            // Update content visibility
            document.querySelectorAll('.type-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelector(`[data-type-content="${type}"]`).classList.add('active');
            
            // Show student picker for donation type
            if (type === 'donation') {
                document.querySelector('[data-type-content="donation-student"]').style.display = 'block';
                loadStudentsForWebsite();
                showTippingComponent();
            } else {
                document.querySelector('[data-type-content="donation-student"]').style.display = 'none';
                hideTippingComponent();
            }
            
            // Load data for new type
            loadDataForType(type);
            
            // Clear previous selections
            clearSelections();
        }

        // Load data based on type
        function loadDataForType(type) {
            if (type === 'auction') {
                loadAuctionsForWebsite();
            } else if (type === 'sales') {
                loadTicketsForWebsite();
            } else if (type === 'donation') {
                loadStudentsForWebsite();
            }
        }

        // Load auctions
        function loadAuctionsForWebsite() {
            // This would be an AJAX call to get auctions
            // For now, we'll assume the frontend handles this
            fetch(`/api/auctions?website_id=${websiteId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.auctions) {
                        renderAuctionList(data.auctions);
                    }
                })
                .catch(error => {
                    console.error('Error loading auctions:', error);
                    document.getElementById('auctionList').innerHTML = 
                        '<div class="text-center py-5 text-danger"><i class="fas fa-exclamation"></i> Failed to load auctions</div>';
                });
        }

        // Load tickets
        function loadTicketsForWebsite() {
            fetch(`/api/tickets?website_id=${websiteId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.tickets) {
                        renderTicketList(data.tickets);
                    }
                })
                .catch(error => {
                    console.error('Error loading tickets:', error);
                    document.getElementById('ticketList').innerHTML = 
                        '<div class="text-center py-5 text-danger"><i class="fas fa-exclamation"></i> Failed to load tickets</div>';
                });
        }

        // Load students
        function loadStudentsForWebsite() {
            fetch(`/api/students?website_id=${websiteId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.students) {
                        renderStudentList(data.students);
                    }
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                    document.getElementById('studentList').innerHTML = 
                        '<div class="text-center py-5 text-danger"><i class="fas fa-exclamation"></i> Failed to load students</div>';
                });
        }

        // Render auction list
        function renderAuctionList(auctions) {
            const list = document.getElementById('auctionList');
            if (auctions.length === 0) {
                list.innerHTML = '<div class="text-center py-5 text-muted"><i class="fas fa-inbox"></i> No auctions available</div>';
                return;
            }
            
            // If QR scanned, show only selected auction (no clickable list)
            if (isQRScanned && selectedIdFromUrl) {
                const selected = auctions.find(a => a.id == selectedIdFromUrl);
                if (selected) {
                    list.innerHTML = `
                        <div class="selection-item selected" style="cursor: default;">
                            <div class="selection-item-radio"></div>
                            <div class="selection-item-label">
                                <div>${selected.title}</div>
                                <div class="selection-item-sublabel">Value: $${parseFloat(selected.value).toFixed(2)}</div>
                            </div>
                        </div>
                    `;
                    document.getElementById('auctionIdInput').value = selected.id;
                    return;
                }
            }
            
            list.innerHTML = auctions.map(auction => `
                <div class="selection-item ${selectedIdFromUrl == auction.id ? 'selected' : ''}" data-id="${auction.id}">
                    <div class="selection-item-radio"></div>
                    <div class="selection-item-label">
                        <div>${auction.title}</div>
                        <div class="selection-item-sublabel">Value: $${parseFloat(auction.value).toFixed(2)}</div>
                    </div>
                </div>
            `).join('');
            
            attachSelectionListeners('auction');
        }

        // Render ticket list
        function renderTicketList(tickets) {
            const list = document.getElementById('ticketList');
            if (tickets.length === 0) {
                list.innerHTML = '<div class="text-center py-5 text-muted"><i class="fas fa-inbox"></i> No tickets available</div>';
                return;
            }
            
            // If QR scanned, show only selected ticket (no clickable list)
            if (isQRScanned && selectedIdFromUrl) {
                const selected = tickets.find(t => t.id == selectedIdFromUrl);
                if (selected) {
                    list.innerHTML = `
                        <div class="selection-item selected" style="cursor: default;">
                            <div class="selection-item-radio"></div>
                            <div class="selection-item-label">
                                <div>${selected.name}</div>
                                <div class="selection-item-sublabel">${selected.category_name || 'Ticket'} • $${parseFloat(selected.price).toFixed(2)}</div>
                            </div>
                        </div>
                    `;
                    document.getElementById('ticketIdInput').value = selected.id;
                    return;
                }
            }
            
            list.innerHTML = tickets.map(ticket => `
                <div class="selection-item ${selectedIdFromUrl == ticket.id ? 'selected' : ''}" data-id="${ticket.id}">
                    <div class="selection-item-radio"></div>
                    <div class="selection-item-label">
                        <div>${ticket.name}</div>
                        <div class="selection-item-sublabel">${ticket.category_name || 'Ticket'} • $${parseFloat(ticket.price).toFixed(2)}</div>
                    </div>
                </div>
            `).join('');
            
            attachSelectionListeners('sales');
        }

        // Render student list
        function renderStudentList(students) {
            const list = document.getElementById('studentList');
            if (students.length === 0) {
                list.innerHTML = '<div class="text-center py-5 text-muted"><i class="fas fa-inbox"></i> No students available</div>';
                return;
            }
            
            // If QR scanned, show only selected student (no clickable list)
            if (isQRScanned && selectedIdFromUrl) {
                const selected = students.find(s => s.id == selectedIdFromUrl);
                if (selected) {
                    list.innerHTML = `
                        <div class="selection-item selected" style="cursor: default;">
                            <div class="selection-item-radio"></div>
                            <div class="selection-item-label">
                                <div>${selected.name} ${selected.last_name}</div>
                                <div class="selection-item-sublabel">${selected.email}</div>
                            </div>
                        </div>
                    `;
                    document.getElementById('studentIdInput').value = selected.id;
                    return;
                }
            }
            
            list.innerHTML = students.map(student => `
                <div class="selection-item ${selectedIdFromUrl == student.id ? 'selected' : ''}" data-id="${student.id}">
                    <div class="selection-item-radio"></div>
                    <div class="selection-item-label">
                        <div>${student.name} ${student.last_name}</div>
                        <div class="selection-item-sublabel">${student.email}</div>
                    </div>
                </div>
            `).join('');
            
            attachSelectionListeners('donation');
        }

        // Attach selection listeners
        function attachSelectionListeners(type) {
            let listContainer, inputElement, requiredElement;
            
            if (type === 'auction') {
                listContainer = document.getElementById('auctionList');
                inputElement = document.getElementById('auctionIdInput');
                requiredElement = document.querySelector('.auction-required');
            } else if (type === 'sales') {
                listContainer = document.getElementById('ticketList');
                inputElement = document.getElementById('ticketIdInput');
                requiredElement = document.querySelector('.sales-required');
            } else if (type === 'donation') {
                listContainer = document.getElementById('studentList');
                inputElement = document.getElementById('studentIdInput');
                requiredElement = document.querySelector('.donation-student-required');
            }
            
            // If QR scanned, don't attach click listeners (make items non-selectable)
            if (isQRScanned) {
                return;
            }
            
            listContainer.querySelectorAll('.selection-item').forEach(item => {
                item.addEventListener('click', function() {
                    // Remove previous selection
                    listContainer.querySelectorAll('.selection-item').forEach(i => i.classList.remove('selected'));
                    
                    // Add selection to clicked item
                    this.classList.add('selected');
                    
                    // Update hidden input
                    const id = this.getAttribute('data-id');
                    inputElement.value = id;
                    
                    // Hide required message
                    if (requiredElement) {
                        requiredElement.classList.remove('show');
                    }
                });
                
                // Auto-select if matching URL parameter
                if (selectedIdFromUrl && item.getAttribute('data-id') == selectedIdFromUrl) {
                    item.classList.add('selected');
                    inputElement.value = selectedIdFromUrl;
                    if (requiredElement) {
                        requiredElement.classList.remove('show');
                    }
                }
            });
        }

        // Amount button selection
        function setupAmountButtons() {
            document.querySelectorAll('.amount-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const amount = this.getAttribute('data-amount');
                    document.getElementById('donationAmount').value = amount === '1000' ? 1000 : parseInt(amount);
                });
            });
            
            document.getElementById('donationAmount').addEventListener('input', function() {
                document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
            });
        }

        // Show tipping component
        function showTippingComponent() {
            document.getElementById('tippingContainer').style.display = 'block';
        }

        // Hide tipping component
        function hideTippingComponent() {
            document.getElementById('tippingContainer').style.display = 'none';
        }

        // Clear selections
        function clearSelections() {
            document.getElementById('auctionIdInput').value = '';
            document.getElementById('ticketIdInput').value = '';
            document.getElementById('studentIdInput').value = '';
        }

        // Track form view
        function trackFormView() {
            if (typeof window.trackFunnelEvent === 'function') {
                window.trackFunnelEvent('form_view', {
                    form_type: 'qr_donation',
                    source: 'qr_code',
                    type: currentType
                });
            }
        }

        // Form submission
        document.getElementById('qrDonateForm').addEventListener('submit', function(e) {
            const type = document.getElementById('typeInput').value;
            let isValid = true;
            let errorMsg = '';
            
            // Validate based on type
            if (type === 'donation') {
                const amount = document.getElementById('donationAmount').value;
                const studentId = document.getElementById('studentIdInput').value;
                if (!amount) {
                    isValid = false;
                    errorMsg = 'Please enter a donation amount';
                }
                if (!studentId) {
                    isValid = false;
                    document.querySelector('.donation-student-required').classList.add('show');
                    errorMsg = 'Please select a student beneficiary';
                }
            } else if (type === 'auction') {
                const auctionId = document.getElementById('auctionIdInput').value;
                if (!auctionId) {
                    isValid = false;
                    document.querySelector('.auction-required').classList.add('show');
                    errorMsg = 'Please select an auction';
                }
            } else if (type === 'sales') {
                const ticketId = document.getElementById('ticketIdInput').value;
                if (!ticketId) {
                    isValid = false;
                    document.querySelector('.sales-required').classList.add('show');
                    errorMsg = 'Please select a ticket';
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                if (errorMsg) {
                    alert(errorMsg);
                }
            }
        });
    </script>
</body>
</html>
