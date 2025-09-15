<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class,'reference_id','id');
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class,'reference_id','id');
    }

    public function ticket()
    {
        return $this->belongsTo(TicektSell::class,'reference_id','id');
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class,'reference_id','id');
    }
}
