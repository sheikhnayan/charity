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
        'is_main_site',
    ];

    protected $casts = [
        'state' => 'array',
        'is_template' => 'boolean',
        'is_main_site' => 'boolean',
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
     * Scope for main site pages
     */
    public function scopeMainSite($query)
    {
        return $query->where('is_main_site', true);
    }
    
    /**
     * Scope for website-specific pages
     */
    public function scopeWebsitePages($query)
    {
        return $query->where('is_main_site', false);
    }
    
    /**
     * Scope for pages belonging to a specific website
     */
    public function scopeForWebsite($query, $websiteId)
    {
        return $query->where('website_id', $websiteId)->where('is_main_site', false);
    }
    
    /**
     * Check if this is a main site page
     */
    public function isMainSitePage()
    {
        return $this->is_main_site;
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
