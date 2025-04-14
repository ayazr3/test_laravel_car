@extends('car.navbar')

@section('title')
{{ __('Create car') }}
@endsection

@section('content')
<form method="POST" action="{{ route('car.store') }}"  enctype="multipart/form-data">
    @csrf
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Brand -->
    <div>
        <x-input-label for="brand" :value="__('Brand')" />
        <x-text-input id="brand" class="block mt-1 w-full" type="text" name="brand" :value="old('brand')" required autofocus autocomplete="brand" />
        <x-input-error :messages="$errors->get('brand')" class="mt-2" />
    </div>

    <!-- model -->
    <div>
        <x-input-label for="model" :value="__('Model')" />
        <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model')" required autofocus autocomplete="model" />
        <x-input-error :messages="$errors->get('model')" class="mt-2" />
    </div>

    <!-- year -->
    <div>
        <x-input-label for="year" :value="__('Year')" />
        <x-text-input id="year" class="block mt-1 w-full" type="number" name="year" :value="old('year')" required autofocus autocomplete="year" />
        <x-input-error :messages="$errors->get('year')" class="mt-2" />
    </div>

    <!-- price -->
    <div>
        <x-input-label for="price" :value="__('Price')" />
        <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price')" required autofocus autocomplete="price" />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <!-- currency -->
    <div>
        <x-input-label for="currency" :value="__('Currency')" />
        <x-text-input id="currency" class="block mt-1 w-full" type="text" name="currency" :value="old('currency')" required autofocus autocomplete="currency" />
        <x-input-error :messages="$errors->get('currency')" class="mt-2" />
    </div>

    <!-- description -->
    <div>
        <x-input-label for="description" :value="__('Description')" />
        <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" required autofocus autocomplete="description" />
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <!-- color -->
    <div>
        <x-input-label for="color" :value="__('Color')" />
        <x-text-input id="color" class="block mt-1 w-full" type="color" name="color" :value="old('color')" required autofocus autocomplete="color" />
        <x-input-error :messages="$errors->get('color')" class="mt-2" />
    </div>

    {{-- Image --}}
    <div>
        <x-input-label for="image" :value="__('Image')" />
        <x-text-input type="file" name="images[]" multiple class="block mt-1 w-full"/>
        <x-input-error :messages="$errors->get('image')" class="mt-2" />

    </div>

        <!-- حقل البحث عن الموقع -->
        <div class="mb-3 mt-5">
            <x-input-label for="location-search" :value="__('Location Search')" />
            <x-text-input id="location-search" class="block mt-1 w-full" type="text" placeholder="write location" />

        </div>

        <!-- الخريطة -->
        <div id="map" style="height: 300px; border-radius: 8px; margin-bottom: 15px;"></div>

        <!-- حقول الإحداثيات المخفية -->
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lng" id="lng">


        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4" >
                {{ __('Create') }}
            </x-primary-button>
        </div>
    </form>
    <script>
        // تهيئة الخريطة عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            // إحداثيات افتراضية (الرياض)
            const defaultLat = 24.7136;
            const defaultLng = 46.6753;

            // تهيئة الخريطة
            const map = L.map('map').setView([defaultLat, defaultLng], 13);

            // إضافة طبقة الخريطة العربية
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // إضافة علامة قابلة للسحب
            const marker = L.marker([defaultLat, defaultLng], {
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
            updatePosition({ lat: defaultLat, lng: defaultLng });
        });
    </script>

    <style>
        #map {
            border: 1px solid #ddd;
        }
        .leaflet-container {
            font-family: inherit;
        }
    </style>
@endsection


