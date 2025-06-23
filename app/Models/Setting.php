<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public function website()
    {
        return $this->belongsTo(Website::class, 'user_id', 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function header()
    {
        return $this->hasOne(Header::class, 'user_id', 'user_id');
    }
}
