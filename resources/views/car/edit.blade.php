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

    <!-- معرض الصور الحالية مع خيارات الحذف -->
    <div class="mt-6">
        <x-input-label :value="__('Current Images')" />
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-2">
            @foreach ($car->images as $index => $image)
                <div class="flex flex-col items-center">
                    <!-- الصورة -->
                    <div class="relative w-full h-32 mb-2">
                        <img src="{{ asset('storage/' . $image) }}"
                             class="w-full h-full object-cover rounded-lg shadow">
                    </div>

                    <!-- زر الحذف -->
                    <label class="inline-flex items-center cursor-pointer bg-gray-100 px-3 py-1 rounded-lg hover:bg-gray-200 transition">
                        <input type="checkbox" name="deleted_images[]" value="{{ $index }}"
                               class="form-checkbox h-4 w-4 text-red-600 rounded mr-2">
                        <span class="text-sm text-gray-700">حذف الصورة</span>
                    </label>

                    <input type="hidden" name="existing_images[]" value="{{ $image }}">
                </div>
            @endforeach
        </div>
        <p class="text-sm text-gray-500 mt-2">حدد الصور التي تريد حذفها ثم احفظ التعديلات</p>
    </div>
    <!-- إضافة صور جديدة -->
    <div class="mt-6">
        <x-input-label for="new_images" :value="__('Add New Images')" />
        <x-text-input id="new_images" type="file" name="new_images[]"
                      multiple class="block mt-1 w-full" accept="image/*" />
        <p class="text-sm text-gray-500 mt-1">يمكنك اختيار عدة صور مرة واحدة (JPEG, PNG, JPG, GIF)</p>
    </div>


    <!-- حقل البحث عن الموقع -->
    <div class="mb-3 mt-5">
        <x-input-label for="location-search" :value="__('Location Search')" />
        <x-text-input id="location-search" class="block mt-1 w-full" type="text" placeholder="write location" />

    </div>

    <!-- الخريطة -->
    <div id="map" style="height: 300px; border-radius: 8px; margin-bottom: 15px;"></div>

    <!-- حقول الإحداثيات المخفية -->
    <input type="hidden" name="lat" id="lat" value="{{ old('lat', $car->location['lat'] ?? '') }}">
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
    // إضافة تأثير عند اختيار الصور للحذف
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input[name="deleted_images[]"]');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const imageContainer = this.closest('.relative');
                if (this.checked) {
                    imageContainer.style.opacity = '0.7';
                    imageContainer.style.border = '2px solid red';
                } else {
                    imageContainer.style.opacity = '1';
                    imageContainer.style.border = 'none';
                }
            });
        });
    });

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
