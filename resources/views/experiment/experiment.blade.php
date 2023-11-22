@extends('templates.backend.main-sidebar-navbar')

@section('title', 'Experiment')
@section('heading', 'Experiment Management')
@section('heading-right')
    <form action="{{ route("$route.destroy", '') }}" method="POST" class="btn-heading">
        @method('DELETE')
        <button type="button" class="btn btn-info btn-sm btn-detail" data-show="data"><i class="bi bi-info"></i></button>
        <button type="button" class="btn btn-warning btn-sm btn-edit" data-show="data"><i class="bi bi-pencil"></i></button>
        <button type="button" class="btn btn-danger btn-sm btn-delete" data-show="data"><i class="fa fa-trash"></i></button>
        <button type="button" class="btn btn-success btn-sm btn-add " data-show="data"><i class="fa fa-plus"></i></button>
        
        {{-- <button type="button" class="btn btn-success btn-sm " data-show="form"><i class="bi bi-file-excel"></i> Import excel</button> --}}
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
                    <form action="" method="POST" class="row row-gap-2" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama role...">
                        </div>
                        <div class="col-md-6">
                            <label>Agama</label>
                            <select name="religion" class="form-select">
                                <option value="" selected disabled>- Pilih Agama -</option>
                                <option value="islam">islam</option>
                                <option value="kristen">kristen</option>
                                <option value="hindu">hindu</option>
                                <option value="buddha">buddha</option>
                                <option value="konghucu">konghucu</option>
                                <option value="katolik">katolik</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Nilai</label>
                            <input type="number" name="score" class="form-control" placeholder="Masukkan nama role...">
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control" placeholder="Masukkan nama role...">
                        </div>
                        <div class="col-md-6">
                            <label>Foto</label>
                            <input type="file" name="picture" class="form-control" placeholder="Masukkan nama role...">
                        </div>
                        <div class="col-md-6">
                            <label>Alamat</label>
                            <textarea name="address" class="form-control" placeholder="Masukkan alamat"></textarea>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fa fa-paper-plane"></i> Tambah</button>
                        </div>
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
                    <form action="" method="POST" class="row" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama role...">
                        </div>
                        <div class="col-md-6">
                            <label>Agama</label>
                            <select name="religion" class="form-select">
                                <option value="" selected disabled>- Pilih Agama -</option>
                                <option value="islam">islam</option>
                                <option value="kristen">kristen</option>
                                <option value="hindu">hindu</option>
                                <option value="buddha">buddha</option>
                                <option value="konghucu">konghucu</option>
                                <option value="katolik">katolik</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Nilai</label>
                            <input type="number" name="score" class="form-control" placeholder="Masukkan nama role...">
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control" placeholder="Masukkan nama role...">
                        </div>
                        <div class="col-md-6">
                            <label>Foto</label>
                            <input type="file" name="picture" class="form-control" placeholder="Masukkan nama role...">
                            <input type="hidden" name="old_picture" class="form-control" placeholder="Masukkan nama role...">
                        </div>
                        <div class="col-md-6">
                            <label>Alamat</label>
                            <textarea name="address" class="form-control" placeholder="Masukkan alamat"></textarea>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-warning btn-sm mt-2"><i class="bi bi-pencil"></i> Edit</button>
                        </div>
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
                            <p class="ps-1">__name__</p>
                        </div>
                        <div class="col-6">
                            <b>Agama</b>
                            <p class="ps-1">__religion__</p>
                        </div>
                        <div class="col-6">
                            <b>Nilai</b>
                            <p class="ps-1">__score__</p>
                        </div>
                        <div class="col-6">
                            <b>Tanggal</b>
                            <p class="ps-1">__birth_date__</span></p>
                        </div>
                        <div class="col-6">
                            <b>Foto</b>
                            <img class="img-fluid" src="{{ asset('uploads/experiment') }}/__picture__" alt="">
                        </div>
                        <div class="col-6">
                            <b>Alamat</b>
                            <p class="ps-1">__address__</p>
                        </div>
                        <div class="w-100">
                            <hr>
                        </div>
                        <div class="col-6">
                            <p><small><b>Created :</b> __created_on__</small></p>
                        </div>
                        <div class="col-6">
                            <p><small><b>Edited :</b> __updated_on__</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
