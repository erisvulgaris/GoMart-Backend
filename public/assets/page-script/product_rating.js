$(document).ready(function () {
  // Initialize the DataTable with export buttons
  var table = $("#view_product").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    buttons: [
      {
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

  $("#custom-search").on("keyup", function () {
    table.search(this.value).draw(); // Trigger search when typing in custom search field
  });
  $("#custom-length-change").on("change", function () {
    table.page.len($("#custom-length-change").val()).draw();
  });

  // Link the export dropdown to the DataTable export buttons
  $(".dt-export-copy").on("click", function (e) {
    e.preventDefault();
    table.button(0).trigger(); // Trigger Copy
  });
  $(".dt-export-csv").on("click", function (e) {
    e.preventDefault();
    table.button(1).trigger(); // Trigger CSV
  });
  $(".dt-export-excel").on("click", function (e) {
    e.preventDefault();
    table.button(2).trigger(); // Trigger Excel
  });
  $(".dt-export-pdf").on("click", function (e) {
    e.preventDefault();
    table.button(3).trigger(); // Trigger PDF
  });
  $(".dt-export-print").on("click", function (e) {
    e.preventDefault();
    table.button(4).trigger(); // Trigger Print
  });
});

function updateReview(id, status, el = null) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "error",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, proceed please!",
  }).then((result) => {
    $.ajax({
      url: "/admin/product/rating/update",
      type: "POST",
      data: {
         id, status
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");

           const row = $(el).closest("tr");
            const actionCell = row.find("td").eq(7); // 8th column (0-indexed)
            const statusCell = row.find("td").eq(6); // 7th column

            // Update status text (optional)

            // Update action button
            let newButton = "";
            if (status == 1) {
              newButton = `<button class="btn btn-sm btn-danger-light" data-tooltip="Reject Rating" onclick="updateReview(${id}, 2, this)"><i class="fi fi-br-hand"></i></button>`;
              statusCell.html("<span class='badge badge-success'>Approved</span>");
            } else {
              newButton = `<button class="btn btn-sm btn-primary-light" data-tooltip="Approve Rating" onclick="updateReview(${id}, 1, this)"><i class="fi fi-br-social-network"></i></button>`;
              statusCell.html("<span class='badge badge-danger'>Rejected</span>");
            }
            actionCell.html(newButton);
        } else {
          toastr.error(response.message, "Admin says");
        }
      },

      error: function (e) {
        toastr.error("Error While deleting category", "Admin says");
      },
    });
  });
}