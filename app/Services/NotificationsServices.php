<?php 
namespace App\Services;

use App\Models\User;
use App\Services\LocationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Notifications\OrderIsCreated;
use App\Notifications\OrderIsOnTheWay;
use App\Notifications\OrderIsDelivered;
use App\Notifications\CustomNotification;
use App\Notifications\DeliveryIsReceived;
use App\Notifications\SerialDelivery;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DeliveryOrderIsToLated;
use App\Notifications\DeliveryProposalIsAccepted;
use App\Notifications\ShipmentLeftTheSortingArea;
use App\Notifications\ShipmentArrivedAtTheSortingArea;

class NotificationsServices{
    public static function deliveryOrderCreated($order,$selected_deliveries = null){
        $deliveries = User::Delivery();

        if($selected_deliveries == null){
            $deliveries_selected = collect(LocationService::DeliveryTrackingLocation($deliveries,[
                'lat' => $order->location_lat,
                'lng' => $order->location_lng
            ]));
        } else {
            $formated_selected = json_decode($selected_deliveries,true);
            $deliveries_selected = $deliveries->whereIn('id',$formated_selected)->get();
        }

        try{
            $firebase_tokens = $deliveries_selected->pluck('device_token')->all();
            Notification::sendNow($deliveries_selected, new OrderIsCreated($firebase_tokens));
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }

    public static function DeliveryProposalAccepted($order,$delivery){
        try{
            $firebase_tokens[] = $delivery->device_token ?: null;
            Notification::sendNow($delivery, new DeliveryProposalIsAccepted($firebase_tokens,$order));
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }

    public static function DeliveryOrderIsToLated($order,$delivery){
        try{
            $firebase_tokens[] = $delivery->device_token ?: null;
            Notification::sendNow($delivery, new DeliveryOrderIsToLated($firebase_tokens,$order));
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }

    public static function DeliveryIsReceived($order,$company){
        try{
            $firebase_tokens[] = $company->device_token ?: null;
            Notification::sendNow($company, new DeliveryIsReceived($firebase_tokens,$order));
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }

    public static function OrderIsOnTheWay($order,$company){
        try{
            $firebase_tokens[] = $company->device_token ?: null;
            Notification::sendNow($company, new OrderIsOnTheWay($firebase_tokens,$order));
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }

    public static function OrderIsDelivered($order,$company){
        try{
            $firebase_tokens[] = $company->device_token ?: null;
            Notification::sendNow($company, new OrderIsDelivered($firebase_tokens,$order));
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }

    public static function SendSerialToClient($order,$company){
        try{
           Notification::sendNow($company, new SerialDelivery($order));
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }

    public static function InstructionsOrderChenged($instructions,$order,$company){
        try{
            $firebase_tokens[] = $company->device_token ?: null;
            foreach($instructions as $instruction):
                if($instruction == 'shipment_arrived_at_the_sorting_area'){
                    Notification::sendNow($company, new ShipmentArrivedAtTheSortingArea($firebase_tokens,$order));
                } elseif($instruction == 'shipment_left_the_sorting_area'){
                    Notification::sendNow($company, new ShipmentLeftTheSortingArea($firebase_tokens,$order));
                }
            endforeach;
        } catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }

    public static function CustomNotification($groups,$notification){
        $firebase_tokens = $groups->pluck('device_token');
        Notification::send($groups, new CustomNotification($notification));
    }
}