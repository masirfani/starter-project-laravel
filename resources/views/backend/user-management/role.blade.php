@extends('templates.backend.main-sidebar-navbar')

@section('title', 'Role')
@section('heading', 'Role Management')
@section('heading-right')
    <button class="btn btn-success btn-sm add-btn"><i class="fa fa-plus"></i></button>
    <button class="btn btn-dark btn-sm back-btn"><i class="fa fa-angle-left"></i></button>
@endsection

@section('content')
<div class="row view-data">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row view-form">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('role.store') }}" method="POST">
                    @csrf
                    <label>Nama Role</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama role...">
                    <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="fa fa-paper-plane"></i> Tambah</button>
                </form>
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
            $(".view-"+view).show();
            if ($(".view-data").is(":hidden")) {
                $(".add-btn").hide();
                $(".back-btn").show();
            }else{
                $(".add-btn").show();
                $(".back-btn").hide();
            }
        }
        $(".add-btn").click(function(){
            show_view("form");
        });
        $(".back-btn").click(function(){
            show_view("data");
        });

        $(".view-form form").submit(function(e){
            e.preventDefault();
            ajax_crud($(this));
        })

        function ajax_crud(form){
            $(".error-message").remove();
            $.ajax({
                url:  form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                dataType: 'json',
                success: function(data, status, xhr) {
                    $('#contentData').html(data);
                    show_msg(data, status);
                },
                error: function(data, status, xhr){
                    show_msg(data, status);

                    if (data.responseJSON.errors != null) {
                        Object.keys(data.responseJSON.errors).forEach(function (see) {
                            var errorMessages = data.responseJSON.errors[see];

                            var pesan = `
                                <p class="error-message text-danger mb-0">${errorMessages}</p>
                            `;
                            $('.view-form [name="'+see+'"]').after(pesan); 
                            console.log(errorMessages);
                            console.log($('[name="'+see+'"]'));
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

        $('.datatable').DataTable({
            pagingType: 'simple',
            responsive: true,
            dom:
                "<'row'<'col-3'l><'col-9'f>>" +
                "<'row dt-row'<'col-sm-12'tr>>" +
                "<'row'<'col-4'i><'col-8'p>>",
            "language": {
                "info": "Page _PAGE_ of _PAGES_",
                "lengthMenu": "_MENU_ ",
                "search": "",
                "searchPlaceholder": "Search.."
            },
            // processing: true,
            // serverSide: true,
            // ajax: {

            //     type: 'POST',
            //     headers: {
            //         'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            //     }
            // },
            // columns: [
            //     {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            //     {data: 'code', name: 'code'},
            //     {data: 'name', name: 'name'},
            //     {data: 'status', name: 'status'},
            //     {data: 'location', name: 'location'},
            //     {data: 'action', name: 'action'},
            // ]
        });
    </script>
@endpush
