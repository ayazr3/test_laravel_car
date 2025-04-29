
@extends('car.navbar')

@section('title')
{{ __('Dashbord') }}
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h4 class="mb-0">
            @if(auth()->user()->role == 'admin')
            <span class="badge bg-danger">{{ __('System Administrator') }}</span>
            @elseif(auth()->user()->role == 'vendor')
            <span class="badge bg-primary">بائع</span>
            @endif
             {{ __('Welcome,') }}{{ auth()->user()->name }}
        </h4>
    </div>
    <div class="card-body">
        <!-- إحصائيات البائع -->
        @if(auth()->user()->role == 'vendor' || auth()->user()->role == 'admin')
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Cars on display') }}</h5>
                        <p class="h2 text-primary">{{ auth()->user()->cars()->count() }}</p>
                        <a href="{{ route('car.create') }}" class="btn btn-sm btn-outline-primary">{{ __('Add New Car') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Sold cars') }}</h5>
                        <p class="h2 text-success">{{ auth()->user()->cars()->where('sold', true)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-info">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Available cars') }}</h5>
                        <p class="h2 text-info">{{ auth()->user()->cars()->where('sold', false)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- إحصائيات المدير -->
        @if(auth()->user()->role == 'admin')
        <div class="row mb-4 border-top pt-3">
            <div class="col-md-3">
                <div class="card border-danger">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Total users') }}</h5>
                        <p class="h2 text-danger">{{ \App\Models\User::count() }}</p>
                        <a href="" class="btn btn-sm btn-outline-danger">{{ __('Add New User') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Total cars') }}</h5>
                        <p class="h2 text-warning">{{ \App\Models\Car::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-dark">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Sold cars') }}</h5>
                        <p class="h2 text-dark">{{ \App\Models\Car::where('sold', true)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-secondary">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Vendor') }}</h5>
                        <p class="h2 text-secondary">{{ \App\Models\User::where('role', 'Vendor')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

        <!-- آخر السيارات المضافة (للبائع) -->
        @if(auth()->user()->role == 'vendor')
        <div class="card border-primary mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">آخر السيارات المضافة</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الصورة</th>
                                <th>الموديل</th>
                                <th>السعر</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(auth()->user()->cars()->latest()->take(5)->get() as $car)
                            <tr>
                                <td>
                                    @if($car->images)
                                    <img src="{{ asset('storage/' . $car->images[0]) }}" width="60" class="rounded">
                                    @endif
                                </td>
                                <td>{{ $car->brand }} {{ $car->model }}</td>
                                <td>{{ number_format($car->price) }} {{ $car->currency }}</td>
                                <td>
                                    @if($car->sold)
                                    <span class="badge bg-success">مباعة</span>
                                    @else
                                    <span class="badge bg-warning text-dark">متاحة</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('car.edit', $car->id) }}" class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->role == 'admin')
            <div class="row mb-4 border-top pt-3">
                <!-- الإحصائيات الحالية -->
                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <h5 class="card-title">الشكاوي والاقتراحات</h5>
                            <p class="h2 text-info">{{ \App\Models\Review::count() }}</p>
                            <a href="{{ route('review.index') }}" class="btn btn-sm btn-outline-info">إدارة الشكاوي</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
{{-- <img src="{{ asset('Grey and Black Car Rental Service Logo.png') }}" alt="" class="img-fluid"width="50%" height="50%"> --}}
@endsection




