@extends('layouts.app')

@section('content')
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Show all Apps</h3>
                    <p class="text-subtitle text-muted">Show all apps in Quick App Store.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Alert</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row" id="basic-table">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Table with outer spacing</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <p class="card-text">Using the most basic table up, here’s how
                                    <code>.table</code>-based tables look in Bootstrap. You can use any example
                                    of below table for your table and it can be use with any type of bootstrap
                                    tables.
                                </p>
                                <!-- Table with outer spacing -->
                                <div class="table-responsive">
                                    <table class="table table-lg">
                                        <thead>
                                            <tr>
                                                <th>NAME</th>
                                                <th>RATE</th>
                                                <th>SKILL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-bold-500">Michael Right</td>
                                                <td>$15/hr</td>
                                                <td class="text-bold-500">UI/UX</td>

                                            </tr>
                                            <tr>
                                                <td class="text-bold-500">Morgan Vanblum</td>
                                                <td>$13/hr</td>
                                                <td class="text-bold-500">Graphic concepts</td>

                                            </tr>
                                            <tr>
                                                <td class="text-bold-500">Tiffani Blogz</td>
                                                <td>$15/hr</td>
                                                <td class="text-bold-500">Animation</td>

                                            </tr>
                                            <tr>
                                                <td class="text-bold-500">Ashley Boul</td>
                                                <td>$15/hr</td>
                                                <td class="text-bold-500">Animation</td>

                                            </tr>
                                            <tr>
                                                <td class="text-bold-500">Mikkey Mice</td>
                                                <td>$15/hr</td>
                                                <td class="text-bold-500">Animation</td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Table without outer spacing</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <p class="card-text">Using the most basic table up, here’s how
                                    <code>.table</code>-based tables look in Bootstrap. You can use any example
                                    of below table for your table and it can be use with any type of bootstrap
                                    tables.
                                </p>
                            </div>

                            <!-- Table with no outer spacing -->
                            <div class="table-responsive">
                                <table class="table mb-0 table-lg">
                                    <thead>
                                        <tr>
                                            <th>NAME</th>
                                            <th>RATE</th>
                                            <th>SKILL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-bold-500">Michael Right</td>
                                            <td>$15/hr</td>
                                            <td class="text-bold-500">UI/UX</td>

                                        </tr>
                                        <tr>
                                            <td class="text-bold-500">Morgan Vanblum</td>
                                            <td>$13/hr</td>
                                            <td class="text-bold-500">Graphic concepts</td>

                                        </tr>
                                        <tr>
                                            <td class="text-bold-500">Tiffani Blogz</td>
                                            <td>$15/hr</td>
                                            <td class="text-bold-500">Animation</td>

                                        </tr>
                                        <tr>
                                            <td class="text-bold-500">Ashley Boul</td>
                                            <td>$15/hr</td>
                                            <td class="text-bold-500">Animation</td>

                                        </tr>
                                        <tr>
                                            <td class="text-bold-500">Mikkey Mice</td>
                                            <td>$15/hr</td>
                                            <td class="text-bold-500">Animation</td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    {{-- <div class="container"> --}}
    <h1 class="mt-5 mb-3">Apps</h1>
    <p>This is menu for managing all android apps in CV. Karya Hidup Sentosa Company.</p>
    <a class="btn btn-secondary" href="{{ route('app.create') }}">Create new</a>

    @if ($data->isEmpty())
        <p class="mt-4 text-warning">There no apps to show, add a new one with pressing Create new button.</p>
    @endif
    @foreach ($data->items() as $row)
        <div class="row">
            <div>
                <img src="{{ str_contains($row->icon_url, 'http') ? $row->icon_url : asset("storage/$row->icon_url") }}"
                    width="50" height="50">
            </div>
            <p>Name : {{ $row->name }}</p>
            <p>Package Name : {{ $row->package_name }}</p>
            <div>
                <a class="btn btn-primary" href="{{ url("app/$row->id/") }}">View</a>
            </div>
            <br>
            <br>
        </div>
    @endforeach

    <div class="d-flex justify-content-center">
        {!! $data->links() !!}
    </div>
    {{-- </div> --}}
@endsection
