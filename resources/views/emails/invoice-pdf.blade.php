<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction Invoice - {{ $transaction->transaction_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .invoice-title {
            font-size: 20px;
            margin: 10px 0;
            color: #333;
        }
        .invoice-meta {
            font-size: 12px;
            color: #666;
        }
        .details-section {
            margin: 20px 0;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #dee2e6;
        }
        .details-grid {
            width: 100%;
            margin: 15px 0;
        }
        .details-row {
            display: table;
            width: 100%;
            margin: 8px 0;
        }
        .detail-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
            color: #555;
            vertical-align: top;
        }
        .detail-value {
            display: table-cell;
            width: 60%;
            color: #333;
            vertical-align: top;
        }
        .financial-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }
        .financial-row {
            display: table;
            width: 100%;
            margin: 8px 0;
            font-size: 14px;
        }
        .financial-label {
            display: table-cell;
            width: 70%;
            font-weight: bold;
        }
        .financial-amount {
            display: table-cell;
            width: 30%;
            text-align: right;
            font-weight: bold;
        }
        .financial-row.total {
            border-top: 2px solid #007bff;
            padding-top: 10px;
            margin-top: 15px;
            font-size: 16px;
            color: #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
        .two-column {
            width: 100%;
            margin: 20px 0;
        }
        .column {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            margin-right: 4%;
        }
        .column:last-child {
            margin-right: 0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="logo">{{ $website->name }}</div>
            <h1 class="invoice-title">TRANSACTION INVOICE</h1>
            <div class="invoice-meta">
                Invoice #: {{ $transaction->transaction_id }}<br>
                Date: {{ $transaction->created_at->format('M d, Y') }}<br>
                Status: <span class="status-badge {{ $transaction->status == 1 ? 'status-approved' : 'status-pending' }}">
                    {{ $transaction->status == 1 ? 'Approved' : 'Pending' }}
                </span>
            </div>
        </div>

        <div class="two-column">
            <div class="column">
                <div class="section-title">Transaction Details</div>
                <div class="details-grid">
                    <div class="details-row">
                        <div class="detail-label">Transaction ID:</div>
                        <div class="detail-value">{{ $transaction->transaction_id }}</div>
                    </div>
                    <div class="details-row">
                        <div class="detail-label">Type:</div>
                        <div class="detail-value">{{ ucfirst($transaction->type) }}</div>
                    </div>
                    <div class="details-row">
                        <div class="detail-label">Payment Method:</div>
                        <div class="detail-value">{{ ctype_digit($transaction->transaction_id[0]) ? 'Authorize.net' : 'Stripe' }}</div>
                    </div>
                    <div class="details-row">
                        <div class="detail-label">Website:</div>
                        <div class="detail-value">{{ $website->name }}</div>
                    </div>
                    @if($transaction->ip_address)
                    <div class="details-row">
                        <div class="detail-label">IP Address:</div>
                        <div class="detail-value">{{ $transaction->ip_address }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="column">
                <div class="section-title">Customer Information</div>
                <div class="details-grid">
                    <div class="details-row">
                        <div class="detail-label">Name:</div>
                        <div class="detail-value">{{ $transaction->name }} {{ $transaction->last_name }}</div>
                    </div>
                    <div class="details-row">
                        <div class="detail-label">Email:</div>
                        <div class="detail-value">{{ $transaction->email }}</div>
                    </div>
                    @if($transaction->phone)
                    <div class="details-row">
                        <div class="detail-label">Phone:</div>
                        <div class="detail-value">{{ $transaction->phone }}</div>
                    </div>
                    @endif
                    @if($transaction->address)
                    <div class="details-row">
                        <div class="detail-label">Address:</div>
                        <div class="detail-value">
                            @if($transaction->apartment){{ $transaction->apartment }}, @endif
                            {{ $transaction->address }}<br>
                            {{ $transaction->city }}, {{ $transaction->state }} {{ $transaction->zip }}<br>
                            {{ $transaction->country }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($transaction->type === 'investment' && $transaction->investment)
        <div class="details-section">
            <div class="section-title">Investment Information</div>
            <div class="details-grid">
                <div class="details-row">
                    <div class="detail-label">Investor Name:</div>
                    <div class="detail-value">{{ $transaction->investment->investor_name }}</div>
                </div>
                <div class="details-row">
                    <div class="detail-label">Investment Type:</div>
                    <div class="detail-value">{{ $transaction->investment->investor_type }}</div>
                </div>
                <div class="details-row">
                    <div class="detail-label">Share Quantity:</div>
                    <div class="detail-value">{{ number_format($transaction->investment->share_quantity) }}</div>
                </div>
                <div class="details-row">
                    <div class="detail-label">Investment Amount:</div>
                    <div class="detail-value">${{ number_format($transaction->investment->investment_amount, 2) }}</div>
                </div>
                @if($transaction->investment->notes)
                <div class="details-row">
                    <div class="detail-label">Notes:</div>
                    <div class="detail-value">{{ $transaction->investment->notes }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="financial-summary">
            <div class="section-title">Financial Summary</div>
            <div class="financial-row">
                <div class="financial-label">Amount:</div>
                <div class="financial-amount">${{ number_format($transaction->amount, 2) }}</div>
            </div>
            @if($transaction->fee_paid)
            <div class="financial-row">
                <div class="financial-label">Processing Fee ({{ $fee_percentage ?? 2.9 }}%):</div>
                <div class="financial-amount">${{ number_format(($transaction->amount / 100) * ($fee_percentage ?? 2.9), 2) }}</div>
            </div>
            @endif
            <div class="financial-row total">
                <div class="financial-label">Total Paid:</div>
                <div class="financial-amount">${{ number_format($transaction->fee_paid ? $total_with_fee : $transaction->amount, 2) }}</div>
            </div>
        </div>

        <div class="footer">
            <p><strong>Thank you for your {{ $transaction->type === 'investment' ? 'investment' : 'donation' }}!</strong></p>
            <p>{{ $website->name }} | {{ $website->domain }}</p>
            <p>
                <small>
                    This invoice was generated on {{ now()->format('M d, Y \a\t g:i A') }}<br>
                    For questions about this transaction, please contact us at {{ config('mail.from.address', 'noreply@' . $website->domain) }}
                </small>
            </p>
        </div>
    </div>
</body>
</html>