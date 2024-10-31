@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Orders')


@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>تتبع الشحنة</h5>
                </div>
                <div class="card-body">
                    <div id="map-tracking"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>تفاصيل الشحنة</h5>
                </div>
                <div class="card-body">
                    <ul class="order-datails">
                        <li>
                            <strong>{{ __('tracking id') }}</strong>
                            <label>{{ $order->unique_id }}</label>
                        </li>
                        <li>
                            <strong>{{ __('Location') }}</strong>
                            <label>{{ $order->location_name }}</label>
                        </li>
                        <li>
                            <strong>{{ __('Destination') }}</strong>
                            <label>{{ $order->destination_name }}</label>
                        </li>
                        <li>
                            <strong>{{ __('Seller Name') }}</strong>
                            <label>{{ $order->seller_name }}</label>
                        </li>
                        <li>
                            <strong>{{ __('Customer Name') }}</strong>
                            <label>{{ $order->customer_name }}</label>
                        </li>
                        <li>
                            <strong>{{ __('Notes') }}</strong>
                            <label>{{ $order->customer_notes }}</label>
                        </li>
                        <li>
                            <strong>{{ __('Status') }}</strong>
                            <span class="badge bg-success">{{ __($order->order_status) }}</span>
                        </li>
                        <li>
                            <strong>{{ __('Proposals Count') }}</strong>
                            <span class="badge bg-info">{{ $order->proposals_count }}</span>
                        </li>
                        
                        <li>
                            @php $instructions = explode(',',$order->insrtuctions)  @endphp
                            <strong>{{ __('instructions') }}</strong>
                            @foreach($instructions as $instruction)
                                <span class="badge bg-warning">{{ $instruction }}</span>
                            @endforeach
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
    .order-datails{
        padding:0px;
    }
    .order-datails li{
        display: flex !important;
        justify-content: space-between;
        background-color: #eee;
        padding: 10px;
        margin-bottom:10px; 
        flex-wrap: wrap;
    }
    #map-tracking{
        height:400px;
    }
</style>
@endpush

@push('footer_script')
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&sensor=false&language=ar"></script>
<script>
    const GoogleLocationAddressAutoComeplete  = {
        map :null,
        InitMap:async function(Data){
            GoogleLocationAddressAutoComeplete.map = await new google.maps.Map(document.getElementById(Data.map), {
                center: Data.location,
                zoom: 17,
                mapTypeControl: false,
            });
        },
        TrackingLocation:async function(Data){
            const map = GoogleLocationAddressAutoComeplete.map;
            const options = {
                fields: ["formatted_address", "geometry", "name"],
                strictBounds: false,
            };

            const markerLocation = new google.maps.Marker({
                position: Data.location,
                map,
                anchorPoint: new google.maps.Point(0, -29),
                draggable: true
            });

            const markerDestination = new google.maps.Marker({
                position: Data.destination,
                map,
                anchorPoint: new google.maps.Point(0, -29),
                draggable: true
            });

            DeliveryPlanCoordinates = [];
            DeliveryPlanCoordinates.push(Data.location);

            await Data.Coordinates.forEach(element => {
                DeliveryPlanCoordinates.push({lat:Number(element.lat),lng:Number(element.long)});
            });

            const deliveryPath = new google.maps.Polyline({
                path: DeliveryPlanCoordinates,
                geodesic: true,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 5,
            });

            deliveryPath.setMap(map);
        },
        AjaxReloadMapLocations:function(){
            let router_ajax = "{{ route('orders.ajax-trackings',':unique_id') }}";
            router_ajax = router_ajax.replace(':unique_id',"{{ $order->unique_id }}");
            $.ajax({
                type: 'POST',
                url: router_ajax,
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: async function( result ) {
                    await GoogleLocationAddressAutoComeplete.TrackingLocation({
                        map:'map-tracking',
                        location:{lat:Number(result.order.location_lat),lng:Number(result.order.location_lng)},
                        destination:{lat:Number(result.order.destination_lat),lng:Number(result.order.destination_lng)},
                        Coordinates:result.order.trackings
                    });
                }
            });
        }
    };
    document.addEventListener('DOMContentLoaded',function(){
        setTimeout(async () => {
            let MapAttr = {
                map:'map-tracking',
                location:{lat:Number("{{ $order->location_lat }}"),lng:Number("{{ $order->location_lng }}")},
                destination:{lat:Number("{{ $order->destination_lat }}"),lng:Number("{{ $order->destination_lng }}")},
                Coordinates:{!! json_encode($order->trackings) !!}
            };
            await GoogleLocationAddressAutoComeplete.InitMap(MapAttr);
            await GoogleLocationAddressAutoComeplete.TrackingLocation(MapAttr);
        }, 3000);

        setInterval(async () => {
            await GoogleLocationAddressAutoComeplete.AjaxReloadMapLocations();
        },10000);
    });

</script>
@endpush