$(document).ready(function() {
    var table = $("#view_product").DataTable({
        paging: true,
        lengthChange: false,
        ordering: true,
        info: true,
        autoWidth: true,
        responsive: true,
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

function updateReview(rating_id, is_approved_to_show) {
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
                url: "/admin/product/rating/update",
                type: "POST",
                data: {
                    rating_id,
                    is_approved_to_show
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, "Admin says");
                        let badgeSpan = $("#status-" + rating_id);
                        let actionButtons = $("#actions-" + rating_id);

                        if (is_approved_to_show == 1) {
                            badgeSpan.html('<span class="badge badge-success">Approved</span>');
                            actionButtons.html('<button class="btn btn-sm btn-danger-light" data-tooltip="Reject Rating" onclick="updateReview(' + rating_id + ', 2)"><i class="fi fi-br-hand"></i></button>');
                        } else if (is_approved_to_show == 2) {
                            badgeSpan.html('<span class="badge badge-danger">Rejected</span>');
                            actionButtons.html('<button class="btn btn-sm btn-primary-light" data-tooltip="Approve Rating" onclick="updateReview(' + rating_id + ', 1)"><i class="fi fi-br-social-network"></i></button>');
                        } else {
                            badgeSpan.html('<span class="badge badge-warning">Pending</span>');
                            actionButtons.html('<button class="btn btn-sm btn-primary-light" data-tooltip="Approve Rating" onclick="updateReview(' + rating_id + ', 1)"><i class="fi fi-br-social-network"></i></button>\
                                        <button class="btn btn-sm btn-danger-light" data-tooltip="Reject Rating" onclick="updateReview(' + rating_id + ', 2)"><i class="fi fi-br-hand"></i></button>');
                        }

                    } else {
                        toastr.error(response.message, "Admin says");
                    }
                },
                error: function(e) {
                    toastr.error("Error While updating review", "Admin says");
                },
            });
        } else {
            toastr.info("Updating review cancelled", "Admin says");
            return false;
        }
    })
}