<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\User;
use App\Models\Setting;
use App\Models\Header;
use App\Models\DirectDeposit;
use App\Models\MailedCheck;
use Auth;
use Hash;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Website::get();

        return view('admin.website.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.website.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            //code...
            $add = new Website;
            $add->user_id = Auth::user()->id;
            $add->name = $request->name;
            $add->domain = $request->domain;
            $add->status = 1;
            $add->save();

            $user = new User;
            $user->name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'user';
            $user->website_id = $add->id;
            $user->goal = 50000;
            $user->description = 'test description';
            $user->photo = 'images/1744993540.png';
            $user->save();

            $update = Website::find($add->id);
            $update->user_id = $user->id;
            $update->update();

            $setting = new Setting;
            $setting->user_id = $user->id;
            $setting->logo = 'images/1744993540.png';
            $setting->banner = '1745001151.png';
            $setting->title = $add->name;
            $setting->title2 = $add->name;
            $setting->sub_title = $add->name;
            $setting->date = now();
            $setting->location = 'Canada';
            $setting->time = '13:08';
            $setting->description = 'Test Description';
            $setting->save();

            $header = new Header;
            $header->user_id = $user->id;
            $header->website_id = $add->id;
            $header->background = '#ffffff';
            $header->color = '#000';
            $header->status = 1;
            $header->floating = 1;
            $header->menu = 1;
            $header->save();

            $n = new DirectDeposit;
            $n->user_id = $user->id;
            $n->save();

            $m = new MailedCheck;
            $m->user_id = $user->id;
            $m->save();

            return redirect()->route('admin.website.index')->with('success', 'Website created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Website::find($id);
        return view('admin.website.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $update = Website::find($id);
        $update->name = $request->name;
        $update->domain = $request->domain;
        $update->status = $request->status;
        $update->update();

        return redirect()->route('admin.website.index')->with('success', 'Website updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Website::find($id);
        $delete->delete();

        return redirect()->route('admin.website.index')->with('success', 'Website deleted successfully.');
    }
}
