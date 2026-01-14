<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
<<<<<<< HEAD
    protected $fillable = [
        'transaction_id', 'website_id', 'amount', 'type', 'name', 'last_name', 'email',
        'address', 'apartment', 'city', 'state', 'zip', 'phone', 'country', 'ip_address',
        'fee', 'fee_paid', 'status', 'reference_id', 'name_on_card', 'tip_amount', 'tip_percentage'
    ];
    
=======
>>>>>>> ea49ecefbe36c0f92d498ac7d03714246f091736
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
<<<<<<< HEAD

=======
>>>>>>> ea49ecefbe36c0f92d498ac7d03714246f091736
