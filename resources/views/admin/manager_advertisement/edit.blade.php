@extends('car.navbar')

@section('title', __('Edit Advertisement'))

@section('content')
<div class="card border-primary mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">{{ __('Edit Advertisement') }}: {{ $ad->fullname }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('manager.ads.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fullname" class="form-label">{{ __('Full Name') }}</label>
                    <input type="text" class="form-control" id="fullname" name="fullname"
                           value="{{ old('fullname', $ad->fullname) }}" required>
                </div>

                <div class="col-md-6">
                    <label for="url" class="form-label">{{ __('URL') }}</label>
                    <input type="url" class="form-control" id="url" name="url"
                           value="{{ old('url', $ad->url) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="{{ old('email', $ad->email) }}" required>
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label">{{ __('Phone') }}</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                           value="{{ old('phone', $ad->phone) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                           value="{{ old('start_date', $ad->start_date->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6">
                    <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                           value="{{ old('end_date', $ad->end_date->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('Current Image') }}</label>
                    @if($ad->image)
                        <img src="{{ asset('storage/' . $ad->image) }}" class="img-thumbnail mb-2" width="150">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="delete_image" name="delete_image">
                            <label class="form-check-label text-danger" for="delete_image">
                                {{ __('Delete Image') }}
                            </label>
                        </div>
                    @endif
                    <input type="file" class="form-control mt-2" id="image" name="image" accept="image/*">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('Location') }}</label>
                    <div class="input-group mb-3">
                        <input type="text" id="location-search" class="form-control"
                               value="{{ $ad->location['address'] ?? '' }}" placeholder="Search location">
                        <button class="btn btn-outline-secondary" type="button" id="search-location">
                            {{ __('Search') }}
                        </button>
                    </div>
                    <div id="map" style="height: 300px; width: 100%; margin-bottom: 15px;"></div>
                    <input type="hidden" name="lat" id="lat" value="{{ $ad->location['lat'] ?? '' }}">
                    <input type="hidden" name="lng" id="lng" value="{{ $ad->location['lng'] ?? '' }}">
                    <input type="hidden" name="address" id="address" value="{{ $ad->location['address'] ?? '' }}">
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('manager.ads.index') }}" class="btn btn-secondary me-2">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ __('Update Advertisement') }}
                </button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const initialLat = {{ $ad->location['lat'] ?? '24.7136' }};
    const initialLng = {{ $ad->location['lng'] ?? '46.6753' }};
    const map = L.map('map').setView([initialLat, initialLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);

    marker.on('dragend', function(e) {
        updatePosition(e.target.getLatLng());
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updatePosition(e.latlng);
    });

    document.getElementById('search-location').addEventListener('click', function() {
        const query = document.getElementById('location-search').value;
        if (query.length < 3) return;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    map.setView([lat, lon], 15);
                    marker.setLatLng([lat, lon]);
                    updatePosition({lat, lng: lon});
                    document.getElementById('address').value = data[0].display_name;
                }
            });
    });

    function updatePosition(latLng) {
        document.getElementById('lat').value = latLng.lat;
        document.getElementById('lng').value = latLng.lng;
    }
});
</script>
@endsection
