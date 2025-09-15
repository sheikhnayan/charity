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
        
        // Get all request data without validation
        $data = $request->all();
        
        // Handle section visibility checkboxes
        $data['show_hero'] = $request->has('show_hero');
        $data['show_stats'] = $request->has('show_stats');
        $data['show_about'] = $request->has('show_about');
        $data['show_services'] = $request->has('show_services');
        $data['show_testimonials'] = $request->has('show_testimonials');
        $data['show_contact'] = $request->has('show_contact');

        \Log::info('DealMaker Admin Update - Final Data: ' . json_encode($data));

        try {
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