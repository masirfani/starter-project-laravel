@extends('templates.backend.main-sidebar-navbar')

@section('title', 'My Profile')
@section('heading', 'Hello '.auth()->user()->name.", welcome back...")

@push('style')
    <style>
        .edit-picture img{
            width: 200px;
            height: 200px;
        }        
        .edit-picture .avatar-status{
            width: 40px;
            height: 40px;
        }        
        .avatar-status i{
            color: white;
        }
    </style>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('auth.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")

                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-center mb-3">
                            <div class="avatar bg-warning me-3 edit-picture">
                                <img src="{{ asset('uploads/user/'.auth()->user()->picture) }}" alt="profile-photo">
                                <span class="avatar-status bg-warning text-light d-flex justify-content-center">
                                    <i class="fa fa-pencil-alt align-self-center"></i>
                                </span>
                            </div>
                            <input type="file" name="picture" class="input-picture d-none" accept=".png, .jpeg, .jpg">
                        </div>
                        <div class="offset-md-3 col-md-6">
                            <h5 class="font-bold">Name</h5>
                            <input type="text" name="name" class="form-control mb-3" value="{{ old('name') ?? auth()->user()->name }}">
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror

                            <h5 class="font-bold">Email</h5>
                            <input type="email" name="email" class="form-control mb-3" value="{{ old('email') ?? auth()->user()->email }}">
                            @error('email')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror

                            <h5 class="font-bold">New Password</h5>
                            <input type="password" name="password" class="form-control mb-3 password">
                            @error('password')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror

                            <h5 class="font-bold">Confirm New Password</h5>
                            <input type="password" name="confirm_password" class="form-control mb-3 confirm-password">

                            <button class="btn btn-primary btn-register ms-auto w-100 mb-5">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    $(".password, .confirm-password").keyup(function() {
        check_password();
    });

    function check_password() {
        var password = $(".password");
        var confirm_password = $(".confirm-password");
        var btn_register = $(".btn-register");

        if (password.val().length >= 8) {
            password.removeClass("is-invalid");
            password.addClass("is-valid");

            if (confirm_password.val().length > 0) {
                if (password.val() == confirm_password.val()) {
                    confirm_password.addClass("is-valid");
                    confirm_password.removeClass("is-invalid");

                    btn_register.removeAttr("disabled")
                } else {
                    confirm_password.removeClass("is-valid");
                    confirm_password.addClass("is-invalid");

                    btn_register.attr("disabled", "")
                }
            }
        } else {
            btn_register.attr("disabled", "");
            if (password.val().length == 0) {
                btn_register.removeAttr("disabled");
            }
            password.removeClass("is-valid");
            password.addClass("is-invalid");
        }
    }
    $(".edit-picture").click(function(){
        $(".input-picture").click();
    })
</script>
@endpush