<?php
namespace App\Services;

class LocationService{
    public static function distance($from_location, $to_location, $unit = 'k') {
        $theta = round($from_location['lng'],7) - round($to_location['lng'],7);
        $dist  = sin(deg2rad(round($from_location['lat'],7))) * sin(deg2rad(round($to_location['lat'],7))) +  cos(deg2rad(round($from_location['lat'],7))) * cos(deg2rad(round($to_location['lat'],7))) * cos(deg2rad($theta));
        $dist  = acos($dist);
        $dist  = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit  = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }


    public static function OrdersTrackingLocation($orders,$delivery_location = [],$ids_only = false,$distance = 5){
        if($orders->count() > 0):
            $orders->chunk(200,function($orders) use ($delivery_location,&$selected_orders,&$distance,$ids_only){
                foreach($orders as $order):
                    if(!isset($order->location_lat)) continue;
                    if(!isset($order->location_lng)) continue;
                    $get_distanc = self::distance([
                        'lat'  => $order->location_lat,
                        'lng'  => $order->location_lng
                    ],$delivery_location);

                    if($get_distanc <= $distance):
                        $selected_orders[] = ($ids_only == false ? $order : $order->id);
                    endif;
                endforeach;
            });

            if($distance <= 35):
                if(!isset($selected_orders)):
                    $distance = $distance + 1;
                    return self::OrdersTrackingLocation($orders,$delivery_location,$ids_only,$distance);
                endif;
            endif;
        endif;
        return $selected_orders ?? [];
    }

    public static function DeliveryTrackingLocation($deliveries,$order_location = [],$ids_only = false,$distance = 5){
        if($deliveries->count() > 0):
            $deliveries->chunk(200,function($deliveries) use ($order_location,&$selected_deliveries,&$distance,$ids_only){
                foreach($deliveries as $delivery):
                    if(!isset($delivery->lat)) continue;
                    if(!isset($delivery->lng)) continue;
                    $get_distanc = self::distance([
                        'lat'  => $delivery->lat,
                        'lng'  => $delivery->lng
                    ],$order_location);

                    if($get_distanc <= $distance):
                        $selected_deliveries[] = ($ids_only == false ? $delivery : $delivery->id);
                    endif;
                endforeach;
            });

            if($distance <= 35):
                if(!isset($selected_deliveries)):
                    $distance = $distance + 1;
                    return self::DeliveryTrackingLocation($deliveries,$order_location,$ids_only,$distance);
                endif;
            endif;
        endif;
        return $selected_deliveries ?? [];
    }
}