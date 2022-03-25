@extends('layouts.app')

@section('content')
    <div class="container">

        <section class="vh-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-12 col-lg-9 col-xl-7">
                        <div class="card shadow-2-strong card-login" style="border-radius: 15px;">
                            <div class="card-body p-4 p-md-5">
                                <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Login Form</h3>
                                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                    {{ csrf_field() }}

                                    <div class="row mb-4">

                                        <div class="col-md-6">

                                            <div class="form-outline">
                                                <label class="form-label" for="registration-number">Registration
                                                    Number</label>
                                                <input type="text" id="registration-number" name="registration_number"
                                                       class="form-control form-control-lg"
                                                       value="{{ old('registration_number') }}" required autofocus/>
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
                                                       class="form-control form-control-lg" required autofocus/>
                                            </div>

                                        </div>

                                        @if ($errors->has('password'))
                                            <p class="text-danger pt-2">{{ $errors->first('password') }}</p>
                                        @endif

                                    </div>


                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="remember"
                                                            {{ old('remember') ? 'checked' : '' }}> Remember Me
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-2">
                                        <input class="btn btn-primary btn-lg" type="submit" value="Submit"/>
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            Forgot Your Password?
                                        </a>
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
