<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Donation;
use App\Models\Setting;
use App\Models\Header;
use App\Models\Footer;
use App\Models\Website;
use App\Models\DirectDeposit;
use App\Models\MailedCheck;
use App\Models\WireTransfer;
use App\Models\Auction;
use App\Models\Tax;
use App\Models\TaxReceipt;
use App\Models\Transaction;
use Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->role != 'admin') {
            $data = Setting::where('user_id', Auth::user()->id)->first();

            return view('user.setting', compact('data'));
        }else{
            $data = Setting::get();

            return view('admin.setting.list', compact('data'));
        }

    }

    public function wire_transfer()
    {
        $user = Auth::user();

        $data = WireTransfer::where('user_id',$user->id)->first();

        return view('user.wire_transfer', compact('data'));
    }

    public function wire_transfer_store(Request $request)
    {
        // dd($request->all());
        $data = WireTransfer::where('user_id', Auth::user()->id)->first();
        // dd(Auth::user()->id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->city = $request->city;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->zip = $request->zip;
        $data->paybale_to = $request->paybale_to;
        $data->send_check_to = $request->send_check_to;
        $data->address_to_send = $request->address_to_send;
        $data->city_to_send = $request->city_to_send;
        $data->beneficiary_address = $request->beneficiary_address;
        $data->beneficiary_zip = $request->beneficiary_zip;
        $data->beneficiary_city = $request->beneficiary_city;
        $data->beneficiary_country = $request->beneficiary_country;
        $data->beneficiary_state = $request->beneficiary_state;
        $data->update();

        return redirect()->back()->with('success', 'Direct Deposit Updated successfully');
    }

    public function mailed_deposit()
    {
        $user = Auth::user();

        $data = MailedCheck::where('user_id',$user->id)->first();

        return view('user.mailed_deposit', compact('data'));
    }

    public function direct_deposit()
    {
        $user = Auth::user();

        $data = DirectDeposit::where('user_id',$user->id)->first();

        return view('user.direct_deposit', compact('data'));
    }

    public function direct_deposit_store(Request $request)
    {
        $data = DirectDeposit::where('user_id', Auth::user()->id)->first();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->city = $request->city;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->zip = $request->zip;
        $data->name_in_bank = $request->name_in_bank;
        $data->bank_name = $request->bank_name;
        $data->account_type = $request->account_type;
        $data->account_number = $request->account_number;
        $data->routing_number = $request->routing_number;
        $data->update();

        return redirect()->back()->with('success', 'Direct Deposit Updated successfully');
    }

    public function tax()
    {
        $user = Auth::user();

        $data = Tax::where('user_id',$user->id)->first();

        return view('user.tax', compact('data'));
    }

    public function tax_store(Request $request)
    {
        // dd(Auth::user()->id);
        $data = Tax::where('user_id', Auth::user()->id)->first();
        $data->name = $request->name;
        $data->business_name = $request->business_name;
        $data->address = $request->address;
        $data->zip = $request->zip;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->tin = $request->tin;
        $data->type_of_tin = $request->type_of_tin;
        $data->update();

        return redirect()->back()->with('success', 'Tax Information Updated successfully');
    }

    public function tax_receipt_list()
    {
        $data = User::where('role', 'user')->latest()->get();

        return view('admin.tax-receipt.index', compact('data'));
    }

    public function tax_receipt()
    {
        $user = Auth::user();

        $data = TaxReceipt::where('user_id',$user->id)->first();

        return view('user.tax_receipt', compact('data'));
    }

    public function tax_receipt_show($id)
    {
        $data = TaxReceipt::where('user_id',$id)->first();

        return view('admin.tax-receipt.show', compact('data'));
    }

    public function tax_list()
    {

        $data = User::where('role', 'user')->latest()->get();


        return view('admin.tax.index', compact('data'));
    }

    public function tax_show($id)
    {
        $data = Tax::where('user_id',$id)->first();

        return view('admin.tax.show', compact('data'));

    }

    public function tax_receipt_store(Request $request)
    {
        // dd($request->all());
        $data = TaxReceipt::where('user_id', Auth::user()->id)->first();
        $data->organization = $request->organization;
        $data->phone_number = $request->phone_number;
        $data->website = $request->website;
        $data->charitable_id = $request->charitable_id;
        $data->reference = $request->reference;
        $data->number_prefix = $request->number_prefix;
        $data->starting_number = $request->starting_number;
        $data->address = $request->address;
        $data->zip = $request->zip;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->country = $request->country;

            if ($request->hasFile('logo')) {
                $data->logo = $request->file('logo')->store('uploads', 'public');
            }

            if ($request->hasFile('signature')) {
                $data->signature = $request->file('signature')->store('uploads', 'public');
            }

        $data->update();

        return redirect()->back()->with('success', 'Tax Receipt Information Updated successfully');
    }

    public function mailed_deposit_store(Request $request)
    {
        $data = MailedCheck::where('user_id', Auth::user()->id)->first();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->city = $request->city;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->zip = $request->zip;
        $data->paybale_to = $request->paybale_to;
        $data->send_check_to = $request->send_check_to;
        $data->address_to_send = $request->address_to_send;
        $data->city_to_send = $request->city_to_send;
        $data->country_to_send = $request->country_to_send;
        $data->state_to_send = $request->state_to_send;
        $data->zip_to_send = $request->zip_to_send;
        $data->update();

        return redirect()->back()->with('success', 'Mailed Deposit Updated successfully');
    }

    public function setting($id)
    {
        $data = Setting::find($id);

        return view('admin.setting.index', compact('data'));
    }

    public function store_menu(Request $request)
    {
        $data = Header::where('id', $request->id)->first();
        $data->status = $request->status;
        $data->color = $request->color;
        $data->background = $request->background;
        $data->menu = $request->menu;
        $data->floating = $request->floating;
        $data->logo_size = $request->logo_size;
        $data->update();

        if ($request->has('menu_order')) {
            foreach ($request->menu_order as $order => $pageId) {
                \App\Models\Page::where('id', $pageId)->update(['position' => $order]);
            }
        }

        return redirect()->back()->with('success', 'Menu Updated successfully');
    }

    public function store_footer(Request $request)
    {
        $data = Footer::where('id', $request->id)->first();
        $data->status = $request->status;
        $data->color = $request->color;
        $data->privacy = $request->privacy;
        $data->background = $request->background;
        $data->menu = $request->menu;
        $data->message = $request->message;
        $data->copy_right = $request->copy_right;
        $data->social = $request->social;
        $data->facebook = $request->facebook;
        $data->instagram = $request->instagram;
        $data->twitter = $request->twitter;
        $data->linkedin = $request->linkedin;
        $data->youtube = $request->youtube;
        $data->pinterest = $request->pinterest;
        $data->tiktok = $request->tiktok;
        $data->blue_sky = $request->blue_sky;
        $data->update();

        return redirect()->back()->with('success', 'Menu Updated successfully');
    }

    public function store(Request $request){
        // dd($request->all());
        $id = $request->id;

        $add = Setting::find($id);
        $add->title = $request->title;
        $add->description = $request->description;
        $add->location = $request->location;
        $add->payout_method = $request->payout_method;
        $add->title2 = $request->title2;
        $add->sub_title = $request->sub_title;
        $add->date = $request->date;
        $add->api_key = $request->api_key;
        $add->api_secret = $request->api_secret;
        $add->goal = $request->goal;
        $add->site_status = $request->site_status;
        $add->payment_method = $request->payment_method;
        $add->time = $request->time;
        $add->participant_name = $request->participant_name;
        $add->team_name = $request->team_name;

        $add->organization = $request->organization;
        $add->phone = $request->phone;
        $add->charitable_id = $request->charitable_id;
        $add->address = $request->address;
        $add->zip = $request->zip;
        $add->city = $request->city;
        $add->country = $request->country;
        $add->state = $request->state;
        $add->privacy = $request->privacy;
        $add->terms = $request->terms;
        $add->refund = $request->refund;


        if (isset($request->logo)) {
            $file = $request->file('logo');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $add->logo = $fileName;
            # code...
        }

        if (isset($request->banner)) {
            $file = $request->file('banner');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $add->banner = $fileName;
            # code...
        }

        $add->save();
        return redirect()->back()->with('success', 'Setting Updated successfully');

    }

    public function payment_method()
    {
        $data = User::where('role',  'user')->get();

        return view('admin.payment_method.index', compact('data'));
    }

    public function payment_method_details($id)
    {
        $mailed = MailedCheck::where('user_id', $id)->first();
        $direct = DirectDeposit::where('user_id', $id)->first();
        $wire = WireTransfer::where('user_id', $id)->first();

        return view('admin.payment_method.payment_method', compact('mailed', 'direct', 'wire'));
    }


    public function donation()
    {
        $user = Auth::user();

        if ($user->role == 'admin') {
            # code...
            $data = Transaction::latest()->get();
            $websites = \App\Models\Website::all();

            return view('admin.donation', compact('data', 'websites'));
        }elseif($user->role == 'user'){
            $websites = Website::where('user_id', $user->id)->select('id')->first();
            // $websites = $websites->pluck('id')->toArray();

            // dd($websites);

            $data = Transaction::where('website_id',$websites->id)->get();

            return view('user.donation', compact('data', 'websites'));
        }else{
            $data = Donation::where('user_id',Auth::user()->id)->with('user')->get();

            return view('user.donation', compact('data'));
        }


    }

    public function approve($id)
    {
        $data = Donation::find($id);
        $data->status = 1;
        $data->save();

        return redirect()->back()->with('success', 'Donation Approved successfully');
    }

    public function student_approve($id)
    {
        $data = User::find($id);
        $data->status = 1;
        $data->save();

        return redirect()->back()->with('success', 'User Approved successfully');
    }

    public function student()
    {

        if (Auth::user()->role == 'admin') {
            # code...
            $data = User::where('role', '!=','user')->get();

            $websites = \App\Models\Website::all();

            return view('admin.students', compact('data', 'websites'));
        }elseif(Auth::user()->role == 'group_leader'){
            $data = User::where('group_id',Auth::user()->id)->where('id','!=',Auth::user()->id)->get();

            return view('user.students', compact('data'));
        }else{
            $websites = Website::where('user_id', Auth::user()->id)->select('id')->get();
            $websites = $websites->pluck('id')->toArray();

            $data = User::where('role', '!=','user')->whereIn('website_id',[$websites])->get();

            return view('user.students', compact('data'));
        }

    }

    public function menu($id)
    {
        $data = Header::find($id);

        $pages = \App\Models\Page::where('website_id',$data->website_id)->orderBy('position')->get();

        return view('admin.menu.menu', compact('data', 'pages'));
    }

    public function menu_index()
    {
        $data = Header::get();

        return view('admin.menu.index', compact('data'));
    }

    public function footer($id)
    {
        $data = Footer::where('user_id',$id)->first();

        return view('admin.footer.footer', compact('data'));
    }

    public function footer_index()
    {
        $data = User::where('role','user')->latest()->get();

        return view('admin.footer.index', compact('data'));
    }

    public function auction_index()
    {
        $data = Website::get();

        return view('admin.auction.index', compact('data'));
    }

    public function auction_edit($id)
    {
        $data = Auction::where('website_id', $id)->get();

        $website = Website::find($id);

        return view('admin.auction.auction', compact('data','website'));
    }

    public function auction_edit_auction($id)
    {
        $data = Auction::find($id);

        return view('admin.auction.edit', compact('data'));
    }

    public function auction_add($id)
    {
        $website = Website::find($id);

        return view('admin.auction.add', compact('website'));
    }

    public function store_auction(Request $request)
    {
        // dd($request->all());
        $data = new Auction();
        $data->website_id = $request->id;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->dead_line = $request->deadline;
        $data->value = $request->value;
        $data->timezone = $request->timezone;
        $data->status = $request->status;
        $data->save();

        if (isset($request->images)) {
            foreach ($request->images as $key => $value) {
                # code...
                $file = $value;
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $image = new \App\Models\AuctionImage();
                $image->auction_id = $data->id;
                $image->image = $fileName;
                $image->save();
            }
        }

        return redirect()->route('admin.auction.edit',[$data->website_id])->with('success', 'Auction Created successfully');

    }

    public function update_auction(Request $request, $id)
    {
        // dd($request->all());
        $data = Auction::find($id);
        $data->title = $request->title;
        $data->description = $request->description;
        $data->dead_line = $request->deadline;
        $data->value = $request->value;
        $data->timezone = $request->timezone;
        $data->status = $request->status;
        $data->update();

        // Remove old images
        if (isset($request->delete_images)) {
            foreach ($request->delete_images as $key => $value) {
                # code...
                $image = \App\Models\AuctionImage::find($value);
                if ($image) {
                    // Delete the image file from storage
                    if (file_exists(public_path('uploads/' . $image->image))) {
                        unlink(public_path('uploads/' . $image->image));
                    }
                    // Delete the image record from database
                    $image->delete();
                }
            }
        }

        // $remove = \App\Models\AuctionImage::where('auction_id', $data->id)->delete();

        if (isset($request->images)) {
            foreach ($request->images as $key => $value) {
                # code...
                $file = $value;
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $image = new \App\Models\AuctionImage();
                $image->auction_id = $data->id;
                $image->image = $fileName;
                $image->save();
            }
        }

        return redirect()->route('admin.auction.edit',[$data->website_id])->with('success', 'Auction Updated successfully');

    }



}
