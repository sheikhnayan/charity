# NOTIFICATIONS QUICK START

## For Users (Website Owners)

### 1. Access Notifications
- Dashboard → Site Settings (left sidebar) → **Notifications**
- Or go directly to: `/users/notifications`

### 2. Enable Notifications
- Click blue "Enable Notifications" button
- Accept browser permission when prompted
- Status should show green checkmark ✓

### 3. Choose Notification Types
**For Fundraisers:**
- ✓ Donation Notifications
- ✓ Goal Reached
- ✓ Campaign Updates
- ✓ Auction Activity
- ✓ Ticket Purchases
- ✓ Donor Messages

**For Investments:**
- ✓ Investment Inquiries
- ✓ Funding Milestones
- ✓ Investment Updates
- ✓ New Investor Applications
- ✓ Investment Transactions
- ✓ Compliance Alerts

### 4. Set Frequency
- **Real-time**: Get notifications instantly
- **Hourly**: Get summary every hour
- **Daily**: Get summary every day
- **Weekly**: Get summary every week

### 5. Set Quiet Hours
- When you don't want notifications
- Example: 22:00 (10 PM) to 08:00 (8 AM)
- No notifications during these hours

### 6. Click Save Settings

### 7. Test It
- Click "Send Test Notification"
- You should see a notification appear
- If not, troubleshoot below

---

## Quick Troubleshooting

### No notification appears?
1. Check quiet hours aren't active
2. Make sure notifications are enabled (green checkmark)
3. Check browser notification settings
4. Try refreshing page and testing again
5. Check browser console for errors (F12)

### Can't enable notifications?
1. Browser blocked notifications - check site settings
2. Try a different browser
3. Clear browser cache and cookies
4. Make sure you're logged in

### Notification shows different types than expected?
1. Hard refresh (Ctrl+Shift+R on Windows)
2. Clear browser cache
3. Check that you have the right website selected
4. Log out and back in

---

## For Developers

### Key Files
```
Routes:
  routes/web.php - /users/notifications endpoint

Controller:
  app/Http/Controllers/User/NotificationController.php

Views:
  resources/views/user/notifications.blade.php
  resources/views/user/main.blade.php (sidebar)

Payment Handlers:
  app/Http/Controllers/QRCodeDonationController.php
  app/Http/Controllers/CoinbaseController.php
  app/Http/Controllers/AuthorizeNetController.php

Core Service:
  app/Services/PushNotificationService.php
```

### Adding Notifications to New Payment Method
```php
use App\Services\PushNotificationService;

class NewPaymentController {
    protected $notificationService;
    
    public function __construct() {
        $this->notificationService = new PushNotificationService();
    }
    
    public function processPayment() {
        // ... payment processing ...
        
        // After successful payment:
        $this->notificationService->sendDonationNotification(
            $donation->user_id,      // Who to notify
            $donation->amount,       // Amount
            $donorName,             // Display name
            $donation->id           // Reference ID
        );
    }
}
```

### Checking if Notifications Working
```bash
# Clear cache
php artisan cache:clear

# Check Firebase credentials
grep -i "firebase\|fcm" .env

# Check notification tables exist
php artisan tinker
> Schema::hasTable('user_notification_tokens')
> Schema::hasTable('push_notifications')

# Check recent notifications sent
> DB::table('push_notifications')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();
```

---

## Testing Checklist

- [ ] User can access /users/notifications
- [ ] Notifications page shows site-type specific types
- [ ] User can enable notifications
- [ ] Test notification is received
- [ ] User can save preferences
- [ ] Make real donation
- [ ] Notification received for donation
- [ ] QR code donation triggers notification
- [ ] Quiet hours prevent notifications
- [ ] Multiple devices both receive notifications
- [ ] Device can be removed from connected devices

---

## Notification Types Reference

### Fundraiser Sites
| Type | When Sent | Who Receives |
|---|---|---|
| Donation | When someone donates | Website Owner |
| Goal Reached | When goal is hit | Website Owner |
| Campaign Update | When campaign progresses | Website Owner |
| Auction Activity | Auction wins/bids | Auction Creator |
| Ticket Purchase | When ticket bought | Website Owner |
| Donor Message | When donor sends message | Website Owner |

### Investment Sites
| Type | When Sent | Who Receives |
|---|---|---|
| Investment Inquiry | New investor question | Investment Creator |
| Funding Milestone | Milestone reached | Investment Creator |
| Investment Update | Progress update | Investment Creator |
| New Application | New investor applies | Investment Creator |
| Transaction | Payment received | Investment Creator |
| Compliance Alert | Compliance issue | Investment Creator |

---

## API Endpoints (Developers)

### Get Current Preferences
```
GET /api/notifications/preferences
Headers: X-Requested-With: XMLHttpRequest
Returns: { preferences: { ... } }
```

### Save Preferences  
```
POST /api/notifications/preferences
Body: { donation_enabled: true, frequency: 'realtime', ... }
Headers: X-CSRF-TOKEN, X-Requested-With: XMLHttpRequest
Returns: { success: true }
```

### Get Devices
```
GET /api/notifications/devices
Headers: X-Requested-With: XMLHttpRequest
Returns: { devices: [ { device_type, browser, last_used_at, ... } ] }
```

### Send Test
```
POST /api/notifications/test
Headers: X-CSRF-TOKEN, X-Requested-With: XMLHttpRequest
Returns: { success: true }
```

### Save Token
```
POST /api/notifications/save-token
Body: { token: "fcm_token...", device_type: "web", browser: "Chrome" }
Headers: X-CSRF-TOKEN, X-Requested-With: XMLHttpRequest
Returns: { success: true, token_id: 123 }
```

---

## Common Errors & Solutions

### Error: "Notification permission was denied"
**Solution**: Browser blocked notifications
1. Go to browser site settings for this domain
2. Find "Notifications" permission
3. Change from "Block" to "Allow"
4. Reload page and try again

### Error: "No active tokens for user"
**Solution**: Notification service couldn't find device token
1. Make sure you enabled notifications recently
2. Try clicking "Re-register Device" button
3. Check browser console for JS errors
4. Try different browser

### Error: "Push notification error" in logs
**Solution**: Firebase not configured properly
1. Check .env has FIREBASE_PROJECT_ID
2. Check .env has FIREBASE_VAPID_KEY  
3. Check Firebase project exists
4. Run `php artisan cache:clear`

### Notifications not respected for user role
**Solution**: Check notification routing
1. Verify donation.user_id is correct recipient
2. Check NotificationPreference exists for user
3. Check user hasn't disabled notification type
4. Check quiet hours aren't active

---

## Performance Notes

- Notifications sent asynchronously (non-blocking)
- Database calls are minimal
- Firebase handles delivery (not our server)
- Quiet hours checked server-side
- No performance impact on payments

---

## Security Notes

- CSRF protection on all endpoints
- User can only see own notifications/devices
- Sensitive data (tokens) not in responses
- Webhook signature verification for Coinbase
- User preferences stored per user_id

---

## Monitoring

### Check notification delivery
```sql
SELECT 
  type, 
  COUNT(*) as count,
  status,
  created_at
FROM push_notifications
GROUP BY type, status
ORDER BY created_at DESC;
```

### Check user preferences
```sql
SELECT 
  u.name,
  np.donation_enabled,
  np.auction_won_enabled,
  np.frequency,
  COUNT(unt.id) as device_count
FROM users u
LEFT JOIN notification_preferences np ON u.id = np.user_id
LEFT JOIN user_notification_tokens unt ON u.id = unt.user_id AND unt.is_active = 1
GROUP BY u.id;
```

### Check failed notifications
```sql
SELECT * 
FROM push_notifications 
WHERE status = 'failed' 
ORDER BY created_at DESC 
LIMIT 10;
```

---

**Last Updated**: January 15, 2026  
**Version**: 1.0  
**Status**: Production Ready
