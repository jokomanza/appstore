@extends('admin.layouts.admin')

@section('recent-apps-card')
    <div class="card">
        <div class="card-header">
            <h4>Recent Apps</h4>
        </div>
        <div class="card-content pb-4">

            @foreach ($recentApps as $app)
                <div onclick="location.href='{{ route('admin.app.show', $app->package_name) }}';"
                     style="cursor: pointer;"
                     class="recent-message d-flex px-4 py-3 ripple">
                    <div class="avatar avatar-lg">
                        <img
                                src="{{ asset("storage/$app->icon_url") }}">
                    </div>
                    <div class="name ms-4">
                        <h5 class="mb-1">{{ $app->name }}</h5>
                        <h6 class="text-muted mb-0">{{ $app->type }}</h6>
                    </div>
                </div>
            @endforeach

            <div class="px-4">
                <a href="{{ route('admin.app.index') }}"
                   class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>
                    Show more</a>
            </div>
        </div>
    </div>
@endsection

@include('base.home')
