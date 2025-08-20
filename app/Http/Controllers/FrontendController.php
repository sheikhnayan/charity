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
use Mail;

class FrontendController extends Controller
{

    public function index()
    {

        $url = url()->current();
        if( $url == 'fundably.org' || $url == 'https://fundably.org' || $url == 'http://fundably.org' || $url == 'http://127.0.0.1:8000') {
            return redirect()->route('admin.index', 1);
        }
        $doamin = parse_url($url, PHP_URL_HOST);
        // dd($doamin);
        $check = Website::where('domain', $doamin)->first();
        $user_id = $check->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = footer::where('user_id', $user_id)->first();
        $data = Page::where('user_id', $user_id)->where('default', 1)->first();

        if($setting->site_status == 1){
            return view('page', compact('setting', 'header', 'data', 'check','footer'));
        }else{
            $data = null;
            return view('page', compact('setting', 'header', 'data', 'check','footer'));
        }
    }

    public function donate()
    {
        $data = User::limit(10)->get();


        return view('donate', compact('data'));
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
        $data = Page::where('website_id', $check->id)->where('name', str_replace('-', ' ', $id))->first();
        $user_id = $check->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = footer::where('user_id', $user_id)->first();

        return view('page', compact('setting', 'header', 'data', 'check','footer'));
    }
}
