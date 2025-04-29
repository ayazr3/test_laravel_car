@extends('car.navbar')

@section('title')
{{ __('Manager Advertisement') }}
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card border-primary mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">{{ ('All Advertisement') }}</h5>
        <a href="{{ route('manager.ads.create') }}" class="btn btn-sm btn-light m-3">
           {{ __('Create New Advertisement') }}
        </a>
        <a href="{{ route('admin.featured-requests') }}" class="btn btn-sm btn-light m-3">
            {{ __('Featured Requests') }}
         </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Picture</th>
                        <th>Full Name</th>
                        <th>URL</th>
                        <th>Hit</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Location</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>procedures</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($advs as $adv)
                    <tr>
                        <td>
                            @if($adv->image)
                                <img src="{{ asset('storage/' . $adv->image) }}" width="60" class="rounded">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td>{{ $adv->fullname }}</td>
                        <td><a href="{{ $adv->url }}" target="_blank">Visit Link</a></td>
                        <td>{{ $adv->hit }}</td>
                        <td>{{ $adv->start_date->format('Y-m-d') }}</td>
                        <td>{{ $adv->end_date->format('Y-m-d') }}</td>
                        <td>
                            @if(is_array($adv->location) && isset($adv->location['lat']))
                            <div class="mini-map"
                                 data-lat="{{ $adv->location['lat'] }}"
                                 data-lng="{{ $adv->location['lng'] }}"
                                 style="width: 100px; height: 80px;">

                            </div>
                            @else
                            <span class="text-muted">No location data</span>
                            @endif
                        </td>
                        <td>{{ $adv->email }}</td>
                        <td>{{ $adv->phone }}</td>


                        <td>
                            {{-- <a href="{{ route('car.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                Edit
                            </a> --}}
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('manager.ads.show', $adv->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                   show
                                </a>

                                <form action="{{ route('admin.ads.toggle', $adv->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $adv->is_public ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $adv->is_public ? 'Hide' : 'show' }}
                                    </button>
                                </form>
                                <a href="{{ route('manager.ads.edit', $adv->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    edit
                                 </a>

                                <form action="{{ route('manager.ads.destroy', $adv->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this user?')">
                                        Delete
                                    </button>
                                </form>
                              </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- إضافة مكتبة Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<style>
    .mini-map {
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .leaflet-container {
        background: transparent !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.mini-map').forEach(mapDiv => {
        const lat = parseFloat(mapDiv.dataset.lat);
        const lng = parseFloat(mapDiv.dataset.lng);

        const map = L.map(mapDiv).setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map);
    });
});
</script>
@endsection
