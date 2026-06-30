function updateSystemUser() {
  var user_id = $("#user_id").val();
  var fname = $("#fname").val();
  var role_id = $("#role_id").val();

  var lname = $("#lname").val();
  var mobile = $("#mobile").val();

  var email = $("#email").val();

  var pass = $("#pass").val();
  var cpass = $("#cpass").val();

  if (role_id == "") {
    toastr.error("Select Role ", "Admin says");
    return false;
  }
  if (fname == "" || lname == "") {
    toastr.error("Name is required", "Admin says");
    return false;
  }

  if (email == "") {
    toastr.error("Email is required", "Admin says");
    return false;
  }
  if (!(pass == "")) {
    if (pass.length < 8) {
      toastr.error("Password should be atleast 8 characters", "Admin says");
      return false;
    }

    if (!(pass == cpass)) {
      toastr.error("Enter same password & confirm password ", "Admin says");
      return false;
    }
  }

  $.ajax({
    url: "/admin/system-user/update",
    type: "POST",
    data: {
      user_id,
      role_id,
      fname,
      lname,
      mobile,
      email,
      pass,
      cpass,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        setTimeout(function () {
          window.location.href = "/admin/system-user";
        }, 2000);
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

function addSystemUser() {
  var fname = $("#fname").val();
  var role_id = $("#role_id").val();

  var lname = $("#lname").val();
  var mobile = $("#mobile").val();

  var email = $("#email").val();

  var pass = $("#pass").val();
  var cpass = $("#cpass").val();

  if (role_id == "") {
    toastr.error("Select Role ", "Admin says");
    return false;
  }
  if (fname == "" || lname == "") {
    toastr.error("Name is required", "Admin says");
    return false;
  }

  if (email == "") {
    toastr.error("Email is required", "Admin says");
    return false;
  }
  if (pass.length < 8) {
    toastr.error("Password should be atleast 8 characters", "Admin says");
    return false;
  }

  if (!(pass == cpass)) {
    toastr.error("Enter same password & confirm password ", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/system-user/add",
    type: "POST",
    data: {
      role_id,
      fname,
      lname,
      mobile,
      email,
      pass,
      cpass,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#view_system_user").DataTable().ajax.reload();
        $("#myform").trigger("reset");
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

$("#view_system_user").dataTable({
  paging: true,
  lengthChange: true,
  searching: true,
  ordering: true,
  info: true,
  autoWidth: true,
  responsive: true,
  ajax: {
    url: "/admin/system-user/list",
    type: "POST",
    dataType: "json",
    dataSrc: "data",
  },
});

function deleteSystemUser(id) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "error",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, proceed please!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/system-user/delete",
        type: "POST",
        data: {
          user_id: id,
        },
        dataType: "json",
        success: function (response) {
          if (response.success == true) {
            toastr.success(response.message, "Admin says");
            $("#view_system_user").DataTable().ajax.reload();
          } else {
            toastr.error(response.message, "Admin says");
            return false;
          }
        },

        error: function (e) {
          toastr.error("Error While deleting System User", "Admin says");
        },
      });
    } else {
      toastr.info("Deleting System User Cancelled", "Admin says");
      return false;
    }
  });
}
