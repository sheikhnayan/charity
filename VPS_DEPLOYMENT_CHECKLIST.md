# DEPLOYMENT CHECKLIST FOR VPS

## 🚀 **Files That Need to Be on Your VPS**

### **Critical Files for Payment Funnel Tracking:**

1. **PaymentFunnelService.php** ✅
   - Path: `app/Services/PaymentFunnelService.php`
   - Contains all tracking logic

2. **PaymentFunnelEvent.php** ✅ 
   - Path: `app/Models/PaymentFunnelEvent.php`
   - Database model for events

3. **Updated AuthorizeNetController.php** ⚠️
   - Path: `app/Http/Controllers/AuthorizeNetController.php`
   - Line 496: `$this->trackPaymentFunnel('completed', 'ticket', $request->amount, $charge->id, null, null);`
   - THIS IS THE KEY LINE THAT CALLS THE TRACKING

4. **Database Migration** ⚠️
   - Migration file: `create_payment_funnel_events_table.php`
   - Must be run: `php artisan migrate`

5. **Composer Package** ⚠️
   - Jenssegers/Agent package for device detection
   - Must run: `composer install`

## 🔧 **VPS Deployment Steps**

1. **Upload updated files** (especially AuthorizeNetController.php)
2. **Run migration**: `php artisan migrate`
3. **Install dependencies**: `composer install`
4. **Clear caches**: `php artisan config:clear && php artisan cache:clear`

## 🎯 **The Missing Link**

The analytics events show your purchase went through on the VPS, but the payment funnel tracking didn't happen because:

- Either the `trackPaymentFunnel` call on line 496 doesn't exist on your VPS
- Or the PaymentFunnelService/migration hasn't been deployed
- Or there's an error preventing the tracking from executing

## ✅ **Quick Test**

To verify this is the issue, make another test purchase on your VPS AFTER deploying these files.