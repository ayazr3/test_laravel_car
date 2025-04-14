<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="row m-3">
                        <div class="col-4">
                          <div id="list-example" class="list-group">
                            <a class="list-group-item list-group-item-action" href="#list-item-1">Setting</a>
                            <a class="list-group-item list-group-item-action" href="#list-item-2">User</a>
                            <a class="list-group-item list-group-item-action" href="#list-item-3">Review</a>
                            <a class="list-group-item list-group-item-action" href="#list-item-4">Ads</a>
                          </div>
                        </div>
                        <div class="col-8">
                            <img src="{{ asset('Grey and Black Car Rental Service Logo.png') }}" alt="" class="img-fluid"width="50%" height="50%">

                          {{-- <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" class="scrollspy-example" tabindex="0">
                            <h4 id="list-item-1">Item 1</h4>
                            <p>...</p>
                            <h4 id="list-item-2">Item 2</h4>
                            <p>...</p>
                            <h4 id="list-item-3">Item 3</h4>
                            <p>...</p>
                            <h4 id="list-item-4">Item 4</h4>
                            <p>...</p> --}}
                          </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
