# Live Server Deployment Checklist

## Files Created/Modified for Website-Specific Payment Credentials

### 1. Database Migration
- `database/migrations/2025_09_15_151843_create_website_payment_settings_table.php`
  - ✅ Already run locally
  - ❗ NEEDS TO BE RUN ON LIVE SERVER

### 2. New Model
- `app/Models/WebsitePaymentSetting.php`
  - ✅ Complete with encrypted credential handling

### 3. New Service
- `app/Services/PaymentGatewayService.php`
  - ✅ Complete with dynamic credential loading

### 4. New Controller
- `app/Http/Controllers/WebsitePaymentController.php`
  - ✅ Complete admin interface for payment settings

### 5. Modified Files
- `app/Models/Website.php` - Added payment relationships
- `app/Http/Controllers/AuthorizeNetController.php` - Updated to use website-specific credentials
- `resources/views/stripe.blade.php` - Updated to use website-specific Stripe keys
- `resources/views/admin/websites/index.blade.php` - Added Payment button
- `routes/web.php` - Added payment settings routes

### 6. New View
- `resources/views/admin/website/payment-settings.blade.php`
  - ✅ Complete admin interface

## Live Server Deployment Steps

### Step 1: Pull Latest Code
```bash
git pull origin main
```

### Step 2: Run Migration
```bash
php artisan migrate
```

### Step 3: Clear Caches
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Step 4: Set Permissions (if needed)
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Step 5: Verify Database Table
Check that `website_payment_settings` table was created with these columns:
- id, website_id, payment_method, stripe_publishable_key, stripe_secret_key, 
- stripe_webhook_secret, authorize_login_id, authorize_transaction_key, 
- authorize_sandbox, is_active, settings, created_at, updated_at

### Step 6: Test Admin Interface
1. Go to `/admin/websites`
2. Click "Payment" button on any website
3. Configure test credentials
4. Test connection

### Step 7: Test Payment Processing
1. Try a donation/investment on a configured website
2. Verify it uses website-specific credentials
3. Check transaction processing

## Important Notes

1. **Backward Compatibility**: System falls back to global settings (from `settings` table) if no website-specific credentials are configured

2. **Security**: All payment credentials are encrypted in the database

3. **Error Handling**: Proper validation and error messages for missing credentials

4. **Testing**: Use sandbox/test credentials first before switching to production

## Verification Commands for Live Server

```bash
# Check migration status
php artisan migrate:status

# Verify table structure
php artisan tinker --execute="Schema::hasTable('website_payment_settings') ? 'Table exists' : 'Table missing'"

# Check if model works
php artisan tinker --execute="App\Models\WebsitePaymentSetting::count()"

# Test service
php artisan tinker --execute="app(App\Services\PaymentGatewayService::class)"
```

## Rollback Plan (if needed)
```bash
# Rollback migration
php artisan migrate:rollback --step=1
```