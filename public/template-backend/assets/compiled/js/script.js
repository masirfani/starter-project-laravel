let selectedId = [];
let form       = $(".btn-delete-all").parents("form");

// READ DATA
let datatable = $('.datatable').DataTable({
    pagingType: 'simple',
    responsive: true,
    dom:
    "<'row'<'col-6'lB><'col-6'f>>" +
    "<'row dt-row'<'col-sm-12'tr>>" +
    "<'row'<'col-4'i><'col-8'p>>",
    "language": {
        "info": "Page _PAGE_ of _PAGES_",
        "lengthMenu": "_MENU_ ",
        "search": "",
        "searchPlaceholder": "Cari sesuatu..."
    },
    processing: true,
    serverSide: true,
    ajax: {
        url: $(".route").html(),
        type: 'GET'
    },
    columns: columnConfigs,
    buttons: [
        {
            extend: 'copy',
            text: '<i class="fa fa-copy"></i> Copy',
            titleAttr: 'Copy',
            className: 'btn btn-secondary btn-sm',
        },
        {
            extend: 'excel',
            text: '<i class="fa fa-file-excel"></i> Excel',
            titleAttr: 'Export to Excel',
            className: 'btn btn-success btn-sm',
        },
        {
            extend: 'pdf',
            text: '<i class="fa fa-file-pdf"></i> PDF',
            titleAttr: 'Export to PDF',
            className: 'btn btn-danger btn-sm',
        }
    ]
});

let getAttr = form.attr("action");
form.submit(function(e){
    e.preventDefault();
    ajaxCrud($(this), 'delete');
});
$("body table.datatable").on("click", "tr td:not(:last-child)",function(){
    var rowData = datatable.row($(this).closest('tr')).data();
    var clickedId = rowData ? rowData['id'] : null;
    
    if (clickedId !== null) {
        var index = selectedId.indexOf(clickedId);
    
        if (index === -1) {
            // Add the ID if it's not already in the array
            selectedId.push(clickedId);
            $(this).closest('tr').addClass('selected');
        } else {
            // Remove the ID if it's already in the array
            selectedId.splice(index, 1);
            $(this).closest('tr').removeClass('selected');
        }
        
        form.attr("action", `${getAttr}/${selectedId.join(',')}`);
    }
});

function reload_table(){
    $(".datatable").DataTable().ajax.reload(function(){
        $(this).fadeIn('slow');
        selectedId = [];
    });
}

// BUTTON BACK
$(".btn-back").click(function(){
    showView("data");
});

// BUTTON ADD
$(".btn-add").click(function(){
    showView("form");
});

// SUBMIT FORM / CREATE DATA
$(".view-form form").submit(function(e){
    e.preventDefault();
    ajaxCrud($(this), 'form');
});

// EDIT MOMENT
$("body").on("click", ".btn-edit", function(){
    showView("edit");
    let form = $(this).parents('form');
    let url  = `${$(".route").html()}/${form.data("id")}`;
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
            showMsg(data, status);
        }
    });
});

// SUBMIT EDIT / UPDATE DATA
$(".view-edit form").submit(function(e){
    e.preventDefault();
    ajaxCrud($(this), 'edit');
});

// DELETE MOMENT
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
            ajaxCrud(form, "delete");
        }
    });
});

// DELETE SELECTED
$("body").on("click", ".btn-delete-all", function(){
    if(selectedId.length == 0){
        showMsg('Pilih data dari table dulu!!!', 'info')
    }else{
        let form = $(this).parents("form");
        Swal.fire({
            title: "Apakah anda yakin",
            text: "Ingin menghapus semua data yang terpilih???",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#e74c3c",
            cancelButtonColor: "#34495e",
            confirmButtonText: "Iya",
            cancelButtonText: "Tidak",
        }).then((result) => {
            if (result.isConfirmed) {
                ajaxCrud(form, "delete");
            }
        });
    }
});


// BUTTON DETAIL
$("body").on("click", ".btn-detail", function(){
    showView("detail");
    let form = $(this).parents('form');
    let url  = `${$(".route").html()}/${form.data("id")}`;
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
            showMsg(data, status);
        }
    });
});

function ajaxCrud(form, view){
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
            showMsg(data, status);
            reload_table();
            if (view != "delete") {
                showView("data");
            }
            $(`.view-${view} form`)[0].reset();
            $(`.view-${view} form .is-invalid`).removeClass("is-invalid");
        },
        error: function(data, status, xhr){
            showMsg(data, status);

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

showView("data");
function showView(view) {
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

function showMsg(response, type) {

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
        title: response.message || (response.responseJSON && response.responseJSON.message) || response
    });
    
}

$(window).on('resize', function() {
    datatable.columns.adjust();
});