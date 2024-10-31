@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Create Order')

@section('content')
    <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <h4>{{ __('Create Order')}}</h4>
            <div class="col-12 col-lg-8">
                <!-- Product Information -->
                <div class="card mb-4">
                    <div class="card-body">
                        @if(auth()->user()->role == 'admin')
                            <div class="mb-3">
                                <label class="form-label" for="ecommerce-product-name">{{ __('Company Name')}}</label>
                                <select id="defaultSelect" class="form-select" name="company_id" >
                                    <option>{{ __('Select Company Name')}}</option>
        
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"> {{ $company->user->name }}</option>
                                    @endforeach
        
                                </select>
                                @error('company_id')
                                    <div style="color: red; font-weight: bold"> {{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('Seller Name')}}</label>
                            <input type="text" class="form-control" id="ecommerce-product-name" 
                                name="seller_name" aria-label="Product title" value="{{ old('seller_name') }}" required />
                            @error('seller_name')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('Location On Map')}}</label>
                            <input type="text" class="form-control" id="location" 
                                name="location" aria-label="Product title" value="{{ old('location') }}"/>
                            @error('location')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                            <input type="hidden" id="location_lat" name="location_lat" required/>
                            <input type="hidden" id="location_lng" name="location_lng" required/>
                            <input type="hidden" id="location_full_address" name="location_full_address"/>
                            <div id="map-location" style="height:300px"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('Location Name')}}</label>
                            <input type="text" class="form-control" id="location_name"
                                name="location_name" aria-label="Product title" value="{{ old('location_name') }}" required />
                            @error('location')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('Customer Name')}}</label>
                            <input type="text" class="form-control" id="ecommerce-product-name"
                                name="customer_name" aria-label="Product title" value="{{ old('customer_name') }}" required />
                            @error('customer_name')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('Customer Phone')}}</label>
                            <input type="text" class="form-control" id="ecommerce-product-name"
                                name="customer_phone" aria-label="Product title" value="{{ old('customer_phone') }}" required />
                            @error('customer_phone')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('Destination On Map')}}</label>
                            <input type="text" class="form-control" id="destination" 
                                name="destination" aria-label="Product title" value="{{ old('destination') }}"/>
                            @error('destination')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                            <input type="hidden" id="destination_lat"  name="destination_lat" required/>
                            <input type="hidden" id="destination_lng"  name="destination_lng" required/>
                            <input type="hidden" id="destination_full_address" name="destination_full_address"/>
                            <div id="map-destination" style="height:300px"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('Destination Name')}}</label>
                            <input type="text" class="form-control" id="destination_name"
                                name="destination_name" aria-label="Product title" value="{{ old('destination_name') }}" required />
                            @error('destination')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('Notes')}}</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                            @error('customer_notes')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('Attachments')}}</label>
                            <input type="file" name="attachment" class="form-control" id="inputGroupFile01" />
                            @error('attachments')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Description -->
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">
                        <span class="ti-xs ti ti-plus me-1"></span>{{ __('Add New Order')}}
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
                                    <select class="js-example-basic-single" name="selected_deliveries[]" multiple="multiple">
                                        <option value>اختيار المندوبين</option>
                                        @foreach ($deliveries as $delivery)
                                            <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h5>{{ __('Number Orders')}}</h5>
                                <input type="text" value="1" class="form-control" id="destination_name"
                                    name="no_orders" aria-label="Product title" value="{{ old('no_orders') }}" required />
                                @error('no_orders')
                                    <div style="color: red; font-weight: bold"> {{ $message }}</div>
                                @enderror
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
    .lists-card li{
        padding: 0px 0px 0px 40px;
    }
    .ManualLocation{
        display:none;
    }
   
</style>
@endpush

@push('footer_script')
<script>
    // In your Javascript (external .js resource or <script> tag)
    jQuery('.js-example-basic-single').select2();
    jQuery('.choiceHOWToArrive').change(function(){
        let TypeChoiced = jQuery(this).val();
        if(TypeChoiced == 'ManualLocation'){
            jQuery(`.${TypeChoiced}`).css('display','block');
            jQuery(`.automaticLocation`).css('display','none');
            console.log(TypeChoiced);
        }
        else if(TypeChoiced == 'automaticLocation'){
            jQuery(`.${TypeChoiced}`).css('display','block');
            jQuery(`.ManualLocation`).css('display','none');
            console.log(TypeChoiced);
        }
    });
    
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&sensor=false&language=ar"></script>
<script async defer src="https://unpkg.com/google-maps-current-location"></script>
<script>
    const GoogleLocationAddressAutoComeplete  = {
        GetAddressLocation:function(Ids){
            const map = new google.maps.Map(document.getElementById(Ids.map), {
                center: { lat: 24.774265, lng: 46.738586 },
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
               document.getElementById(Ids.full_address).value = place.formatted_address;
               document.getElementById(Ids.address_lat).value = place.geometry.location.lat();
               document.getElementById(Ids.address_lng).value = place.geometry.location.lng();

              console.log(place.formatted_address);
            });

            google.maps.event.addListener(map, "center_changed",function(){
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
                            var placeName = place.formatted_address;
                            document.getElementById(Ids.full_address).value = placeName;
                            const premise = place.address_components[1].long_name;
                            document.getElementById(Ids.address).value = premise;
                            console.log("Premise:", premise);
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
            await GoogleLocationAddressAutoComeplete.GetAddressLocation({
                address_search:'location',
                map:'map-location',
                full_address:'location_full_address',
                address:'location_name',
                address_lat:'location_lat',
                address_lng:'location_lng'
            });
    
            // set destination
            await GoogleLocationAddressAutoComeplete.GetAddressLocation({
                address_search:'destination',
                map:'map-destination',
                full_address:'destination_full_address',
                address:'destination_name',
                address_lat:'destination_lat',
                address_lng:'destination_lng'
            });
        }, 3000);
    });
</script>
@endpush