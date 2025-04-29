@extends('car.navbar')

@section('title')
{{ __('Ad Details') }}
@endsection

@section('content')
<div class="card border-primary mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Ad Details: {{ $ad->fullname }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>URL:</strong> <a href="{{ $ad->url }}" target="_blank">{{ $ad->url }}</a></p>
                <p><strong>Email:</strong> {{ $ad->email }}</p>
                <p><strong>Phone:</strong> {{ $ad->phone }}</p>
                <p><strong>Status:</strong>
                    <span class="badge {{ $ad->is_public ? 'bg-success' : 'bg-warning' }}">
                        {{ $ad->is_public ? 'Published' : 'Unpublished' }}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                @if($ad->image)
                <img src="{{ asset('storage/' . $ad->image) }}" class="img-fluid rounded mb-3">
                @endif
            </div>
        </div>

        <div class="mt-4">
            <h5>Location</h5>
            <div id="map" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const locationData = @json($ad->location);
    const map = L.map('map').setView([locationData.lat, locationData.lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([locationData.lat, locationData.lng]).addTo(map)
        .bindPopup(locationData.address || 'Ad Location');
});
</script>
@endsection
