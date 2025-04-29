
        @extends('car.navbar')

@section('title', __('My Cars'))

@section('content')
<div class="mt-6">
    <a href="{{ route('car.create') }}" class="btn btn-secondary bg-green-500 text-black px-4 py-2 rounded hover:bg-green-600">
        {{ __('Add New Car') }}
    </a>
</div>
<div class="container mx-auto p-4">

    <h1 class="text-2xl font-bold mb-6">{{ __('List My Car') }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($cars as $car)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div id="carImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        {{-- @foreach($car->images as $key => $image)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 carousel-img" alt="صورة السيارة {{ $key + 1 }}">
                        </div>
                        @endforeach --}}
                        @foreach($car->images as $key => $image)
                            @if(is_array($image))
                                <img src="{{ asset('storage/' . $image[0]) }}" class="d-block w-100 carousel-img" alt="صورة السيارة {{ $key + 1 }}">
                            @else
                                <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 carousel-img" alt="صورة السيارة {{ $key + 1 }}">
                            @endif
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

                <div class="p-4">
                    <h2 class="text-xl font-semibold">{{ $car->brand }} {{ $car->model }}</h2>
                    <p class="text-gray-600">{{ $car->year }} • {{ $car->price }} {{ $car->currency }}</p>
                    <p class="mt-2 text-sm">{{ Str::limit($car->description, 100) }}</p>

                    <div class="mt-4 flex justify-between">
                        <a href="{{ route('car.edit', $car->id) }}" class="text-blue-600 hover:text-blue-800">
                           {{ __('Update')}}
                        </a>
                        <form action="{{ route('car.destroy', $car->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                {{ __('Delete') }}
                            </button>
                        </form>
                        <form action="{{ route('car.toggle-sold', $car->id) }}" method="POST" class="inline">
                            @csrf
                            @method('POST') <!-- بعض الأنظمة تفضل PATCH بدل POST -->
                            <button type="submit"
                                    class="px-4 py-2 rounded {{ $car->sold ? 'bg-green-500' : 'bg-red-500' }} text-white"
                                    onclick="return confirm('تغيير حالة السيارة؟')">
                                {{ $car->sold ? '✓ مباعة' : '✗ متاحة' }}
                            </button>
                        </form>
                        <div class="mt-2">
                            @if($car->is_featured)
                                <span class="badge bg-success">إعلان مميز</span>
                            @else
                                <form action="{{ route('cars.request-featured', $car->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        طلب إعلان مميز
                                    </button>
                                </form>

                                @if($car->featured_status == 'rejected')
                                    <div class="alert alert-danger mt-2">
                                        <small>سبب الرفض: {{ $car->rejection_reason }}</small>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


</div>

<script>
    function scrollToImage(index) {
        const carousel = document.querySelector('.carousel');
        const items = carousel.querySelectorAll('.carousel-item');
        items[index].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

</script>

<style>
    .carousel {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }
    .carousel-item {
        flex: none;
        scroll-snap-align: start;
        width: 100%;
    }
    .sold-btn {
        transition: all 0.3s ease;
    }
    .sold-btn:hover {
        transform: scale(1.05);
        opacity: 0.9;
    }
</style>
@endsection

