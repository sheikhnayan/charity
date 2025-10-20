<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function website()
    {
        return $this->belongsTo(Website::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(TicketImage::class);
    }

    public function features()
    {
        return $this->hasMany(TicketFeature::class);
    }
}
