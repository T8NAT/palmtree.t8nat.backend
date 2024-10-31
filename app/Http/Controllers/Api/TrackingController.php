<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Tracking;
use App\enum\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrackingResource;

class TrackingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function updateOrderLocation(Request $request)
    {
        //
        $request->validate([
            'lat'      => 'required',
            'long'     => 'required'
        ]);

        $status = [
            OrderStatus::HeadingToDestination,
            OrderStatus::ArrivedAtDestination,
            OrderStatus::ReceivedByCourier,
            OrderStatus::DeliveryInProgress
        ];

        $request->user()->update([
            'lat' => $request->input('lat'),
            'lng' => $request->input('long')
        ]);

        $orders = Order::where([
            'selected_delivery_id' => $request->user()->id
        ])->whereIn('order_status',$status)->pluck('id')->toArray();

        if(count($orders) > 0):
            foreach($orders as $order_id):
                Tracking::updateOrCreate([
                    'user_id'  => $request->user()->id,
                    'order_id' => $order_id,
                    'lat'      => $request->input('lat'),
                    'long'     => $request->input('long')
                ]);
            endforeach;
        endif;

        return response()->json([
            'status'  => 'success',
            'message' => __('Location is updated successfully')
        ]);
    }

    public function getTrackings(Request $request,$order_id){
        $trackings = Tracking::query();

        if($request->user()->role == 'delivery'):
            $trackings = $trackings->where([
                'user_id'  => $request->user()->id,
                'order_id' => $order_id
            ]);
        endif;

        

        if($request->user()->role == 'company'):
            $trackings = $trackings->WhereHas('order',function($query) use($order_id){
                return $query->where([
                    'company_id'  => request()->user()->company_info ? request()->user()->company_info->id : null,
                    'order_id'    => $order_id
                ]);
            });
        endif;

        return response()->json([
            'status'  => 'success',
            'data'    => TrackingResource::collection($trackings->with('order','delivery')->get())
        ]);
    }

}
