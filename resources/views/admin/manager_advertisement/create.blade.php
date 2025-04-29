@extends('car.navbar')

@section('title', __('Create New Advertisement'))

@section('content')
<div class="card border-primary mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">{{ __('Create New Advertisement') }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('manager.ads.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fullname" class="form-label">{{ __('Full Name') }}</label>
                    <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                           id="fullname" name="fullname" value="{{ old('fullname') }}" required>
                    @error('fullname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="url" class="form-label">{{ __('URL') }}</label>
                    <input type="url" class="form-control @error('url') is-invalid @enderror"
                           id="url" name="url" value="{{ old('url') }}" required>
                    @error('url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label">{{ __('Phone') }}</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                           id="phone" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="image" class="form-label">{{ __('Advertisement Image') }}</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                           id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('Location') }}</label>
                    <div class="input-group mb-3">
                        <input type="text" id="location-search" class="form-control" placeholder="Search location">
                        <button class="btn btn-outline-secondary" type="button" id="search-location">
                            {{ __('Search') }}
                        </button>
                    </div>
                    <div id="map" style="height: 300px; width: 100%; margin-bottom: 15px;"></div>
                    <input type="hidden" name="lat" id="lat">
                    <input type="hidden" name="lng" id="lng">
                    <input type="hidden" name="address" id="address">
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('manager.ads.index') }}" class="btn btn-secondary me-2">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ __('Create Advertisement') }}
                </button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const defaultLat = 24.7136;
    const defaultLng = 46.6753;
    const map = L.map('map').setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

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
