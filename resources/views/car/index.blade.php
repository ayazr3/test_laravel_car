{{-- @extends('car.navbar')

@section('title')
{{ __('All car') }}
@endsection

@section('content')
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    <a class="btn btn-secondary justify-content-center" href="{{ route('car.create') }}">Create</a>
    <div class="row">
        @foreach ($cars as $car)
        <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Brand</th>
                <th scope="col">Model</th>
                <th scope="col">Year</th>
                <th scope="col">Price</th>
                <th scope="col">images</th>
                <th scope="col">sold</th>
                <th scope="col">Description</th>
                <th scope="col">color</th>
                <th scope="col">location</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{ $car->id }}</td>
                <td>{{ $car->brand }} </td>
                <td>{{ $car->model }}</td>
                <td>{{ $car->year }}</td>
                <td>{{ $car->price .' '. $car->currency }}</td>
                <td></td>
                <td>{{ $car->sold }}</td>
                <td>{{ $car->description }}</td>
                <td>{{ $car->color }}</td>
                <td></td>
              </tr>
            </tbody>
          </table>
            {{-- <div class="col-md-4 mb-4">

                <div class="card">
                    <img src="{{ asset('storage/'.$car->path) }}" class="card-img-top" >
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('car.edit', $car->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <a href="{{ route('car.show', $car->id) }}" class="btn btn-sm btn-primary">Show</a>
                <form action="{{ route('car.destroy', $car->id) }}" method="POST"onsubmit="return confirm('Are you sure you want to delete this image?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div> --}}
        {{-- @endforeach
    </div> --}}

        {{-- <div class="row">
            @foreach($images as $image)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ asset('storage/'.$image->path) }}" class="card-img-top" alt="{{ $image->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $image->title }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('images.edit', $image->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <a href="{{ route('images.show', $image->id) }}" class="btn btn-sm btn-primary">Show</a>
                    <form action="{{ route('images.destroy', $image->id) }}" method="POST"onsubmit="return confirm('Are you sure you want to delete this image?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            @endforeach
        </div> --}}

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
                <div class="carousel-inner">
                   <div class="carousel carousel-dark slide">
                    @foreach ($car->images as $image)
                        <div class=" ">
                            <img src="{{ asset('storage/' . $image) }}"
                                 class="w-full h-48 object-cover"
                                 alt="{{ $car->brand }} {{ $car->model }}" width="20px"height="20px">
                        </div>
                    @endforeach
                    </div>

                </div>

                <div class="carousel-dots flex justify-center mt-2">
                    @foreach ($car->images as $index)
                        <button class="w-2 h-2 mx-1 rounded-full bg-gray-300"
                                onclick="scrollToImage({{ $index }})"></button>
                    @endforeach
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
                        {{-- <form action="{{ route('car.toggle-sold', $car->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="sold-btn px-4 py-2 rounded-full text-white font-medium {{ $car->sold ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}"
                                onclick="return confirm('هل تريد تغيير حالة السيارة؟')">
                                {{ $car->sold ? '✓ مباعة' : '✗ متاحة' }}
                            </button>
                        </form> --}}
                        <form action="{{ route('car.toggle-sold', $car->id) }}" method="POST" class="inline">
                            @csrf
                            @method('POST') <!-- بعض الأنظمة تفضل PATCH بدل POST -->
                            <button type="submit"
                                    class="px-4 py-2 rounded {{ $car->sold ? 'bg-green-500' : 'bg-red-500' }} text-white"
                                    onclick="return confirm('تغيير حالة السيارة؟')">
                                {{ $car->sold ? '✓ مباعة' : '✗ متاحة' }}
                            </button>
                        </form>
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
//     document.querySelectorAll('form[action*="toggle-sold"]').forEach(form => {
//     form.addEventListener('submit', async (e) => {
//         e.preventDefault();

//         if (!confirm('هل تريد تغيير حالة السيارة؟')) return;

//         try {
//             const response = await fetch(form.action, {
//                 method: 'POST',
//                 headers: {
//                     'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
//                     'Accept': 'application/json'
//                 }
//             });

//             if (response.ok) {
//                 location.reload(); // أو يمكنك استخدام AJAX لتحديث الواجهة فقط
//             }
//         } catch (error) {
//             console.error('Error:', error);
//         }
//     });
// });
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

