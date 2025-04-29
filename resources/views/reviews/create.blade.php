<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            @if($car)
                                شكوى بخصوص سيارة {{ $car->brand }} {{ $car->model }}
                            @else
                                تقديم شكوى أو اقتراح عام
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('review.store') }}">
                            @csrf

                            @if($car)
                                <input type="hidden" name="id_car" value="{{ $car->id }}">
                            @endif

                            <div class="mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">
                                    @if($car)
                                        الشكوى أو الملاحظات
                                    @else
                                        الاقتراح أو الشكوى
                                    @endif
                                </label>
                                <textarea class="form-control @error('note') is-invalid @enderror"
                                        id="note" name="note" rows="5" required>{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    @if($car)
                                        إرسال الشكوى
                                    @else
                                        إرسال الاقتراح
                                    @endif
                                </button>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">رجوع</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
