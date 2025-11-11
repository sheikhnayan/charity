<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $fillable = [
        'user_id',
        'website_id',
        'background',
        'background_type',
        'status',
        'color',
        'menu',
        'message',
        'copy_right',
        'social',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'youtube',
        'pinterest',
        'tiktok',
        'blue_sky',
        'disclaimer_text',
        'description_text',
        'background_image_desktop',
        'background_image_mobile'
    ];
}
