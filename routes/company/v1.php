<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\FirebaseController;
use App\Http\Controllers\Api\TrackingController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Api\Authentication\Logout;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Authentication\AuthenticatedController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// auth 
Route::post('register', [AuthenticatedController::class, 'register']);
Route::post('login', [AuthenticatedController::class, 'login']);

// log out
Route::middleware('auth:sanctum')->group(function () {
    // show orders 
    Route::get('/orders', [OrderController::class, 'index']);
    Route::patch('/update-profile', [CompanyController::class, 'updateMyProfile']);

    Route::get('my-profile',[AuthenticatedController::class, 'getMeProfile']);
    
    // count delivered and not deliverred orders for company 
    Route::get('/orders-count', [OrderController::class, 'CountOrders']);

    // get pending orders not accepted or refused by user
    Route::get('/pending-orders', [OrderController::class, 'getPendingOrdersCompany']);
});