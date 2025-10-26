<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\TracksAnalytics;

class Donation extends Model
{
    use TracksAnalytics;

    protected static function booted()
    {
        static::created(function ($donation) {
            $donation->trackConversion(
                $donation->amount,
                'donation',
                [
                    'payment_method' => $donation->payment_method,
                    'status' => $donation->status,
                ]
            );
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
