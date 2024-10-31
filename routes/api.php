<?php

use App\Http\Controllers\Api\Authentication\AuthenticatedController;
use App\Http\Controllers\Api\Authentication\Logout;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\FirebaseController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Salla\SallaController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\TrackingController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

  // create Order
  Route::post('/order/create', [OrderController::class, 'store']);



  Route::post('/logout', [AuthenticatedController::class, 'logout']);
  Route::get('my-profile', [AuthenticatedController::class, 'getMeProfile']);
  Route::patch('companies/{user}/profile/', [CompanyController::class, 'updateProfile']);
  Route::post('firebase-device-token', [FirebaseController::class, 'firebaseUdateDeviceToken']);
  Route::post('push-notification', [NotificationController::class, 'pushNotification']);
  Route::get('notifications', [NotificationController::class, 'index']);

  // user accept order
  Route::patch('/orders/{order}/accept', [OrderController::class, 'acceptOrder']);

  // user refuse order
  Route::patch('/orders/{order}/refuse', [OrderController::class, 'refuseOrder']);

  // tracking
  Route::put('/set-tracking', [TrackingController::class, 'updateOrderLocation']);
  Route::get('/get-tracking/{order_id}', [TrackingController::class, 'getTrackings']);

  Route::patch('update-location', [AuthenticatedController::class, 'udpateLocation']);

  // get pending orders not accepted or refused by user
  Route::get('/pending-orders', [OrderController::class, 'getPendingOrders']);

  // get pending orders not accepted or refused by user
  Route::get('/company/pending-orders', [OrderController::class, 'getPendingOrdersCompany']);

  // change status
  Route::put('/orders/{order}/change-status', [OrderController::class, 'changeOrderStatus']);
  Route::put('/orders/{order}/delivery-order', [OrderController::class, 'orderIsDelivered']);
});

// status
Route::get('/get-status', [StatusController::class, 'getStatus']);

// show orders
Route::get('/orders', [OrderController::class, 'index']);




Route::post('/print-order/{unique_id}', [OrderController::class, 'print_order']);



// create Order
Route::get('/order/canceled/{id}', [OrderController::class, 'canceled']);


// count completed orders
Route::get('/user/delivered-orders-count', [OrderController::class, 'getDeliveredOrdersCount']);

// show completed orders
Route::get('/user/delivered-orders', [OrderController::class, 'getDeliveredOrders']);

// show wait orders
Route::get('/user/wait-approve-orders', [OrderController::class, 'getApproveWaitOrders']);

//show pending orders
Route::get('/user/filtered-orders', [OrderController::class, 'getFilteredOrders']);

// order info
// show order info
Route::get('/user/orders/{orderId}', [OrderController::class, 'getOrderInfo']);

// show orders for company
Route::get('/company/all-orders-for-company', [OrderController::class, 'getAllOrdersForCompany']);

//search by order id
Route::get('/orders/search/{unique_id}', [OrderController::class, 'search']);

// get latest choser order for user
Route::get('/latest-chosen-order', [OrderController::class, 'latestChosenOrder']);

// get   choser orders for user
Route::get('/chosen-orders', [OrderController::class, 'chosenOrders']);



// count delivered and not deliverred orders for company
Route::get('/orders-count', [OrderController::class, 'CountOrders']);





Route::post('/company-payload', [SallaController::class, 'getPayload']);
