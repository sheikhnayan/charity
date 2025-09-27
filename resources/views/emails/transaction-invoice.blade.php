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
            <h2>{{ $transaction_type_label ?? 'Transaction' }} {{ in_array($transaction->type, ['student', 'general']) ? 'Receipt' : 'Confirmation' }}</h2>
            <p>{{ in_array($transaction->type, ['student', 'general']) ? 'Receipt' : 'Confirmation' }} for Transaction #{{ $transaction->transaction_id }}</p>
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
                        <span class="detail-value">{{ $transaction_type_label ?? ucfirst($transaction->type) }}</span>
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

            {{-- Student Donation Details --}}
            @if($transaction->type === 'student' && isset($donation))
            <div class="detail-section">
                <h3>Student Donation Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Supporting Organization:</span>
                    <span class="detail-value">{{ $website->name }}</span>
                </div>
                @if($donation->student_name)
                <div class="detail-row">
                    <span class="detail-label">Student Name:</span>
                    <span class="detail-value">{{ $donation->student_name }}</span>
                </div>
                @endif
                @if($donation->student_id)
                <div class="detail-row">
                    <span class="detail-label">Student ID:</span>
                    <span class="detail-value">{{ $donation->student_id }}</span>
                </div>
                @endif
                @if($donation->purpose)
                <div class="detail-row">
                    <span class="detail-label">Purpose:</span>
                    <span class="detail-value">{{ $donation->purpose }}</span>
                </div>
                @endif
            </div>
            @endif

            {{-- General Donation Details --}}
            @if($transaction->type === 'general' && isset($donation))
            <div class="detail-section">
                <h3>Donation Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Organization:</span>
                    <span class="detail-value">{{ $website->name }}</span>
                </div>
                @if($donation->purpose)
                <div class="detail-row">
                    <span class="detail-label">Donation Purpose:</span>
                    <span class="detail-value">{{ $donation->purpose }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Tax Deductible:</span>
                    <span class="detail-value">Please consult your tax advisor</span>
                </div>
            </div>
            @endif

            {{-- Ticket Purchase Details --}}
            @if($transaction->type === 'ticket' && isset($ticket_sale))
            <div class="detail-section">
                <h3>Ticket Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Event Organizer:</span>
                    <span class="detail-value">{{ $website->name }}</span>
                </div>
                @if($ticket_sale->details && $ticket_sale->details->count() > 0)
                    @foreach($ticket_sale->details as $detail)
                    <div class="detail-row">
                        <span class="detail-label">{{ $detail->ticket->name ?? 'Ticket' }}:</span>
                        <span class="detail-value">Quantity: {{ $detail->quantity }} @ ${{ number_format($detail->ticket->price ?? 0, 2) }} each</span>
                    </div>
                    @endforeach
                @endif
                @if($ticket_sale->event_date)
                <div class="detail-row">
                    <span class="detail-label">Event Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($ticket_sale->event_date)->format('M d, Y') }}</span>
                </div>
                @endif
            </div>
            @endif

            {{-- Auction Bid Details --}}
            @if($transaction->type === 'auction' && isset($auction))
            <div class="detail-section">
                <h3>Auction Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Auction House:</span>
                    <span class="detail-value">{{ $website->name }}</span>
                </div>
                @if($auction->name)
                <div class="detail-row">
                    <span class="detail-label">Item Name:</span>
                    <span class="detail-value">{{ $auction->name }}</span>
                </div>
                @endif
                @if($auction->description)
                <div class="detail-row">
                    <span class="detail-label">Item Description:</span>
                    <span class="detail-value">{{ Str::limit($auction->description, 100) }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Bid Amount:</span>
                    <span class="detail-value">${{ number_format($transaction->amount, 2) }}</span>
                </div>
                @if($auction->end_date)
                <div class="detail-row">
                    <span class="detail-label">Auction End Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($auction->end_date)->format('M d, Y') }}</span>
                </div>
                @endif
            </div>
            @endif

            {{-- Investment Details --}}
            @if($transaction->type === 'investment' && isset($investment))
            <div class="detail-section">
                <h3>Investment Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Investment Company:</span>
                    <span class="detail-value">{{ $website->name }}</span>
                </div>
                @if($investment->investor_name)
                <div class="detail-row">
                    <span class="detail-label">Investor Name:</span>
                    <span class="detail-value">{{ $investment->investor_name }}</span>
                </div>
                @endif
                @if($investment->investor_type)
                <div class="detail-row">
                    <span class="detail-label">Investor Type:</span>
                    <span class="detail-value">{{ $investment->investor_type }}</span>
                </div>
                @endif
                @if($investment->share_quantity)
                <div class="detail-row">
                    <span class="detail-label">Share Quantity:</span>
                    <span class="detail-value">{{ number_format($investment->share_quantity) }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Investment Amount:</span>
                    <span class="detail-value">${{ number_format($investment->investment_amount ?? $transaction->amount, 2) }}</span>
                </div>
                @if($investment->security_name)
                <div class="detail-row">
                    <span class="detail-label">Security:</span>
                    <span class="detail-value">{{ $investment->security_name }}</span>
                </div>
                @endif
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
            @if(in_array($transaction->type, ['student', 'general']))
                <p>🙏 Thank you for your generous donation to {{ $website->name }}!</p>
                <p>Your contribution makes a meaningful difference.</p>
            @elseif($transaction->type === 'ticket')
                <p>🎟️ Thank you for your ticket purchase!</p>
                <p>We look forward to seeing you at the event.</p>
            @elseif($transaction->type === 'auction')
                <p>🔨 Thank you for participating in our auction!</p>
                <p>We'll notify you if you win this item.</p>
            @elseif($transaction->type === 'investment')
                <p>💼 Thank you for your investment in {{ $website->name }}!</p>
                <p>We appreciate your confidence in our venture.</p>
            @else
                <p>Thank you for your transaction!</p>
            @endif
            
            <p><strong>📎 A detailed PDF {{ in_array($transaction->type, ['student', 'general']) ? 'receipt' : 'confirmation' }} is attached to this email for your records.</strong></p>
            <p>{{ $website->name }} | {{ $website->domain }}</p>
            <p><small>This is a computer-generated {{ in_array($transaction->type, ['student', 'general']) ? 'receipt' : 'confirmation' }}. If you have any questions, please contact us at {{ config('mail.from.address', 'noreply@' . $website->domain) }}</small></p>
        </div>
    </div>
</body>
</html>