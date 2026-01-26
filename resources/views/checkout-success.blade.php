@extends('layouts.main')

@section('title', 'Checkout Success')

@section('content')
<div class="checkout-success-container">
    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>

        <h1>Thank You!</h1>
        <p class="success-message">Your purchase has been completed successfully.</p>

        <div class="order-details">
            <div class="detail-row">
                <span>Order Date:</span>
                <span>{{ $transaction['timestamp']->format('M d, Y H:i A') }}</span>
            </div>
            <div class="detail-row">
                <span>Email:</span>
                <span>{{ $transaction['email'] }}</span>
            </div>
            <div class="detail-row highlight">
                <span>Order Total:</span>
                <span>${{ number_format($transaction['total'], 2) }}</span>
            </div>
        </div>

        <div class="order-items">
            <h3>Items Purchased</h3>
            <div class="items-list">
                @php
                    $subtotal = $transaction['subtotal'] ?? 0;
                @endphp
                @foreach($transaction['items'] as $item)
                    <div class="item-row">
                        <div>
                            <p class="item-name">{{ $item['name'] }}</p>
                            <p class="item-type">{{ ucfirst($item['type']) }}</p>
                        </div>
                        <p class="item-amount">${{ number_format($item['total'] ?? $item['amount'] ?? 0, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="payment-summary" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; text-align: left;">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>${{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Processing Fee:</span>
                <span>${{ number_format($transaction['processing_fee'] ?? 0, 2) }}</span>
            </div>
            @if(($transaction['tip_amount'] ?? 0) > 0)
            <div class="summary-row">
                <span>Tip:</span>
                <span>${{ number_format($transaction['tip_amount'], 2) }}</span>
            </div>
            @endif
            <div class="summary-row highlight" style="border-top: 1px solid #dee2e6; padding-top: 10px; margin-top: 10px; font-weight: bold;">
                <span>Total Paid:</span>
                <span>${{ number_format($transaction['total'], 2) }}</span>
            </div>
        </div>

        <div class="success-actions">
            <a href="/" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Home
            </a>
            {{-- <a href="/donate" class="btn btn-secondary">
                <i class="fas fa-heart"></i> Make Another Donation
            </a> --}}
        </div>

        <div class="support-note">
            <p>A confirmation email has been sent to <strong>{{ $transaction['email'] }}</strong></p>
            <p>If you have any questions, please <a href="/contact">contact us</a>.</p>
        </div>
    </div>
</div>

<style>
    .checkout-success-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        margin-top: 80px;
    }

    .success-card {
        background: white;
        border-radius: 12px;
        padding: 60px 40px;
        max-width: 600px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        text-align: center;
    }

    .success-icon {
        margin-bottom: 30px;
    }

    .success-icon i {
        font-size: 80px;
        color: #27ae60;
        display: block;
        animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .success-card h1 {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .success-message {
        font-size: 16px;
        color: #7f8c8d;
        margin-bottom: 30px;
    }

    /* Order Details */
    .order-details {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
        font-size: 14px;
        color: #2c3e50;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-row.highlight {
        background: white;
        padding: 12px;
        margin: 0 -12px;
        border-bottom: none;
        font-weight: 600;
        border-radius: 6px;
        border: 2px solid #667eea;
    }

    .detail-row.highlight span:last-child {
        color: #667eea;
        font-size: 18px;
    }

    /* Order Items */
    .order-items {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        text-align: left;
    }

    .order-items h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .items-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 12px;
        background: white;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    .item-name {
        margin: 0 0 4px 0;
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
    }

    .item-type {
        margin: 0;
        font-size: 12px;
        color: #95a5a6;
    }

    .item-amount {
        font-size: 16px;
        font-weight: 700;
        color: #667eea;
        margin: 0;
    }

    /* Payment Summary */
    .payment-summary {
        display: flex;
        flex-direction: column;
        gap: 0;
        text-align: left;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #dee2e6;
        font-size: 14px;
        color: #2c3e50;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-row span:last-child {
        font-weight: 600;
        color: #2c3e50;
    }

    .summary-row.highlight span:last-child {
        color: #667eea;
        font-size: 16px;
        font-weight: 700;
    }

    /* Actions */
    .success-actions {
        display: flex;
        gap: 16px;
        margin-bottom: 30px;
        justify-content: center;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background: #ecf0f1;
        color: #2c3e50;
    }

    .btn-secondary:hover {
        background: #dfe6e9;
        color: #2c3e50;
        text-decoration: none;
    }

    /* Support Note */
    .support-note {
        padding: 16px;
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
        border-radius: 4px;
        text-align: left;
        font-size: 13px;
        color: #1565c0;
    }

    .support-note p {
        margin: 8px 0;
    }

    .support-note a {
        color: #1565c0;
        text-decoration: underline;
    }

    .support-note a:hover {
        color: #0d47a1;
    }

    /* Responsive */
    @media (max-width: 600px) {
        .success-card {
            padding: 40px 24px;
        }

        .success-card h1 {
            font-size: 24px;
        }

        .success-icon i {
            font-size: 60px;
        }

        .success-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection
