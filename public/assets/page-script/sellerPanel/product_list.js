$(document).ready(function() {
    var table = $("#view_product").DataTable({
        paging: true,
        lengthChange: false,
        ordering: true,
        info: true,
        autoWidth: true,
        responsive: true,
        ajax: {
            url: "/seller/product/list",
            type: "POST",
            dataType: "json",
            dataSrc: "data",
            data: function(d) {
                d.category = $("#category").val();
                d.seller = $("#seller").val();
                d.status = $("#status").val();
                d.stock = $("#stock").val();
            }
        },
        buttons: [{
                extend: "copy",
                title: "Export Data",
            },
            {
                extend: "csv",
                title: "Export Data",
            },
            {
                extend: "excel",
                title: "Export Data",
            },
            {
                extend: "pdf",
                title: "Export Data",
            },
            {
                extend: "print",
                title: "Export Data",
            },
        ],
    });
    // Hide the original DataTable buttons
    table.buttons().container().hide();

    $(".dataTables_filter").hide();

    $("#custom-search").on("keyup", function() {
        table.search(this.value).draw(); // Trigger search when typing in custom search field
    });
    $("#custom-length-change").on("change", function() {
        table.page.len($("#custom-length-change").val()).draw();
    });

    // Link the export dropdown to the DataTable export buttons
    $(".dt-export-copy").on("click", function(e) {
        e.preventDefault();
        table.button(0).trigger(); // Trigger Copy
    });
    $(".dt-export-csv").on("click", function(e) {
        e.preventDefault();
        table.button(1).trigger(); // Trigger CSV
    });
    $(".dt-export-excel").on("click", function(e) {
        e.preventDefault();
        table.button(2).trigger(); // Trigger Excel
    });
    $(".dt-export-pdf").on("click", function(e) {
        e.preventDefault();
        table.button(3).trigger(); // Trigger PDF
    });
    $(".dt-export-print").on("click", function(e) {
        e.preventDefault();
        table.button(4).trigger(); // Trigger Print
    });
    $(".filter-product").on('change', function() {
        table.ajax.reload();
    })
});

function deleteproduct(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "error",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, proceed please!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/seller/product/delete",
                type: "POST",
                data: {
                    product_id: id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, "Admin says");

                    } else {
                        toastr.error(response.message, "Admin says");

                    }

                    $("#view_product").DataTable().ajax.reload()
                },

                error: function(e) {
                    toastr.error("Error While deleting Product", "Admin says");
                },
            });
        } else {
            toastr.info("Deleting Product Cancelled", "Admin says");
            return false;
        }
    })
}

function downloadQR(id) {
    $.ajax({
        url: "/seller/product/generate-qr",
        type: "POST",
        data: {
            product_id: id
        },
        xhrFields: { responseType: 'blob' }, // Handle binary data
        success: function(response) {
            let blob = new Blob([response], {
                type: "image/png"
            });
            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = "qrcode.png";
            link.click();
        },
        error: function() {
            toastr.error("Error generating QR Code", "Admin says");
        }
    });
}