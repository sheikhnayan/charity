<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
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
