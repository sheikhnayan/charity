<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function images()
    {
        return $this->hasMany(AuctionImage::class, 'auction_id');
    }
}
