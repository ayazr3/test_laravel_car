@extends('car.navbar')

@section('title', __('Edit User'))

@section('content')
<div class="card border-primary mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">{{ __('Edit User') }}: {{ $user->name }}</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('manager.user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="{{ old('email', $user->email) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">{{ __('Phone') }}</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                           value="{{ old('phone', $user->phone) }}" required>
                </div>

                {{-- <div class="col-md-6">
                    <label for="location" class="form-label">{{ __('Location') }}</label>
                    <input type="text" class="form-control" id="location" name="location"
                           value="{{ old('location', $user->location) }}" required>
                </div> --}}
            </div>
            <div class="row mb-3">
                <!-- حقل البحث عن الموقع -->
                <div class="mb-3 mt-5">
                    <x-input-label for="location-search" :value="__('Location Search')" />
                    <x-text-input id="location-search" class="block mt-1 w-full" type="text" placeholder="write location" />

                </div>

                <!-- الخريطة -->
                <div id="map" style="height: 300px; border-radius: 8px; margin-bottom: 15px;"></div>

                <!-- حقول الإحداثيات المخفية -->
                <input type="hidden" name="lat" id="lat" value="{{ old('lat', $user->location['lat'] ?? '') }}">
                <input type="hidden" name="lng" id="lng" value="{{ old('lng', $user->location['lng'] ?? '') }}">

            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('Current Role') }}</label>
                    <div class="form-control">
                        @if($user->role == 'admin')
                            <span class="badge bg-warning text-dark">Admin</span>
                        @else
                            <span class="badge bg-success">Vendor</span>
                        @endif
                    </div>
                </div>

                <!-- حقل الصورة -->
                <div class="col-md-6">
                    <label class="form-label">{{ __('Profile Image') }}</label>

                    @if($user->images && !empty(json_decode($user->images)[0]))
                        <div class="mb-3 relative group">
                            <img src="{{ asset('storage/' . json_decode($user->images)[0]) }}"
                                 width="150" class="img-thumbnail rounded-lg shadow mb-2">

                            <div class="flex items-center space-x-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="delete_image" class="form-checkbox text-red-600">
                                    <span class="ml-2 text-red-600">{{ __('Delete Image') }}</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <div class="mt-2">
                        <label for="new_image" class="block text-sm font-medium text-gray-700">
                            {{ __('New Image') }}
                        </label>
                        <input type="file" id="new_image" name="new_image"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('manager.user.index') }}" class="btn btn-secondary me-2">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ __('Update User') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>


    // // تهيئة الخريطة بالإحداثيات الحالية
    document.addEventListener('DOMContentLoaded', function() {
        const initialLat = {{ $car->location['lat'] ?? '24.7136' }};
        const initialLng = {{ $car->location['lng'] ?? '46.6753' }};

        const map = L.map('map').setView([initialLat, initialLng], 13);
        // إضافة طبقة الخريطة العربية
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
    // تعيين القيم الأولية للحقول المخفية
        document.getElementById('lat').value = initialLat;
        document.getElementById('lng').value = initialLng;

            // إضافة علامة قابلة للسحب
            const marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(map);

            // تحديث الإحداثيات عند تحريك العلامة
            marker.on('dragend', function(e) {
                updatePosition(e.target.getLatLng());
            });

            // تحديث الإحداثيات عند النقر على الخريطة
            map.on('click', function(e) {
                marker.setLatLng(e.latLng);
                updatePosition(e.latLng);
            });

            // وظيفة لتحديث حقول الإحداثيات
            function updatePosition(latLng) {
                document.getElementById('lat').value = latLng.lat;
                document.getElementById('lng').value = latLng.lng;
            }

            // البحث عن موقع (استخدام Nominatim API)
            document.getElementById('location-search').addEventListener('change', function() {
                const query = this.value;
                if (query.length < 3) return;

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const lat = parseFloat(data[0].lat);
                            const lon = parseFloat(data[0].lon);
                            map.setView([lat, lon], 15);
                            marker.setLatLng([lat, lon]);
                            updatePosition({ lat, lng: lon });
                        }
                    });
            });

            // تعيين الإحداثيات الافتراضية
            updatePosition({ lat: initialLat, lng: initialLng });
        });
    </script>

    <style>
        #map {
            border: 1px solid #ddd;
        }
        .leaflet-container {
            font-family: inherit;
        }
        /* أنماط إضافية لتحسين واجهة الصور */
        .relative.group:hover {
            transform: scale(1.03);
            transition: transform 0.2s;
        }
        .form-checkbox:checked {
            background-color: #ef4444;
            border-color: #ef4444;
        }
        .relative {
            transition: all 0.2s ease;
        }
    </style>
@endsection
