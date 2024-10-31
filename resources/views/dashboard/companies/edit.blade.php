@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Edit Company')

@section('content')
    <form action="{{ route('companies.update', $company->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h4>{{ __('Edit Company')}}</h4>
        <div class="row">
            <div class="col-12 col-lg-6">
                <!-- Product Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-tile mb-0">{{ __('Company Information')}}</h5>
                    </div>
                    <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="ecommerce-product-name">{{ __('Name')}}</label>
                                <input type="text" class="form-control" id="ecommerce-product-name" 
                                    name="name" aria-label="Product title" value="{{ old('name',$company->user->name) }}" required />
                                @error('name')
                                    <div style="color: red; font-weight: bold"> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="ecommerce-product-name">{{ __('Email')}}</label>
                                <input type="email" class="form-control" id="ecommerce-product-name" 
                                    name="email" aria-label="Company Email" value="{{ old('email',$company->user->email) }}" required />
                                @error('email')
                                    <div style="color: red; font-weight: bold"> {{ $message }}</div>
                                @enderror
                            </div>
                        <!-- Description -->
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-tile mb-0">{{ __('Sub Address')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('address')}}</label>
                            <input type="text" class="form-control" id="address-2" 
                                aria-label="Product title" value="{{ old('address',$company->sub_address) }}" />
                            @error('sub_address')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="address_info">
                            <input type="hidden" id="formated_sub_address" name="sub_address" value="{{ old('sub_address',$company->sub_address) }}"/>
                            <input type="hidden" id="address_sub_lat" name="sub_address_lat" value="{{ old('sub_address_lat',$company->sub_address_lat) }}"/>
                            <input type="hidden" id="address_sub_lng" name="sub_address_lng" value="{{ old('sub_address_lng',$company->sub_address_lng) }}"/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('City')}}</label>
                            <input type="text" class="form-control" id="sub_city" 
                                name="sub_city" aria-label="Product title" value="{{ old('sub_city',$company->sub_city) }}" />
                            @error('city')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('street')}}</label>
                            <input type="text" class="form-control" id="sub_street" 
                                name="sub_street" aria-label="Product title" value="{{ old('sub_street',$company->sub_street) }}" />
                            @error('street')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('neighbourhood')}}</label>
                            <input type="text" class="form-control" id="sub_neighbourhood" 
                                name="sub_neighbourhood" aria-label="Product title" value="{{ old('sub_neighbourhood',$company->sub_neighbourhood) }}" />
                            @error('neighbourhood')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('postal Code')}}</label>
                            <input type="text" class="form-control" id="sub_postalCode" 
                                name="sub_postalCode" aria-label="Product title" value="{{ old('sub_postalCode',$company->sub_postalCode) }}" />
                            @error('sub_postalCode')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <!-- Product Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-tile mb-0">{{ __('Main Address')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('address')}}</label>
                            <input type="text" class="form-control" id="address-1"  aria-label="Product title" value="{{ old('address',$company->address) }}" required />
                            @error('address')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="address_info">
                            <input type="hidden" id="formated_address" name="address" value="{{ old('address',$company->address) }}"/>
                            <input type="hidden" id="address_lat" name="address_lat" value="{{ old('address_lat',$company->address_lat) }}"/>
                            <input type="hidden" id="address_lng" name="address_lng" value="{{ old('address_lng',$company->address_lng) }}"/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('City')}}</label>
                            <input type="text" class="form-control" 
                                name="city" aria-label="Product title" value="{{ old('city',$company->city) }}" required />
                            @error('city')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('street')}}</label>
                            <input type="text" class="form-control"
                                name="street" aria-label="Product title" value="{{ old('street',$company->street) }}" required />
                            @error('street')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('neighbourhood')}}</label>
                            <input type="text" class="form-control"
                                name="neighbourhood" aria-label="Product title" value="{{ old('neighbourhood',$company->neighbourhood) }}" required />
                            @error('neighbourhood')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="ecommerce-product-name">{{ __('postal Code')}}</label>
                            <input type="text" class="form-control" 
                                name="postalCode" aria-label="Product title" value="{{ old('postalCode',$company->postalCode) }}" required />
                            @error('postalCode')
                                <div style="color: red; font-weight: bold"> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mb-2">
            <span class="ti-xs ti ti-plus me-1"></span>{{ __('Update Company')}}
        </button>
    </form>

@endsection


@push('footer_script')
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&sensor=false&language=ar"></script>
<script>
    const GoogleLocationAddressAutoComeplete  = {
        GetAddressLocation:function(Ids){
            let address = document.getElementById(Ids.address_search);
            const options = {
                fields: ["formatted_address", "geometry", "name"],
                strictBounds: false,
            };
            const autocomplete = new google.maps.places.Autocomplete(address, options);
            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

               document.getElementById(Ids.address).value = place.formatted_address;
               document.getElementById(Ids.address_lat).value = place.geometry.location.lat();
               document.getElementById(Ids.address_lng).value = place.geometry.location.lng();

               console.log(place.formatted_address);
            });
        }
    };
    document.addEventListener('DOMContentLoaded',function(){
        setTimeout(async () => {
            GoogleLocationAddressAutoComeplete.GetAddressLocation({
                address_search:'address-1',
                address:'formated_address',
                address_lat:'address_lat',
                address_lng:'address_lng'
            });

            GoogleLocationAddressAutoComeplete.GetAddressLocation({
                address_search:'address-2',
                address:'formated_sub_address',
                address_lat:'address_sub_lat',
                address_lng:'address_sub_lng'
            });
        },3000);
    });
</script>
@endpush