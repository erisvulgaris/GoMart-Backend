$(document).ready(function () {
  $("#min_time").timepicker({
    timeFormat: "h:mm p",
    interval: 60,
    minTime: "10",
    defaultTime: "11",
    startTime: "10:00",
    dynamic: false,
    dropdown: true,
    scrollbar: true,
  });
  $("#max_time").timepicker({
    timeFormat: "h:mm p",
    interval: 60,
    minTime: "10",
    defaultTime: "11",
    startTime: "10:00",
    dynamic: false,
    dropdown: true,
    scrollbar: true,
  });
});

$(document).ready(function () {
  $("#myTime").DataTable({
    paging: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/timeslot/list",
      type: "POST",
      dataType: "json",
      dataSrc: "data",
    },
  });
});

function addTime() {
  var dummy1;
  var dummy2;
  var min_time = $("#min_time").val(); //10:00 AM
  var max_time = $("#max_time").val(); //2:00 PM

  if (min_time == "") {
    toastr.error("Minimum Time is required", "Admin says");
    return false;
  }

  if (max_time == "") {
    toastr.error("Maximum Time is required", "Admin says");
    return false;
  }

  var time_hr1 = parseInt(min_time.split(":")[0]); //hr 10
  var type1 = min_time.split(" ")[1]; //time type AM

  var time_hr2 = parseInt(max_time.split(":")[0]); //hr 2
  var type2 = max_time.split(" ")[1]; //type pm

  if (type1 == "PM") {
    if (time_hr1 == 12) {
      dummy1 = time_hr1;
    } else {
      dummy1 = 12 + time_hr1;
    }
  } else {
    dummy1 = time_hr1;
  }

  if (type2 == "PM") {
    if (time_hr2 == 12) {
      dummy2 = time_hr2;
    } else {
      dummy2 = 12 + time_hr2;
    }
  } else {
    dummy2 = time_hr2;
  }

  if (dummy2 > dummy1) {
    $.ajax({
      url: "/admin/timeslot/add",
      type: "POST",
      data: {
        min_time: min_time,
        max_time: max_time,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");
        } else {
          toastr.error(response.message, "Admin says");
          return false;
        }
        $("#myTime").DataTable().ajax.reload();
      },
    });
  } else {
    toastr.error("Max Time should be grater than Min time", "Admin says");
  }
}

function deletetime(id) {
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
      url: "/admin/timeslot/delete",
      type: "POST",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          toastr.success(response.message, "Admin says");
          $("#myTime").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
        }
      },

      error: function (e) {
        toastr.error("Error While deleting TimeSlot", "Admin says");
      },
    });
  });
}
