@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Show Offers')

@section('content')
    <h4>{{ __('Show Offers')}}</h4>
    <div class="row g-4">
        @foreach ($offers as $offer)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body text-center">

                        <div class="mx-auto my-3">
                            <img src="../../assets/img/avatars/47.jpg" alt="Avatar Image" class="rounded-circle w-px-100" />
                        </div>
                        <h4 class="mb-1 card-title">{{ $offer->delivery->name }}</h4>
                        <span class="pb-1">{{ $offer->delivery->unique_id }}</span>
                        <div class="d-flex align-items-center justify-content-center my-3 gap-2">

                            @if($offer->approved == 'approved')
                                <a href="javascript:;"><span class="badge bg-label-primary">{{ __('Selected')}}</span></a>
                            @else
                                <a href="javascript:;"><span class="badge bg-label-warning">{{ __('Not Selected')}}</span></a>
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-around my-3 py-1">
                            <div>
                                <h4 class="mb-0">
                                    @php 
                                        // $distance = LocationServices::distance([
                                        //     'lat' => $offer->delivery
                                        // ],[

                                        // ]);
                                    @endphp
                                    500M
                                </h4>
                                <span>{{ __('Away')}}</span>
                            </div>
                            <div>
                                <h4 class="mb-0">
                                    {{ $offer->delivery->orders_in_holds ?: 0 }}
                                </h4>
                                <span>{{ __('In Hold')}}</span>
                            </div>
                            <div>
                                <h4 class="mb-0">
                                    {{ $offer->delivery->orders_finished ?: 0 }}
                                </h4>
                                <span>{{ __('Finished')}}</span>
                            </div>
                        </div>
                        <form action="{{ route('offers.accept', ['offer' => $offer->id]) }}" method="POST">
                            @csrf
                            @method('PATCH') <!-- Use PATCH method for updating -->
                            <!-- Add hidden input fields for order_id and offer_id -->
                            <input type="hidden" name="order_id" value="{{ $offer->order_id }}">
                            <div class="d-flex align-items-center justify-content-center">
                                @if($offer->status == 'accepted')
                                    @if ($offer->approved == 'approved')
                                        <button type="submit" class="btn btn-primary d-flex align-items-center me-3" disabled>
                                            <i class="ti-xs me-1 ti ti-user-check me-1"></i>{{ __('Accepted')}}
                                        </button>
                                    @elseif($offer->approved == 'unapproved')
                                        <span class="badge bg-danger">{{ __('Refused') }}</span>
                                    @else
                                        <button type="submit" class="btn btn-primary d-flex align-items-center me-3">
                                            <i class="ti-xs me-1 ti ti-user-check me-1"></i>{{ __('Accept')}}
                                        </button>
                                    @endif
                                @elseif($offer->status == 'refused')
                                    <span class="badge bg-danger">{{ __('Refused') }}</span>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
