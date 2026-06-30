
$(document).ready(function() {
    $("#order_date").daterangepicker({
        startDate: moment().startOf('day'),
        endDate: moment().endOf('day'),
        locale: {
            format: "MM/DD/YYYY",
        },
    });
    var table = $("#view_order").DataTable({
        dom: "Bfrtip",
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        responsive: true,
        ajax: {
            url: "/seller/orders/list",
            type: "POST",
            dataType: "json",
            dataSrc: "data",
            data: function(d) {
                d.order_date = $("#order_date").val() || moment().format('MM/DD/YYYY') + ' - ' + moment().format('MM/DD/YYYY');
                d.status = $("#status").val();
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
    $("#assignDeliveryDateForm").on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData($("#assignDeliveryDateForm")[0]);;

        $.ajax({
            url: 'seller/orders/delivery_date/update', // Adjust the URL as needed
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message, "Admin says");
                    $("#assignDeliveryDateForm").trigger('reset')
                    table.ajax.reload();
                    $("#assignDeliveryDate").modal('hide')
                } else {
                    toastr.error(response.message, "Admin says");
                }
            },
            error: function(xhr) {
                // Handle errors
                toastr.error('An error occurred. Please try again.');
            }
        });
    })
});

function assignDeliveryDate(id) {
    $("#order_id").val(id);
    $("#show_order_id").text(id);
    $("#assignDeliveryDate").modal("toggle");
}

function assignDeliveryBoy(order_id, delivery_boy_id) {
    $.ajax({
        url: "/seller/orders/assignDeliveryBoy",
        type: "POST",
        data: {
            order_id: order_id,
            delivery_boy_id: delivery_boy_id,
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                toastr.success(response.message, "Admin says");
                table.ajax.reload();
            } else {
                toastr.error(response.message, "Admin says");
            }
            $("#assign").modal("toggle");
        },
    });

}