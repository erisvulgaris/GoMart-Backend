$(document).ready(function () {
  var table = $("#view_product").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/seller/stock-management/list",
      type: "POST",
      dataType: "json",
      dataSrc: "data",
      data: function (d) {
        d.category = $("#category").val();
        d.status = $("#status").val();
        d.stock = $("#stock").val();
      },
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

  $(document).on("click", ".stock-cell", function () {
    const id = $(this).data("id");

    // Hide the stock text and show the input with the update button
    $(this).find(".editable-stock").hide();
    $(this).find(`.stock-input[data-id='${id}']`).show().focus();
    $(this).find(`.update-stock-btn[data-id='${id}']`).show();
  });

  // AJAX to update stock when update button is clicked
  $(document).on("click", ".update-stock-btn", function () {
    const id = $(this).data("id");
    const newStock = $(`.stock-input[data-id='${id}']`).val();

    $.ajax({
      url: "/seller/stock-management/updateStock", // Adjust the URL according to your route
      type: "POST",
      data: {
        variant_id: id,
        stock: newStock,
      },
      success: function (response) {
        if (response.success) {
          $(
            `.stock-input[data-id='${id}'], .update-stock-btn[data-id='${id}']`
          ).hide();
          $(`.editable-stock[data-id='${id}']`).text(newStock).show();

          // $("#view_product").DataTable().ajax.reload()
          toastr.success(response.message, "Admin says");
        } else {
          toastr.error(response.message, "Admin says");
        }
      },
    });
  });
  $(".filter-product").on("change", function () {
    table.ajax.reload();
  });
});
