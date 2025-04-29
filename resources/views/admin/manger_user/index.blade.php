@extends('car.navbar')

@section('title')
{{ __('Manager User') }}
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card border-primary mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">{{ ('All user') }}</h5>
        <a href="{{ route('manager.user.create') }}" class="btn btn-sm btn-light m-3">
           {{ __('Create New User') }}
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>procedures</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            {{-- @if($user->images)
                            <img src="{{ asset('storage/user'. $user->id  .'/'. $user->images[0]) }}" width="60" class="rounded">
                            @endif --}}
                            @if($user->images && !empty(json_decode($user->images)[0]))
                                <img src="{{ asset('storage/' . json_decode($user->images)[0]) }}" width="60" class="rounded">
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->location }}</td>
                        <td>
                            @if($user->role == 'vendor')
                            <span class="badge bg-success">vendor</span>
                            @else
                            <span class="badge bg-warning text-dark">admin</span>
                            @endif
                        </td>
                        <td>
                            @if($user->status)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            {{-- <a href="{{ route('car.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                Edit
                            </a> --}}
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('manager.user.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                   edit
                                </a>

                                <form action="{{ route('manager.user.toggle-role', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Toggle Role">role</button>
                                </form>
                                <form action="{{ route('manager.user.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $user->status ? 'btn-outline-danger' : 'btn-outline-success' }}" title="Toggle Status">
                                        {{ $user->status ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                                <form action="{{ route('manager.user.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this user?')">
                                        Delete
                                    </button>
                                </form>
                              </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
