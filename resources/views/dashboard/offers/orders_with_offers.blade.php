@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Offers')

@section('content')
    <h4>{{ __('Offers')}}</h4>

    <div class="card">
        <h5 class="card-header">{{ __('Offers Table')}}</h5>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>

                            <th>#</th>
                            <th>{{ __('Seller Name')}}</th>
                            <th>{{ __('Location')}}</th>
                            <th>{{ __('Customer Name')}}</th>
                            <th>{{ __('Destination')}}</th>
                            <th>{{ __('Number Of Offers')}}</th>
                            <th>{{ __('Show Offers')}}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($ordersWithOffers as $order)
                            <tr>

                                <td>
                                    {{ ($ordersWithOffers->currentPage() - 1) * $ordersWithOffers->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $order->seller_name }}</td>
                                <td>
                                    {{ $order->location_name }}
                                </td>

                                <td>
                                    {{ $order->customer_name }}
                                </td>
                                <td>
                                    {{ $order->destination_name }}
                                </td>
                                <td class="text-center">
                                    <span class="">{{ $order->proposals_count }}</span>
                                </td>
                                <td>
                                    <a c class="btn btn-primary"
                                        href="{{ route('offers.show_for_order', ['order' => $order->id]) }}">
                                        {{ __('Order')}} {{ $order->id }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $ordersWithOffers->links() }}
    </div>
@endsection
