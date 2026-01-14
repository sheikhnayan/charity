# Push Notification Quick Reference

## 🔥 Send Notifications (Backend)

```php
use App\Services\PushNotificationService;

$notificationService = new PushNotificationService();

// 💰 Donation received
$notificationService->sendDonationNotification(
    userId: 1,
    amount: 100.00,
    donorName: 'John Doe',
    donationId: 123
);

// 🔨 Auction outbid
$notificationService->sendAuctionOutbidNotification(
    userId: 1,
    auctionTitle: 'Vintage Watch',
    newBid: 250.00,
    auctionId: 456
);

// 🎯 Goal reached
$notificationService->sendGoalReachedNotification(
    userId: 1,
    campaignName: 'Save the Planet',
    goalAmount: 10000.00,
    campaignId: 789
);

// 🎉 Auction won
$notificationService->sendAuctionWonNotification(
    userId: 1,
    auctionTitle: 'Vintage Watch',
    winningBid: 300.00,
    auctionId: 456
);

// 📢 Campaign update
$notificationService->sendCampaignUpdateNotification(
    userId: 1,
    campaignName: 'Save the Planet',
    updateMessage: 'We reached 50% of our goal!',
    campaignId: 789
);

// 📈 Investment milestone
$notificationService->sendInvestmentMilestoneNotification(
    userId: 1,
    milestoneName: '1000 investors',
    totalRaised: 50000.00,
    investmentId: 101
);

// 🎫 Ticket purchased
$notificationService->sendTicketPurchaseNotification(
    userId: 1,
    eventName: 'Charity Gala 2025',
    quantity: 2,
    ticketId: 202
);

// 🔔 Custom notification
$notificationService->sendToUser(
    userId: 1,
    title: 'Custom Title',
    body: 'Custom message here',
    data: [
        'url' => '/custom-page',
        'custom_field' => 'value'
    ],
    type: 'general'
);
```

## 🎨 Notification Types

| Type | Icon | Use Case |
|------|------|----------|
| `donation` | 💰 | New donation received |
| `auction_outbid` | 🔨 | User outbid in auction |
| `auction_won` | 🎉 | User won auction |
| `goal_reached` | 🎯 | Campaign goal achieved |
| `campaign_update` | 📢 | Campaign news/update |
| `investment_milestone` | 📈 | Investment progress |
| `ticket_purchased` | 🎫 | Event ticket confirmed |
| `general` | 🔔 | Generic notification |

## 🔧 Quick Configuration

### .env File
```bash
FCM_SERVER_KEY=your_firebase_server_key_here
```

### push-notifications.js (Line 14)
```javascript
vapidKey: 'YOUR_ACTUAL_VAPID_KEY_FROM_FIREBASE'
```

## 📍 Important URLs

- **Settings Page**: `/admin/notification-settings`
- **API Base**: `/api/notifications/`
- **Service Worker**: `/firebase-messaging-sw.js`
- **Manifest**: `/manifest.json`

## 🎯 API Quick Reference

```javascript
// Get unread count
fetch('/api/notifications/unread-count')

// Get notification list
fetch('/api/notifications/list?limit=10')

// Mark as read
fetch('/api/notifications/123/read', { method: 'POST' })

// Mark all as read
fetch('/api/notifications/mark-all-read', { method: 'POST' })

// Get preferences
fetch('/api/notifications/preferences')

// Update preferences
fetch('/api/notifications/preferences', {
    method: 'POST',
    body: JSON.stringify({
        donation_enabled: true,
        quiet_hours_start: '22:00',
        quiet_hours_end: '08:00'
    })
})

// Send test
fetch('/api/notifications/test', { method: 'POST' })
```

## 🔍 Debug Checklist

- [ ] FCM_SERVER_KEY in `.env`
- [ ] VAPID key in `push-notifications.js`
- [ ] User is authenticated
- [ ] Browser permission = "granted"
- [ ] HTTPS enabled (or localhost)
- [ ] Service worker registered
- [ ] FCM token saved to database
- [ ] Check Laravel logs for errors
- [ ] Check browser console for JS errors

## 📊 Database Tables

```sql
-- Check if token is registered
SELECT * FROM user_notification_tokens WHERE user_id = 1;

-- Check notification history
SELECT * FROM push_notifications WHERE user_id = 1 ORDER BY created_at DESC;

-- Check user preferences
SELECT * FROM notification_preferences WHERE user_id = 1;

-- Get unread count
SELECT COUNT(*) FROM push_notifications WHERE user_id = 1 AND read_at IS NULL;
```

## 🚀 Testing Steps

1. Login to admin panel
2. Go to `/admin/notification-settings`
3. Click "Enable Push Notifications"
4. Allow in browser prompt
5. Click "Send Test Notification"
6. Check notification appears
7. Click bell icon in header
8. Verify notification shows in dropdown

## 💡 Pro Tips

- Notifications respect user preferences (quiet hours, enabled types)
- Service worker handles background notifications automatically
- Tokens auto-refresh when expired
- Multi-device support (one user, many tokens)
- Click notification to navigate to relevant page
- Badge shows unread count
- Auto-refresh every 30 seconds

## 🎭 Customization Examples

### Add badge to notification
```php
$data = [
    'badge' => '/images/icon-badge.png',
    'icon' => '/images/custom-icon.png'
];
```

### Require interaction (notification stays visible)
```php
$data = [
    'requireInteraction' => 'true'
];
```

### Add action buttons
```javascript
// In firebase-messaging-sw.js
registration.showNotification(title, {
    body: body,
    actions: [
        { action: 'view', title: '👀 View' },
        { action: 'dismiss', title: '✖️ Dismiss' }
    ]
});
```

### Custom sound
```php
$data = [
    'sound' => '/sounds/notification.mp3'
];
```

## ⚡ Performance Tips

- Use `isBackground: true` for silent notifications
- Batch notifications for multiple events
- Use quiet hours for overnight silence
- Set appropriate `requireInteraction` only for critical alerts
- Use notification grouping for similar events

## 📱 Browser Compatibility

| Browser | Version | Status |
|---------|---------|--------|
| Chrome | 50+ | ✅ Full support |
| Firefox | 44+ | ✅ Full support |
| Edge | 17+ | ✅ Full support |
| Safari | 16+ | ✅ Full support |
| Opera | 37+ | ✅ Full support |
| IE | Any | ❌ Not supported |

## 🔐 Security Notes

- CSRF token required for POST requests
- Auth middleware on all API routes
- Token hashing for unique identification
- Automatic token deactivation on errors
- No sensitive data in notification payload
