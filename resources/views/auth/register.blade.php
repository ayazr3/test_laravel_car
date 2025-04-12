 <x-guest-layout>
    <form method="POST" action="{{ route('register') }}"  enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autofocus autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

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
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
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


</x-guest-layout>




