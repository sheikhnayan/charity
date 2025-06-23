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
use App\Http\Middleware\admin;

Route::get('authorize/payment/{id}', [AuthorizeNetController::class, 'index']);
Route::post('authorize/payment', [AuthorizeNetController::class, 'paymentPost'])->name('authorize.payment');

Route::get('/', [
    FrontendController::class, 'index'
])->name('home');

Route::get('/page-builder', function () {
    return view('page-builder');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/page/{id}', [FrontendController::class, 'page'])->name('page');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/donate', [FrontendController::class, 'donate'])->name('donate');

Route::post('/donation', [FrontendController::class, 'donation'])->name('donation');

Route::get('/student/{slug}', [FrontendController::class, 'student'])->name('donate');

Route::get('/leader-board', [FrontendController::class, 'leaderBoard'])->name('leader-board');

Route::get('/volunteer', [FrontendController::class, 'volunteer'])->name('volunteer');

Route::get('/photo', [FrontendController::class, 'photo'])->name('photo');

Route::get('/about', [FrontendController::class, 'about'])->name('about');

Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

Route::group(['prefix' => 'users', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('user.donation');
    });

    Route::get('/setting', [AdminController::class, 'index']);

    Route::get('/direct_deposit', [AdminController::class, 'direct_deposit']);
    Route::post('/direct_deposit/store', [AdminController::class, 'direct_deposit_store']);

    Route::get('/mailed_deposit', [AdminController::class, 'mailed_deposit']);
    Route::post('/mailed_deposit/store', [AdminController::class, 'mailed_deposit_store']);


    Route::get('/profile', function () {
        return view('user.profile');
    });

    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::get('/donation', function () {
        return view('user.donation');
    });

    Route::get('/student',[
        AdminController::class, 'student'
    ])->name('admin.student');
});

Route::group(['prefix' => 'admins', 'middleware' => ['auth',admin::class]], function () {
    Route::get('/', [
        AdminController::class, 'index'
    ])->name('admin.index');

    Route::get('/setting/{id}', [
        AdminController::class, 'setting'
    ])->name('admin.setting');

    Route::post('/store',[AdminController::class, 'store'])->name('admin.store');

    Route::get('/menu',[AdminController::class, 'menu_index'])->name('admin.menu');

    Route::get('/menu/{id}',[AdminController::class, 'menu'])->name('admin.menu');

    Route::post('/menu/store',[AdminController::class, 'store_menu'])->name('admin.menu.store');

    Route::get('/payment',[AuthorizeNetController::class, 'setting'])->name('admin.payment.setting');

    Route::post('/payment/update',[AuthorizeNetController::class, 'update'])->name('admin.payment.update');


    Route::get('/approve/{id}',[
        AdminController::class, 'approve'
    ])->name('admin.approve');

    Route::get('/student/approve/{id}',[
        AdminController::class, 'student_approve'
    ])->name('admin.student.approve');

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





});

