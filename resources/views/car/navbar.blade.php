<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @yield('title')
        </h2>

    </x-slot>

    @section('sidebar')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="row m-3">
                        <div class="col-4">
                          <div id="list-example" class="list-group">
                            <a class="list-group-item list-group-item-action" href="{{ route('car.index') }}">{{ __('Manager Cars') }}</a>
                            {{-- <a class="list-group-item list-group-item-action" href="#list-item-2">User</a>
                            <a class="list-group-item list-group-item-action" href="#list-item-3">Review</a>
                            <a class="list-group-item list-group-item-action" href="#list-item-4">Ads</a> --}}
                            <!-- خيارات المدير -->
                            @if(auth()->user()->role == 'admin')
                            <a class="list-group-item list-group-item-action" href="{{ route('manager.user.index') }}">{{ __('Manager Users') }}</a>
                            <a class="list-group-item list-group-item-action" href="{{ route('manager.ads.index') }}">{{ __('Manager Advertisements') }}</a>
                            <a class="list-group-item list-group-item-action" href="{{ route('review.index') }}">{{ __('Manager Complaints and suggestions') }}</a>
                            <a class="list-group-item list-group-item-action" href="{{ route('car.index') }}">{{ __('Manager Setting') }}</a>


                            @endif
                          </div>
                        </div>
                        <div class="col-8">

                            @yield('content')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @show
</x-app-layout>
