<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Page;
use App\Models\Donation;
use App\Models\Website;
use App\Models\Header;
use App\Models\Setting;

class FrontendController extends Controller
{

    public function index()
    {

        $url = url()->current();
        if( $url == 'fundably.org' || $url == 'https://fundably.org' || $url == 'http://fundably.org' || $url == 'http://127.0.0.1:8000') {
            return redirect()->route('admin.index', 1);
        }
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();
        $user_id = $check->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $data = Page::where('user_id', $user_id)->where('default',1)->first();


        return view('page', compact('setting', 'header', 'data', 'check'));
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
    public function leaderBoard()
    {
        return view('leader-board');
    }

    public function student($slug)
    {

        $array = explode('-', $slug);

        $id = $array[0];

        // dd($id);

        $data = User::where('id', $id)->first();

        $donations = Donation::where('user_id', $id)->get();

        return view('student', compact('data', 'donations'));
    }

    public function donation(Request $request)
    {
        // dd($request->all());
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

        if(isset($request->anonymous_donation)) {
            $add->hide = 1;
        } else {
            $add->hide = 0;

        }

        $add->status = 0;
        $add->save();


        return redirect('/authorize/payment/'.$add->id)->with('success', 'Donation Pending');
    }

    public function page($id)
    {
        $data = Page::find($id);
        $url = url()->current();
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();
        $user_id = $check->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();

        return view('page', compact('setting', 'header', 'data', 'check'));
    }
}
