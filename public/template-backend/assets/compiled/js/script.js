showView("form");
let selectedId = [];
let getAttr = $(".btn-delete").parents("form").attr("action");

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
            $("body").on("click", key, function () {
                if (typeof dataButton[key] === "string") {
                    showView(dataButton[key]);
                } else if (Array.isArray(dataButton[key])) {
                    // If the value is an array, assume the first element is the name of a function to call
                    // and the remaining elements are functions to execute
                    var action = dataButton[key][0];
                    if (typeof action === "string" && typeof window[action] === "function") {
                        window[action]();
                    }
                    for (var i = 1; i < dataButton[key].length; i++) {
                        dataButton[key][i]();
                    }
                } else if (typeof dataButton[key] === "function") {
                    dataButton[key]();
                } else {
                    showView(dataButton[key]);
                }
            });
        })(key);
    }
}


// SUBMIT FORM / CREATE DATA
$(".view-form form").submit(function (e) {
    e.preventDefault();
    ajaxCrud(this, "form");
});

// EDIT MOMENT
$("body").on("click", ".btn-edit", function () {
    
});

// SUBMIT EDIT / UPDATE DATA
$(".view-edit form").submit(function (e) {
    e.preventDefault();
    ajaxCrud(this, "edit");
});

// DELETE MOMENT
$("body").on("click", ".btn-delete", function () {
    if (selectedId.length == 0) {
        showMsg("Pilih data dari table dulu!!!", "info");
    } else {
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
                form.submit();
            }
        });
    }
});

$(".btn-delete").parents("form").submit(function (e) {
    e.preventDefault();
    ajaxCrud(this, "delete");
});

// BUTTON DETAIL
let dataDetail = $(".card-detail").html();
$("body").on("click", ".btn-detail", function () {
    if (selectedId.length == 0) {
        showMsg("Pilih data dari table dulu!!!", "info");
    } else {
        showView("detail");
        $(".card-detail").html(dataDetail);
        let form = $(this).parents("form");
        form.attr("action", "");
        let url = `${$(".route").html()}/${$(".selected-id").html()}`;
        $.ajax({
            url: url,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",
            success: function (data, status, xhr) {
                $(".card-detail").html("");
                let newDataDetail = "";
                data.forEach(function (see, number) {
                    newDataDetail += dataDetail;
                });
                $(".card-detail").html(newDataDetail);
                data.forEach(function (see, number) {
                    Object.keys(see).forEach(function (key) {
                        replacePatternInNode($(".view-detail"),key, [see[key]]);
                    });
                });
            },
            error: function (data, status, xhr) {
                showMsg(data, status);
            },
        });
    }
});

function ajaxCrud(form, view) {
    $(".error-message").remove();
    var dataForm = new FormData(form);
    form = $(form);
    console.log(dataForm);
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

// function replacePlaceholderWithData(name, data) {
//     const placeholderRegex = new RegExp(`__${name}__`, "g");
//     let dataIndex = 0; // Keep track of the current index in the loop

//     // Select all elements within elements having class 'some-class'
//     $('*').each(function() {
//         // Replace placeholders in element text content
//         $(this).contents().each(function () {
//             if (this.nodeType === Node.TEXT_NODE) {
//                 let content = $(this).text();
//                 if (placeholderRegex.test(content) && dataIndex < data.length) {
//                     content = content.replace(placeholderRegex, data[dataIndex]);
//                     $(this).text(content);
//                     dataIndex++; // Move to the next value in the loop
//                 }
//             }
//         });

//         // Replace placeholders in element attributes
//         $.each(this.attributes, function () {
//             if (placeholderRegex.test(this.value) && dataIndex < data.length) {
//                 const updatedValue = this.value.replace(placeholderRegex, data[dataIndex]);
//                 $(this.ownerElement).attr(this.name, updatedValue);
//                 dataIndex++; // Move to the next value in the loop
//             }
//         });
//     });
// }


$(window).on("resize", function () {
    datatable.columns.adjust();
});

// console.log();
