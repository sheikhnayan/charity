<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'user_id',
        'type',
        'status',
        'share_price',
        'min_investment',
        'investment_tiers',
        'investment_disclaimer',
        'sticky_footer_button_bg',
        'sticky_footer_button_text',
        'sticky_footer_text_color',
        'sticky_footer_bg_color'
    ];

    /**
     * Check if website is a fundraiser type
     */
    public function isFundraiser()
    {
        return $this->type === 'fundraiser';
    }

    /**
     * Check if website is an investment type
     */
    public function isInvestment()
    {
        return $this->type === 'investment';
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function setting()
    {
        return $this->belongsTo(Setting::class, 'user_id', 'user_id');
    }
    
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function header()
    {
        return $this->hasOne(Header::class, 'website_id', 'id');
    }

    public function footer()
    {
        return $this->hasOne(Footer::class, 'website_id', 'id');
    }

    /**
     * Get the newsletter subscriptions for this website
     */
    public function newsletterSubscriptions()
    {
        return $this->hasMany(NewsletterSubscription::class);
    }

    /**
     * Get only active newsletter subscriptions
     */
    public function activeNewsletterSubscriptions()
    {
        return $this->hasMany(NewsletterSubscription::class)->where('status', 'active');
    }

    /**
     * Get the payment settings for this website
     */
    public function paymentSettings()
    {
        return $this->hasOne(WebsitePaymentSetting::class);
    }

    /**
     * Get the active payment configuration for this website
     * Falls back to settings table if no website-specific payment settings exist
     */
    public function getPaymentConfig()
    {
        $websitePaymentSettings = $this->paymentSettings;
        
        if ($websitePaymentSettings && $websitePaymentSettings->is_active) {
            return $websitePaymentSettings->getPaymentConfig();
        }
        
        // Fallback to settings table (existing behavior)
        $setting = $this->setting;
        if ($setting) {
            if ($setting->payment_method === 'stripe') {
                return [
                    'publishable_key' => $setting->stripe_publishable_key,
                    'secret_key' => $setting->stripe_secret_key,
                ];
            } else {
                return [
                    'login_id' => $setting->authorize_login_id,
                    'transaction_key' => $setting->authorize_transaction_key,
                    'sandbox' => true, // Default to sandbox for fallback
                ];
            }
        }
        
        return [];
    }

    /**
     * Get the payment method for this website
     */
    public function getPaymentMethod()
    {
        $websitePaymentSettings = $this->paymentSettings;
        
        if ($websitePaymentSettings && $websitePaymentSettings->is_active) {
            return $websitePaymentSettings->payment_method;
        }
        
        // Fallback to settings table
        $setting = $this->setting;
        return $setting ? $setting->payment_method : 'authorize';
    }
}
