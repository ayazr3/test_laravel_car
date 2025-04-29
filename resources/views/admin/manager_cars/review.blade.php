@extends('car.navbar')

@section('title', __('Review Car Ad'))

@section('content')
<div class="card border-primary mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">{{ __('Review Car Advertisement') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h4>{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h4>
                <p><strong>Price:</strong> {{ $car->price }} {{ $car->currency }}</p>
                <p><strong>Color:</strong> {{ $car->color }}</p>
                <p><strong>Description:</strong> {{ $car->description }}</p>
                <p><strong>Seller:</strong> {{ $car->user->name }}</p>
                <p><strong>Contact:</strong> {{ $car->user->email }}</p>
            </div>
            <div class="col-md-6">
                @foreach($car->images as $image)
                    <img src="{{ asset('storage/' . $image) }}" class="img-fluid mb-2" style="max-height: 200px;">
                @endforeach
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-between">
            <form action="{{ route('admin.cars.approve', $car->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Approve</button>
            </form>

            <form action="{{ route('admin.cars.reject', $car->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="admin_notes">Rejection Reason</label>
                    <textarea name="admin_notes" id="admin_notes" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger mt-2">Reject</button>
            </form>
        </div>
    </div>
</div>
@endsection
