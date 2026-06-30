$(document).ready(function() {
    $("#txn_date").daterangepicker({
        startDate: moment().startOf('day'),
        endDate: moment().endOf('day'),
        locale: {
            format: "MM/DD/YYYY",
        },
    });
    var table = $("#view_seller_withdrawal_request_list").DataTable({
        paging: true,
        lengthChange: false,
        ordering: true,
        info: true,
        autoWidth: true,
        responsive: true,
        ajax: {
            url: "/seller/withdrawal-request/list",
            type: "POST",
            dataType: "json",
            dataSrc: "data",
            data: function(d) {
                d.txn_date = $("#txn_date").val() || moment().format('MM/DD/YYYY') + ' - ' + moment().format('MM/DD/YYYY');
                d.method = $("#method").val();
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

    $("#fundTransferForm").on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData($("#fundTransferForm")[0]);;

        $.ajax({
            url: '/seller/withdrawal-request/add-request', // Adjust the URL as needed
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message, "Admin says");
                    $("#fundTransferForm").trigger('reset')
                    table.ajax.reload();
                    $("#modal-default").modal('hide')
                    const selectDeliveryBoy = $("#seller_id");
                    selectDeliveryBoy.empty();
                    selectDeliveryBoy.append('<option value="">Select Seller</option>');
                    response.seller.forEach(boy => {
                        const option = `<option value="${boy.id}">${boy.store_name} (${response.currency} ${boy.balance})</option>`;
                        selectDeliveryBoy.append(option);
                    });

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