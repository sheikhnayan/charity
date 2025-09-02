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
        
        if ($check->type == 'investment') {
            // For investment websites, just show the homepage with menu sections
            $data = Page::where('user_id', $user_id)->where('default', 1)->first();
            $menuSections = $this->extractMenuSections($data);
            
            if($setting->site_status == 1){
                return view('page-investment', compact('setting', 'header', 'data', 'check','footer', 'menuSections'));
            }else{
                $data = null;
                $menuSections = [];
                return view('page-investment', compact('setting', 'header', 'data', 'check','footer', 'menuSections'));
            }
        } else {
            // For fundraiser websites, use existing behavior
            $data = Page::where('user_id', $user_id)->where('default', 1)->first();

            if($setting->site_status == 1){
                return view('page-new', compact('setting', 'header', 'data', 'check','footer'));
            }else{
                $data = null;
                return view('page-new', compact('setting', 'header', 'data', 'check','footer'));
            }
        }
    }

    public function donate()
    {
        $data = User::limit(10)->get();


        return view('donate', compact('data'));
    }

    public function invest()
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
        
        return view('invest', compact('setting', 'header', 'footer', 'website'));
    }

    public function saveInvestmentInfo(Request $request)
    {
        $request->validate([
            'investor_name' => 'required|string|max:255',
            'investor_email' => 'required|email|max:255',
            'investor_phone' => 'nullable|string|max:20',
            'investment_amount' => 'required|numeric|min:1',
        ]);

        $url = url()->previous();
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->first();

        if (!$website) {
            return response()->json(['error' => 'Website not found'], 404);
        }

        $setting = \App\Models\Setting::where('user_id', $website->user_id)->first();
        $sharePrice = $setting && $setting->share_price ? $setting->share_price : 1.00;
        $shareQuantity = floor($request->investment_amount / $sharePrice);

        $investment = Investment::create([
            'website_id' => $website->id,
            'investor_name' => $request->investor_name,
            'investor_email' => $request->investor_email,
            'investor_phone' => $request->investor_phone,
            'investment_amount' => $request->investment_amount,
            'share_quantity' => $shareQuantity,
            'deal_id' => $setting && $setting->deal_id ? $setting->deal_id : null,
            'status' => 'completed', // Auto-complete for demo purposes
            'investor_data' => $request->only(['address', 'city', 'state', 'zip', 'country', 'accredited_investor'])
        ]);

        return response()->json([
            'success' => true,
            'investment_id' => $investment->id,
            'message' => 'Investment processed successfully'
        ]);
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
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();
        
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

        return view('page-new', compact('setting', 'header', 'data', 'check','footer'));
    }

    public function dealmakerDemo()
    {
        return view('dealmaker-demo');
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
}
