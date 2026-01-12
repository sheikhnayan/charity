# Multiple Contact Emails - Quick Reference

## What Was Added?

A new feature that allows website admins to configure multiple email addresses that will receive contact form submissions.

## How to Use

### As a Website Administrator:

1. Go to: **Settings → Website → Edit**
2. Find the section: **"Contact Form Notification Emails"**
3. Enter email addresses (minimum 1 required)
4. Click **"Add Another Email"** for additional recipients
5. Click the **trash icon** to remove an email
6. Click **Submit** to save

### Result:

When someone submits the contact form:
- ✅ Email goes to all configured contact emails
- ✅ Email goes to the website owner (admin email)
- ✅ All recipients get the same notification

## Key Features

- ✨ **Easy Management**: Add/remove emails with simple UI buttons
- 🔒 **Email Validation**: Both frontend and backend validation
- 🛡️ **Duplicate Prevention**: Same email won't be sent twice
- 📧 **Admin Always Notified**: Owner email automatically included
- ↩️ **Backward Compatible**: Works with existing websites

## Database

New column added to `websites` table:
- `contact_emails` (JSON) - Array of contact notification emails

## Example Contact Emails JSON:
```json
[
    "sales@company.com",
    "support@company.com",
    "info@company.com"
]
```

## Email Recipients Priority

When a contact form is submitted, emails are sent to:
1. All emails in `contact_emails` array
2. Website owner's email (admin)
3. Duplicates are automatically removed

## Run Migration

```bash
php artisan migrate
```

## Files Changed

- `database/migrations/2026_01_12_add_contact_emails_to_websites.php` ✨ NEW
- `app/Models/Website.php` - Added fillable + casts
- `resources/views/admin/website/edit.blade.php` - Added UI
- `resources/views/admin/website/create.blade.php` - Added UI
- `app/Http/Controllers/WebsiteController.php` - Added validation & handling
- `app/Http/Controllers/FrontendController.php` - Updated email sending logic

## Validation Rules

```php
contact_emails: nullable|array
contact_emails.*: email
```

## Support

For issues or questions about the contact emails feature, refer to:
- `MULTIPLE_CONTACT_EMAILS_IMPLEMENTATION.md` (detailed documentation)
- Check database migration file for schema details
