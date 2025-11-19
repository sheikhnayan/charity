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
use Hash;
use App\Models\PageComment;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function change_password()
    {
        return view('admin.change-password');
    }

    public function update_password(Request $request)
    {

        if ($request->new_password != $request->confirm_password) {
            # code...
            return redirect()->back()->with('error', 'New Password and Confirm Password do not match');
        }

        $user = Auth::user()->id;

        $data = User::find($user);

        $data->password = Hash::make(request()->new_password);

        $data->save();

        return redirect()->back()->with('success', 'Password updated successfully');
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
        $data->logo_height = $request->logo_height;
        $data->invest_now_button_text = $request->invest_now_button_text ?? 'Invest Now';
        
        // Handle investor exclusives fields for investment websites
        if ($request->has('show_investor_exclusives')) {
            $data->show_investor_exclusives = $request->show_investor_exclusives;
        }
        if ($request->has('investor_exclusives_text')) {
            $data->investor_exclusives_text = $request->investor_exclusives_text;
        }
        if ($request->has('investor_exclusives_url')) {
            $data->investor_exclusives_url = $request->investor_exclusives_url;
        }
        if ($request->has('topbar_background_color')) {
            $data->topbar_background_color = $request->topbar_background_color;
        }
        if ($request->has('topbar_text_color')) {
            $data->topbar_text_color = $request->topbar_text_color;
        }
        
        // Handle contact top bar fields for investment websites
        if ($request->has('show_contact_topbar')) {
            $data->show_contact_topbar = $request->show_contact_topbar;
        }
        if ($request->has('contact_phone')) {
            $data->contact_phone = $request->contact_phone;
        }
        if ($request->has('contact_email')) {
            $data->contact_email = $request->contact_email;
        }
        if ($request->has('contact_address')) {
            $data->contact_address = $request->contact_address;
        }
        if ($request->has('contact_cta_text')) {
            $data->contact_cta_text = $request->contact_cta_text;
        }
        if ($request->has('contact_cta_url')) {
            $data->contact_cta_url = $request->contact_cta_url;
        }
        if ($request->has('contact_topbar_bg_color')) {
            $data->contact_topbar_bg_color = $request->contact_topbar_bg_color;
        }
        if ($request->has('contact_topbar_text_color')) {
            $data->contact_topbar_text_color = $request->contact_topbar_text_color;
        }
        if ($request->has('contact_cta_bg_color')) {
            $data->contact_cta_bg_color = $request->contact_cta_bg_color;
        }
        if ($request->has('contact_cta_text_color')) {
            $data->contact_cta_text_color = $request->contact_cta_text_color;
        }
        
        // Handle header font family
        if ($request->has('header_font_family')) {
            $data->header_font_family = $request->header_font_family;
        }
        
        // Handle section-specific font families
        if ($request->has('menu_font_family')) {
            $data->menu_font_family = $request->menu_font_family;
        }
        if ($request->has('contact_topbar_font_family')) {
            $data->contact_topbar_font_family = $request->contact_topbar_font_family;
        }
        if ($request->has('investor_exclusives_font_family')) {
            $data->investor_exclusives_font_family = $request->investor_exclusives_font_family;
        }
        
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
        $data->background_type = $request->background_type ?? 'color';
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
        
        // Handle investment-specific fields
        if ($request->has('disclaimer_text')) {
            $data->disclaimer_text = $request->disclaimer_text;
        }
        if ($request->has('description_text')) {
            $data->description_text = $request->description_text;
        }
        
        // Handle image uploads
        if ($request->hasFile('background_image_desktop')) {
            $file = $request->file('background_image_desktop');
            $filename = time() . '_desktop_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data->background_image_desktop = $filename;
        }
        
        if ($request->hasFile('background_image_mobile')) {
            $file = $request->file('background_image_mobile');
            $filename = time() . '_mobile_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data->background_image_mobile = $filename;
        }
        
        $data->update();

        return redirect()->back()->with('success', 'Footer Updated successfully');
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
        
        // Investment-specific fields
        if ($request->filled('investment_title')) {
            $add->investment_title = $request->investment_title;
        }
        if ($request->filled('asset_type')) {
            $add->asset_type = $request->asset_type;
        }
        if ($request->filled('offering_type')) {
            $add->offering_type = $request->offering_type;
        }


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
        }elseif($user->role == 'customer'){
            $data = Transaction::where('email',$user->email)->get();

            return view('user.donation', compact('data'));
        }
        else{
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

    public function updateTransactionStatus(Request $request)
    {
        try {
            $transaction = Transaction::where('transaction_id', $request->transaction_id)->first();
            
            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            // Map status values
            $statusMap = [
                'completed' => 1,
                'cancelled' => 2,
                'refunded' => 3,
                'pending' => 0
            ];

            if (!array_key_exists($request->status, $statusMap)) {
                return response()->json(['error' => 'Invalid status'], 400);
            }

            $transaction->status = $statusMap[$request->status];
            $transaction->internal_status = strtoupper($request->status);
            $transaction->save();

            return response()->json(['success' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update status: ' . $e->getMessage()], 500);
        }
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
        $website = \App\Models\Website::find($data->website_id);
        $customFonts = \App\Models\CustomFont::active()->get();

        return view('admin.menu.menu', compact('data', 'pages', 'website', 'customFonts'));
    }

    public function menu_index()
    {
        $data = Header::get();

        return view('admin.menu.index', compact('data'));
    }

    public function footer($id)
    {
        $data = Footer::where('user_id',$id)->first();
        $website = Website::where('user_id', $id)->first();
        $customFonts = \App\Models\CustomFont::active()->get();

        // dd($id);

        return view('admin.footer.footer', compact('data', 'website', 'customFonts'));
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

    public function update_auction_status($id, Request $request)
    {
        $auction = Auction::find($id);
        
        if (!$auction) {
            return response()->json(['error' => 'Auction not found'], 404);
        }
        
        $auction->status = $request->status;
        $auction->save();
        
        return response()->json(['success' => 'Status updated successfully']);
    }

    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Store in public/uploads directory
            $image->move(public_path('uploads'), $imageName);
            
            $imageUrl = asset('uploads/' . $imageName);

            return response()->json([
                'success' => true,
                'url' => $imageUrl,
                'message' => 'Image uploaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 400);
        }
    }

    public function uploadVideo(Request $request)
    {
        // Set runtime upload limits for larger videos
        ini_set('upload_max_filesize', '50M');
        ini_set('post_max_size', '52M');
        ini_set('max_execution_time', '600');
        ini_set('memory_limit', '512M');
        
        try {
            // Log for debugging
            \Log::info('Video upload attempt', [
                'has_file' => $request->hasFile('video'),
                'request_size' => $request->header('Content-Length'),
                'files' => array_keys($request->allFiles())
            ]);
            
            $request->validate([
                'video' => 'required|file|mimes:mp4,webm,ogg,avi,mov,wmv|max:51200', // 50MB max
            ]);

            $video = $request->file('video');
            $videoName = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
            
            // Store in public/uploads directory
            $video->move(public_path('uploads'), $videoName);
            
            $videoUrl = asset('uploads/' . $videoName);

            return response()->json([
                'success' => true,
                'url' => $videoUrl,
                'message' => 'Video uploaded successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Video upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display newsletter management dashboard
     */
    public function newsletter_index()
    {
        $user = Auth::user();
        
        if ($user->role == 'admin') {
            // Admin can see all websites
            $websites = Website::with(['activeNewsletterSubscriptions'])->get();
        } else {
            // Regular user can only see their websites
            $websites = Website::where('user_id', $user->id)
                ->with(['activeNewsletterSubscriptions'])
                ->get();
        }

        return view('admin.newsletter.index', compact('websites'));
    }

    /**
     * Manage subscriptions for a specific website
     */
    public function newsletter_manage($website_id)
    {
        $user = Auth::user();
        
        // Check if user has access to this website
        $website = Website::where('id', $website_id);
        if ($user->role != 'admin') {
            $website = $website->where('user_id', $user->id);
        }
        $website = $website->firstOrFail();

        $subscriptions = \App\Models\NewsletterSubscription::where('website_id', $website_id)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $stats = [
            'total' => \App\Models\NewsletterSubscription::where('website_id', $website_id)->count(),
            'active' => \App\Models\NewsletterSubscription::where('website_id', $website_id)->where('status', 'active')->count(),
            'inactive' => \App\Models\NewsletterSubscription::where('website_id', $website_id)->where('status', 'inactive')->count(),
        ];

        return view('admin.newsletter.manage', compact('website', 'subscriptions', 'stats'));
    }

    /**
     * Send email to newsletter subscribers
     */
    public function newsletter_send_email(Request $request)
    {
        $request->validate([
            'website_id' => 'required|exists:websites,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_type' => 'required|in:all,active'
        ]);

        $user = Auth::user();
        
        // Check if user has access to this website
        $website = Website::where('id', $request->website_id);
        if ($user->role != 'admin') {
            $website = $website->where('user_id', $user->id);
        }
        $website = $website->firstOrFail();

        // Get subscribers based on recipient type
        $query = \App\Models\NewsletterSubscription::where('website_id', $request->website_id);
        if ($request->recipient_type === 'active') {
            $query->where('status', 'active');
        }
        $subscribers = $query->get();

        $emailsSent = 0;
        $failedEmails = [];

        foreach ($subscribers as $subscriber) {
            try {
                \Mail::raw($request->message, function ($message) use ($subscriber, $request, $website) {
                    $message->to($subscriber->email)
                           ->subject($request->subject)
                           ->from(config('mail.from.address', 'noreply@' . $website->domain), $website->name);
                });
                $emailsSent++;
            } catch (\Exception $e) {
                $failedEmails[] = $subscriber->email;
                \Log::error('Failed to send newsletter email', [
                    'email' => $subscriber->email,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $message = "Newsletter sent successfully! {$emailsSent} emails sent.";
        if (!empty($failedEmails)) {
            $message .= " Failed to send to: " . implode(', ', array_slice($failedEmails, 0, 5));
            if (count($failedEmails) > 5) {
                $message .= " and " . (count($failedEmails) - 5) . " more.";
            }
        }

        return back()->with('success', $message);
    }

    /**
     * Delete a newsletter subscription
     */
    public function newsletter_delete_subscription($id)
    {
        $user = Auth::user();
        
        $subscription = \App\Models\NewsletterSubscription::with('website')->findOrFail($id);
        
        // Check if user has access to this website
        if ($user->role != 'admin' && $subscription->website->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        $subscription->delete();

        return back()->with('success', 'Subscription deleted successfully.');
    }

    /**
     * Export newsletter subscriptions to CSV
     */
    public function newsletter_export($website_id)
    {
        $user = Auth::user();
        
        // Check if user has access to this website
        $website = Website::where('id', $website_id);
        if ($user->role != 'admin') {
            $website = $website->where('user_id', $user->id);
        }
        $website = $website->firstOrFail();

        $subscriptions = \App\Models\NewsletterSubscription::where('website_id', $website_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'newsletter_subscriptions_' . $website->name . '_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($subscriptions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['First Name', 'Last Name', 'Email', 'Phone', 'Country Code', 'Status', 'Subscribed Date']);

            foreach ($subscriptions as $subscription) {
                fputcsv($file, [
                    $subscription->first_name ?? '',
                    $subscription->last_name ?? '',
                    $subscription->email,
                    $subscription->phone ?? '',
                    $subscription->country_code ?? '',
                    $subscription->status,
                    $subscription->subscribed_at ? $subscription->subscribed_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Comment Management Methods
    public function comments_index()
    {
        $comments = PageComment::with(['website', 'replies'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.comments.index', compact('comments'));
    }

    public function comments_reply(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:5000',
        ]);

        $parentComment = PageComment::findOrFail($id);
        
        // Create admin reply
        PageComment::create([
            'page_identifier' => $parentComment->page_identifier,
            'component_id' => $parentComment->component_id,
            'website_id' => $parentComment->website_id,
            'author_name' => 'Site Administrator',
            'author_email' => Auth::user()->email,
            'comment' => $request->comment,
            'is_approved' => true,
            'is_anonymous' => false,
            'is_admin_reply' => true,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'parent_id' => $id
        ]);

        return redirect()->back()->with('success', 'Reply posted successfully!');
    }

    public function comments_delete($id)
    {
        $comment = PageComment::findOrFail($id);
        
        // Delete the comment and all its replies
        $comment->replies()->delete();
        $comment->delete();
        
        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    /**
     * Download transaction invoice as PDF
     */
    public function downloadTransactionInvoice($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        $website = $transaction->website;
        
        $total_with_fee = $transaction->amount + (($transaction->amount / 100) * ($website->paymentSettings->fee ?? 2.9));
        
        $pdf = Pdf::loadView('emails.invoice-pdf', [
            'transaction' => $transaction,
            'website' => $website,
            'total_with_fee' => $total_with_fee
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        return $pdf->download('invoice-' . $transaction->transaction_id . '.pdf');
    }

    /**
     * Resend invoice email for a transaction
     */
    public function resendTransactionInvoice($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        $website = $transaction->website;
        
        try {
            \Mail::to($transaction->email)->send(new \App\Mail\TransactionInvoice($transaction, $website));
            return response()->json(['success' => true, 'message' => 'Invoice email sent successfully!']);
        } catch (\Exception $e) {
            \Log::error('Failed to resend transaction invoice', [
                'transaction_id' => $transaction->transaction_id,
                'email' => $transaction->email,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to send invoice email.']);
        }
    }

}
