@extends('templates.backend.main-sidebar-navbar')

@section('title', 'Role')
@section('heading', 'Role Management')
@section('heading-right')
    <button class="btn btn-success btn-sm btn-add"><i class="fa fa-plus"></i></button>
    <button class="btn btn-dark btn-sm btn-back"><i class="fa fa-angle-left"></i></button>
@endsection

@section('content')
<span class="route">{{ $route }}</span>
{{-- DATA --}}
<div class="row view-data">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="table table-hover table-sm datatable w-100"></table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FORM VIEW --}}
<div class="row view-form">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route("$route.store") }}" method="POST">
                    @csrf
                    <label>Nama Role</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama role...">
                    <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fa fa-paper-plane"></i> Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- FORM EDIT --}}
<div class="row view-edit">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="" method="PUT">
                    @csrf
                    <label>Nama Role</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama role...">
                    <button type="submit" class="btn btn-warning btn-sm mt-2"><i class="fa fa-paper-plane"></i> Edit</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- DETAIL --}}
<div class="row view-detail">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body set-detail">
                <div class="row">
                    <div class="col-6">
                        <b>Nama</b> 
                        <p><span class="detail-name"></span></p>       
                    </div>
                    <div class="w-100"><hr></div>
                    <div class="col-6"> 
                        <p><small><b>Dibuat :</b> <span class="detail-created_on"></span></small></p>     
                    </div>
                    <div class="col-6">
                        <p><small><b>Terakhir di edit :</b> <span class="detail-updated_on"></span></small></p>     
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
