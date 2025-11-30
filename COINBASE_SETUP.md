# Coinbase Commerce Integration Setup Guide

## Overview
This application now supports cryptocurrency payments through **Coinbase Commerce**. Users can pay with Bitcoin, Ethereum, USDC, USDT, DAI, and Litecoin.

## Features Implemented
- ✅ Full Coinbase Commerce API integration
- ✅ Support for 6 cryptocurrencies (BTC, ETH, USDC, USDT, DAI, LTC)
- ✅ Webhook handling for payment status updates
- ✅ Payment funnel tracking for analytics
- ✅ Support for all transaction types (donations, tickets, auctions, investments)
- ✅ Automatic reference model updates after payment completion
- ✅ Secure HMAC SHA256 webhook verification

## Setup Instructions

### 1. Create Coinbase Commerce Account
1. Visit [https://commerce.coinbase.com/](https://commerce.coinbase.com/)
2. Sign up or log in with your Coinbase account
3. Complete business verification if required

### 2. Get API Credentials
1. Go to **Settings** in your Coinbase Commerce dashboard
2. Navigate to **API Keys** section
3. Click **Create an API Key**
4. Copy the generated API key (you'll only see it once!)
5. Go to **Webhook subscriptions**
6. Copy the **Webhook Shared Secret**

### 3. Configure Environment Variables
Add these variables to your `.env` file:

```env
# Coinbase Commerce Configuration
COINBASE_API_KEY=your_api_key_here
COINBASE_WEBHOOK_SECRET=your_webhook_secret_here
COINBASE_WEBHOOK_URL=https://yourdomain.com/webhook/coinbase
```

**Important Notes:**
- Replace `your_api_key_here` with your actual Coinbase Commerce API key
- Replace `your_webhook_secret_here` with your webhook shared secret
- Replace `https://yourdomain.com` with your actual domain
- For local testing, use ngrok (see Testing section below)

### 4. Setup Webhook in Coinbase Commerce
1. In Coinbase Commerce dashboard, go to **Settings** → **Webhook subscriptions**
2. Click **Add an endpoint**
3. Enter your webhook URL: `https://yourdomain.com/webhook/coinbase`
4. Select events to receive:
   - ✅ `charge:confirmed` - Payment confirmed
   - ✅ `charge:failed` - Payment failed
   - ✅ `charge:delayed` - Payment delayed
   - ✅ `charge:pending` - Payment pending
   - ✅ `charge:resolved` - Payment resolved
5. Click **Add endpoint**

### 5. Run Database Migration
Run the migration to create the `crypto_payments` table:

```bash
php artisan migrate
```

This creates the table with the following structure:
- `charge_code` - Unique Coinbase charge identifier
- `charge_id` - Coinbase charge ID (after creation)
- `payment_type` - Type (donation, ticket, auction, investment)
- `reference_id` - ID of the original transaction
- `user_id` - User making payment (optional)
- `website_id` - Associated website
- `amount` - Payment amount
- `currency` - Currency (USD, EUR, etc.)
- `status` - Payment status (pending, completed, failed, delayed, resolved)
- `hosted_url` - Coinbase hosted checkout URL
- `session_id` - Analytics session ID
- `charge_data` - Full Coinbase charge object (JSON)
- `completed_at` - Payment completion timestamp

## Testing

### Local Development with ngrok
Since Coinbase needs to send webhooks to your server, you'll need a public URL for local testing:

1. **Install ngrok**: Download from [https://ngrok.com/](https://ngrok.com/)

2. **Start ngrok tunnel**:
   ```bash
   ngrok http 80
   ```

3. **Update environment variables** with ngrok URL:
   ```env
   COINBASE_WEBHOOK_URL=https://your-ngrok-url.ngrok.io/webhook/coinbase
   ```

4. **Configure webhook** in Coinbase Commerce with ngrok URL

5. **Test payment flow**:
   - Visit any donation/ticket/auction page
   - Click "Pay with Crypto" button
   - Complete test payment in Coinbase Commerce
   - Monitor webhook events in your logs

### Test Modes
Coinbase Commerce automatically provides test mode when you first sign up:
- Test payments don't require actual cryptocurrency
- Use the Coinbase Commerce dashboard to simulate different payment outcomes
- Check `storage/logs/laravel.log` for webhook processing details

## Payment Flow

### 1. User Initiates Payment
- User selects "Pay with Crypto" on checkout page
- Redirected to `/crypto-payment` with payment details
- Clicks "Continue to Coinbase Commerce"

### 2. Charge Creation
- Frontend calls `/coinbase/create-charge` API endpoint
- Backend creates Coinbase charge with:
  - Amount in USD
  - Payment metadata (type, reference_id, user_id, website_id)
  - Redirect URLs for success/cancel
- Payment funnel tracks: `payment_initiated` → `payment_processing`
- User redirected to Coinbase hosted checkout page

### 3. User Pays on Coinbase
- User selects cryptocurrency (BTC, ETH, USDC, etc.)
- Coinbase generates payment address/QR code
- User sends crypto from their wallet
- Coinbase monitors blockchain for transaction

### 4. Webhook Events
Coinbase sends webhook events as payment progresses:

- **`charge:pending`**: Payment received, waiting for confirmations
- **`charge:confirmed`**: Payment confirmed (enough blockchain confirmations)
  - Backend updates crypto_payments status to 'completed'
  - Updates original reference model (Donation, Ticket, etc.)
  - Tracks funnel: `payment_completed`
  - User can now access purchased items
  
- **`charge:failed`**: Payment failed or expired
  - Updates status to 'failed'
  - Tracks funnel: `payment_failed`
  
- **`charge:delayed`**: Payment detected but needs more confirmations
  - Updates status to 'delayed'
  
- **`charge:resolved`**: Previously delayed payment now resolved
  - Updates status to 'resolved'

### 5. Return to Site
- User redirected back to success/cancel page based on payment outcome
- Can check payment status at `/coinbase/status/{chargeCode}`

## Routes Added

### Public Routes
- `GET /crypto-payment` - Display crypto payment page
- `POST /coinbase/create-charge` - Create Coinbase charge (AJAX)
- `POST /webhook/coinbase` - Receive Coinbase webhooks (CSRF excluded)
- `GET /coinbase/status/{chargeCode}` - Check payment status

### Files Created
- `config/coinbase.php` - Configuration
- `app/Services/CoinbaseCommerceService.php` - API wrapper
- `app/Http/Controllers/CoinbaseController.php` - Payment controller
- `database/migrations/xxxx_create_crypto_payments_table.php` - Database schema
- `resources/views/crypto-payment.blade.php` - Frontend UI

## Analytics Integration

Crypto payments are fully integrated with the payment funnel system:

### Tracked Events
1. **`payment_initiated`** - When user clicks "Pay with Crypto"
2. **`payment_processing`** - When Coinbase charge is created
3. **`payment_completed`** - When payment is confirmed
4. **`payment_failed`** - When payment fails

### Dashboard Metrics
All crypto payments appear in:
- Payment Method Analytics (`/admins/payment-methods/analytics`)
- Conversion Funnel Analytics (`/analytics`)
- Revenue reports by payment method

## Security Features

### Webhook Verification
- All webhooks verified using HMAC SHA256 signature
- Uses `COINBASE_WEBHOOK_SECRET` for verification
- Rejects webhooks with invalid signatures

### CSRF Protection
- Webhook endpoint excluded from CSRF middleware
- All other endpoints require CSRF tokens

### Data Validation
- Request validation on charge creation
- Amount and reference ID required
- Payment type must be valid (donation, ticket, auction, investment)

## Supported Transaction Types

### 1. Donations
```php
/crypto-payment?type=donation&reference_id=123&amount=100.00&website_id=1
```

### 2. Event Tickets
```php
/crypto-payment?type=ticket&reference_id=456&amount=50.00&website_id=1
```

### 3. Auction Items
```php
/crypto-payment?type=auction&reference_id=789&amount=500.00&website_id=1
```

### 4. Investment Opportunities
```php
/crypto-payment?type=investment&reference_id=321&amount=1000.00&website_id=1
```

## Troubleshooting

### Webhooks Not Received
1. Check webhook URL is publicly accessible
2. Verify webhook secret matches `.env` configuration
3. Check `storage/logs/laravel.log` for errors
4. Ensure `/webhook/coinbase` is CSRF excluded in `bootstrap/app.php`

### Payment Not Updating Reference Model
1. Check `crypto_payments` table for payment record
2. Verify `charge_confirmed` webhook was received
3. Check logs for `handleConfirmed` processing
4. Ensure reference model exists (Donation, Ticket, etc.)

### API Errors
1. Verify API key is correct in `.env`
2. Check Coinbase Commerce dashboard for API rate limits
3. Review `storage/logs/laravel.log` for detailed errors

### Test Payments Not Working
1. Ensure you're in test mode in Coinbase Commerce
2. Check API credentials are from correct environment
3. Verify webhook URL is accessible from internet

## Production Checklist

Before going live:
- [ ] Valid Coinbase Commerce business account
- [ ] Production API key configured
- [ ] Webhook endpoint publicly accessible (HTTPS required)
- [ ] Webhook secret properly configured
- [ ] SSL certificate installed
- [ ] Database migration run
- [ ] Test payment completed successfully
- [ ] Webhook events processing correctly
- [ ] Payment funnel tracking verified
- [ ] Email notifications configured
- [ ] Customer support prepared for crypto questions

## Support Resources
- [Coinbase Commerce Documentation](https://commerce.coinbase.com/docs/)
- [Webhook Events Reference](https://commerce.coinbase.com/docs/api/#webhooks)
- [API Reference](https://commerce.coinbase.com/docs/api/)
- [Test Mode Guide](https://commerce.coinbase.com/docs/api/#test-mode)

## Additional Notes

### Supported Currencies
The system currently supports:
- Bitcoin (BTC)
- Ethereum (ETH)
- USD Coin (USDC)
- Tether (USDT)
- Dai (DAI)
- Litecoin (LTC)

To add more currencies, update `config/coinbase.php`:
```php
'supported_currencies' => ['BTC', 'ETH', 'USDC', 'USDT', 'DAI', 'LTC', 'NEW_CURRENCY'],
```

### Blockchain Confirmation Times
- Bitcoin: 10-60 minutes (1-6 confirmations)
- Ethereum: 2-5 minutes (12 confirmations)
- Litecoin: 5-15 minutes (6 confirmations)
- Stablecoins (USDC/USDT/DAI): 2-5 minutes

### Customer Experience
Users do NOT need:
- Coinbase account
- Application download
- Registration

Users DO need:
- Cryptocurrency wallet
- Sufficient crypto balance + network fees
- Basic understanding of sending crypto

---

**Integration Complete!** Your charity platform now accepts cryptocurrency payments. 🚀
