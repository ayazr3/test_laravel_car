@extends('car.navbar')

@section('title', __('طلبات الإعلانات المميزة'))

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5>طلبات الإعلانات المميزة</h5>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>السيارة</th>
                    <th>البائع</th>
                    <th>السعر</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cars as $car)
                <tr>
                    <td>
                        <a href="{{ route('cars.details', $car->id) }}">
                            {{ $car->brand }} {{ $car->model }} ({{ $car->year }})
                        </a>
                    </td>
                    <td>{{ $car->user->name }}</td>
                    <td>{{ $car->price }} {{ $car->currency }}</td>
                    <td>
                        <form action="{{ route('admin.approve-featured', $car->id) }}"
                              method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">موافقة</button>
                        </form>

                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectModal{{ $car->id }}">
                            رفض
                        </button>

                        <!-- Modal للرفض -->
                        <div class="modal fade" id="rejectModal{{ $car->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.reject-featured', $car->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">رفض طلب الإعلان المميز</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>سبب الرفض</label>
                                                <textarea name="reason" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">إلغاء</button>
                                            <button type="submit" class="btn btn-danger">رفض</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
