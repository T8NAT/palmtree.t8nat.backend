@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Orders')


@section('content')

    <h4>{{ __('Orders')}}</h4>


    <div class="card">
        <h5 class="card-header">{{ __('Orders Table')}}</h5>
        <div class="card-body">

        <form action="{{ route('orders.search') }}" method="post" enctype="multipart/form-data">
            
            @csrf

    
    <div class="mb-3">
                        <input type="text" name="search" class="form-control">
                     
                    </div>

    <button type="submit" class="btn btn-primary mb-2">

               {{ __('Search')}}
                </button>
            
            
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>

                            <th>#</th>
                            <th>{{ __('Seller Name')}}</th>
                            <th>{{ __('Location')}}</th>
                            <th>{{ __('Customer Name')}}</th>
                            <th>{{ __('Destination')}}</th>
                            <th>{{ __('Status')}}</th>
                            <th>{{ __('Unique_id')}}</th>
                            <th>{{ __('Serial No') }}</th>
                            <th>{{ __('Created At')}}</th>
                            <th>{{ __('trackings Orders')}}</th>
                            <th>{{ __('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($orders as $order)
                            <tr>

                                <td>
                                    {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
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

                                @if ($order->order_status == 'Accepted')
                                    <td><span class="badge bg-label-success me-1">{{ $order->order_status }}</span></td>
                                @else
                                    <td><span class="badge bg-label-primary me-1">{{ $order->order_status }}</span></td>

                                @endif
                                <td>
                                    {{ $order->unique_id }}
                                </td>
                                <td>
                                    {{ $order->serialNo ?: '-' }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}
                                </td>
                                <td>
                                    <a href="{{ route('orders.show',$order->unique_id) }}" class="btn btn-danger btn-sm" style="line-height: 1.7em;">
                                        {{ __('track order') }}
                                    </a>
                                </td>
                                <td>
                                    @if(auth()->user()->role == 'admin')
                                        @if($order->selected_delivery_id != null)
                                            <form method="post" action="{{ route('notifiy-order-is-tolate', $order->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm" style="display: inline">
                                                    اشعار بالتاخير
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <form target="_blank" action="{{ route('orders.print-order', $order->unique_id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="ti ti-print me-1"></i>
                                                    {{ __('Print Order')}}
                                                </button>
                                            </form>
                                            <a class="dropdown-item" href="{{ route('orders.edit', $order->id) }}">
                                                <i class="ti ti-pencil me-1"></i>
                                                {{ __('Edit')}}
                                            </a>

                                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                                class="d-inline" id="deleteForm">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item" onclick="confirmDelete()">
                                                    <i class="ti ti-trash me-1"></i>
                                                    {{ __('delete')}}
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- SweetAlert script -->
                                    <script>
                                        function confirmDelete() {
                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'You want to delete this order!',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonColor: '#3085d6',
                                                confirmButtonText: 'Yes, delete it!'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('deleteForm').submit();
                                                }
                                            });
                                        }
                                    </script>
                                </td>
                            </tr>
                        @endforeach




                    </tbody>

                </table>

            </div>

        </div>
        {{ $orders->links() }}

    </div>


@endsection
