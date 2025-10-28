<?php

Route::get('/debug-analytics', function() {
    echo "<h1>Analytics Debug</h1>";
    
    // Check latest analytics event
    $latest = App\Models\AnalyticsEvent::latest()->first();
    if ($latest) {
        echo "<h2>Latest Analytics Event:</h2>";
        echo "<pre>";
        print_r($latest->toArray());
        echo "</pre>";
    } else {
        echo "<p>No analytics events found</p>";
    }
    
    // Check if middleware is working by creating a test event
    echo "<h2>Creating Test Event:</h2>";
    try {
        $event = new App\Models\AnalyticsEvent();
        $event->event_type = 'test_debug';
        $event->website_id = 1; // Use any valid website ID
        $event->session_id = 'debug-session';
        $event->url = request()->fullUrl();
        $event->user_agent = request()->userAgent();
        $event->ip_address = request()->ip();
        $event->method = request()->method();
        $event->utm_source = 'debug';
        $event->device_type = 'debug';
        $event->browser = 'debug';
        $event->save();
        
        echo "<p>Test event created successfully with ID: " . $event->id . "</p>";
        echo "<pre>";
        print_r($event->toArray());
        echo "</pre>";
    } catch (Exception $e) {
        echo "<p>Error creating test event: " . $e->getMessage() . "</p>";
    }
});