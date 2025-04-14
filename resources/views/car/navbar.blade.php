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
                            <a class="list-group-item list-group-item-action" href="{{ route('car.index') }}">Manager Car</a>
                            {{-- <a class="list-group-item list-group-item-action" href="#list-item-2">User</a>
                            <a class="list-group-item list-group-item-action" href="#list-item-3">Review</a>
                            <a class="list-group-item list-group-item-action" href="#list-item-4">Ads</a> --}}
                          </div>
                        </div>
                        <div class="col-8">

                            @yield('content')
                        </div>
                    </div>
                </div>
                 {{-- <button type="button" class="btn btn-secondary justify-content-center">Create</button>
                    <table class="table table-striped">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                          </tr>
                          <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                          </tr>
                          <tr>
                            <th scope="row">3</th>
                            <td>John</td>
                            <td>Doe</td>
                            <td>@social</td>
                          </tr>
                        </tbody>
                      </table> --}}


            </div>
        </div>
    </div>
    @show
</x-app-layout>
