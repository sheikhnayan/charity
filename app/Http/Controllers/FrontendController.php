<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Page;
use App\Models\Donation;
use App\Models\Website;
use App\Models\Header;
use App\Models\Footer;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\TicektSell;
use App\Models\TicketSellDetail;
use App\Models\Investment;
use App\Models\DealmakerConfig;
use App\Services\PaymentFunnelService;
use Mail;

class FrontendController extends Controller
{
    public function productDetails($id)
    {
        $ticket = Ticket::with('website')->findOrFail($id);
        
        // Get website header/footer/settings
        $website = $ticket->website;
        $user_id = $website->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = Footer::where('user_id', $user_id)->first();
        
        // Calculate actual available shares from sales for property type
        if ($ticket->type === 'property') {
            // Get total shares sold from ticket_sell_details
            $totalSold = TicketSellDetail::where('ticket_id', $ticket->id)
                ->whereHas('ticketSell', function($query) {
                    $query->where('status', 'success'); // Only count successful sales
                })
                ->sum('quantity');
            
            // Update the ticket object with calculated values
            $ticket->available_shares = $ticket->total_shares - $totalSold;
            
            return view('property-details', compact('ticket', 'setting', 'header', 'footer', 'website'));
        }
        
        return view('product-details', compact('ticket', 'setting', 'header', 'footer', 'website'));
    }

    public function index()
    {

        $url = url()->current();
        if( $url == 'http://fundconnects.com' || $url == 'fundconnects.com' || $url == 'https://fundconnects.com' || $url == 'http://127.0.0.1:8000') {
            // return redirect()->route('admin.index', 1);
           return $this->dealmakerDemo();
        }
        $doamin = parse_url($url, PHP_URL_HOST);
        // dd($doamin);
        $check = Website::where('domain', $doamin)->first();
        $user_id = $check->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = footer::where('user_id', $user_id)->first();
        
        // Get active custom fonts
        $customFonts = \App\Models\CustomFont::active()->get();
        
        // Consolidated template - use page-investment.blade.php for both website types
        $data = Page::where('user_id', $user_id)->where('default', 1)->first();
        $menuSections = $this->extractMenuSections($data);
        
        if($setting->site_status == 1){
            return view('page-investment', compact('setting', 'header', 'data', 'check','footer', 'menuSections', 'customFonts'));
        }else{
            $data = null;
            $menuSections = [];
            return view('page-investment', compact('setting', 'header', 'data', 'check','footer', 'menuSections', 'customFonts'));
        }
    }

    public function donate()
    {
        $data = User::limit(10)->get();


        return view('donate', compact('data'));
    }

    public function invest(Request $request)
    {
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->first();
        
        if (!$website) {
            abort(404);
        }
        
        $user_id = $website->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = Footer::where('user_id', $user_id)->first();
        
        // Get amount from URL parameter if provided
        $amount = $request->get('amount');
        
        // Clean and decode the amount if provided
        if ($amount) {
            // URL decode the amount
            $amount = urldecode($amount);
            // Remove currency symbols and convert to numeric value
            $amount = preg_replace('/[^0-9.,]/', '', $amount);
            // Remove commas
            $amount = str_replace(',', '', $amount);
            // Convert to float and back to ensure it's a clean number
            $amount = floatval($amount);
        }

        // dd($url);

        if($url == 'https://ladyoriginaltee.com/invest') {
            return view('dummy-login', compact('setting', 'header', 'footer', 'website', 'amount'));
        }
        
        return view('invest', compact('setting', 'header', 'footer', 'website', 'amount'));
    }

    public function saveInvestmentInfo(Request $request)
    {
        try {

            $url = url()->previous();
            $domain = parse_url($url, PHP_URL_HOST);
            $website = Website::where('domain', $domain)->first();

            if (!$website) {
                return redirect()->back()->with('error', 'Website not found');
            }

            $setting = \App\Models\Setting::where('user_id', $website->user_id)->first();
            $sharePrice = $setting && $setting->share_price ? $setting->share_price : 1.00;
            $shareQuantity = floor($request->investment_amount / $sharePrice);

            // Store all form data collected from the page
            $allFormData = $request->input('form_data', []);
            
            $investment = Investment::create([
                'website_id' => $website->id,
                'investor_name' => $request->investor_name,
                'investor_email' => $request->investor_email,
                'investor_phone' => $request->investor_phone,
                'investment_amount' => $request->investment_amount,
                'investor_type' => $request->investor_type,
                'share_quantity' => $shareQuantity,
                'deal_id' => $setting && $setting->deal_id ? $setting->deal_id : null,
                'status' => 'pending', // Set to pending for payment processing
                'investor_data' => array_merge([
                    'address' => $request->input('address'),
                    'city' => $request->input('city'),
                    'state' => $request->input('state'),
                    'zip' => $request->input('postalCode') ?: $request->input('zip'), // Handle both field names
                    'country' => $request->input('country'),
                    'accredited_investor' => $request->input('accredited_investor'),
                    'incorporation_state' => $request->input('incorporation_state'),
                    'ein' => $request->input('ein'),
                    'trust_type' => $request->input('trust_type'),
                    'custodian' => $request->input('custodian'),
                    'ira_type' => $request->input('ira_type'),
                    'phone' => $request->input('phone'),
                    // Investor type specific data
                    'individual_name' => $request->input('individual_name'),
                    'date_of_birth' => $request->input('date_of_birth'),
                    // SSN and Tax ID fields
                    'ssn' => $request->input('ssn') ?: $request->input('taxpayer_id'),
                    'primary_ssn' => $request->input('primary_ssn') ?: $request->input('joint.joint_holder_taxpayer_id'),
                    'secondary_ssn' => $request->input('secondary_ssn'),
                    'taxpayer_id' => $request->input('taxpayer_id'),
                    'joint_holder_taxpayer_id' => $request->input('joint.joint_holder_taxpayer_id'),
                    
                    // Date of Birth fields
                    'primary_dob' => $request->input('primary_dob'),
                    'secondary_dob' => $request->input('secondary_dob'),
                    
                    // Name fields
                    'primary_name' => $request->input('primary_name'),
                    'secondary_name' => $request->input('secondary_name'),
                    'corporation_name' => $request->input('corporation_name'),
                    'trust_name' => $request->input('trust_name'),
                    'ira_holder_name' => $request->input('ira_holder_name'),
                ], $allFormData ?: [])
            ]);

            // Debug: Log what was actually saved
            \Log::info('=== INVESTMENT CREATED ===');
            \Log::info('Investment ID:', [$investment->id]);
            \Log::info('Investor Type Saved:', [$investment->investor_type]);
            \Log::info('SSN Fields Captured:', [
                'ssn' => $request->input('ssn'),
                'taxpayer_id' => $request->input('taxpayer_id'), 
                'primary_ssn' => $request->input('primary_ssn'),
                'secondary_ssn' => $request->input('secondary_ssn'),
                'joint_holder_taxpayer_id' => $request->input('joint.joint_holder_taxpayer_id')
            ]);
            \Log::info('All Request Data:', $request->all());
            \Log::info('Investor Data Saved:', $investment->investor_data);
            \Log::info('Full Investment Record:', $investment->toArray());
            \Log::info('=== END INVESTMENT DEBUG ===');

            // Track payment initiation for investment
            try {
                $funnelService = new PaymentFunnelService();
                $funnelService->trackPaymentInitiated(
                    'investment',
                    $request->investment_amount,
                    'authorize_net',
                    [
                        'investor_type' => $investment->investor_type,
                        'share_quantity' => $shareQuantity,
                        'investor_data' => $investment->investor_data
                    ],
                    null // investments don't have user_id
                );
            } catch (\Exception $e) {
                \Log::error('Payment funnel tracking error in investment: ' . $e->getMessage());
            }

            // Redirect to payment page like donation and auction
            return redirect('/authorize/payment/investment/'.$investment->id)->with('success', 'Investment Pending Payment');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    public function processInvestment(Request $request)
    {
        $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string'
        ]);

        $investment = \App\Models\Investment::find($request->investment_id);
        
        $investment->update([
            'status' => 'processing',
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id
        ]);

        // Here you would integrate with your payment processor
        // For now, we'll just mark it as completed
        $investment->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Investment processed successfully',
            'redirect_url' => route('invest.thank-you', ['id' => $investment->id])
        ]);
    }

    public function investmentThankYou(Request $request)
    {
        $investment_id = $request->query('id');
        $investment = \App\Models\Investment::find($investment_id);
        
        if (!$investment) {
            return redirect()->route('invest');
        }

        return view('investment.thank-you', compact('investment'));
    }

    public function investmentStatus($id)
    {
        $investment = \App\Models\Investment::find($id);
        
        if (!$investment) {
            return response()->json(['error' => 'Investment not found'], 404);
        }

        return response()->json([
            'status' => $investment->status,
            'kyc_status' => $investment->kyc_status,
            'aml_status' => $investment->aml_status,
            'amount' => $investment->formatted_amount,
            'shares' => $investment->share_quantity
        ]);
    }

    public function investmentContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000'
        ]);

        // Here you would send an email to the admin
        // For now, we'll just return success
        
        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully'
        ]);
    }

    public function investmentTerms()
    {
        return view('investment.terms');
    }

    public function investmentPrivacy()
    {
        return view('investment.privacy');
    }

    public function volunteer()
    {
        return view('volunteer');
    }
    public function photo()
    {
        return view('photo');
    }
    public function about()
    {
        return view('about');
    }
    public function contact()
    {
        return view('contact');
    }

    public function contact_form(Request $request)
    {
        $emails = $request->input('notification_emails', []);
        if (empty($emails)) {
            $emails = ['sheikhnayan1997@gmail.com']; // fallback email
        }

        $subject = 'New Contact Form Submission';
        $html = '
            <h2>Contact Form Submission</h2>
            <p><strong>Name:</strong> ' . e($request->name) . '</p>
            <p><strong>Email:</strong> ' . e($request->email) . '</p>
            <p><strong>Message:</strong><br>' . nl2br(e($request->message)) . '</p>
        ';

        foreach ($emails as $to) {
            \Mail::send([], [], function ($message) use ($to, $subject, $html) {
                $message->to($to)
                    ->subject($subject)
                    ->html($html); // <-- use html() instead of setBody()
            });
        }

        return back()->with('success', 'Your message has been sent!');
    }

    public function custom_form(Request $request)
    {
        $emails = $request->input('notification_emails', []);
        if (empty($emails)) {
            $emails = ['sheikhnayan1997@gmail.com']; // fallback email
        }

        $subject = 'New Contact Form Submission';
        $html = '
            <h2>Contact Form Submission</h2>
            <p><strong>Name:</strong> ' . e($request->name) . '</p>
            <p><strong>Email:</strong> ' . e($request->email) . '</p>
            <p><strong>Message:</strong><br>' . nl2br(e($request->message)) . '</p>
        ';

        foreach ($emails as $to) {
            \Mail::send([], [], function ($message) use ($to, $subject, $html) {
                $message->to($to)
                    ->subject($subject)
                    ->html($html); // <-- use html() instead of setBody()
            });
        }

        return back()->with('success', 'Your message has been sent!');
    }

    public function leaderBoard()
    {
        return view('leader-board');
    }

    public function student($slug)
    {

        $array = explode('-', $slug);

        $id = $array[0];

        $url = url()->current();
        if( $url == 'fundably.org' || $url == 'https://fundably.org' || $url == 'http://fundably.org' || $url == 'http://127.0.0.1:8000') {
            return redirect()->route('admin.index', 1);
        }
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();

        // dd($id);

        $data = User::where('id', $id)->first();

        $donations = Donation::where('user_id', $id)->get();

        return view('student', compact('data', 'donations','check'));
    }

    public function donation(Request $request)
    {
        $request->validate([
            'donation_amount' => 'required|numeric',
            // 'user_id' => 'required|exists:users,id',
        ]);

        $url = url()->current();
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();

        $add = new Donation;
        $add->user_id = $request->user_id;
        $add->amount = $request->donation_amount;
        $add->comment = $request->leave_comment;
        $add->first_name = $request->first_name;
        $add->last_name = $request->last_name;
        $add->email = $request->email;
        $add->website_id = $check->id;
        $add->type = 'student';

        if(isset($request->anonymous_donation)) {
            $add->hide = 1;
        } else {
            $add->hide = 0;

        }

        $add->status = 0;
        $add->save();

        // Track payment initiation for student donation
        try {
            $funnelService = new PaymentFunnelService();
            $funnelService->trackPaymentInitiated(
                'student',
                $request->donation_amount,
                'authorize_net',
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'comment' => $request->leave_comment,
                    'anonymous' => isset($request->anonymous_donation)
                ],
                $request->user_id
            );
        } catch (\Exception $e) {
            \Log::error('Payment funnel tracking error in student donation: ' . $e->getMessage());
        }

        return redirect('/authorize/payment/donation/'.$add->id)->with('success', 'Donation Pending');
    }

    public function tickets(Request $request){
        $url = url()->current();
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();

        $amount = 0;

        $quantity = 0;

        foreach ($request->ticket as $key => $value) {
            # code...
            if($value['quantity'] > 0){
                
            $ticket = Ticket::find($value['id']);

            // For property type, use price_per_share instead of price
            if($ticket->type === 'property') {
                $a = (float) $ticket->price_per_share * (int) $value['quantity'];
            } else {
                $a = (int) $ticket->price * (int) $value['quantity'];
            }

            $amount += $a;

            $quantity += (int) $value['quantity'];
            }
        }

        $add = new TicektSell;
        $add->quantity = $quantity;
        $add->amount = $amount;
        $add->status = 0;
        $add->website_id = $check->id;
        $add->save();

        foreach ($request->ticket as $key => $value) {
            # code...
            $ticket= Ticket::find($value['id']);
            
            if((int) $value['quantity'] > 0){
                
            $sell = new TicketSellDetail;
            $sell->ticket_sell_id = $add->id;
            $sell->ticket_id = $value['id'];
            $sell->quantity = $value['quantity'];
            
            // For property type, use price_per_share instead of price
            if($ticket->type === 'property') {
                $sell->amount = (int) $value['quantity'] * (float) $ticket->price_per_share;
            } else {
                $sell->amount = (int) $value['quantity'] * (int) $ticket->price;
            }
            
            $sell->save();
            }

        }

        // Track payment initiation for ticket purchase
        try {
            $funnelService = new PaymentFunnelService();
            $funnelService->trackPaymentInitiated(
                'ticket',
                $amount,
                'authorize_net',
                [
                    'quantity' => $quantity,
                    'tickets' => array_filter($request->ticket, function($item) {
                        return $item['quantity'] > 0;
                    })
                ],
                null // tickets don't have user_id
            );
        } catch (\Exception $e) {
            \Log::error('Payment funnel tracking error in ticket purchase: ' . $e->getMessage());
        }

        return redirect('/authorize/payment/ticket/'.$add->id)->with('success', 'Donation Pending');
    }

    public function donation_general(Request $request)
    {
        // dd($request->all());


        $url = url()->current();
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();

        $add = new Donation;
        $add->user_id = $check->user_id;
        $add->amount = $request->donation_amount;
        $add->website_id = $check->id;
        $add->comment = $request->leave_comment;
        $add->first_name = $request->first_name;
        $add->last_name = $request->last_name;
        $add->email = $request->email;
        $add->type = 'general';

        if(isset($request->anonymous_donation)) {
            $add->hide = 1;
        } else {
            $add->hide = 0;

        }

        $add->status = 0;
        $add->save();

        // Track payment initiation for general donation
        try {
            $funnelService = new PaymentFunnelService();
            $funnelService->trackPaymentInitiated(
                'general',
                $request->donation_amount,
                'authorize_net',
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'comment' => $request->leave_comment,
                    'anonymous' => isset($request->anonymous_donation)
                ],
                null // general donations don't have user_id
            );
        } catch (\Exception $e) {
            \Log::error('Payment funnel tracking error in general donation: ' . $e->getMessage());
        }

        return redirect('/authorize/payment/donation/'.$add->id)->with('success', 'Donation Pending');
    }

    public function page($id)
    {
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);

        // dd($domain);
        
        // Check if this is a main site URL
        $mainSiteUrls = [
            'fundconnects.com',
            'www.fundconnects.com',
            // '127.0.0.1'
        ];

        
        if (in_array($domain, $mainSiteUrls)) {
            // dd($domain);
            // Main site page logic - look for pages with is_main_site = true
            $data = Page::mainSite()->where('name', str_replace('-', ' ', $id))->first();
            
            if (!$data) {
                abort(404);
            }
            
            // Get active custom fonts
            $customFonts = \App\Models\CustomFont::active()->get();
            
            // For main site pages, we don't need website-specific settings
            $setting = null;
            $header = null;
            $footer = null;
            $check = null;
            $menuSections = $this->extractMenuSections($data);
            
            return view('page-investment', compact('setting', 'header', 'data', 'check', 'footer', 'menuSections', 'customFonts'));
        }
        
        // Existing website-specific page logic
        $check = Website::where('domain', $domain)->first();
        
        if (!$check) {
            abort(404);
        }
        
        // Check if this is an investment website
        if ($check->type == 'investment') {
            // For investment websites, redirect to homepage since everything is on one page
            return redirect('/');
        }
        
        // Get active custom fonts
        $customFonts = \App\Models\CustomFont::active()->get();
        
        // For fundraiser websites, continue with existing multi-page behavior
        $data = Page::where('website_id', $check->id)->where('name', str_replace('-', ' ', $id))->first();
        $user_id = $check->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = footer::where('user_id', $user_id)->first();

        // Use consolidated template
        $menuSections = $this->extractMenuSections($data);
        return view('page-investment', compact('setting', 'header', 'data', 'check','footer', 'menuSections', 'customFonts'));
    }

    public function dealmakerDemo()
    {
        // Use the separate DealMaker configuration system
        $setting = DealmakerConfig::getInstance();
        
        return view('dealmaker-demo', compact('setting'))->with('config', $setting);
    }

    /**
     * Extract menu sections from page state for investment websites
     */
    private function extractMenuSections($page)
    {
        if (!$page || !$page->state) {
            return [];
        }

        $state = is_string($page->state) ? json_decode($page->state, true) : $page->state;
        $menuSections = [];

        if (!is_array($state)) {
            return [];
        }

        // Check if state has components array (new format) or is direct array (old format)
        $components = isset($state['components']) ? $state['components'] : $state;

        foreach ($components as $component) {
            // Check if this is an inner-section with menu enabled
            if (isset($component['type']) && $component['type'] === 'inner-section') {
                $innerSectionData = $component['innerSectionData'] ?? [];
                
                if (isset($innerSectionData['addToMenu']) && $innerSectionData['addToMenu'] && 
                    isset($innerSectionData['menuTitle']) && !empty($innerSectionData['menuTitle'])) {
                    
                    $menuSections[] = [
                        'title' => $innerSectionData['menuTitle'],
                        'sectionId' => $innerSectionData['sectionId'] ?? strtolower(str_replace(' ', '-', $innerSectionData['menuTitle']))
                    ];
                }
            }
        }

        return $menuSections;
    }

    public function newsletterSubscribe(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:20',
                'country_code' => 'nullable|string|max:5',
                'website_id' => 'required|exists:websites,id'
            ]);

            // Check if email already exists for this website
            $existingSubscription = \App\Models\NewsletterSubscription::where('email', $request->email)
                ->where('website_id', $request->website_id)
                ->first();

            if ($existingSubscription) {
                if ($existingSubscription->status === 'active') {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are already subscribed to our newsletter!'
                    ]);
                } else {
                    // Reactivate subscription and update with new data
                    $existingSubscription->update([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'phone' => $request->phone,
                        'country_code' => $request->country_code ?? '+1',
                        'status' => 'active',
                        'subscribed_at' => now()
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Welcome back! Your subscription has been reactivated.'
                    ]);
                }
            }

            // Create new subscription
            \App\Models\NewsletterSubscription::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country_code' => $request->country_code ?? '+1',
                'website_id' => $request->website_id,
                'status' => 'active',
                'subscribed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for subscribing to our newsletter!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid email address.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}
