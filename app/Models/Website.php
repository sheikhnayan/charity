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
}
