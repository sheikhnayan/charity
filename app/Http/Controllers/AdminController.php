<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Donation;
use App\Models\Setting;
use App\Models\Header;
use App\Models\Website;
use App\Models\DirectDeposit;
use App\Models\MailedCheck;
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

        return redirect()->back()->with('success', 'Menu Updated successfully');
    }

    public function store(Request $request){

        $id = $request->id;

        $add = Setting::find($id);
        $add->title = $request->title;
        $add->description = $request->description;
        $add->location = $request->location;
        $add->title2 = $request->title2;
        $add->sub_title = $request->sub_title;
        $add->date = $request->date;
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


    public function donation()
    {
        $user = Auth::user();

        $websites = Website::where('user_id', $user->id)->select('id')->get();
        $websites = $websites->pluck('id')->toArray();

        $data = Donation::whereIn('website_id',[$websites])->with('user')->get();

        return view('admin.donation', compact('data'));
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

            return view('admin.students', compact('data'));
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

        return view('admin.menu.menu', compact('data'));
    }

    public function menu_index()
    {
        $data = Header::get();

        return view('admin.menu.index', compact('data'));
    }

}
