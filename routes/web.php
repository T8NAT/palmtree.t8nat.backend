<?php

use App\Http\Controllers\pages\Page2;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\Company\OrderController as CompnayOrderController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\Dashboard\CompanyController;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\Dashboard\OrderUserController;
use App\Http\Controllers\Dashboard\OrderDetailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Main Page Route
Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');


Route::get('test', function(){
echo "hello";
});

// locale
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

// pages
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

Route::get('show-invoice-pdf/{unique_id}',[OrderController::class,'show_invoice_pdf'])->name('show_invoice_pdf');

Route::middleware(['auth'])->group(function () {
    Route::post('/print-order/{unique_id}',[OrderController::class,'print_order'])->name('orders.print-order');
    Route::middleware('role:admin')->group(function () {
        // Landing page route
        Route::get('/', [HomePage::class, 'index'])->name('pages-home');
        // Users resource routes
        Route::resource('users', UserController::class);
    
        // Companies resource routes
        Route::resource('companies', CompanyController::class);
    
        //offers 
        Route::get('orders/with-offers', [OrderUserController::class, 'showOrdersWithOffers'])->name('offers.show_orders_with_offers');
        Route::get('orders/{order}/offers', [OrderUserController::class, 'showOffersForOrder'])->name('offers.show_for_order');
    
        Route::patch('offers/accept/{offer}', [OrderUserController::class, 'acceptOffer'])->name('offers.accept');
    
        // orders routes 
        Route::resource('orders', OrderController::class);
    
        Route::post('orders/ajax-trackings/{unique_id}',[OrderController::class, 'ajaxTrackings'])->name('orders.ajax-trackings');
    
        Route::post('/orders/search', [OrderController::class, 'search'])->name('orders.search');
    
        Route::get('/order/detail',[OrderDetailController::class,'index'])->name('orders.detail.index');
        Route::post('/order/detail',[OrderDetailController::class,'store'])->name('orders.detail.store');
        Route::delete('/order/detail/{detail}',[OrderDetailController::class,'destroy'])->name('orders.detail.destroy');
    
        Route::post('notifiy-order-is-tolate/{order}',[OrderUserController::class,'notifiyOrderIsTolate'])->name('notifiy-order-is-tolate');
        
        Route::get('notifications',[NotificationController::class,'index'])->name('notifications');
        Route::post('push-notification',[NotificationController::class,'push_notification'])->name('push-notification');
    });
    
    Route::middleware('role:company')->group(function () {
        // orders routes 
        Route::resource('my-orders', CompnayOrderController::class);
        Route::post('orders/ajax-trackings/{unique_id}',[CompnayOrderController::class, 'ajaxTrackings'])->name('orders.ajax-trackings');
        Route::post('/orders/search', [CompnayOrderController::class, 'search'])->name('orders.search');
    });
});



//auth routes 
Route::get('login', [LoginBasic::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginBasic::class, 'authenticate']);
Route::post('logout', [LoginBasic::class, 'logout'])->name('logout');


// Route::get('orders/{order}/applied-offers', [OrderUserController::class, 'showAppliedOffers'])->name('orders.applied_offers');
