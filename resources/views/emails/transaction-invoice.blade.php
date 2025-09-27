<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .invoice-container { background: white; max-width: 800px; margin: 0 auto; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #007bff; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #007bff; }
        .invoice-details { margin: 20px 0; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .detail-section { background: #f8f9fa; padding: 15px; border-radius: 5px; }
        .detail-section h3 { margin: 0 0 10px 0; color: #333; font-size: 16px; }
        .detail-row { margin: 8px 0; display: flex; justify-content: space-between; }
        .detail-label { font-weight: bold; color: #555; }
        .detail-value { color: #333; }
        .financial-summary { background: #e9ecef; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .financial-row { display: flex; justify-content: space-between; margin: 10px 0; font-size: 16px; }
        .financial-row.total { border-top: 2px solid #007bff; padding-top: 10px; font-weight: bold; font-size: 18px; color: #007bff; }
        .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; color: #6c757d; }
        @media print { body { background-color: white; } .invoice-container { box-shadow: none; } }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="logo">{{ $website->name }}</div>
            <h2>Transaction Invoice</h2>
            <p>Invoice for Transaction #{{ $transaction->transaction_id }}</p>
        </div>

        <div class="invoice-details">
            <div class="details-grid">
                <div class="detail-section">
                    <h3>Transaction Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Transaction ID:</span>
                        <span class="detail-value">{{ $transaction->transaction_id }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value">{{ $transaction->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Type:</span>
                        <span class="detail-value">{{ ucfirst($transaction->type) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value">{{ $transaction->status == 1 ? 'Approved' : 'Pending' }}</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Customer Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Name:</span>
                        <span class="detail-value">{{ $transaction->name }} {{ $transaction->last_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $transaction->email }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value">{{ $transaction->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Address:</span>
                        <span class="detail-value">
                            {{ $transaction->address }}<br>
                            {{ $transaction->city }}, {{ $transaction->state }} {{ $transaction->zip }}<br>
                            {{ $transaction->country }}
                        </span>
                    </div>
                </div>
            </div>

            @if($transaction->type === 'investment' && $transaction->investment)
            <div class="detail-section">
                <h3>Investment Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Investor Name:</span>
                    <span class="detail-value">{{ $transaction->investment->investor_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Investment Type:</span>
                    <span class="detail-value">{{ $transaction->investment->investor_type }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Share Quantity:</span>
                    <span class="detail-value">{{ $transaction->investment->share_quantity }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Investment Amount:</span>
                    <span class="detail-value">${{ number_format($transaction->investment->investment_amount, 2) }}</span>
                </div>
            </div>
            @endif

            <div class="financial-summary">
                <h3>Financial Summary</h3>
                <div class="financial-row">
                    <span>Amount:</span>
                    <span>${{ number_format($transaction->amount, 2) }}</span>
                </div>
                @if($transaction->fee_paid)
                <div class="financial-row">
                    <span>Processing Fee:</span>
                    <span>${{ number_format(($transaction->amount / 100) * ($website->paymentSettings->fee ?? 2.9), 2) }}</span>
                </div>
                @endif
                <div class="financial-row total">
                    <span>Total Paid:</span>
                    <span>${{ number_format($transaction->fee_paid ? $total_with_fee : $transaction->amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your {{ $transaction->type === 'investment' ? 'investment' : 'donation' }}!</p>
            <p><strong>📎 A detailed PDF invoice is attached to this email for your records.</strong></p>
            <p>{{ $website->name }} | {{ $website->domain }}</p>
            <p><small>This is a computer-generated invoice. If you have any questions, please contact us at {{ config('mail.from.address', 'noreply@' . $website->domain) }}</small></p>
        </div>
    </div>
</body>
</html>