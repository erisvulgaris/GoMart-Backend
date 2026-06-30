function addGroupcategory() {
  let group_category_title = $("#group_category_title").val().trim();

  if (!group_category_title) {
    toastr.error("Group Category Title is required", "Admin says");
    return;
  }

  $.ajax({
    url: "/admin/group-category/add",
    type: "POST",
    data: { group_category_title: group_category_title },
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
        $("#view_group_category").DataTable().ajax.reload();
        $("#GroupcategoryForm").trigger("reset");
      } else {
        toastr.error(response.message, "Admin says");
      }
    }
  });
}


function updateGroupcategory(id) {
  let group_category_title = $("#group_category_title").val().trim();

  if (!group_category_title) {
    toastr.error("Group Category Title is required", "Admin says");
    return;
  }

  $.ajax({
    url: "/admin/group-category/update/" + id,
    type: "POST",
    data: { group_category_title: group_category_title },
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
        $("#view_group_category").DataTable().ajax.reload();
        function route() {
          location = "/admin/group-category";
        }
        setTimeout(route, 2500);
      } else {
        toastr.error(response.message, "Admin says");
      }
    }
  });
}



$(document).ready(function () {
  // Initialize the DataTable with export buttons
  var table = $("#view_group_category").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/group-category/list",
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


function deletegroupcategory(id) {
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
      url: "/admin/group-category/delete",
      type: "POST",
      data: {
        group_category_id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");

          $("#view_group_category").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
        }
      },

      error: function (e) {
        toastr.error("Error While deleting group category", "Admin says");
      },
    });
  });
}