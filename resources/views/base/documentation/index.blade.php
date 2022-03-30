<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Documentations</h3>
                <p class="text-subtitle text-muted">Display all Documentations.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    @yield('breadcrumb')
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>Android Development Standard</h4>
            </div>
            <div class="card-body">

                @if(File::exists(public_path('storage/android_development_standard.pdf')))

                    <iframe src="{{ asset('/storage/android_development_standard.pdf') }}" width="100%"
                            height="900px"></iframe>

                @else
                    <p>Currently there is no Development Standard</p>
                @endif

            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Android Development Guide</h4>
            </div>
            <div class="card-body">

                @if(File::exists(public_path('storage/android_development_guide.pdf')))

                    <iframe src="{{ asset('/storage/android_development_guide.pdf') }}" width="100%"
                            height="900px"></iframe>

                @else
                    <p>Currently there is no Development Guide</p>
                @endif


            </div>
        </div>

    </section>
</div>