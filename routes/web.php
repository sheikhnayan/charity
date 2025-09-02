<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Api\PageBuilderController;
use App\Http\Controllers\AuthorizeNetController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\AuctionController;
use App\Http\Middleware\admin;

Route::get('authorize/payment/{type}/{id}', [AuthorizeNetController::class, 'index']);
Route::post('authorize/payment', [AuthorizeNetController::class, 'paymentPost'])->name('authorize.payment');
Route::post('authorize/stripe', [AuthorizeNetController::class, 'paymentStripe'])->name('stripe.post');
Route::get('/product', function(){
    return view('thank-you');
});


Route::get('/run-migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return 'Migration completed: ' . Artisan::output();
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/', [
    FrontendController::class, 'index'
])->name('home');

Route::get('/page-builder', function () {
    return view('page-builder');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/auction', [AuctionController::class, 'all'])->name('auction');

Route::get('/place-bid', [AuctionController::class, 'store'])->name('auction.store');

Route::get('/auction/{id}', [AuctionController::class, 'show'])->name('auction-show');

Route::get('/page/{id}', [FrontendController::class, 'page'])->name('page');

Route::get('/dealmaker-demo', [FrontendController::class, 'dealmakerDemo'])->name('dealmaker.demo');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/donate', [FrontendController::class, 'donate'])->name('donate');

Route::get('/invest', [FrontendController::class, 'invest'])->name('invest');

// Investment-related routes
Route::post('/invest/save-info', [FrontendController::class, 'saveInvestmentInfo'])->name('invest.save-info');
Route::post('/invest/process-investment', [FrontendController::class, 'processInvestment'])->name('invest.process');
Route::get('/invest/thank-you', [FrontendController::class, 'investmentThankYou'])->name('invest.thank-you');
Route::get('/invest/status/{id}', [FrontendController::class, 'investmentStatus'])->name('invest.status');
Route::post('/invest/contact', [FrontendController::class, 'investmentContact'])->name('invest.contact');
Route::get('/invest/terms', [FrontendController::class, 'investmentTerms'])->name('invest.terms');
Route::get('/invest/privacy', [FrontendController::class, 'investmentPrivacy'])->name('invest.privacy');

Route::post('/donations', [FrontendController::class, 'donation'])->name('donation');

Route::post('/tickets', [FrontendController::class, 'tickets'])->name('tickets');

Route::post('/custom-form', [FrontendController::class, 'custom_form'])->name('custom-form');

Route::post('/donation-general', [FrontendController::class, 'donation_general'])->name('donation-general');

Route::get('/profile/{slug}', [FrontendController::class, 'student'])->name('donate');

Route::get('/leader-board', [FrontendController::class, 'leaderBoard'])->name('leader-board');

Route::get('/volunteer', [FrontendController::class, 'volunteer'])->name('volunteer');

Route::get('/photo', [FrontendController::class, 'photo'])->name('photo');

Route::get('/about', [FrontendController::class, 'about'])->name('about');

Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

Route::post('/contact-form', [FrontendController::class, 'contact_form'])->name('contact-form');

Route::group(['prefix' => 'users', 'middleware' => 'auth'], function () {
    Route::get('/', [AdminController::class, 'donation']);

    Route::get('/setting', [AdminController::class, 'index']);

    Route::get('/direct_deposit', [AdminController::class, 'direct_deposit']);
    Route::post('/direct_deposit/store', [AdminController::class, 'direct_deposit_store']);

    Route::get('/mailed_deposit', [AdminController::class, 'mailed_deposit']);
    Route::post('/mailed_deposit/store', [AdminController::class, 'mailed_deposit_store']);

    Route::get('/wire_transfer', [AdminController::class, 'wire_transfer']);
    Route::post('/wire_transfer/store', [AdminController::class, 'wire_transfer_store']);

    Route::get('/tax', [AdminController::class, 'tax']);
    Route::post('/tax/store', [AdminController::class, 'tax_store']);

    Route::get('/tax-receipt', [AdminController::class, 'tax_receipt']);
    Route::post('/tax-receipt/store', [AdminController::class, 'tax_receipt_store']);


    Route::get('/profile', function () {
        return view('user.profile');
    });

    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::get('/donation', [AdminController::class, 'donation']);

    Route::get('/student',[
        AdminController::class, 'student'
    ])->name('admin.student');
});

    Route::post('/admins/store',[AdminController::class, 'store'])->name('admin.store');

    Route::get('/admins/approve/{id}',[
        AdminController::class, 'approve'
    ])->name('admin.approve');

    Route::get('/admins/student/approve/{id}',[
        AdminController::class, 'student_approve'
    ])->name('admin.student.approve');

Route::group(['prefix' => 'admins', 'middleware' => ['auth',admin::class]], function () {
    Route::get('/', [
        AdminController::class, 'index'
    ])->name('admin.index');

    Route::get('/setting/{id}', [
        AdminController::class, 'setting'
    ])->name('admin.setting');

    Route::get('/tax/show/{id}',[AdminController::class, 'tax_show'])->name('admin.tax.show');

    Route::get('/tax-list',[AdminController::class, 'tax_list'])->name('admin.tax.list');

    Route::get('/tax-receipt/show/{id}',[AdminController::class, 'tax_receipt_show'])->name('admin.tax-receipt.show');

    Route::get('/tax-receipt-list',[AdminController::class, 'tax_receipt_list'])->name('admin.tax-receipt.list');

    Route::get('/auction/{id}',[AdminController::class, 'auction_edit'])->name('admin.auction.edit');

    Route::get('/auction/add/{id}',[AdminController::class, 'auction_add'])->name('admin.add');

    Route::get('/menu',[AdminController::class, 'menu_index'])->name('admin.menu');

    Route::get('/auction',[AdminController::class, 'auction_index'])->name('admin.auction');

    Route::get('/auction/{id}',[AdminController::class, 'auction_edit'])->name('admin.auction.edit');

    Route::get('/auction/add/{id}',[AdminController::class, 'auction_add'])->name('admin.add');

    Route::get('/auction-edit/{id}',[AdminController::class, 'auction_edit_auction'])->name('admin.edit-auction');

    Route::post('/auction/store',[AdminController::class, 'store_auction'])->name('admin.auction.store');

    Route::post('/auction/update/{id}',[AdminController::class, 'update_auction'])->name('admin.auction.update');

    Route::get('/menu/{id}',[AdminController::class, 'menu'])->name('admin.menu');

    Route::post('/menu/store',[AdminController::class, 'store_menu'])->name('admin.menu.store');

    Route::get('/payment',[AuthorizeNetController::class, 'setting'])->name('admin.payment.setting');

    Route::post('/payment/update',[AuthorizeNetController::class, 'update'])->name('admin.payment.update');

    Route::get('/payout-methods',[AdminController::class, 'payment_method'])->name('admin.payment-method');

    Route::get('/payment_method/{id}',[AdminController::class, 'payment_method_details'])->name('admin.payment-method.details');

    Route::get('/footer',[AdminController::class, 'footer_index'])->name('admin.footer');

    Route::get('/footer/{id}',[AdminController::class, 'footer'])->name('admin.footer');

    Route::post('/footer/store',[AdminController::class, 'store_footer'])->name('admin.footer.store');





    Route::get('/donation', [
        AdminController::class, 'donation'
    ])->name('admin.donation');

    Route::get('/student',[
        AdminController::class, 'student'
    ])->name('admin.student');

    route::group(['prefix' => 'website'], function () {
        Route::get('/', [
            WebsiteController::class, 'index'
        ])->name('admin.website.index');

        Route::get('/create', [
            WebsiteController::class, 'create'
        ])->name('admin.website.create');

        Route::post('/store', [
            WebsiteController::class, 'store'
        ])->name('admin.website.store');

        Route::get('/edit/{id}', [
            WebsiteController::class, 'edit'
        ])->name('admin.website.edit');

        Route::post('/update/{id}', [
            WebsiteController::class, 'update'
        ])->name('admin.website.update');

        Route::get('/delete/{id}', [
            WebsiteController::class, 'delete'
        ])->name('admin.website.delete');
    });

    Route::prefix('ticket')->name('admin.ticket.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/store', [TicketController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TicketController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [TicketController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [TicketController::class, 'destroy'])->name('delete');
    });

    Route::prefix('sponsor')->name('admin.sponsor.')->group(function () {
        Route::get('/', [SponsorController::class, 'index'])->name('index');
        Route::get('/create', [SponsorController::class, 'create'])->name('create');
        Route::post('/store', [SponsorController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SponsorController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SponsorController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SponsorController::class, 'destroy'])->name('delete');
    });

    route::group(['prefix' => 'page'], function () {
        Route::get('/', [
            PageBuilderController::class, 'index'
        ])->name('admin.page.index');

        Route::get('/create', [
            PageBuilderController::class, 'create'
        ])->name('admin.page.create');

        Route::post('/store', [
            PageBuilderController::class, 'store'
        ])->name('admin.page.store');

        Route::get('/show/{id}', [
            PageBuilderController::class, 'show'
        ])->name('admin.page.show');

        Route::get('/edit/{id}', [
            PageBuilderController::class, 'edit'
        ])->name('admin.page.edit');

        Route::post('/update/{id}', [
            PageBuilderController::class, 'update'
        ])->name('admin.page.update');

        Route::get('/delete/{id}', [
            PageBuilderController::class, 'delete'
        ])->name('admin.page.delete');

        Route::post('/save/{id}',
         [PageBuilderController::class, 'save'
        ])->name('admin.page.save');

        Route::get('/load/{id}', [PageBuilderController::class, 'load'
        ])->name('admin.page.load');
    });

    // Image upload route for page builder
    Route::post('/upload-image', [AdminController::class, 'uploadImage'])->name('admin.upload.image');
    
    // Video upload route for page builder
    Route::post('/upload-video', [AdminController::class, 'uploadVideo'])->name('admin.upload.video');

});

// Temporary test route for video upload (remove after testing)
Route::post('/test-upload-video', function(Illuminate\Http\Request $request) {
    try {
        $request->validate([
            'video' => 'required|file|mimes:mp4,webm,ogg,avi,mov,wmv|max:10240',
        ]);

        $video = $request->file('video');
        $videoName = time() . '_test_' . uniqid() . '.' . $video->getClientOriginalExtension();
        
        $video->move(public_path('uploads'), $videoName);
        $videoUrl = asset('uploads/' . $videoName);

        return response()->json([
            'success' => true,
            'url' => $videoUrl,
            'message' => 'Video uploaded successfully (test route)'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 400);
    }
});

