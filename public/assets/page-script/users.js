function validateFileType() {
  var fileName = document.getElementById("cat_img").value;
  var idxDot = fileName.lastIndexOf(".") + 1;
  var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
  if (extFile == "jpeg" || extFile == "png" || extFile == "pdf") {
    //TO DO
  } else {
    alert("Only jpg, jpeg, png and pdf files are allowed!");
    $("#cat_img").val("");
  }
}
$("#view_user").dataTable({
  paging: true,
  lengthChange: true,
  searching: true,
  ordering: true,
  info: true,
  autoWidth: true,
  responsive: true,
  ajax: {
    url: "/admin/users/list",
    type: "POST",
    dataType: "json",
    dataSrc: "data",
  },
});

function deleteuser(id) {
  Swal.fire({
    title: "Are you sure?",
    text: "Do you really want to delete user?",
    icon: "error",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, proceed please!",
  }).then((result) => {
    $.ajax({
      url: "/admin/user/delete",
      type: "POST",
      data: {
        user_id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          toastr.success(response.message, "Admin says");
          $("#view_user").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
        }
      },
      error: function (e) {
        toastr.error("Error While deleting User", "Admin says");
      },
    });
  });
}

function editstatus(id, status) {
  var a = confirm("Do you really want to change user status?");
  Swal.fire({
    title: "Are you sure?",
    text: "Do you really want to change user status?",
    icon: "error",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, proceed please!",
  }).then((result) => {
    $.ajax({
      url: "/admin/user/update_status",
      type: "POST",
      data: {
        userid: id,
        status: status,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          toastr.success(response.message, "Admin says");
          $("#view_user").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
        }
      },

      error: function (e) {
        toastr.error("Error While changing user status", "Admin says");
      },
    });
  });
}

function addWalletAmount(user_id) {
  $("#user_id").val(user_id);
  $.ajax({
    url: "/admin/user/get_wallet",
    type: "POST",
    data: {
      user_id: user_id,
    },
    dataType: "json",
    success: function (response) {
      $("#wallet_user_name").text(response.data[0][0]);
      // console.log(response.data[0][0])

      $("#addWalletModal").modal("toggle");
    },
  });
}

function addWalletModal() {
  var user_id = $("#user_id").val();
  var walletAmount = $("#walletAmount").val();
  var flag = $("#flag").val();
  var remark = $("#remark").val();

  if (remark.length > 0 && walletAmount.length > 0) {
    $.ajax({
      url: "/admin/user/wallet/add_amount_by_id",
      type: "POST",
      data: {
        user_id: user_id,
        walletAmount: walletAmount,
        flag: flag,
        remark: remark,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          $("#view_user").DataTable().ajax.reload();
          $("#user_id").val("");
          $("#walletAmount").val("");
          $("#remark").val("");

          toastr.success(response.message, "Admin says");
          $("#addWalletModal").modal("toggle");
        } else {
          toastr.error(response.message, "Admin says");
        }
      },
    });
  } else {
    toastr.error("Enter all Field", "Admin says");
  }
}

function walletHistoryModel(id) {
  $("#walletHistoryModel").modal("show");

  $("#user_wallet_list").dataTable().fnDestroy();
  $("#user_wallet_list").DataTable({
    dom: "lfrtip",
    lengthChange: true,
    searching: true,
    ordering: false,
    info: true,
    autoWidth: true,
    responsive: true,
    paging: true,
    ajax: {
      url: "/admin/user/user_wallet_list",
      type: "POST",
      dataType: "json",
      dataSrc: "data",
      data: {
        id,
      },
    },
  });
}
