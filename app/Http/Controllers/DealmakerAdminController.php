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
        // dd($request->all());
        \Log::info('DealMaker Admin Update - Request Data: ' . json_encode($request->all()));
        \Log::info('DealMaker Admin Update - Files: ' . json_encode($request->allFiles()));
        
        // Get all request data without validation
        $data = $request->all();
        
        // Handle all section visibility checkboxes
        $data['show_hero'] = $request->has('show_hero');
        $data['show_stats'] = $request->has('show_stats');
        $data['show_about'] = $request->has('show_about');
        $data['show_services'] = $request->has('show_services');
        $data['show_testimonials'] = $request->has('show_testimonials');
        $data['show_contact'] = $request->has('show_contact');
        $data['show_announcement'] = $request->has('show_announcement');
        $data['show_difference_section'] = $request->has('show_difference_section');
        $data['show_case_studies'] = $request->has('show_case_studies');
        $data['show_capital_raising'] = $request->has('show_capital_raising');
        $data['show_final_cta'] = $request->has('show_final_cta');

        // Handle social media visibility checkboxes
        $data['show_linkedin'] = $request->has('show_linkedin');
        $data['show_twitter'] = $request->has('show_twitter');
        $data['show_facebook'] = $request->has('show_facebook');
        $data['show_instagram'] = $request->has('show_instagram');

        // Handle video URLs
        if ($request->filled('bg_video_url')) {
            $data['bg_video_url'] = $request->input('bg_video_url');
            // $data['hero_background_video'] = $request->input('bg_video_url');
        }

        // dd($data['bg_video_url']);

        if ($request->filled('bg_video_poster_url')) {
            $data['bg_video_poster_url'] = $request->input('bg_video_poster_url');
        }

        // Handle Final CTA background image uploads
        if ($request->hasFile('final_cta_growth_image')) {
            $file = $request->file('final_cta_growth_image');
            $filename = time() . '_final_cta_growth.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/dealmaker'), $filename);
            $data['final_cta_growth_image'] = 'uploads/dealmaker/' . $filename;
        }

        if ($request->hasFile('final_cta_sky_image')) {
            $file = $request->file('final_cta_sky_image');
            $filename = time() . '_final_cta_sky.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/dealmaker'), $filename);
            $data['final_cta_sky_image'] = 'uploads/dealmaker/' . $filename;
        }

        if ($request->hasFile('final_cta_city_image')) {
            $file = $request->file('final_cta_city_image');
            $filename = time() . '_final_cta_city.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/dealmaker'), $filename);
            $data['final_cta_city_image'] = 'uploads/dealmaker/' . $filename;
        }

        // Handle logo upload
        if ($request->hasFile('uploaded_logo')) {
            \Log::info('DealMaker: Processing logo upload');
            $file = $request->file('uploaded_logo');
            $filename = time() . '_logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/dealmaker'), $filename);
            $data['uploaded_logo'] = 'uploads/dealmaker/' . $filename;
            \Log::info('DealMaker: Logo uploaded to: ' . $data['uploaded_logo']);
        } else {
            \Log::info('DealMaker: No logo file uploaded');
        }

        // Handle OG image upload
        if ($request->hasFile('uploaded_og_image')) {
            \Log::info('DealMaker: Processing OG image upload');
            $file = $request->file('uploaded_og_image');
            $filename = time() . '_og_image.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/dealmaker'), $filename);
            $data['uploaded_og_image'] = 'uploads/dealmaker/' . $filename;
            \Log::info('DealMaker: OG image uploaded to: ' . $data['uploaded_og_image']);
        } else {
            \Log::info('DealMaker: No OG image file uploaded');
        }

        // Handle testimonial image uploads
        if ($request->has('testimonials')) {
            $testimonials = $request->input('testimonials', []);
            
            foreach ($testimonials as $index => $testimonial) {
                if ($request->hasFile("testimonial_image_$index")) {
                    $file = $request->file("testimonial_image_$index");
                    $filename = time() . "_testimonial_{$index}." . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/dealmaker'), $filename);
                    $testimonials[$index]['image'] = 'uploads/dealmaker/' . $filename;
                }
            }
            
            $data['testimonials'] = $testimonials;
        }

        \Log::info('DealMaker Admin Update - Final Data: ' . json_encode($data));

        try {
            // Create uploads directory if it doesn't exist
            if (!file_exists(public_path('uploads/dealmaker'))) {
                mkdir(public_path('uploads/dealmaker'), 0755, true);
            }

            $setting = DealmakerConfig::getInstance();
            \Log::info('DealMaker Admin Update - Current Setting ID: ' . $setting->id);
            
            $result = $setting->update($data);
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
        $data = $request->all();

        $setting = DealmakerConfig::getInstance();
        
        $logos = $setting->client_logos ?? [];
        $logos[] = $data;

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