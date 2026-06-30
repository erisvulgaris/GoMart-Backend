function convertImage(event) {
  var fileName = document.getElementById("brand_image").value;
  var idxDot = fileName.lastIndexOf(".") + 1;
  var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
  if (
    extFile == "jpeg" ||
    extFile == "png" ||
    extFile == "webp" ||
    extFile == "jpg"
  ) {
    const brand_image_webp = document.querySelector("#brand_image_webp");

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
        brand_image_webp.src = webpImage;
      };
    }
  } else {
    toastr.error("file not supported", "Admin says");
    return false;
    $("#brand_image").val("");
  }
}

function addBrand() {
  var brand_name = $("#brand_name").val().trim();
  var files = $("#brand_image_webp")[0].currentSrc;

  var regex = /^[A-Za-z][A-Za-z\s]*$/;

  if (brand_name === "") {
    toastr.error("Brand name is required", "Admin says");
    return false;
  }

  if (!regex.test(brand_name)) {
    toastr.error(
      "Brand name must start with an alphabet and contain only letters and spaces",
      "Admin says"
    );
    return false;
  }

  brand_name = brand_name.replace(/\b\w/g, function (char) {
    return char.toUpperCase();
  });

  if (files.length == 0) {
    toastr.error("Brand image is required", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/brand/add",
    type: "POST",
    data: {
      brand_name: brand_name,
      brand_image: files,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#view_brand").DataTable().ajax.reload();
        $("#myform").trigger("reset");
        $("#brand_image_webp").attr("src", "");
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

function updateBrand() {
  var brand_name = $("#brand_name").val().trim();
  var files = $("#brand_image_webp")[0].currentSrc;
  var brand_id = $("#id").val();

  var regex = /^[A-Za-z][A-Za-z\s]*$/;

  if (brand_name === "") {
    toastr.error("Brand name is required", "Admin says");
    return false;
  }

  if (!regex.test(brand_name)) {
    toastr.error(
      "Brand name must start with an alphabet and contain only letters and spaces",
      "Admin says"
    );
    return false;
  }

  brand_name = brand_name.replace(/\b\w/g, function (char) {
    return char.toUpperCase();
  });

  $.ajax({
    url: "/admin/brand/update",
    type: "POST",
    data: {
      brand_name: brand_name,
      brand_id: brand_id,
      files: files,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");

        function route() {
          location = "/admin/brand";
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
  var table = $("#view_brand").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/brand/list",
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

function deletebrand(id) {
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
      url: "/admin/brand/delete",
      type: "POST",
      data: {
        brand_id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");

          $("#view_brand").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
        }
      },

      error: function (e) {
        toastr.error("Error While deleting brand", "Admin says");
      },
    });
  });
}
