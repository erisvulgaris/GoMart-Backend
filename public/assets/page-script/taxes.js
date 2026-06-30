$(document).ready(function () {
  // Initialize the DataTable with export buttons
  var table = $("#view_taxes").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/taxes/list",
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

function addTax() {
  var tax = $("#tax").val();
  var percentage = $("#percentage").val();

  if (tax == "") {
    toastr.error(" Tax is required", "Admin says");
    return false;
  }

  if (percentage == "") {
    toastr.error("Percentage is required", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/taxes/add",
    type: "POST",
    data: {
      tax: tax,
      percentage: percentage,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#view_taxes").DataTable().ajax.reload();
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

function updateTax() {
  var tax = $("#tax").val();
  var percentage = $("#percentage").val();
  var taxid = $("#taxid").val();

  if (tax == "") {
    toastr.error("Enter Tax name is required", "Admin says");
    return false;
  }
  if (percentage == "") {
    toastr.error("Percentage is required", "Admin says");
    return false;
  }
  $.ajax({
    url: "/admin/taxes/update",
    type: "POST",
    data: {
      tax: tax,
      taxid: taxid,
      percentage: percentage,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");

        function route() {
          location = "/admin/taxes";
        }
        setTimeout(route, 2500);
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

function deleteTax(id) {
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
      url: "/admin/taxes/delete",
      type: "POST",
      data: {
        taxid: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");

          $("#view_taxes").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
        }
      },

      error: function (e) {
        toastr.error("Error While deleting taxes", "Admin says");
      },
    });
  });
}
