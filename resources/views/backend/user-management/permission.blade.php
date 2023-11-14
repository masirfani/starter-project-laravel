@extends('templates.backend.main-sidebar-navbar')

@section('title', 'Permission')
@section('heading', 'Permission Management')
@section('heading-right')
    <button class="btn btn-success btn-sm btn-add"><i class="fa fa-plus"></i></button>
    <button class="btn btn-dark btn-sm btn-back"><i class="fa fa-angle-left"></i></button>
@endsection

@push('style')
    <style>
        .datatable tr th:last-child,th:first-child{
            width: 1%!important;
            white-space: nowrap!important;
        }

        div.dataTables_wrapper div.dataTables_paginate ul.pagination {
            justify-content: end!important;
        }
        div.dataTables_wrapper div.dataTables_length, div.dataTables_wrapper div.dataTables_info, div.dataTables_wrapper div.dataTables_paginate{
            text-align: start!important;
        }
        div.dataTables_wrapper div.dataTables_filter{
            text-align: end!important;
        }

        .view-form{
            display: none;
        }
        .view-detail{
            display: none;
        }
        .view-edit{
            display: none;
        }

        .btn-back{
            display: none;
        }
    </style>
@endpush

@section('content')
<div class="row view-data">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="table table-hover table-sm datatable w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row view-form">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route("$route.store") }}" method="POST">
                    @csrf
                    <label>Nama Permission</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama permission...">
                    <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fa fa-paper-plane"></i> Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row view-edit">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="" method="PUT">
                    @csrf
                    <label>Nama Permission</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama permission...">
                    <button type="submit" class="btn btn-warning btn-sm mt-2"><i class="fa fa-paper-plane"></i> Edit</button>
                </form>
            </div>
        </div>
    </div>
</div>
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

@push('script')
    <script>
        show_view("data");
        function show_view(view) {
            $(".view-data").hide();
            $("[class*=view-]").hide();
            if(view != "data"){
                $(document).on({
                    ajaxStart: function() { 
                        $(".view-"+view+" .card-body").hide();
                        $(".view-"+view+" .card").append(`
                            <div class="card-body loading">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        `);
                    },
                    ajaxStop: function() { 
                        $(".loading").remove();
                        $(".view-"+view+" .card-body").show();
                    }    
                });
                $(".view-"+view).show(500);
            }else{
                $(".view-"+view).show(500);
            }
            if ($(".view-data").is(":hidden")) {
                $(".btn-add").hide();
                $(".btn-back").show(500);
            }else{
                $(".btn-add").show(500);
                $(".btn-back").hide();
            }
        }

        function ajax_crud(form, view){
            $(".error-message").remove();
            $.ajax({
                url:  form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(data, status, xhr) {
                    $('#contentData').html(data);
                    show_msg(data, status);
                    reload_table();
                    console.log("data");
                    if (view != "delete") {
                        show_view("data");
                    }
                    $(`.view-${view} form`)[0].reset();
                },
                error: function(data, status, xhr){
                    show_msg(data, status);

                    if (data.responseJSON.errors != null) {
                        Object.keys(data.responseJSON.errors).forEach(function (see) {
                            var errorMessages = data.responseJSON.errors[see];

                            var pesan = `
                                <p class="error-message text-danger mb-0">${errorMessages}</p>
                            `;
                            $(`.view-${view} [name="${see}"]`).addClass("is-invalid"); 
                            $(`.view-${view} [name="${see}"]`).after(pesan); 
                        });
                    }
                }
            });
        }

        function show_msg(response, type) {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type,
                title: response.message ?? response.responseJSON.message
            });
            
        }

        function reload_table(){
            $(".datatable").DataTable().ajax.reload(function(){
                $(this).fadeIn('slow');
            });
        }

        $(".btn-add").click(function(){
            show_view("form");
        });

        $(".btn-back").click(function(){
            show_view("data");
        });

        $(".view-form form").submit(function(e){
            e.preventDefault();
            ajax_crud($(this), 'form');
        });
        
        $(".view-edit form").submit(function(e){
            e.preventDefault();
            ajax_crud($(this), 'edit');
        });


        var columnConfigs = {!! $config_table !!};
        $('.datatable').DataTable({
            pagingType: 'simple',
            responsive: true,
            dom:
                "<'row'<'col-3'l><'col-9'f>>" +
                "<'row dt-row'<'col-sm-12'tr>>" +
                "<'row'<'col-4'i><'col-8 justify-content-end'p>>",
            "language": {
                "info": "Page _PAGE_ of _PAGES_",
                "lengthMenu": "_MENU_ ",
                "search": "",
                "searchPlaceholder": "Cari sesuatu..."
            },
            processing: true,
            serverSide: true,
            ajax: {
                url:  "{{ route("$route.index") }}",
                type: 'GET'
            },
            columns: columnConfigs
        });

        $("body").on("click", ".btn-delete", function(){
            let form = $(this).parents("form");
            Swal.fire({
                title: "Apakah anda yakin",
                text: "Ingin menghapus data ini???",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e74c3c",
                cancelButtonColor: "#34495e",
                confirmButtonText: "Iya",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    ajax_crud(form, "delete");
                }
            });
        });

        $("body").on("click", ".btn-edit", function(){
            show_view("edit");
            let form = $(this).parents('form');
            let url  = `${form.data("action")}/${form.data("id")}`;
            $.ajax({
                url:  url,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(data, status, xhr) {

                    Object.keys(data).forEach(function(key){
                        $('.view-edit [name="'+key+'"]').val(data[key])
                    });

                    $(".view-edit form").attr("action", url);
                },
                error: function(data, status, xhr){
                    show_msg(data, status);
                }
            });
        });

        $("body").on("click", ".btn-detail", function(){
            show_view("detail");
            let form = $(this).parents('form');
            let url  = `${form.data("action")}/${form.data("id")}`;
            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(data, status, xhr) {
                    Object.keys(data).forEach(function(key){
                        $(".detail-"+key).html(data[key]);
                    });
                },
                error: function(data, status, xhr){
                    show_msg(data, status);
                }
            });
        });

        $(window).on('resize', function() {
            $('.datatable').columns.adjust();
        });
    </script>
@endpush
