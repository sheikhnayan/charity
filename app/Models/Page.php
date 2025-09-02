<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

    protected $fillable = [
        'user_id',
        'website_id',
        'name',
        'state',
        'status',
        'position',
        'meta_title',
        'meta_description',
        'background_color',
        'default',
    ];

    protected $casts = [
        'state' => 'array',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
