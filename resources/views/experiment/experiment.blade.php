@extends('templates.backend.main-sidebar-navbar')

@section('title', 'Experiment')
@section('heading', 'Experiment Management')
@section('heading-right')
    <form action="{{ route("$route.destroy", '') }}" method="DELETE" class="btn-heading">
        <button type="button" class="btn btn-info btn-sm btn-detail" data-show="data"><i class="bi bi-info"></i></button>
        <button type="button" class="btn btn-warning btn-sm btn-edit" data-show="data"><i class="bi bi-pencil"></i></button>
        <button type="button" class="btn btn-danger btn-sm btn-delete" data-show="data"><i class="fa fa-trash"></i></button>
        <button type="button" class="btn btn-success btn-sm btn-add " data-show="data"><i class="fa fa-plus"></i></button>
        
        <button type="button" class="btn btn-success btn-sm " data-show="form"><i class="bi bi-file-excel"></i> Import excel</button>
        <button type="button" class="btn btn-dark btn-sm btn-back " data-show="form,edit,detail"><i class="fa fa-angle-left"></i></button>
    </form>
@endsection

@section('content')
    <span class="route">{{ $route }}</span>
    <span class="selected-id"></span>
    {{-- DATA --}}
    <div class="row g-0 view-data">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body loading">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive p-3 px-3">
                        <table class="table table-hover table-sm datatable w-100"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM VIEW --}}
    <div class="row g-0 view-form">
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
    <div class="row g-0 view-edit">
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
    <div class="row g-0 view-detail">
        <div class="col-md-12 card-detail">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <b>Nama</b>
                            <p class="ps-1"><span class="detail-name"></span></p>
                        </div>
                        <div class="col-6">
                            <b>Agama</b>
                            <p class="ps-1"><span class="detail-religion"></span></p>
                        </div>
                        <div class="w-100">
                            <hr>
                        </div>
                        <div class="col-6">
                            <p><small><b>Created :</b> <span class="detail-created_on"></span></small></p>
                        </div>
                        <div class="col-6">
                            <p><small><b>Edited :</b> <span class="detail-updated_on"></span></small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
