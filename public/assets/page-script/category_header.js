function addHeaderCategory() {
  let header_category_title = $("#header_category_title").val().trim();
  let header_category_icon = $("#header_category_icon").val().trim();
  let category_id = $("#category_id").val().trim();
  let icon_library = $("#icon_library").val().trim();

  if (!header_category_title) {
    toastr.error("Header Category Title is required", "Admin says");
    return;
  }

  $.ajax({
    url: "/admin/header-category/add",
    type: "POST",
    data: {
      header_category_title: header_category_title,
      header_category_icon: header_category_icon,
      category_id: category_id,
      icon_library: icon_library
    },
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
        $("#view_header_category").DataTable().ajax.reload();
        $("#HeaderCategoryForm").trigger("reset");
      } else {
        toastr.error(response.message, "Admin says");
      }
    }
  });
}




function updateHeadercategory() {
  let header_category_id = $("#header_category_id").val();
  let header_category_title = $("#header_category_title").val().trim();
  let header_category_icon = $("#header_category_icon").val().trim();
  let category_id = $("#category_id").val().trim();
  let icon_library = $("#icon_library").val().trim();

  if (!header_category_title) {
    toastr.error("Header Category Title is required", "Admin says");
    return;
  }

  $.ajax({
    url: "/admin/header-category/update",
    type: "POST",
    data: {
      header_category_title: header_category_title,
      header_category_icon: header_category_icon,
      category_id: category_id,
      header_category_id: header_category_id,
      icon_library:icon_library
    },
    success: function (response) {
      if (response.success) {
        toastr.success(response.message, "Admin says");
        function route() {
          location = "/admin/header-category";
        }
        setTimeout(route, 2500); 7
      } else {
        toastr.error(response.message, "Admin says");
      }
    }
  });
}



$(document).ready(function () {
  // Initialize the DataTable with export buttons
  var table = $("#view_header_category").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/header-category/list",
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


function deleteheadercategory(id) {
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
      url: "/admin/header-category/delete",
      type: "POST",
      data: {
        header_category_id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");

          $("#view_header_category").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
        }
      },

      error: function (e) {
        toastr.error("Error While deleting header category", "Admin says");
      },
    });
  });
}