function addNotification() {
  var user_id     = $("#user").val();
  var user_type   = $("#test").val();
  var title       = $("#title").val();
  var description = $("#description").val();
  var noti_image  = $("#noti_image").val();

  if (!title) {
    toastr.error("Title is required.", "Admin says");
    return false;
  }

  // Disable button and show loader
  $("#send_notification").prop("disabled", true);
  $("#btn_send_text").hide();
  $("#btn_send_loader").show();

  $.ajax({
    url: "/admin/notification/add",
    type: "POST",
    data: {
      user_id,
      user_type,
      title,
      description,
      noti_image,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        $("#user").val("");
        $("#test").val("0");
        $("#title").val("");
        $("#description").val("");
        $("#searchKeyword").val("");
        $("#noti_image").val("");
        $("#noti_image_preview").hide();
        $("#noti_img_thumb").attr("src", "");
        $("#single").hide();

        toastr.success(response.message, "Admin says");
        $("#view_notification").DataTable().ajax.reload();
      } else {
        toastr.error(response.message, "Admin says");
      }
    },
    error: function () {
      toastr.error("Failed to send notification. Please try again.", "Admin says");
    },
    complete: function () {
      // Re-enable button
      $("#send_notification").prop("disabled", false);
      $("#btn_send_text").show();
      $("#btn_send_loader").hide();
    },
  });
}

// Image picker: convert to base64
$(document).on("change", "#noti_image_input", function () {
  var file = this.files[0];
  if (!file) return;
  var reader = new FileReader();
  reader.onload = function (e) {
    $("#noti_image").val(e.target.result);
    $("#noti_img_thumb").attr("src", e.target.result);
    $("#noti_image_preview").show();
  };
  reader.readAsDataURL(file);
});

$(document).on("click", "#noti_image_clear", function (e) {
  e.preventDefault();
  $("#noti_image_input").val("");
  $("#noti_image").val("");
  $("#noti_img_thumb").attr("src", "");
  $("#noti_image_preview").hide();
});

$(document).ready(function () {
  // Initialize the DataTable with export buttons
  var table = $("#view_notification").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/notification/list",
      type: "POST",
      dataType: "json",
      dataSrc: "data",
      data: function (d) {
        d.is_system_generated = $("#is_system_generated").val();
      },
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
  $(".filter-product").on("change", function () {
    table.ajax.reload();
  });
});



function deleteNotification(id) {
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
      url: "/admin/notification/delete",
      type: "POST",
      data: {
        noti_id: id,
      },
      dataType: "json",
      success: function (response) {
        toastr.success("Notification deleted successfully!", "Admin says");

        $("#view_notification").DataTable().ajax.reload();
      },

      error: function (e) {
        toastr.error("Error While deleting Notification", "Admin says");
      },
    });
  });
}

function showDiv() {
  getSelectValue = document.getElementById("test").value;
  if (getSelectValue == "1") {
    document.getElementById("single").style.display = "block";
  } else {
    document.getElementById("single").style.display = "none";
  }
}

function selectUser(id, name, mobile) {
  $("#user").val(id); //id lane keliye
  // console.log(id);
  $("#searchKeyword").val(name + mobile); //name lane keliye
  $("#searchResult").html(""); //option select kela nantr list hide honar
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
