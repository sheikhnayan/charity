<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

    protected $fillable = [
        'user_id',
        'website_id',
        'template_id',
        'is_template',
        'template_name',
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
        'is_template' => 'boolean',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function template()
    {
        return $this->belongsTo(PageTemplate::class, 'template_id');
    }
    
    /**
     * Save current page as template
     */
    public function saveAsTemplate($templateData)
    {
        return PageTemplate::create([
            'name' => $templateData['name'],
            'description' => $templateData['description'] ?? '',
            'state' => $this->state,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'background_color' => $this->background_color,
            'category' => $templateData['category'] ?? 'general',
            'created_by' => $this->user_id,
            'is_public' => $templateData['is_public'] ?? true,
        ]);
    }
    
    /**
     * Apply template to current page
     */
    public function applyTemplate(PageTemplate $template)
    {
        $this->update([
            'state' => $template->state,
            'meta_title' => $template->meta_title,
            'meta_description' => $template->meta_description,
            'background_color' => $template->background_color,
            'template_id' => $template->id,
        ]);
        
        $template->incrementUsage();
        
        return $this;
    }
}
