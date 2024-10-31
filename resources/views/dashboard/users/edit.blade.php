@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Edit User')

@section('content')
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h4>{{ __('Edit User')}}</h4>

        <div class="col-12 col-lg-8">
            <!-- Product Information -->
            <div class="card mb-4">
        

                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="ecommerce-product-name">Unique ID </label>
                        <input type="text" class="form-control" id="ecommerce-product-name" placeholder="User Name"
                            name="unique_id" aria-label="Product title" required
                            value="{{ old('unique_code', $user->unique_id) }}" />
                        @error('unique_id')
                            <div style="color: red; font-weight: bold"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="ecommerce-product-name">{{ __('Name')}}</label>
                        <input type="text" class="form-control" id="ecommerce-product-name" placeholder="User Name"
                            name="name" aria-label="Product title" required value="{{ old('name', $user->name) }}" />
                        @error('name')
                            <div style="color: red; font-weight: bold"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="ecommerce-product-name">{{ __('Email')}}</label>
                        <input type="email" class="form-control" id="ecommerce-product-name" placeholder="User Email"
                            name="email" aria-label="User Email" value="{{ old('email', $user->email) }}" required />
                        @error('email')
                            <div style="color: red; font-weight: bold"> {{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->

                </div>
                <button type="submit" class="btn btn-primary mb-2">
                    <span class="ti-xs ti ti-plus me-1"></span>{{ __('Update User')}}
                </button>
            </div>

        </div>
    </form>

@endsection
