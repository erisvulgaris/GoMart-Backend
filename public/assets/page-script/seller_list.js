$(document).ready(function () {
  var table = $("#view_seller").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/seller/list/view",
      type: "POST",
      dataType: "json",
      dataSrc: "data",
    },
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

function deleteSeller(id) {
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
        url: "/admin/seller/delete",
        type: "POST",
        data: {
          seller_id: id,
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            toastr.success(response.message, "Admin says");
            $("#view_seller").DataTable().ajax.reload();
          } else {
            toastr.error(response.message, "Admin says");
          }
        },
        error: function (e) {
          toastr.error("Error While deleting Seller", "Admin says");
        },
      });
    } else {
      toastr.info("Deleting Seller Cancelled", "Admin says");
      return false;
    }
  });
}

function deleteSellerCategory(seller_id, seller_category_id) {
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
        url: "/admin/seller/delete-seller-category",
        type: "POST",
        data: {
          seller_id,
          seller_category_id,
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            toastr.success(response.message, "Admin says");
            $("#view_seller").DataTable().ajax.reload();
          } else {
            toastr.error(response.message, "Admin says");
          }
        },
        error: function (e) {
          toastr.error("Error while deleting seller category", "Admin says");
        },
      });
    } else {
      toastr.info("Deleting Seller Cancelled", "Admin says");
      return false;
    }
  });
}
