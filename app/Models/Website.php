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
        'investment_tiers'
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
}
