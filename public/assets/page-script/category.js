function convertImage(event) {
  var fileName = document.getElementById("cat_img").value;
  var idxDot = fileName.lastIndexOf(".") + 1;
  var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
  if (
    extFile == "jpeg" ||
    extFile == "png" ||
    extFile == "webp" ||
    extFile == "jpg"
  ) {
    const cat_img_webp = document.querySelector("#cat_img_webp");

    if (event.target.files.length > 0) {
      let src = URL.createObjectURL(event.target.files[0]);

      let canvas = document.createElement("canvas");
      let ctx = canvas.getContext("2d");
      let userImage = new Image();
      userImage.src = src;

      userImage.onload = function () {
        canvas.width = userImage.width;
        canvas.height = userImage.height;

        ctx.drawImage(userImage, 0, 0);

        let webpImage = canvas.toDataURL("image/webp", 0.8);
        cat_img_webp.src = webpImage;
      };
    }
  } else {
    toastr.error("file not supported", "Admin says");
    return false;
    $("#cat_img").val("");
  }
}

function addCat() {
  var files = $("#cat_img_webp")[0].currentSrc;

  var cat_name = $("#cat_name").val().trim();
  var regex = /^[A-Za-z][A-Za-z\s,&]*$/;

  if (cat_name === "") {
    toastr.error("Category name is required", "Admin says");
    return false;
  }
  var is_bestseller_category = $("#is_bestseller_category").val();
  var category_group_id = $("#category_group_id").val();
  var is_it_have_warning = $("#is_it_have_warning").val();
var warning_content = $("#warning_content").val();


  if (!regex.test(cat_name)) {
    toastr.error(
      "Category name must start with an alphabet and contain only letters and spaces",
      "Admin says"
    );
    return false;
  }

  cat_name = cat_name.replace(/\b\w/g, function (char) {
    return char.toUpperCase();
  });

  if (files.length == 0) {
    toastr.error("Category image is required", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/category/add",
    type: "POST",
    data: {
      cat_name: cat_name,
      cat_img: files,
      is_bestseller_category: is_bestseller_category,
      category_group_id:category_group_id,
      warning_content, is_it_have_warning
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#view_category").DataTable().ajax.reload();
        $("#catForm").trigger("reset");
        $("#cat_img_webp").attr("src", "");
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

function updateCat() {
  var files = $("#cat_img_webp")[0].currentSrc;
  var cat_id = $("#id").val();

  var cat_name = $("#cat_name").val().trim();
  var regex = /^[A-Za-z][A-Za-z\s,&]*$/;
  var is_bestseller_category = $("#is_bestseller_category").val();
  var category_group_id = $("#category_group_id").val();
var is_it_have_warning = $("#is_it_have_warning").val();
var warning_content = $("#warning_content").val();
  if (cat_name === "") {
    toastr.error("Category name is required", "Admin says");
    return false;
  }

  if (!regex.test(cat_name)) {
    toastr.error(
      "Category name must start with an alphabet and contain only letters and spaces",
      "Admin says"
    );
    return false;
  }

  cat_name = cat_name.replace(/\b\w/g, function (char) {
    return char.toUpperCase();
  });

  $.ajax({
    url: "/admin/category/update",
    type: "POST",
    data: {
      cat_name: cat_name,
      cat_id: cat_id,
      files: files,
      is_bestseller_category: is_bestseller_category,
      category_group_id:category_group_id,
      warning_content, is_it_have_warning
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");

        function route() {
          location = "/admin/category";
        }
        setTimeout(route, 2500);
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

$(document).ready(function () {
  // Initialize the DataTable with export buttons
  var table = $("#view_category").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/category/list",
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

function deletecategory(id) {
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
      url: "/admin/category/delete",
      type: "POST",
      data: {
        cat_id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");

          $("#view_category").DataTable().ajax.reload();
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
document.addEventListener("DOMContentLoaded", function () {
  var productList = document.getElementById("product-list");

  // Initialize SortableJS
  var sortable = new Sortable(productList, {
    animation: 150,
  });
  // Save the initial order of product elements (not just IDs)
  var initialOrder = Array.from(productList.children).map(function (item) {
    return item.cloneNode(true); // Clone the nodes to preserve original structure
  });

  document.getElementById("save-order").addEventListener("click", function () {
    // Get the new order of product IDs
    var orderedIds = [];
    document.querySelectorAll("#product-list li").forEach(function (item) {
      orderedIds.push(item.getAttribute("data-id"));
    });

    $.ajax({
      url: "/admin/category_order/update",
      type: "POST",
      data: JSON.stringify({
        order: orderedIds,
      }),
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");
        } else {
          toastr.error(response.message, "Admin says");
        }
      },
    });

    //   // Send the new order to the backend
    //   fetch("product/updateOrder", {
    //     method: "POST",
    //     headers: {
    //       "Content-Type": "application/json",
    //       "X-Requested-With": "XMLHttpRequest",
    //     },
    //     body: JSON.stringify({ order: orderedIds }),
    //   })
    //     .then((response) => response.json())
    //     .then((data) => {
    //       if (data.success) {
    //         alert("Order updated successfully!");
    //       } else {
    //         alert("Failed to update order.");
    //       }
    //     });
  });
  document.getElementById("reset-order").addEventListener("click", function () {
    // Clear the current list
    productList.innerHTML = "";

    // Rebuild the list using the cloned initial nodes
    initialOrder.forEach(function (item) {
      productList.appendChild(item.cloneNode(true));
    });
  });
});
