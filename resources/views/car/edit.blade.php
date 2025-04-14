@extends('car.navbar')

@section('title', __('Edit Car'))

@section('content')
<form method="POST" action="{{ route('car.update', $car->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- حقول البيانات الأساسية -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Brand -->
        <div>
            <x-input-label for="brand" :value="__('Brand')" />
            <x-text-input id="brand" class="block mt-1 w-full" type="text"
                          name="brand" value="{{ old('brand', $car->brand) }}" required />
        </div>

        <!-- Model -->
        <div>
            <x-input-label for="model" :value="__('Model')" />
            <x-text-input id="model" class="block mt-1 w-full" type="text"
                          name="model" value="{{ old('model', $car->model) }}" required />
        </div>

        <!-- year -->
        <div>
            <x-input-label for="year" :value="__('Year')" />
            <x-text-input id="year" class="block mt-1 w-full" type="number"
                          name="year" value="{{ old('year', $car->year) }}" required />
        </div>

        <!-- price -->
        <div>
            <x-input-label for="price" :value="__('Price')" />
            <x-text-input id="price" class="block mt-1 w-full" type="number"
                          name="price" value="{{ old('price', $car->price) }}" required />
        </div>

        <!-- Currency -->
        <div>
            <x-input-label for="currency" :value="__('Currency')" />
            <x-text-input id="currency" class="block mt-1 w-full" type="text"
                          name="currency" value="{{ old('currency', $car->currency) }}" required />
        </div>

        <!-- Description -->
        <div>
            <x-input-label for="description" :value="__('Description')" />
            <x-text-input id="description" class="block mt-1 w-full" type="text"
                          name="description" value="{{ old('description', $car->description) }}" required />
        </div>

        <!-- Color -->
        <div>
            <x-input-label for="color" :value="__('Color')" />
            <x-text-input id="color" class="block mt-1 w-full" type="color"
                          name="color" value="{{ old('color', $car->color) }}" required />
        </div>

    </div>

    <!-- معرض الصور الحالية -->
    <div class="mt-6">
        <x-input-label :value="__('Current Images')" />
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-2">
            @foreach ($car->images as $index => $image)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $image) }}"
                         class="w-full h-32 object-cover rounded-lg shadow">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 bg-black bg-opacity-50 transition">
                        <button type="button"
                                class="text-white bg-red-500 rounded-full p-1"
                                onclick="confirmDeleteImage({{ $index }})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="existing_images[]" value="{{ $image }}">
                </div>
            @endforeach
        </div>
    </div>

    <!-- إضافة صور جديدة -->
    <div class="mt-6">
        <x-input-label for="new_images" :value="__('Add New Images')" />
        <x-text-input id="new_images" type="file" name="new_images[]"
                      multiple class="block mt-1 w-full" />
        <p class="text-sm text-gray-500 mt-1">يمكنك اختيار عدة صور مرة واحدة</p>
    </div>

    <!-- ... باقي الحقول مثل الخريطة ... -->

    <!-- حقل البحث عن الموقع -->
    <div class="mb-3 mt-5">
        <x-input-label for="location-search" :value="__('Location Search')" />
        <x-text-input id="location-search" class="block mt-1 w-full" type="text" placeholder="write location" />

    </div>

    <!-- الخريطة -->
    <div id="map" style="height: 300px; border-radius: 8px; margin-bottom: 15px;"></div>

    <!-- حقول الإحداثيات المخفية -->
    <input type="hidden" name="lat" id="lat" value="{{ old('lat', $car->location['lat'] ?? '') }}>
    <input type="hidden" name="lng" id="lng" value="{{ old('lng', $car->location['lng'] ?? '') }}">


    <div class="flex justify-end mt-6">
        <x-primary-button type="submit">
            {{ __('Update Car') }}
        </x-primary-button>
    </div>
</form>

<script>
    function confirmDeleteImage(index) {
        if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
            // يمكنك إضافة AJAX لحذف الصورة هنا أو تركها للكونترولر
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'deleted_images[]';
            input.value = index;
            document.querySelector('form').appendChild(input);
            event.target.closest('.relative').remove();
        }
    }

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
    </style>
@endsection
