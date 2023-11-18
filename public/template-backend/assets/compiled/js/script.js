let selectedId = [];
let getAttr = $(".btn-delete").parents("form").attr("action");

// READ DATA
let datatable = $('.datatable').DataTable({
    pagingType: 'simple',
    responsive: true,
    dom:
    "<'row g-0'<'col-12 col-md-6 d-flex gap-1'lB><'col-12 col-md-6'f>>" +
    "<'row dt-row'<'col-sm-12'tr>>" +
    "<'row'<'col-7'i><'col-5'p>>",
    "language": {
        "info": "Page <b>_PAGE_</b> of <b>_PAGES_</b>",
        "infoFiltered": "(<b>Total _MAX_</b>)",
        "lengthMenu": "_MENU_ ",
        "search": "",
        "searchPlaceholder": "Cari sesuatu..."
    },
    lengthMenu: [10, 50, 100, 1000],
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
            text: '<i class="fa fa-copy"></i> <small>Copy</small>',
            titleAttr: 'Copy',
            className: 'btn btn-secondary btn-sm ',
        },
        {
            extend: 'excel',
            text: '<i class="fa fa-file-excel"></i> <small>Excel</small>',
            titleAttr: 'Export to Excel',
            className: 'btn btn-success btn-sm ',
        },
        {
            extend: 'pdf',
            text: '<i class="fa fa-file-pdf"></i> <small>PDF</small>',
            titleAttr: 'Export to PDF',
            className: 'btn btn-danger btn-sm ',
        }
    ]
});


$(".btn-delete").parents("form").submit(function(e){
    e.preventDefault();
    ajaxCrud($(this), 'delete');
});

$("body table.datatable").on("click", "tr td:not(.dt-aksi)",function(){
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

        let dataId = selectedId.join(',');
        
        $(".selected-id").html(dataId);
        $(".btn-delete").parents("form").attr("action", `${getAttr}/${dataId}`);
    }
});

function reloadTable(){
    $(".datatable").DataTable().ajax.reload(function(){
        $(this).fadeIn('slow');
        selectedId = [];
    });
}

// BUTTON MOVE
$(".btn-add").click(function(){
    showView("form");
});
$(".btn-back").click(function(){
    showView("data");
});

// SUBMIT FORM / CREATE DATA
$(".view-form form").submit(function(e){
    e.preventDefault();
    ajaxCrud($(this), 'form');
});

// EDIT MOMENT
$("body").on("click", ".btn-edit", function(){
    if(selectedId.length == 0){
        showMsg('Pilih data dari table dulu!!!', 'info')
    }else if(selectedId.length > 1){
        showMsg('Hanya bisa 1 data yang di edit', 'info')
    }else{
        showView("edit");
        let form = $(this).parents('form');
        let url  = `${$(".route").html()}/${$(".selected-id").html()}`;
        $.ajax({
            url:  url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data, status, xhr) {
    
                data.forEach(function(see, number){
                    Object.keys(see).forEach(function(key){
                        $('.view-edit [name="'+key+'"]').val(see[key])
                    });
                });
    
                $(".view-edit form").attr("action", url);
            },
            error: function(data, status, xhr){
                showMsg(data, status);
            }
        });
    }
    
});

// SUBMIT EDIT / UPDATE DATA
$(".view-edit form").submit(function(e){
    e.preventDefault();
    ajaxCrud($(this), 'edit');
});

// DELETE MOMENT
$("body").on("click", ".btn-delete", function(){
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
let dataDetail = $(".card-detail").html();
$("body").on("click", ".btn-detail", function(){
    if(selectedId.length == 0){
        showMsg('Pilih data dari table dulu!!!', 'info')
    }else{
        showView("detail");
        $(".card-detail").html(dataDetail);
        let form = $(this).parents('form');
        form.attr("action", "")
        let url  = `${$(".route").html()}/${$(".selected-id").html()}`;
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data, status, xhr) {
                $(".card-detail").html("");
                let newDataDetail = "";
                data.forEach(function(see, number){
                    newDataDetail += dataDetail;
                    $(".card-detail").html(newDataDetail);
                });
                data.forEach(function(see, number){
                    Object.keys(see).forEach(function(key){
                        $(".detail-"+key).eq(number).html(see[key]);
                    });
                });
            },
            error: function(data, status, xhr){
                showMsg(data, status);
            }
        });
    }
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
            reloadTable();
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
    $(".loading").remove();
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

                $(":focus").removeAttr("autofocus");
                $(".view-"+view+" form input:visible:not(:hidden):first").focus();
            }    
        });
        $(".view-"+view).show(500);
        
        $(".btn-heading .btn-back").show(500);
        $(".btn-heading button:not(.btn-back)").hide();
    }else{
        $(".btn-heading .btn-back").hide();
        $(".btn-heading button:not(.btn-back)").show(500);

        $(".view-"+view).show(500);
        $(".view-"+view+" .card-body").show(500);
    }
    $(":focus").removeAttr("autofocus");
    $(".view-"+view+" form input:visible:not(:hidden):first").focus();
}

function showMsg(response, type) {

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        padding:10,
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