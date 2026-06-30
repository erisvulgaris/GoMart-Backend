function updateSubCat() {
  var cat_id = $("#cat_id").val();
  var sub_cat_name = $("#sub_cat_name").val().trim();
  var files = $("#sub_cat_img_webp")[0].currentSrc;
  var sub_cat_id = $("#sub_cat_id").val();

  if (cat_id == "" || sub_cat_name == "") {
    toastr.error(
      "Select Category & Enter Subcategory name is required",
      "Admin says"
    );
    return false;
  }
  var regex = /^[A-Za-z][A-Za-z\s,&]*$/;

  if (sub_cat_name === "") {
    toastr.error("Subcategory name is required", "Admin says");
    return false;
  }

  if (!regex.test(sub_cat_name)) {
    toastr.error(
      "Subcategory name must start with an alphabet and contain only letters and spaces",
      "Admin says"
    );
    return false;
  }

  sub_cat_name = sub_cat_name.replace(/\b\w/g, function (char) {
    return char.toUpperCase();
  });

  $.ajax({
    url: "/admin/subcategory/update",
    type: "POST",
    data: {
      cat_id: cat_id,
      sub_cat_name: sub_cat_name,
      sub_cat_id: sub_cat_id,
      sub_cat_img: files,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success("Sub Category updated", "Admin says");
        setTimeout(function () {
          window.location.href = "/admin/subcategory";
        }, 2000);
      } else {
        toastr.error("Something went wrong", "Admin says");
        return false;
      }
    },
  });
}

function addSubCat() {
  var cat_id = $("#cat_id").val();
  var sub_cat_name = $("#sub_cat_name").val().trim();
  var files = $("#sub_cat_img_webp")[0].currentSrc;

  if (cat_id == "" || sub_cat_name == "") {
    toastr.error(
      "Select Category & Enter Subcategory name is required",
      "Admin says"
    );
    return false;
  }

  var regex = /^[A-Za-z][A-Za-z\s,&]*$/;

  if (sub_cat_name === "") {
    toastr.error("Subcategory name is required", "Admin says");
    return false;
  }

  if (!regex.test(sub_cat_name)) {
    toastr.error(
      "Subcategory name must start with an alphabet and contain only letters and spaces",
      "Admin says"
    );
    return false;
  }

  sub_cat_name = sub_cat_name.replace(/\b\w/g, function (char) {
    return char.toUpperCase();
  });

  if (files.length == 0) {
    toastr.error("Subcategory image is required", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/subcategory/add",
    type: "POST",
    data: {
      cat_id: cat_id,
      sub_cat_name: sub_cat_name,
      sub_cat_img: files,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#view_sub_category").DataTable().ajax.reload();
        $("#sub_cat_img_webp").attr("src", "");
        $("#myform").trigger("reset");
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

function convertImage(event) {
  var fileName = document.getElementById("sub_cat_img").value;
  var idxDot = fileName.lastIndexOf(".") + 1;
  var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
  if (
    extFile == "jpeg" ||
    extFile == "png" ||
    extFile == "webp" ||
    extFile == "jpg"
  ) {
    const sub_cat_img_webp = document.querySelector("#sub_cat_img_webp");

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
        sub_cat_img_webp.src = webpImage;
      };
    }
  } else {
    toastr.error("file not supported", "Admin says");
    return false;
    $("#sub_cat_img").val("");
  }
}

$(document).ready(function () {
  // Initialize the DataTable with export buttons
  var table = $("#view_sub_category").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/subcategory/list",
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

function deletesubcategory(id) {
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
      url: "/admin/subcategory/delete",
      type: "POST",
      data: {
        subcat_id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          toastr.success(response.message, "Admin says");
          $("#view_sub_category").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
        }
      },

      error: function (e) {
        toastr.error("Error While deleting SubCategory", "Admin says");
      },
    });
  });
}

var initialOrder = "";
var productList = document.getElementById("product-list");
$("#cat_id").on("change", function () {
  var catname = $("#cat_id").val();

  if (catname != "") {
    $.ajax({
      url: "/admin/subcategory/getSub",
      data: {
        cat_change: catname,
      },
      type: "POST",
      success: function (response) {
        let product_list = $("#product-list");
        product_list.html(""); // Clear previous list

        if (response.subcategory && response.subcategory.length > 0) {
          // Sort subcategories by row_order before appending
          response.subcategory.sort((a, b) => a.row_order - b.row_order);

          $.each(response.subcategory, function (index, subcategory) {
            product_list.append(
              `<li data-id="${subcategory.id}">
                <span>${subcategory.name}</span>
                <span class="drag-handle">&#x2630;</span>
              </li>`
            );
          });

          // Clone list items to preserve original structure
          initialOrder = Array.from(product_list.children()).map(function (
            item
          ) {
            return item.cloneNode(true);
          });
        } else {
          product_list.html("<li>No subcategories found</li>");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching subcategories:", error);
      },
    });
  } else {
    $("#product-list").html("");
  }
});
document.addEventListener("DOMContentLoaded", function () {
  // Initialize SortableJS
  var sortable = new Sortable(productList, {
    animation: 150,
  });
  // Save the initial order of product elements (not just IDs)

  document.getElementById("save-order").addEventListener("click", function () {
    // Get the new order of product IDs
    var orderedIds = [];
    document.querySelectorAll("#product-list li").forEach(function (item) {
      orderedIds.push(item.getAttribute("data-id"));
    });

    $.ajax({
      url: "/admin/subcategory_order/update",
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
