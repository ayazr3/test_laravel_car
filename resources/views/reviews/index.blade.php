@extends('car.navbar')

@section('title', 'إدارة الشكاوي والاقتراحات')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">إدارة الشكاوي والاقتراحات</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>البريد الإلكتروني</th>
                            <th>السيارة</th>
                            <th>النص</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $review->email }}</td>
                            <td>
                                @if($review->car)
                                    <a href="{{ route('cars.details', $review->car->id) }}">
                                        {{ $review->car->brand }} {{ $review->car->model }}
                                    </a>
                                @else
                                    <span class="text-muted">عام</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($review->note, 50) }}</td>
                            <td>
                                @if($review->is_public)
                                    <span class="badge bg-success">منشور</span>
                                @else
                                    <span class="badge bg-warning text-dark">غير منشور</span>
                                @endif
                            </td>
                            <td>{{ $review->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('review.toggle-publish', $review->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            {{ $review->is_public ? 'إخفاء' : 'نشر' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('review.destroy', $review->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
