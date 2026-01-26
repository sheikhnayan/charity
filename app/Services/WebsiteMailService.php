<?php

namespace App\Services;

use App\Models\Website;
use Illuminate\Support\Facades\Config;

class WebsiteMailService
{
    /**
     * Apply website-specific mail configuration to runtime
     */
    public static function applyForWebsite(Website $website): void
    {
        $settings = $website->emailSettings;
        if (!$settings || !$settings->is_active) {
            // Fallback from global .env configuration; still ensure from fields
            Config::set('mail.from.address', Config::get('mail.from.address', 'noreply@' . $website->domain));
            Config::set('mail.from.name', Config::get('mail.from.name', $website->name));
            return;
        }

        $mail = $settings->getMailConfig();

        // Base mailer (we stick to smtp)
        Config::set('mail.default', $mail['mailer'] ?: 'smtp');

        // Apply SMTP credentials
        if (!empty($mail['host'])) Config::set('mail.mailers.smtp.host', $mail['host']);
        if (!empty($mail['port'])) Config::set('mail.mailers.smtp.port', $mail['port']);
        Config::set('mail.mailers.smtp.encryption', $mail['encryption']);
        Config::set('mail.mailers.smtp.username', $mail['username']);
        Config::set('mail.mailers.smtp.password', $mail['password']);

        // From address/name
        if (!empty($mail['from']['address'])) {
            Config::set('mail.from.address', $mail['from']['address']);
        } else {
            Config::set('mail.from.address', 'noreply@' . $website->domain);
        }
        Config::set('mail.from.name', !empty($mail['from']['name']) ? $mail['from']['name'] : $website->name);

        // Some mail drivers respect replyTo via message object; store in config for retrieval
        if (!empty($mail['reply_to']['address'])) {
            Config::set('mail.reply_to.address', $mail['reply_to']['address']);
            Config::set('mail.reply_to.name', $mail['reply_to']['name'] ?? null);
        } else {
            Config::set('mail.reply_to.address', null);
            Config::set('mail.reply_to.name', null);
        }
    }
}
