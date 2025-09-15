<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DealmakerConfig;

class DealmakerAdminController extends Controller
{
    public function index()
    {
        $setting = DealmakerConfig::getInstance();
        
        return view('admin.dealmaker-settings', compact('setting'));
    }

    public function update(Request $request)
    {
        \Log::info('DealMaker Admin Update - Request Data: ' . json_encode($request->all()));
        
        // Simplified validation for fields that are actually in the form
        $validated = $request->validate([
            // SEO Meta
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|url',
            
            // Hero Section
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'hero_cta_text' => 'nullable|string|max:100',
            'hero_cta_url' => 'nullable|string|max:255',
            'hero_background_video' => 'nullable|url',
            'hero_background_image' => 'nullable|url',
            
            // Site Branding
            'site_logo' => 'nullable|url',
            'site_tagline' => 'nullable|string|max:255',
            
            // Statistics
            'stat_1_number' => 'nullable|string|max:20',
            'stat_1_text' => 'nullable|string|max:100',
            'stat_2_number' => 'nullable|string|max:20',
            'stat_2_text' => 'nullable|string|max:100',
            'stat_3_number' => 'nullable|string|max:20',
            'stat_3_text' => 'nullable|string|max:100',
            
            // Announcement
            'announcement_text' => 'nullable|string|max:255',
            'announcement_badge' => 'nullable|string|max:100',
            'announcement_url' => 'nullable|string|max:255',
            
            // Custom Code
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
            'custom_head_code' => 'nullable|string',
            
            // Section Toggles (only ones in form)
            'show_hero' => 'boolean',
            'show_stats' => 'boolean',
            'show_about' => 'boolean',
            'show_services' => 'boolean',
            'show_testimonials' => 'boolean',
            'show_contact' => 'boolean'
        ]);

        \Log::info('DealMaker Admin Update - Validated Data: ' . json_encode($validated));

        // Handle section visibility checkboxes
        $validated['show_hero'] = $request->has('show_hero');
        $validated['show_stats'] = $request->has('show_stats');
        $validated['show_about'] = $request->has('show_about');
        $validated['show_services'] = $request->has('show_services');
        $validated['show_testimonials'] = $request->has('show_testimonials');
        $validated['show_contact'] = $request->has('show_contact');

        \Log::info('DealMaker Admin Update - Final Data: ' . json_encode($validated));

        try {
            $setting = DealmakerConfig::getInstance();
            \Log::info('DealMaker Admin Update - Current Setting ID: ' . $setting->id);
            
            $result = $setting->update($validated);
            \Log::info('DealMaker settings update result: ' . ($result ? 'success' : 'failed'));
            
            if ($result) {
                \Log::info('DealMaker settings updated successfully!');
                return redirect()->back()->with('success', 'DealMaker homepage settings updated successfully!');
            } else {
                \Log::error('DealMaker settings update returned false');
                return redirect()->back()->with('error', 'Failed to update settings. Please try again.');
            }
        } catch (\Exception $e) {
            \Log::error('DealMaker settings update error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    public function addLogo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|url',
            'url' => 'nullable|url'
        ]);

        $setting = DealmakerConfig::getInstance();
        
        $logos = $setting->client_logos ?? [];
        $logos[] = $validated;

        $setting->update(['client_logos' => $logos]);

        return response()->json(['success' => true, 'message' => 'Logo added successfully!']);
    }

    public function removeLogo(Request $request, $index)
    {
        $setting = DealmakerConfig::getInstance();
        
        $logos = $setting->client_logos ?? [];
        
        if (isset($logos[$index])) {
            unset($logos[$index]);
            $logos = array_values($logos); // Re-index array
            
            $setting->update(['client_logos' => $logos]);
            
            return response()->json(['success' => true, 'message' => 'Logo removed successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Logo not found!']);
    }
}