<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\WebsitePaymentController;
use App\Http\Controllers\Api\PageBuilderController;
use App\Http\Controllers\AuthorizeNetController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\AuctionController;
use App\Http\Middleware\admin;
use App\Models\Setting;

// Test route to populate demo data
Route::get('/populate-demo', function() {
    Setting::truncate(); // Clear existing data
    
    Setting::create([
        'user_id' => 1,
        'hero_title' => 'ADMIN CONTROLLED HERO!',
        'hero_subtitle' => 'This content is now dynamic from admin panel!',
        'stat_1_number' => '5B+',
        'stat_1_text' => 'Raised via admin',
        'stat_2_number' => '2.5B',
        'stat_2_text' => 'Admin controlled',
        'stat_3_number' => '1200+',
        'stat_3_text' => 'Dynamic offers',
        'meta_title' => 'Dynamic DealMaker | Admin Controlled',
        'meta_description' => 'This page is now completely controlled by the admin panel!',
        'site_logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Amazon_Web_Services_Logo.svg/256px-Amazon_Web_Services_Logo.svg.png',
        'client_logos' => json_encode([
            [
                'name' => 'AWS',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Amazon_Web_Services_Logo.svg/256px-Amazon_Web_Services_Logo.svg.png',
                'url' => 'https://aws.amazon.com'
            ],
            [
                'name' => 'Google',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/120px-Google_%22G%22_logo.svg.png',
                'url' => 'https://google.com'
            ],
            [
                'name' => 'Microsoft',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Microsoft_logo.svg/256px-Microsoft_logo.svg.png',
                'url' => 'https://microsoft.com'
            ]
        ]),
        'slider_images' => json_encode([
            [
                'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561045749461ab86204c2_homepage_phone-02.webp',
                'title' => 'ADMIN CONTROLLED SLIDER!',
                'description' => 'This slider content is now completely dynamic and controlled from the admin panel!',
                'cta_text' => 'Admin Demo',
                'cta_url' => '/admins/dealmaker-settings'
            ],
            [
                'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855610466fede381344c563_homepage_phone-03.webp',
                'title' => 'Dynamic Content Management',
                'description' => 'Change any content on this page through the admin interface without touching code.',
                'cta_text' => 'Manage Content',
                'cta_url' => '/admins/dealmaker-settings'
            ]
        ])
    ]);
    
    return 'Demo data populated! Visit <a href="/dealmaker-demo">dealmaker-demo</a> to see the changes.';
});

Route::get('authorize/payment/{type}/{id}', [AuthorizeNetController::class, 'index']);
Route::post('authorize/payment', [AuthorizeNetController::class, 'paymentPost'])->name('authorize.payment');
Route::post('authorize/stripe', [AuthorizeNetController::class, 'paymentStripe'])->name('stripe.post');
Route::get('/product', function(){
    return view('thank-you');
});

// Comments routes (with CSRF protection)
Route::post('/comments', [App\Http\Controllers\Api\CommentController::class, 'store'])->name('comments.store');
Route::get('/comments', [App\Http\Controllers\Api\CommentController::class, 'index'])->name('comments.index');

// CSRF Test Route
Route::post('/test-csrf', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'CSRF token is valid',
        'data' => $request->all()
    ]);
})->name('test.csrf');

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

// Route::post('/donation-general', [FrontendController::class, 'donation_general'])->name('donation-general');

Route::get('/profile/{slug}', [FrontendController::class, 'student'])->name('donate');

Route::get('/leader-board', [FrontendController::class, 'leaderBoard'])->name('leader-board');

Route::get('/volunteer', [FrontendController::class, 'volunteer'])->name('volunteer');

Route::get('/photo', [FrontendController::class, 'photo'])->name('photo');

Route::get('/about', [FrontendController::class, 'about'])->name('about');

Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

Route::post('/contact-form', [FrontendController::class, 'contact_form'])->name('contact-form');

// Newsletter subscription route
Route::post('/newsletter/subscribe', [FrontendController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

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

    // Newsletter management routes
    Route::get('/newsletter',[AdminController::class, 'newsletter_index'])->name('admin.newsletter');
    Route::get('/newsletter/{website_id}',[AdminController::class, 'newsletter_manage'])->name('admin.newsletter.manage');
    Route::post('/newsletter/send-email',[AdminController::class, 'newsletter_send_email'])->name('admin.newsletter.send');
    Route::delete('/newsletter/subscription/{id}',[AdminController::class, 'newsletter_delete_subscription'])->name('admin.newsletter.delete');
    Route::post('/newsletter/export/{website_id}',[AdminController::class, 'newsletter_export'])->name('admin.newsletter.export');

    // Comment management routes
    Route::get('/comments',[AdminController::class, 'comments_index'])->name('admin.comments');
    Route::post('/comments/{id}/reply',[AdminController::class, 'comments_reply'])->name('admin.comments.reply');
    Route::delete('/comments/{id}',[AdminController::class, 'comments_delete'])->name('admin.comments.delete');

    Route::get('/donation', [
        AdminController::class, 'donation'
    ])->name('admin.donation');

    Route::post('/transactions/update-status', [
        AdminController::class, 'updateTransactionStatus'
    ])->name('admin.transactions.update-status');

    Route::get('/transactions/{transactionId}/download-invoice', [
        AdminController::class, 'downloadTransactionInvoice'
    ])->name('admin.transactions.download-invoice');

    Route::post('/transactions/{transactionId}/resend-invoice', [
        AdminController::class, 'resendTransactionInvoice'
    ])->name('admin.transactions.resend-invoice');

    // Test routes for invoice functionality
    Route::get('/test/invoice-pdf', [
        \App\Http\Controllers\InvoiceTestController::class, 'testInvoice'
    ])->name('admin.test.invoice-pdf');

    Route::get('/test/invoice-email', [
        \App\Http\Controllers\InvoiceTestController::class, 'testEmail'
    ])->name('admin.test.invoice-email');

    // Debug route for fees and SSN
    Route::get('/debug/fees-ssn', [
        \App\Http\Controllers\DebugController::class, 'debugFeesAndSSN'
    ])->name('admin.debug.fees-ssn');
    
    // Email testing routes
    Route::get('/debug/test-email', [
        \App\Http\Controllers\EmailTestController::class, 'testEmail'
    ])->name('admin.debug.test-email');
    
    Route::get('/debug/test-invoice-email', [
        \App\Http\Controllers\EmailTestController::class, 'testInvoiceEmail'
    ])->name('admin.debug.test-invoice-email');
    
    Route::get('/debug/test-investment-email', [
        \App\Http\Controllers\EmailTestController::class, 'testInvestmentEmail'
    ])->name('admin.debug.test-investment-email');

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

        // Payment settings routes
        Route::get('/{website}/payment-settings', [
            WebsitePaymentController::class, 'show'
        ])->name('admin.websites.payment.show');

        Route::put('/{website}/payment-settings', [
            WebsitePaymentController::class, 'update'
        ])->name('admin.websites.payment.update');

        Route::post('/{website}/payment-settings/test', [
            WebsitePaymentController::class, 'test'
        ])->name('admin.websites.payment.test');

        Route::delete('/{website}/payment-settings', [
            WebsitePaymentController::class, 'destroy'
        ])->name('admin.websites.payment.destroy');
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

    // Template management routes
    route::group(['prefix' => 'templates'], function () {
        Route::get('/', [
            \App\Http\Controllers\PageTemplateController::class, 'index'
        ])->name('admin.templates.index');

        Route::get('/create', [
            \App\Http\Controllers\PageTemplateController::class, 'create'
        ])->name('admin.templates.create');

        Route::post('/store', [
            \App\Http\Controllers\PageTemplateController::class, 'store'
        ])->name('admin.templates.store');

        Route::get('/show/{template}', [
            \App\Http\Controllers\PageTemplateController::class, 'show'
        ])->name('admin.templates.show');

        Route::get('/edit/{template}', [
            \App\Http\Controllers\PageTemplateController::class, 'edit'
        ])->name('admin.templates.edit');

        Route::put('/update/{template}', [
            \App\Http\Controllers\PageTemplateController::class, 'update'
        ])->name('admin.templates.update');

        Route::delete('/destroy/{template}', [
            \App\Http\Controllers\PageTemplateController::class, 'destroy'
        ])->name('admin.templates.destroy');

        Route::get('/preview/{template}', [
            \App\Http\Controllers\PageTemplateController::class, 'preview'
        ])->name('admin.templates.preview');

        // AJAX routes
        Route::get('/get-templates', [
            \App\Http\Controllers\PageTemplateController::class, 'getTemplates'
        ])->name('admin.templates.get');

        Route::post('/save-from-page/{page}', [
            \App\Http\Controllers\PageTemplateController::class, 'saveFromPage'
        ])->name('admin.templates.save-from-page');

        Route::post('/apply-to-page/{template}/{page}', [
            \App\Http\Controllers\PageTemplateController::class, 'applyToPage'
        ])->name('admin.templates.apply-to-page');
    });

    // Image upload route for page builder
    Route::post('/upload-image', [AdminController::class, 'uploadImage'])->name('admin.upload.image');
    
    // Video upload route for page builder
    Route::post('/upload-video', [AdminController::class, 'uploadVideo'])->name('admin.upload.video');

    // DealMaker Admin Routes
    Route::get('/dealmaker-settings', [App\Http\Controllers\DealmakerAdminController::class, 'index'])->name('dealmaker.admin.index');
    Route::post('/dealmaker-settings', [App\Http\Controllers\DealmakerAdminController::class, 'update'])->name('dealmaker.admin.update');
    Route::post('/dealmaker-settings/add-logo', [App\Http\Controllers\DealmakerAdminController::class, 'addLogo'])->name('dealmaker.admin.add-logo');
    Route::delete('/dealmaker-settings/remove-logo/{index}', [App\Http\Controllers\DealmakerAdminController::class, 'removeLogo'])->name('dealmaker.admin.remove-logo');

});

// Temporary test route for DealMaker admin (REMOVE AFTER TESTING)
Route::get('/test-dealmaker-admin', function() {
    $setting = App\Models\DealmakerConfig::getInstance();
    return response()->json([
        'current_settings' => $setting->toArray(),
        'message' => 'DealMaker config loaded successfully'
    ]);
});

Route::post('/test-dealmaker-save', function(Illuminate\Http\Request $request) {
    $setting = App\Models\DealmakerConfig::getInstance();
    
    $testData = [
        'meta_title' => 'Test Title - ' . now(),
        'hero_title' => 'Test Hero - ' . now()
    ];
    
    $result = $setting->update($testData);
    
    return response()->json([
        'result' => $result,
        'updated_settings' => $setting->fresh()->toArray(),
        'message' => $result ? 'Save successful' : 'Save failed'
    ]);
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

