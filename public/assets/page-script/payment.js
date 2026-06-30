function changestatus(id, status) {
  Swal.fire({
    title: "Are you sure?",
    text: "You can revert this!",
    icon: "error",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, proceed please!",
  }).then((result) => {
    $.ajax({
      url: "/admin/payment/update_status",
      type: "POST",
      data: {
        payment_id: id,
        status: status,
      },
      dataType: "json",
      success: function (response) {
        toastr.success("Payment status changed successfully!", "Admin says");

        $("#view_product").DataTable().ajax.reload();
      },

      error: function (e) {
        toastr.error("Error While changing status", "Admin says");
      },
    });
  });
}

function changeapi() {
  var api_key = $("#api_key").val();

  $.ajax({
    url: "/admin/payment/update_api",
    type: "POST",
    data: {
      api_key: api_key,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#view_product").DataTable().ajax.reload();
      } else {
        toastr.error(response.message, "Admin says");
      }
      $("#Modal").modal("toggle");
    },
  });
}
