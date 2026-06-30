function convertImage(event) {
  var fileName = document.getElementById("banner_img").value;
  var idxDot = fileName.lastIndexOf(".") + 1;
  var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
  if (
    extFile == "jpeg" ||
    extFile == "png" ||
    extFile == "webp" ||
    extFile == "jpg"
  ) {
    const banner_img_webp = document.querySelector("#banner_img_webp");

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

        let webpImage = canvas.toDataURL("image/webp",  0.8);
        banner_img_webp.src = webpImage;
      };
    }
  } else {
    toastr.error("file not supported", "Admin says");
    return false;
    $("#banner_img").val("");
  }
}

function addBanner() {
  var files = $("#banner_img_webp")[0].currentSrc;
  var category_id = $("#category_id").val();
  var banner_type = $("#banner_type").val();

  if (files.length == 0) {
    toastr.error("Banner image is required", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/banner/add",
    type: "POST",
    data: {
      banner_img: files,
      category_id: category_id,
      banner_type: banner_type,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#view_banner").DataTable().ajax.reload();
        $("#banner_img_webp").attr("src", "");
        $("#addBannerForm").trigger("reset");
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

function updateBanner() {
  var files = $("#banner_img_webp")[0].currentSrc;
  var category_id = $("#category_id").val();
  var banner_id = $("#edit_id").val();
  var banner_type = $("#banner_type").val();

  $.ajax({
    url: "/admin/banner/update",
    type: "POST",
    data: {
      category_id: category_id,
      banner_id: banner_id,
      banner_img: files,
      banner_type: banner_type,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");

        function route() {
          location = "/admin/banner";
        }
        setTimeout(route, 2500);
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

$("#view_banner").dataTable({
  paging: true,
  lengthChange: true,
  searching: true,
  ordering: true,
  info: true,
  autoWidth: true,
  responsive: true,
  ajax: {
    url: "/admin/banner/list",
    type: "POST",
    dataType: "json",
    dataSrc: "data",
  },
});

function deletebanner(id) {
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
      url: "/admin/banner/delete",
      type: "POST",
      data: {
        ban_id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");
          $("#view_banner").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
          return false;
        }
      },
      error: function (e) {
        toastr.error("Error While deleting Banner", "Admin says");
      },
    });
  });
}
