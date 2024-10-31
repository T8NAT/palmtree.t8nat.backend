@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Edit Company')

@section('content')
    <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <h4>{{ __('Edit Orders')}}</h4>
            <div class="col-12 col-lg-8">
                <!-- Product Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-tile mb-0">{{ __('Order Information')}}</h5>
                    </div>
    
                    <div class="card-body">
                        @if(auth()->user()->role == 'admin')
                            <div class="mb-3">
                                <strong class="form-label" for="ecommerce-product-name">{{ __('Company Name')}}</strong>
                                <select id="defaultSelect" class="form-select" name="company_id">
                                    <option>{{ __('Select Company Name')}}</option>
        
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $company->id == $order->company_id ? 'selected' : '' }}>
                                            {{ $company->user->name }}
                                        </option>
                                    @endforeach
                                </select>
        
                                @error('company_id')
                                    <div style="color: red; font-weight: bold"> {{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <div class="mb-3">
                            <strong class="form-label" for="ecommerce-product-name">{{ __('Seller Name')}}</strong>
                            <input type="text" class="form-control" id="ecommerce-product-name" placeholder="Seller Name"
                                name="seller_name" aria-label="Product title" required
                                value="{{ old('seller_name', $order->seller_name) }}" />
                            @error('seller_name')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            @php  $insrtuctions = explode(',',$order->insrtuctions) @endphp
                            <strong class="form-label" for="ecommerce-product-name">{{ __('Order instructions')}}</strong>
                            <ul style="list-style: none;padding-right: 11px;">
                                <li style="padding: 5px;">
                                    <input type="checkbox" name="insrtuctions[]" value="shipment_arrived_at_the_sorting_area" @if(in_array('shipment_arrived_at_the_sorting_area',$insrtuctions)) checked @endif/>
                                    <label>وصلت الشحنه لمنطقه الفرز</label>
                                </li>
                                <li style="padding: 5px;">
                                    <input type="checkbox" name="insrtuctions[]" value="shipment_left_the_sorting_area" @if(in_array('shipment_left_the_sorting_area',$insrtuctions)) checked @endif/>
                                    <label>غادرت الشحنه منطقه الفرز</label>
                                </li>
                                <li style="padding: 5px;">
                                    <input type="checkbox" name="insrtuctions[]" value="shipment_is_on_its_way_to_the_customer" @if(in_array('shipment_is_on_its_way_to_the_customer',$insrtuctions)) checked @endif/>
                                    <label>الشحنة في طريقها للعميل</label>
                                </li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <strong class="form-label" for="ecommerce-product-name">{{ __('Location On Map')}}</strong>
                            <input type="text" class="form-control" id="location" 
                                name="location" aria-label="Product title"/>
                            @error('location_lat')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                            @error('location_lng')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                            <input type="hidden" id="location_lat" name="location_lat" value="{{ $order->location_lat }}" required/>
                            <input type="hidden" id="location_lng" name="location_lng" value="{{ $order->location_lng }}" required/>
                            <input type="hidden" id="location_full_address" name="location_full_address" value="{{ $order->location_full_address }}"/>
                            <div id="map-location" style="height:300px"></div>
                        </div>
                        <div class="mb-3">
                            <strong class="form-label" for="ecommerce-product-name">{{ __('Location Name')}}</strong>
                            <input type="text" class="form-control" id="location_name"
                                name="location_name" aria-label="Product title" value="{{ $order->location_name ?: old('location_name') }}" required />
                            @error('location_name')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <strong class="form-label" for="ecommerce-product-name">{{ __('Customer Name')}}</strong>
                            <input type="text" class="form-control" id="ecommerce-product-name" placeholder="Customer Name"
                                name="customer_name" aria-label="Product title"
                                value="{{ old('customer_name', $order->customer_name) }}" required />
                            @error('customer_name')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('Customer Phone')}}</label>
                            <input type="text" class="form-control" id="ecommerce-product-name"
                                name="customer_phone" aria-label="Product title" value="{{ $order->customer_phone ?: old('customer_phone') }}" required />
                            @error('customer_phone')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <strong class="form-label" for="ecommerce-product-name">{{ __('Destination')}}</strong>
                            <input type="text" class="form-control" id="destination" 
                                name="destination" aria-label="Product title" value=""/>
                            @error('destination_lat')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                            @error('destination_lng')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                            <input type="hidden" id="destination_lat"  name="destination_lat" value="{{ $order->destination_lat }}" required/>
                            <input type="hidden" id="destination_lng"  name="destination_lng" value="{{ $order->destination_lng }}" required/>
                            <input type="hidden" id="destination_full_address" name="destination_full_address" value="{{ $order->destination_full_address }}"/>
                            <div id="map-destination" style="height:300px"></div>
                        </div>
                        <div class="mb-3">
                            <strong class="form-label" for="ecommerce-product-name">{{ __('Destination Name')}}</strong>
                            <input type="text" class="form-control" id="destination_name"
                                name="destination_name" aria-label="Product title" value="{{  $order->destination_name ?: old('destination_name') }}" required />
                            @error('destination')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <strong class="form-label" for="ecommerce-product-name">{{ __('Notes')}}</strong>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Notes">{{ $order->customer_notes }}</textarea>
                            @error('customer_notes')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <strong class="form-label" for="ecommerce-product-name">{{ __('Attachments')}}</strong>
                            <input type="file" name="attachment" class="form-control" id="inputGroupFile01" />
    
                            @if ($order->attachment)
                                <div class="mb-3">
                                    <strong class="form-label">Current Attachment:</strong>
                                    <a href="{{ asset('storage/attachments/' . $order->attachment) }}"
                                        target="_blank">{{ $order->attachment }}</a>
                                </div>
                            @endif
                            @error('attachment')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">
                        <span class="ti-xs ti ti-plus me-1"></span>{{ __('update New Order')}}
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>اختيار المندوبين</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    يتم اختيار المندوب وفقا للمسافة بينة و بين مكان و اتجاة توصيل الشحنة
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="select-delivery">
                                    <h5>تحديد مندوبين معيينين</h5>
                                    @php $selected_delevires_arr = json_decode($order->selected_deliveries,true) ?: [] @endphp
                                    <select class="js-example-basic-single" name="selected_deliveries[]" multiple="multiple">
                                        @foreach($deliveries as $delivery)
                                            <option value="{{ $delivery->id }}" @if(in_array($delivery->id,$selected_delevires_arr)) selected @endif>{{ $delivery->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('style')
<style>
     .select-delivery{
        padding: 20px 0px;
    }
    .select-delivery .select2-container{
        width: 100% !important;
    }
</style>    
@endpush
@push('footer_script')
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&sensor=false&language=ar"></script>
<script async defer src="https://unpkg.com/google-maps-current-location"></script>
<script>
    // In your Javascript (external .js resource or <script> tag)
    jQuery('.js-example-basic-single').select2();
</script>
<script>
    const GoogleLocationAddressAutoComeplete  = {
        GetAddressLocation:function(Ids,CurrentLocation){
            const map = new google.maps.Map(document.getElementById(Ids.map), {
                center: { lat: Number(CurrentLocation.lat) || 24.774265, lng: Number(CurrentLocation.lng) || 46.738586 },
                zoom: 15,
                mapTypeControl: true,
            });
            addCurrentLocation(map);
            let address = document.getElementById(Ids.address_search);
            const options = {
                fields: ["formatted_address", "geometry", "name"],
                strictBounds: false,
            };
            const autocomplete = new google.maps.places.Autocomplete(address, options);

            const marker = new google.maps.Marker({
                position:{lat:Number(CurrentLocation.lat), lng:Number(CurrentLocation.lng)},
                map,
                anchorPoint: new google.maps.Point(0, -29),
            });

            // bounds option in the request.
            autocomplete.bindTo("bounds", map);
            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                map.setCenter(place.geometry.location);
                map.setZoom(15);

                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

               document.getElementById(Ids.address).value = place.formatted_address;
               document.getElementById(Ids.address_lat).value = place.geometry.location.lat();
               document.getElementById(Ids.address_lng).value = place.geometry.location.lng();

              console.log(place.formatted_address);
            });

            google.maps.event.addListener(map, "center_changed", function(){
                var center = this.getCenter();
                var latitude = center.lat();
                var longitude = center.lng();
                // Use the center coordinates to retrieve place information
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'location': center }, function(results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                            // The 'results' array contains information about the place
                            var place = results[0];

                            // Access the desired place details
                            // var placeName = place.formatted_address;
                            // document.getElementById(Ids.address).value = placeName;
                            const premise = place.address_components[1].long_name;
                            document.getElementById(Ids.address).value = premise;
                        }
                    }
                });
                marker.setPosition({ lat: latitude, lng:longitude});
                document.getElementById(Ids.address_lat).value = latitude;
                document.getElementById(Ids.address_lng).value = longitude;
                // document.getElementById(Ids.address_search).value = latitude + ',' + longitude;
            },{marker,Ids});
        }
    };
    document.addEventListener('DOMContentLoaded',function(){
        setTimeout(async () => {
            // set location
            GoogleLocationAddressAutoComeplete.GetAddressLocation({
                address_search:'location',
                map:'map-location',
                address:'location_name',
                full_address:'location_full_address',
                address_lat:'location_lat',
                address_lng:'location_lng'
            },{
                lat:"{{ $order->location_lat }}",
                lng:"{{ $order->location_lng }}"
            });

            // set destination
            GoogleLocationAddressAutoComeplete.GetAddressLocation({
                address_search:'destination',
                map:'map-destination',
                full_address:'destination_full_address',
                address:'destination_name',
                address_lat:'destination_lat',
                address_lng:'destination_lng'
            },{
                lat:"{{ $order->destination_lat }}",
                lng:"{{ $order->destination_lng }}"
            });
        },3000);
    });
</script>
@endpush
