<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $car->brand }} {{ $car->model }} - تفاصيل السيارة</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Leaflet CSS للخريطة -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
        }
        .car-details-container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .carousel-img {
            height: 500px;
            object-fit: cover;
        }
        .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 5px;
            border: none;
            background-color: rgba(0,0,0,0.3);
        }
        .carousel-indicators button.active {
            background-color: #000;
        }
        .carousel-control-prev, .carousel-control-next {
            width: 5%;
            background: rgba(0,0,0,0.2);
            border-radius: 50%;
            margin: 0 10px;
        }
        .specs-list {
            list-style: none;
            padding: 0;
        }
        .specs-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .specs-list li:last-child {
            border-bottom: none;
        }
        .spec-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }
        #map {
            height: 300px;
            border-radius: 8px;
        }
        .contact-btn {
            background: #28a745;
            color: white;
            font-size: 18px;
            padding: 12px 25px;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .contact-btn:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .price-tag {
            font-size: 28px;
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="car-details-container">
        <!-- Carousel للصور -->
        <div id="carImagesCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($car->images as $key => $image)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 carousel-img" alt="صورة السيارة {{ $key + 1 }}">
                </div>
                @endforeach
            </div>

            @if(count($car->images) > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#carImagesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carImagesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>

            <div class="carousel-indicators">
                @foreach($car->images as $key => $image)
                <button type="button" data-bs-target="#carImagesCarousel" data-bs-slide-to="{{ $key }}"
                    class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}"></button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- تفاصيل السيارة -->
        <div class="p-5">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="mb-3">{{ $car->brand }} {{ $car->model }} - {{ $car->year }}</h1>

                    <div class="d-flex align-items-center mb-4">
                        <div class="color-circle me-2" style="background-color: {{ $car->color }}; width: 20px; height: 20px; border-radius: 50%;"></div>
                        <span class="text-muted">{{ $car->color }}</span>
                    </div>

                    <h3 class="mb-3">المواصفات</h3>
                    <ul class="specs-list">
                        <li><span class="spec-label">الماركة:</span> {{ $car->brand }}</li>
                        <li><span class="spec-label">الموديل:</span> {{ $car->model }}</li>
                        <li><span class="spec-label">سنة الصنع:</span> {{ $car->year }}</li>
                        <li><span class="spec-label">الحالة:</span> {{ $car->sold ? 'مباعة' : 'متاحة' }}</li>
                        <li><span class="spec-label">اللون:</span> {{ $car->color }}</li>
                    </ul>

                    <h3 class="mt-4 mb-3">الوصف</h3>
                    <p class="text-muted">{{ $car->description }}</p>
                </div>

                <div class="col-md-4">
                    <div class="bg-light p-4 rounded-3 sticky-top" style="top: 20px;">
                        <div class="price-tag mb-4 text-center">
                            {{ number_format($car->price) }} {{ $car->currency }}
                        </div>

                        <div class="text-center mb-4">
                            <button class="contact-btn">
                                <i class="fas fa-phone-alt me-2"></i> اتصل بالبائع
                            </button>
                        </div>

                        <div class="text-center">
                            <small class="text-muted">تاريخ النشر: {{ $car->created_at->format('Y-m-d') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- خريطة الموقع -->
            <div class="mt-5">
                <h3 class="mb-3">الموقع</h3>
                <div id="map"
                    data-lat="{{ $car->location['lat'] ?? 24.7136 }}"
                    data-lng="{{ $car->location['lng'] ?? 46.6753 }}"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS للخريطة -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // تهيئة الخريطة
        document.addEventListener('DOMContentLoaded', function() {
            const mapEl = document.getElementById('map');
            const lat = parseFloat(mapEl.dataset.lat);
            const lng = parseFloat(mapEl.dataset.lng);

            const map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup('موقع السيارة')
                .openPopup();
        });
    </script>
</body>
</html>
