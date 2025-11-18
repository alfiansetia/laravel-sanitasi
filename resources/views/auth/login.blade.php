@extends('layouts.auth')

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">

                <h1 class="auth-title text-center">
                    <img src="{{ asset('images/logo.png') }}" width="100px" alt="Logo">
                </h1>
                <p class="auth-subtitle mb-3 text-center">{{ config('company.footer') }}</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="email" name="email"
                            class="form-control form-control-xl  @error('email') is-invalid @enderror" placeholder="Email"
                            value="{{ old('email') }}" required autofocus>
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password"
                            class="form-control form-control-xl @error('password') is-invalid @enderror"
                            placeholder="Password" required>
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-check form-check-lg d-flex align-items-end">
                        <input class="form-check-input me-2" name="remember" type="checkbox"
                            {{ old('remember') ? 'checked' : '' }} id="flexCheckDefault">
                        <label class="form-check-label text-gray-600" for="flexCheckDefault">
                            Keep me logged in
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-3">Log in</button>
                </form>
                {{-- <div class="text-center mt-5 text-lg fs-4">
                    <p class="text-gray-600">Don't have an account? <a href="auth-register.html" class="font-bold">Sign
                            up</a>.</p>
                    <p><a class="font-bold" href="auth-forgot-password.html">Forgot password?</a>.</p>
                </div> --}}
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>
@endsection
