@extends('templates.backend.main-auth')

@section('content')
<div id="auth">

    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="index.html"><img src="{{ asset('template-backend/assets/compiled/svg/logo.svg') }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title">Daftar Kuy</h1>
                <p class="auth-subtitle mb-5">Masukkan data dirimu</p>

                <form action="{{ route('auth.store') }}" method="POST">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" name="name" class="form-control form-control-xl" placeholder="Name" value="{{ old('name') }}">
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>
                    @error('name')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" name="email" class="form-control form-control-xl" placeholder="Email" value="{{ old('email') }}">
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                    @error('email')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password" class="form-control form-control-xl password" placeholder="Password" minlength="8">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" class="form-control form-control-xl confirm-password" placeholder="Confirm Password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5 btn-register" disabled>Sign Up</button>
                </form>
                <div class="text-center mt-5 text-lg fs-4">
                    <p class='text-gray-600'>Already have an account? <a href="auth-login.html" class="font-bold">Log in</a>.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>
</div>
@endsection