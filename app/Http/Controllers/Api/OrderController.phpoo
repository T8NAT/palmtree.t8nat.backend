<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Order;
use App\Models\Company;
use App\Models\Proposal;
use App\enum\OrderStatus;
use App\Models\OrderUser;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\LocationService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Services\NotificationsServices;
use App\Http\Resources\ProposalResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon; // Import Carbon for working with dates

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('search');
    }

    public function index()
    {
        $orders = Order::query();

        if(auth()->user()->role == 'company'){
            $orders = $orders->where('company_id',auth()->user()->company_info->id);
        }

        if(auth()->user()->role == 'delivery'){
            $orders = $orders->where('selected_delivery_id',auth()->user()->id);
        }

        $orders = $orders->orderBy('id','desc')->get();
        return ApiResponse::sendResponse(200, 'Orders Retrieved Successfully', OrderResource::collection($orders));
    }
    
    public function getPendingOrdersCompany(){
        if(auth()->user()->role != 'company'){
            return ApiResponse::sendResponse(422, 'غير مسموح لك بعرض هذة الطلبات',[]);
        }
        
        $orders = Order::where([
            'company_id'   => auth()->user()->company_info->id,
            'order_status' => OrderStatus::Pending
        ])->orderBy('id','desc')->get();
        
        return ApiResponse::sendResponse(200, 'Orders Retrieved Successfully', OrderResource::collection($orders));
    }

    public function acceptOrder(Order $order)
    {
        $user = auth()->user();

        // Check if the user has already accepted the order
        if ($order->proposals()->where('delivery_id', $user->id)->exists()) {
            $proposal = Proposal::where([
                'delivery_id' => $user->id,
                'order_id'    => $order->id
            ])->with('delivery','order')->first();
            return ApiResponse::sendResponse(400, 'تم تقديم طلب من قبل',new ProposalResource($proposal));
        }

        // Attach the user to the order (accept the order)
        $proposal = Proposal::create([
            'delivery_id' => $user->id,
            'order_id'    => $order->id,
            'status'      => OrderStatus::Accepted
        ]);

        return ApiResponse::sendResponse(200, 'تم تقديم الطلب علي الاوردر',new ProposalResource($proposal));
    }

    public function getDeliveredOrdersCount()
    {
        $user = auth()->user();
        $deliveredOrdersCount = $user->orders()->where('order_status', OrderStatus::Delivered)->count();
        // Count orders with a status other than the excluded ones
        $pendingOrdersCount = Order::where('order_status',OrderStatus::Pending)->whereHas('proposals',function($query){
            return $query->where([
                'delivery_id' => auth()->user()->id,
                'status'      => 'accepted',
                'approved'    => 'wait'
            ]);
        })->count();

        $data['deliveredOrdersCount'] =  $deliveredOrdersCount;
        $data['pendingOrdersCount'] =  $pendingOrdersCount;

        return ApiResponse::sendResponse(200, 'Count Retrieved Successfully', $data);
    }

    public function getDeliveredOrders()
    {
        $user = auth()->user();
        $deliveredOrders = $user->orders()->where('order_status', OrderStatus::Delivered)->get();
        return ApiResponse::sendResponse(200, 'Completed Retrieved Successfully', OrderResource::collection($deliveredOrders));
    }

    public function getApproveWaitOrders()
    {
        $deliveredOrders = Order::where([
            'order_status' => OrderStatus::Pending
        ])->whereHas('proposals',function($query){
            return $query->where([
                'delivery_id' => auth()->user()->id,
                'status'      => 'accepted',
                'approved'    => 'wait'
            ]);
        })->orderBy('id','desc')->get();
        return ApiResponse::sendResponse(200, 'Completed Retrieved Successfully', OrderResource::collection($deliveredOrders));
    }

    public function getFilteredOrders()
    {
        $user = auth()->user();

        // Specify the statuses to exclude (e.g., 'Pending' and 'Delivered')
        $excludedStatuses = [OrderStatus::Pending,OrderStatus::Delivered];

        // Retrieve orders with a status other than the excluded ones
        $filteredOrders = $user->orders()->orderBy('id','desc')->whereNotIn('order_status', $excludedStatuses)->get();


        return ApiResponse::sendResponse(200, 'Pending Order Retrieved Successfully', $filteredOrders);
    }

    public function getOrderInfo($orderId)
    {
        // Ensure the order belongs to the authenticated user
        $user = auth()->user();

        // Use findOrFail to get the order by ID or return 404 if not found
        $order = $user->orders()->findOrFail($orderId);

        return new OrderResource($order);
    }

    public function getAllOrdersForCompany()
    {
        $companyId = auth()->user()->company_info->id; // Assuming the company_id is stored in the 'id' field of the company table
        $orders = Order::where('company_id', $companyId)->orderBy('id','desc')->get();
        return ApiResponse::sendResponse(200, 'Orders Retrieved Successfully', OrderResource::collection($orders));
    }

    //search for order
    public function search(Request $request,$unique_id){
        if($unique_id):
            $order = Order::query();
            // Search by order ID if the 'search' parameter is provided in the request
            $order->where('unique_id',$unique_id);
            $orders = $order->orderBy('id','desc')->get();
            return ApiResponse::sendResponse(200, 'Order Retrieved Successfully', OrderResource::collection($orders));
        endif;

        return ApiResponse::sendResponse(422, 'Order Isnot Exist', []);
    }

    // latest order for user
    public function latestChosenOrder()
    {
        $user = auth()->user();
        $latestChosenOrder = $user->orders()
            ->where('order_status', OrderStatus::Accepted)
            ->latest()
            ->first();

        if($latestChosenOrder):
            return ApiResponse::sendResponse(200, 'Latest Order Retrieved Successfully', new OrderResource($latestChosenOrder));
        endif;
        return ApiResponse::sendResponse(200, 'Latest Order Retrieved Successfully',[]);
    }

    // show the accepted orders for users
    public function chosenOrders()
    {
        $user = auth()->user();
        $chosenOrders = $user->orders()
            ->orderBy('id','desc')
            ->where('order_status', OrderStatus::Accepted)
            ->get();

        if($chosenOrders):
            return ApiResponse::sendResponse(200, 'Order Retrieved Successfully', OrderResource::collection($chosenOrders));
        endif;
        return ApiResponse::sendResponse(200, 'Order Retrieved Successfully',[]);
    }


    // refuse order

    public function refuseOrder(Order $order)
    {
        $user = auth()->user();

        // Check if the user has already accepted the order
        if ($order->proposals()->where('delivery_id',$user->id)->exists()) {
            $proposal = Proposal::where([
                'delivery_id' => $user->id,
                'order_id'    => $order->id
            ])->with('delivery','order')->first();
            return ApiResponse::sendResponse(400, 'تم تقديم طلب من قبل',new ProposalResource($proposal));
        }

        // Attach the user to the order (accept the order)
        $proposals = Proposal::create([
            'delivery_id' => $user->id,
            'order_id'    => $order->id,
            'status'      => 'refused'
        ]);

        return ApiResponse::sendResponse(200, 'تم رفض الطلب بنجاح', new ProposalResource($proposals));
    }

    // get pending orders not accepted or refused by user
    public function getPendingOrders(Request $request)
    {
        $user = Auth::user();
        $pendingOrders = Order::where([
            'order_status'          => OrderStatus::Pending,
            'selected_delivery_id'  => null, // دا المندوب اللى اتعين للشحنة
            'selected_deliveries'   => null  // ودا اللي الادمن ضافهم فى لوحة التحكم للشحنة
        ])->orderBy('id','desc')->whereDoesntHave('proposals',function($query){
            return $query->where('delivery_id',auth()->user()->id);
        });

        $ordersTrackings = collect(LocationService::OrdersTrackingLocation($pendingOrders,[
            'lat' => $user->lat,
            'lng'=> $user->lng
        ]));

        $pendingOrdersHaveSelectedDelieveires = Order::where([
            ['order_status','=',OrderStatus::Pending],
            ['selected_delivery_id','=',null],
            ['selected_deliveries','!=',null]
        ])->whereDoesntHave('proposals',function($query){
            return $query->where('delivery_id',auth()->user()->id);
        })->whereJsonContains('selected_deliveries',"".auth()->user()->id."")->get();

        $orders = $ordersTrackings->merge($pendingOrdersHaveSelectedDelieveires);

        return ApiResponse::sendResponse(200, 'Order Retrieved Successfully', OrderResource::collection($orders));
    }

    // for company cound delivered and not delivered orders 
    public function CountOrders()
    {
        $user = Auth::user(); // Assuming "company" is the guard name

        if($user->role != 'company'){
            return ApiResponse::sendResponse(422, 'غير مسموح لك بعرض هذة الاحصائيات',[]);
        }

        $deliveredOrdersCount = Order::where('company_id',$user->company_info->id)
            ->where('order_status', OrderStatus::Delivered)
            ->count();

        $pendingOrdersCount = Order::where('company_id',$user->company_info->id)
            ->where('order_status', OrderStatus::Pending)
            ->count();

        return response()->json([
            'data' => [
                'delivered_orders_count' => $deliveredOrdersCount,
                'pending_orders_count' => $pendingOrdersCount,
            ],
        ]);
    }

    // user change order status
    public function changeOrderStatus(Order $order, Request $request)
    {
        $request->validate([
            'new_status' => 'required|exists:statuses,status_name',
        ]);

        if($request->input('new_status') == OrderStatus::Delivered){
            return response()->json([
                'message' => 'Order need Serial Number to delivered',
            ],422);
        }

        $order->update([
            'order_status' => $request->input('new_status'),
        ]);

        try{
            if($order->order_status == OrderStatus::ReceivedByCourier){
                NotificationsServices::DeliveryIsReceived($order,$order->company->user);
                if($order->customer_phone){
                    $order->update([
                        'serialNo' => Str::random(6)
                    ]);
                    $order->fresh();
                    NotificationsServices::SendSerialToClient($order,$order->company->user);
                }
            }

            if($order->order_status == OrderStatus::DeliveryInProgress){
                NotificationsServices::OrderIsOnTheWay($order,$order->company->user);
            }

            
        } catch(Exception $e){}

        return response()->json([
            'message' => 'Order status updated successfully.',
            'data' => [
                'order_id' => $order->id,
                'new_status' => $order->order_status,
            ],
        ]);
    }

    public function orderIsDelivered(Order $order, Request $request){
        $request->validate([
            'serial_code' => 'required|exists:orders,serialNo',
        ],[
            'serial_code.exists' => 'Serial Code is Not exist'
        ]);

        if($order->serialNo != $request->input('serial_code')){
            return response()->json([
                'message' => 'Serial Number is error for this order',
            ],422);
        }

        if($order->selected_delivery_id != auth()->user()->id){
            return response()->json([
                'message' => 'You Not have this order Opps !',
            ],422);
        }

        try{
            $order->update([
                'order_status' => OrderStatus::Delivered,
            ]);

            $order->fresh();

            if($order->order_status == OrderStatus::Delivered){
                NotificationsServices::OrderIsDelivered($order,$order->company->user);
            }
        } catch(Exception $e){}

        return response()->json([
            'message' => 'Order status updated successfully.',
            'data' => [
                'order_id' => $order->id,
                'new_status' => $order->order_status,
            ],
        ]);
    }
}


