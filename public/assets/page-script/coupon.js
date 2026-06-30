var today = new Date();
var dd = String(today.getDate()).padStart(2, "0");
var mm = String(today.getMonth() + 1).padStart(2, "0");
var yyyy = today.getFullYear();
today = yyyy + "-" + mm + "-" + dd;
$("#exp_date").attr("min", today);

function showDiv() {
  getSelectValue = document.getElementById("user_type").value;
  if (getSelectValue == "1") {
    document.getElementById("single").style.display = "block";
    $("#n_use").find("option").remove().end();
    $("#n_use").append('<option value="0">Single Time Valid</option>');
  } else {
    document.getElementById("single").style.display = "none";
    $("#n_use").find("option").remove().end();
    $("#n_use").append(
      '<option value="0">Single Time Valid</option> <option value="1">Multiple Time Valid</option>'
    );
  }
}

function convertImage(event) {
  var fileName = document.getElementById("coupon_img").value;
  var idxDot = fileName.lastIndexOf(".") + 1;
  var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
  if (
    extFile == "jpeg" ||
    extFile == "png" ||
    extFile == "webp" ||
    extFile == "jpg"
  ) {
    const coupon_img_webp = document.querySelector("#coupon_img_webp");

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
        coupon_img_webp.src = webpImage;
      };
    }
  } else {
    toastr.error("file not supported", "Admin says");
    return false;
    $("#coupon_img").val("");
  }
}

function addCoupon() {
  var user_id = $("#user_id").val();
  var user_type = $("#user_type").val();
  var n_use = $("#n_use").val();
  var exp_date = $("#exp_date").val();
  var coupon_code = $("#coupon_code").val();
  var coupon_title = $("#coupon_title").val();
  var coupon_status = $("#coupon_status").val();
  var min_amt = $("#min_amt").val();
  var coupon_value = $("#coupon_value").val();
  var description = $("#description").val();
  var coupon_type = $("#coupon_type").val();
  if (coupon_code.length > 8 || coupon_code.length < 8) {
    toastr.error("Coupon Code length must of 8 Character", "Admin says");
    return false;
  }
  var files = $("#coupon_img_webp")[0].currentSrc;

  if (files.length == 0) {
    toastr.error("Coupon image is required", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/coupon/add",
    type: "POST",
    data: {
      user_id: user_id,
      user_type: user_type,
      n_use: n_use,
      exp_date: exp_date,
      coupon_code: coupon_code,
      coupon_title: coupon_title,
      coupon_status: coupon_status,
      min_amt: min_amt,
      coupon_img: files,
      coupon_value: coupon_value,
      description: description,
      coupon_type:coupon_type
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        $("#coupon_code").val("");
        $("#coupon_title").val("");
        $("#min_amt").val("");
        $("#description").val("");
        $("#coupon_value").val("");
        $("#coupon_type").val("");
        $("#exp_date").val("");
        $("#coupon_img").val("");
        toastr.success(response.message, "Admin says");
        $("#view_coupon").DataTable().ajax.reload();
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

$("#view_coupon").DataTable({
  paging: true,
  lengthChange: true,
  searching: true,
  ordering: true,
  info: true,
  autoWidth: true,
  responsive: true,
  ajax: {
    url: "/admin/coupon/list",
    type: "POST",
    dataType: "json",
    dataSrc: "data",
  },
});

function deletecoupon(id) {
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
      url: "/admin/coupon/delete",
      type: "POST",
      data: {
        c_id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          toastr.success(response.message, "Admin says");

          $("#view_coupon").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
        }
      },

      error: function (e) {
        toastr.error("Error While deleting coupon", "Admin says");
      },
    });
  });
}

function makeid(length) {
  var result = "";
  var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  var charactersLength = characters.length;
  for (var i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  $("#coupon_code").val(result);
}
 


$("#searchKeyword").on("keyup", function () {
  var name = $("#searchKeyword").val(); //for show the releted name list

  if (name.length > 2) {
    $.ajax({
      url: "/admin/users/get_search_user",
      type: "POST",
      data: {
        name: name,
      },
      dataType: "html",
      success: function (res) {
        // console.log(res)
        $("#searchResult").html(res);
      },
    });
  } else {
    $("#searchResult").html("");
  }
});

function selectUser(id, name) {
  $("#user_id").val(id); //id lane keliye
  // console.log(id);
  $("#searchKeyword").val(name); //name lane keliye
  $("#searchResult").html(""); //option select kela nantr list hide honar
}
