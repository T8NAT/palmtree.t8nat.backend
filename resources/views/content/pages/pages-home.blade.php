@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span> Dashboard</h4>

        <!-- Card Border Shadow -->
        <div class="row">
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary"><i
                                        class="ti ti-truck ti-md"></i></span>
                            </div>

                            @php 
                            
          // Count of 'Pending' orders
    $pendingCount = \App\Models\Order::where('order_status', 'Pending')->count();

    // Count of 'Returned' orders
    $returnedCount = \App\Models\Order::where('order_status', 'Returned')->count();

    // Total count of orders
    $totalCount = \App\Models\Order::count();

    // Count of 'Delivered' orders
    $deliveredCount = \App\Models\Order::where('order_status', 'Delivered')->count();

    $CanceledCount = \App\Models\Order::where('order_status', 'Canceled')->count();

    $RecievedCount = \App\Models\Order::where('order_status', 'Received by courier')->count();

    $AccepteddCount = \App\Models\Order::where('order_status', 'Accepted')->count();


                            
                            @endphp


                            <h4 class="ms-1 mb-0">{{ $totalCount }}</h4>
                        </div>
                        <p class="mb-1">Total Orders</p>
                     
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-warning"><i
                                        class="ti ti-alert-triangle ti-md"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ $pendingCount }}</h4>
                        </div>
                        <p class="mb-1"> Pending Order</p>
                        <p class="mb-0">
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-warning"><i
                                        class="ti ti-alert-triangle ti-md"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ $AccepteddCount }}</h4>
                        </div>
                        <p class="mb-1"> Accepting Order</p>
                        <p class="mb-0">
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-warning"><i
                                        class="ti ti-alert-triangle ti-md"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{  $RecievedCount }}</h4>
                        </div>
                        <p class="mb-1"> Received by courier </p>
                        <p class="mb-0">
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-warning"><i
                                        class="ti ti-alert-triangle ti-md"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ $CanceledCount }}</h4>
                        </div>
                        <p class="mb-1"> Canceled Order</p>
                        <p class="mb-0">
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-warning"><i
                                        class="ti ti-alert-triangle ti-md"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ $deliveredCount }}</h4>
                        </div>
                        <p class="mb-1"> Delivered Order</p>
                        <p class="mb-0">
                        </p>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-danger"><i
                                        class="ti ti-git-fork ti-md"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ $returnedCount }}</h4>
                        </div>
                        <p class="mb-1">returned Order</p>
                      
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-info"><i class="ti ti-clock ti-md"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ $deliveredCount }}</h4>
                        </div>
                        <p class="mb-1">Delivered Order</p>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p>For more layout options refer <a
            href="{{ config('variables.documentation') ? config('variables.documentation') . '/laravel-introduction.html' : '#' }}"
            target="_blank" rel="noopener noreferrer">documentation</a>.</p>
@endsection
