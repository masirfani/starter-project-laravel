
let selectedId = [];
let getAttr = $(".btn-delete").parents("form").attr("action");
let dataDetail = $(".card-detail").html();

// READ DATA
var windowHeight = Number($(window).height()) - 270;
let datatable = "";
$(document).ready(function () {
    datatable = $(".datatable").DataTable({
        pagingType: "simple",
        dom:
            "<'row g-0'<'col-12 col-md-6 d-flex gap-1'lB><'col-12 col-md-6'f>>" +
            "<'row dt-row'<'col-sm-12'tr>>" +
            "<'row'<'col-7'i><'col-5'p>>",
        language: {
            info: "Page <b>_PAGE_</b> of <b>_PAGES_</b>",
            infoFiltered: "(<b>Total _MAX_</b>)",
            lengthMenu: "_MENU_ ",
            search: "",
            searchPlaceholder: "Cari sesuatu...",
        },
        lengthMenu: [10, 50, 100, 1000],
        processing: true,
        serverSide: true,
        responsive: true,
        stateSave: true,
        scrollX: true,
        fixedHeader: true,
        scrollCollapse: true,
        scrollY: windowHeight + "px",
        select: "multi",
        ajax: {
            url: $(".route").html(),
            type: "GET",
        },
        columns: columnConfigs,
        buttons: [
            {
                extend: "copy",
                text: '<i class="fa fa-copy"></i> <small>Copy</small>',
                titleAttr: "Copy",
                className: "btn btn-secondary btn-sm ",
            },
            {
                extend: "excel",
                text: '<i class="fa fa-file-excel"></i> <small>Excel</small>',
                titleAttr: "Export to Excel",
                className: "btn btn-success btn-sm ",
            },
            {
                extend: "pdf",
                text: '<i class="fa fa-file-pdf"></i> <small>PDF</small>',
                titleAttr: "Export to PDF",
                className: "btn btn-danger btn-sm ",
            },
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).addClass("responsive-font");
        },
        initComplete: function () {
            // SELECTED
            var api = this.api();

            api.on("select deselect", function (e, dt, type, indexes) {
                if (type === "row") {
                    var selectedRows = api.rows({ selected: true });
                    selectedId = selectedRows.data().pluck("id").toArray();

                    let dataId = selectedId.join(",");

                    $(".selected-id").html(dataId);
                    $(".btn-delete")
                        .parents("form")
                        .attr("action", `${getAttr}/${dataId}`);
                }
            });
        },
    });

    reloadTable();
});

function reloadTable() {
    $(".datatable").DataTable().ajax.reload(function () {
        $(this).fadeIn("slow");
        selectedId = [];
    });
}

// BUTTON MOVE
function buttonMove(dataButton) {
    for (var key in dataButton) {
        (function(key) {
            $("body").on("click", key, function (event) {
                var button = $(event.target).closest(key);
                if (typeof dataButton[key] === "string") {
                    showView(dataButton[key]);
                } else if (typeof dataButton[key] === "function") {
                    dataButton[key](button);
                } else if (Array.isArray(dataButton[key])) {
                    var action = dataButton[key][0];
                    if (typeof action === "string" && typeof window[action] === "function") {
                        window[action](button);
                    }
                    for (var i = 1; i < dataButton[key].length; i++) {
                        dataButton[key][i](button);
                    }
                } else {
                    showView(dataButton[key]);
                }
            });
        })(key);
    }
}


function formAjax(){

    new FormData(form)
    $.ajax({
        url     : url,
        type    : form.attr("method"),
        enctype : form.attr("enctype"),
        data    : form.attr("enctype").serialize,
        headers : {"X-CSRF-TOKEN": $('meta[name = "csrf-token"]').attr("content")},
        processData: false,
        contentType: false,
        dataType:  "json",
        success: function (data, status, xhr) {
            populateForm(data);

            $(".view-edit form").attr("action", url);
        },
        error: function (data, status, xhr) {
            showMsg(data, status);
        },
    });
}


// SUBMIT FORM / CREATE DATA
$(".view-form form").submit(function (e) {
    e.preventDefault();
    ajaxCrud(this, "form");
});


// SUBMIT EDIT / UPDATE DATA
$(".view-edit form").submit(function (e) {
    e.preventDefault();
    ajaxCrud(this, "edit");
});

$(".btn-delete").parents("form").submit(function (e) {
    e.preventDefault();
    ajaxCrud(this, "delete");
});

function ajax(url, method = 'GET', data = null, headers = {}) {
    headers["X-CSRF-TOKEN"] = $('meta[name="csrf-token"]').attr("content");

    return new Promise((onSuccess, onError) => {
        $.ajax({
            url: form.attr("action") ,
            type: form.attr("method"),
            data: dataForm,
            enctype: form.attr("enctype"),
            headers: headers,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (data, status, xhr) {
                onSuccess({
                    data: data,
                    status: status,
                    xhr: xhr
                });
            },
            error: function (data, status, xhr) {
                onError({
                    data: data,
                    status: status,
                    xhr: xhr
                });
            }
        });
    });
}

function ajaxCrud(form, view) {
    $(".error-message").remove();
    var dataForm = new FormData(form);
    form = $(form);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: dataForm,
        enctype: form.attr("enctype"),
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        success: function (data, status, xhr) {
            $("#contentData").html(data);
            showMsg(data, status);
            reloadTable();
            if (view != "delete") {
                showView("data");
            }
            if ($(`.view-${view} form`)[0]) {
                $(`.view-${view} form`)[0].reset();
            }
        },
        error: function (data, status, xhr) {
            showMsg(data, status);

            if (data.responseJSON.errors != null) {
                $(`.view-${view} [name]`).removeClass("is-valid");
                $(`.view-${view} [name]`).removeClass("is-invalid");
                Object.keys(data.responseJSON.errors).forEach(function (see) {
                    var errorMessages = data.responseJSON.errors[see];

                    var pesan = `<p class="error-message text-danger mb-0">${errorMessages}</p>`;

                    $(`.view-${view} [name="${see}"]`).addClass("is-invalid");
                    $(`.view-${view} [name="${see}"]`).after(pesan);
                });
                $(`.view-${view} [name]:not(.is-invalid)`).addClass("is-valid");
            }
        },
    });
}

function showView(view) {
    $(".view-data").hide();
    $("[class*=view-]").hide();
    $(".loading").remove();
    if (view != "data") {
        $(document).on({
            ajaxStart: function () {
                $(".view-" + view + " .card-body").hide();
                $(".view-" + view + " .card").append(`
                    <div class="card-body loading">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                `);
                $(`.error-message`).remove();
                $(`[name]`).removeClass("is-valid");
                $(`[name]`).removeClass("is-invalid");
            },
            ajaxStop: function () {
                $(".loading").remove();
                $(".view-" + view + " .card-body").show();

                $(":focus").removeAttr("autofocus");
                $(".view-" + view + " form input:visible:not(:hidden):first").focus();
                datatable.columns.adjust();
            },
        });
        $(".view-" + view).show(500);
    } else {
        $(".view-" + view).show(500);
        $(".view-" + view + " .card-body").show(500);
    }
    $(`[data-show*='${view}']`).show(500);
    $(`[data-show]:not([data-show*='${view}'])`).hide();

    $(":focus").removeAttr("autofocus");
    $(".view-" + view + " form input:visible:not(:hidden):first").focus();
}

function showMsg(response, type) {  
    const isMobile = window.matchMedia("only screen and (max-width: 768px)").matches;
    const position = isMobile ? "top" : "top-end";

    const Toast = Swal.mixin({
        toast: true,
        position: position,
        showConfirmButton: false,
        timer: 3000,
        showCloseButton: true,
        customClass: {
            closeButton: "toast-close",
        },
        timerProgressBar: true,
        padding: 10,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });

    Toast.fire({
        icon: type,
        title: response.message || (response.responseJSON && response.responseJSON.message) || response,
    });
}

function populateForm(data) {
    data.forEach(function (item) {
        Object.keys(item).forEach(function (key) {
            let value = item[key];
            if (typeof value === "string") {
                let dataEdit = value.split(".");
                let typeImage = ["jpg", "jpeg", "png"];
                if (typeImage.includes(dataEdit[dataEdit.length - 1])) {
                    $('.view-edit [name="old_' + key + '"]').val(value);
                } else {
                    $('.view-edit [name="' + key + '"]').val(value);
                }
            } else if (typeof value === "number") {
                // Handle number types by directly setting the value
                $('.view-edit [name="' + key + '"]').val(value);
            } else if (typeof value === "boolean") {
                // Convert 1 and 0 to true and false for boolean fields
                if (value === 1) {
                    value = true;
                } else if (value === 0) {
                    value = false;
                }
                $('.view-edit [name="' + key + '"]').prop('checked', value);
            } else {
                // For other types of values (objects, arrays, etc.), handle as needed
                $('.view-edit [name="' + key + '"]').val(value);
            }
        });
    });
}


// replace __x__ with some data
function replacePatternInNode(element, name, data) {
    const placeholderRegex = new RegExp(`__${name}__`, "g");
    const node = element[0];

    let dataIndex = 0; // index for the data

    const replaceText = (textContent, regex, replacement) => {
        return textContent.replace(regex, replacement);
    };

    const replaceAttributes = (attr, regex, replacement) => {
        if (regex.test(attr.value)) {
            const newValue = attr.value.replace(regex, replacement);
            node.setAttribute(attr.name, newValue);
        }
    };

    const handleNode = (node) => {
        if (node.nodeType === Node.ELEMENT_NODE) {
            const attributes = node.attributes;
            for (let i = 0; i < attributes.length; i++) {
                const attr = attributes[i];
                replaceAttributes(attr, placeholderRegex, data); // Use direct data value
            }
        }

        if (node.nodeType === Node.TEXT_NODE) {
            node.textContent = replaceText(node.textContent, placeholderRegex, data); // Use direct data value
        }

        if (node.childNodes && node.childNodes.length > 0) {
            node.childNodes.forEach((childNode) => {
                handleNode(childNode);
                dataIndex++; // Increment only within this recursive function if needed
            });
        }
    };

    handleNode(node);
}



$(window).on("resize", function () {
    datatable.columns.adjust();
});
