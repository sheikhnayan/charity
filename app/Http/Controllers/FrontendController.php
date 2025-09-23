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
use Mail;

class FrontendController extends Controller
{

    public function index()
    {

        $url = url()->current();
        if( $url == 'http://ifundup.com' || $url == 'ifundup.com' || $url == 'https://ifundup.com' || $url == 'http://127.0.0.1:8000') {
            return redirect()->route('admin.index', 1);
        }
        $doamin = parse_url($url, PHP_URL_HOST);
        // dd($doamin);
        $check = Website::where('domain', $doamin)->first();
        $user_id = $check->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = footer::where('user_id', $user_id)->first();
        
        // Consolidated template - use page-investment.blade.php for both website types
        $data = Page::where('user_id', $user_id)->where('default', 1)->first();
        $menuSections = $this->extractMenuSections($data);
        
        if($setting->site_status == 1){
            return view('page-investment', compact('setting', 'header', 'data', 'check','footer', 'menuSections'));
        }else{
            $data = null;
            $menuSections = [];
            return view('page-investment', compact('setting', 'header', 'data', 'check','footer', 'menuSections'));
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
        
        return view('invest', compact('setting', 'header', 'footer', 'website', 'amount'));
    }

    public function saveInvestmentInfo(Request $request)
    {
        try {
            $request->validate([
                'investor_name' => 'required|string|max:255',
                'investor_email' => 'required|email|max:255',
                'investor_phone' => 'nullable|string|max:20',
                'investment_amount' => 'required|numeric|min:1',
                'investor_type' => 'required|string|in:individual,joint,corporation,trust,ira',
            ]);

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
                'investor_data' => array_merge(
                    $request->only(['address', 'city', 'state', 'zip', 'country', 'accredited_investor']),
                    $allFormData // Include all collected form data
                )
            ]);

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

            $a = (int) $ticket->price * (int) $value['quantity'];

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
            $sell->amount = (int) $value['quantity'] * (int) $ticket->price;
            $sell->save();
            }

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


        return redirect('/authorize/payment/donation/'.$add->id)->with('success', 'Donation Pending');
    }

    public function page($id)
    {
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        
        // Check if this is a main site URL
        $mainSiteUrls = [
            'ifundup.com',
            'www.ifundup.com',
            '127.0.0.1'
        ];

        
        if (in_array($domain, $mainSiteUrls)) {
            // dd($domain);
            // Main site page logic - look for pages with is_main_site = true
            $data = Page::mainSite()->where('name', str_replace('-', ' ', $id))->first();
            
            if (!$data) {
                abort(404);
            }
            
            // For main site pages, we don't need website-specific settings
            $setting = null;
            $header = null;
            $footer = null;
            $check = null;
            $menuSections = $this->extractMenuSections($data);
            
            return view('page-investment', compact('setting', 'header', 'data', 'check', 'footer', 'menuSections'));
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
        
        // For fundraiser websites, continue with existing multi-page behavior
        $data = Page::where('website_id', $check->id)->where('name', str_replace('-', ' ', $id))->first();
        $user_id = $check->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = footer::where('user_id', $user_id)->first();

        // Use consolidated template
        $menuSections = $this->extractMenuSections($data);
        return view('page-investment', compact('setting', 'header', 'data', 'check','footer', 'menuSections'));
    }

    public function dealmakerDemo()
    {
        // Use the separate DealMaker configuration system
        $setting = DealmakerConfig::getInstance();
        
        // Add client logos and slider images (these can be added to the model later if needed)
        // $setting->client_logos = [
        //     [
        //         'name' => 'EnergyX',
        //         'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685d899d1d298659f84ec99d_EnergyX_NewLogo_HighRez-BLACKBG-04-3.png',
        //         'url' => '#'
        //     ],
        //     [
        //         'name' => 'Pacaso',
        //         'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685d899d9e91f4cd7b6d2ace_pacaso.png',
        //         'url' => '#'
        //     ],
        //     [
        //         'name' => 'Monument',
        //         'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855710e2dd8d0cba5f41de2_mon.png',
        //         'url' => '#'
        //     ],
        //     [
        //         'name' => 'Company',
        //         'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685dc0fe725aac507ac3c76f_5f8ef32e6dd1b4ac67afa1e9_Footer-logo.png',
        //         'url' => '#'
        //     ],
        //     [
        //         'name' => 'Death & Co',
        //         'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685d899d6f9e20e71e81f421_death%20and%20co%20(1).png',
        //         'url' => '#'
        //     ]
        // ];

        // $setting->slider_images = [
        //     [
        //         'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561045749461ab86204c2_homepage_phone-02.webp',
        //         'title' => 'Funding Ambition. Powering Growth.',
        //         'description' => 'DealMaker is the future of capital raising. We provide an end-to-end platform to raise capital directly from individual investors.',
        //         'cta_text' => 'Start Now',
        //         'cta_url' => '/connect'
        //     ],
        //     [
        //         'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855610466fede381344c563_homepage_phone-03.webp',
        //         'title' => 'Raise Boldly. Own Your Future.',
        //         'description' => 'Unlock the power of retail capital. Raise the capital you need to drive growth while building your brand and community.',
        //         'cta_text' => 'Start Now',
        //         'cta_url' => '/connect'
        //     ],
        //     [
        //         'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855610465b5ca9a46afe153_homepage_phone-04.webp',
        //         'title' => 'Real Capital. Retail Experience.',
        //         'description' => 'Raise up to $75M annually with Reg A offerings. The capital you need - no road shows, no trips to Sand Hill Road.',
        //         'cta_text' => 'Start Now',
        //         'cta_url' => '/connect'
        //     ]
        // ];
        
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
