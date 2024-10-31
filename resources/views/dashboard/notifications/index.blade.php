@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Notifications')

@section('content')
    <h4>{{ __('Notifications')}}</h4>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-tile mb-0">{{ __('Send Notification')}}</h5>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('push-notification') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="ecommerce-product-name">{{ __('title')}}</label>
                    <input type="text" class="form-control" id="address-1" name="title"  aria-label="Product title" value="{{ old('title') }}" required />
                    @error('title')
                        <div style="color: red; font-weight: bold"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ecommerce-product-name">{{ __('body')}}</label>
                    <textarea type="text" class="form-control"
                        name="body" aria-label="Product title" required>{{ old('body') }}</textarea>
                    @error('body')
                        <div style="color: red; font-weight: bold"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ecommerce-product-name">{{ __('Send To')}}</label>
                    <select class="form-control" name="type">
                        <option value="all">{{ __('All') }}</option>
                        <option value="all-company"> {{ __('All Companies') }}</option>
                        <option value="all-delivery">{{ __('All Deliveries') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name  }} ( {{ $user->role }} )</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-success" type="submit">
                    {{ __('Send') }}
                </button>
            </form>
        </div>
    </div>
    <div class="card">
        <h5 class="card-header">{{ __('Notifications')}}</h5>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Body') }}</th>
                        <th>{{ __('send_to') }}</th>
                        <th>{{ __('created at') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($notifications as $notification)
                            @php $filter_body = json_decode($notification->data,true) @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ isset($filter_body['title']) ? $filter_body['title'] : '-' }}</td>
                                <td>{{ isset($filter_body['body']) ? $filter_body['body'] : '-' }}</td>
                                <td>{{ $notification->user_name ? $notification->user_name.' ( ' .$notification->user_role. ') ': '-' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($notification->created_at)->format('Y-m-d') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $notifications->links() }}
    </div>
@endsection
