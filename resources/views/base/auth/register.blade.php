@extends('layouts.app')

@section('content')
    <div class="container">

        <section class="vh-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-12 col-lg-9 col-xl-7">
                        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                            <div class="card-body p-4 p-md-5">
                                <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Registration Form</h3>
                                <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                                    {{ csrf_field() }}

                                    <div class="row">
                                        <div class="col-md-6 mb-4">

                                            <div class="form-outline">
                                                <label class="form-label" for="name">Name</label>
                                                <input type="text" id="name" name="name"
                                                    class="form-control form-control-lg" value="{{ old('name') }}"
                                                    required autofocus />
                                            </div>

                                            @if ($errors->has('name'))
                                                <p class="text-danger pt-2">{{ $errors->first('name') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">

                                            <div class="form-outline">
                                                <label class="form-label" for="email">Email</label>
                                                <input type="email" id="email" name="email"
                                                    class="form-control form-control-lg" value="{{ old('email') }}"
                                                    required autofocus />
                                            </div>


                                            @if ($errors->has('email'))
                                                <p class="text-danger pt-2">{{ $errors->first('email') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-outline">
                                                <label class="form-label" for="registration-number">Registration
                                                    Number</label>
                                                <input type="text" id="registration-number" name="registration_number"
                                                    class="form-control form-control-lg"
                                                    value="{{ old('registration_number') }}" required autofocus />
                                            </div>

                                            @if ($errors->has('registration_number'))
                                                <p class="text-danger pt-2">{{ $errors->first('registration_number') }}
                                                </p>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">

                                            <div class="form-outline">
                                                <label class="form-label" for="email">Password</label>
                                                <input type="password" id="password" name="password"
                                                    class="form-control form-control-lg" required autofocus />
                                            </div>

                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-outline">
                                                <label class="form-label" for="password-confirmation">Password
                                                    Confirmation</label>
                                                <input type="password" id="password-confirmation"
                                                    name="password_confirmation" class="form-control form-control-lg"
                                                    required autofocus />
                                            </div>

                                        </div>


                                        @if ($errors->has('password'))
                                            <p class="text-danger pt-2">{{ $errors->first('password') }}</p>
                                        @endif

                                    </div>

                                    <div class="mt-4 pt-2">
                                        <input class="btn btn-primary btn-lg" type="submit" value="Submit" />
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
