@php
    $configData = Helper::appClasses();
@endphp
 
@extends('layouts/layoutMaster')

@section('title', 'Users')


@section('content')

<h4>{{ __('users') }}</h4>
    <div class="card">
        <h5 class="card-header">{{ __('Users Table') }}</h5>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>

                            <th>#</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('unique id') }}</th>
                            <th>{{ __('created at') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($users as $user)
                            <tr>

                                <td>
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    {{ $user->email }}
                                </td>

                                <td>
                                    {{ $user->unique_id }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}
                                </td>



                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                <i class="ti ti-pencil me-1"></i>
                                                {{ __('Edit') }}

                                            </a>
                                
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" id="deleteForm">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item" onclick="confirmDelete()">
                                                    <i class="ti ti-trash me-1"></i>
                                             {{ __('delete') }}

                                            </form>
                                        </div>
                                    </div>
                                
                                    <!-- SweetAlert script -->
                                    <script>
                                        function confirmDelete() {
                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'You want to delete this user!',
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
        {{ $users->links() }}

    </div>


@endsection
