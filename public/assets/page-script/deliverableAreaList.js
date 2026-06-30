$(function () {
  $("#deliverable_area_list").DataTable({
    paging: true,
    lengthChange: false,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: false,
    responsive: true,
    ajax: {
      url: "/admin/deliverable-area/viewlist",
      type: "POST",
      dataType: "json",
      dataSrc: "data",
    },
  });

  var table = $("#view_product").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/product/list",
      type: "POST",
      dataType: "json",
      dataSrc: "data",
      data: function (d) {
        d.category = $("#category").val();
        d.seller = $("#seller").val();
        d.status = $("#status").val();
        d.stock = $("#stock").val();
      },
    },
  });

  $(".filter-product").on("change", function () {
    table.ajax.reload();
  });
});

function deleteDeliverableArea(id) {
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
      url: "/admin/deliverable-area/delete",
      type: "POST",
      data: {
        deliverable_area_id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");
          $("#deliverable_area_list").DataTable().ajax.reload();
        } else {
          toastr.error(response.message, "Admin says");
        }
      },

      error: function (e) {
        toastr.error("Error While deleting Service", "Admin says");
      },
    });
  });
}

function addDeliveryDate(deliverable_id) {
  $("#deliverable_area_id").val(deliverable_id);

  $("#delivery_date_list").DataTable().destroy();

  $("#delivery_date_list").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/deliverable-area/delivery-date",
      type: "POST",
      dataType: "json",
      dataSrc: "data",
      data: function (d) {
        d.deliverable_id = deliverable_id;
      },
    },
  });

  $("#addDeliveryDateModal").modal("toggle");
}

function addDeliveryDateModal() {
  var delivery_date = $("#delivery_date").val();
  var deliverable_area_id = $("#deliverable_area_id").val();

  if (delivery_date !== "") {
    $.ajax({
      url: "/admin/deliverable-area/delivery-date/add",
      type: "POST",
      data: {
        delivery_date: delivery_date,
        deliverable_area_id: deliverable_area_id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success === true) {
          $("#delivery_date").val("");
          toastr.success(response.message, "Admin says");

          // ✅ Destroy only if DataTable is already initialized
          if ($.fn.DataTable.isDataTable("#delivery_date_list")) {
            $("#delivery_date_list").DataTable().clear().destroy();
          }

          // ✅ Reinitialize DataTable
          $("#delivery_date_list").DataTable({
            paging: true,
            lengthChange: false,
            ordering: true,
            info: true,
            autoWidth: true,
            responsive: true,
            ajax: {
              url: "/admin/deliverable-area/delivery-date",
              type: "POST",
              dataType: "json",
              dataSrc: "data",
              data: function (d) {
                return {
                  ...d,
                  deliverable_id: deliverable_area_id,
                };
              },
            },
          });
        } else {
          toastr.error(response.message, "Admin says");
        }
      },
      error: function () {
        toastr.error("Error while adding delivery date", "Admin says");
      },
    });
  } else {
    toastr.error("Enter Delivery Date", "Admin says");
  }
}

function deleteDeliveryDate(id) {
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
        url: "/admin/deliverable-area/delivery-date/delete",
        type: "POST",
        data: {
          date_id: id,
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            toastr.success(response.message, "Admin says");

            const deliverable_area_id = $("#deliverable_area_id").val();

            // ✅ Safely destroy the existing table instance
            if ($.fn.DataTable.isDataTable("#delivery_date_list")) {
              $("#delivery_date_list").DataTable().clear().destroy();
            }

            // ✅ Reinitialize the DataTable with fresh data
            $("#delivery_date_list").DataTable({
              paging: true,
              lengthChange: false,
              ordering: true,
              info: true,
              autoWidth: true,
              responsive: true,
              ajax: {
                url: "/admin/deliverable-area/delivery-date",
                type: "POST",
                dataType: "json",
                dataSrc: "data",
                data: function (d) {
                  return {
                    ...d,
                    deliverable_id: deliverable_area_id,
                  };
                },
              },
            });
          } else {
            toastr.error(response.message, "Admin says");
          }
        },
        error: function (e) {
          toastr.error("Error While deleting TimeSlot", "Admin says");
        },
      });
    }
  });
}

function addTimeslot(deliverable_id) {
  $("#deliverable_area_id_for_timeslot").val(deliverable_id);
  $("#timeslot_list").DataTable().destroy();

  $("#timeslot_list").DataTable({
    paging: true,
    lengthChange: false,
    ordering: true,
    info: true,
    autoWidth: true,
    responsive: true,
    ajax: {
      url: "/admin/deliverable-area/timeslot",
      type: "POST",
      dataType: "json",
      dataSrc: "data",
      data: function (d) {
        d.deliverable_id = deliverable_id;
      },
    },
  });

  $("#addTimeslotModal").modal("toggle");
}

function addTimeslotModal() {
  var deliverable_area_id_for_timeslot = $(
    "#deliverable_area_id_for_timeslot"
  ).val();
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
      url: "/admin/deliverable-area/timeslot/add",
      type: "POST",
      data: {
        deliverable_area_id: deliverable_area_id_for_timeslot,
        min_time: min_time,
        max_time: max_time,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          $("#delivery_date").val("");
          toastr.success(response.message, "Admin says");
          if ($.fn.DataTable.isDataTable("#timeslot_list")) {
            $("#timeslot_list").DataTable().clear().destroy();
          }

          $("#timeslot_list").DataTable({
            paging: true,
            lengthChange: false,
            ordering: true,
            info: true,
            autoWidth: true,
            responsive: true,
            ajax: {
              url: "/admin/deliverable-area/timeslot",
              type: "POST",
              dataType: "json",
              dataSrc: "data",
              data: function (d) {
                return {
                  ...d,
                  deliverable_id: deliverable_area_id_for_timeslot,
                };
              },
            },
          });
        } else {
          toastr.error(response.message, "Admin says");
        }
      },
    });
  } else {
    toastr.error("Max Time should be grater than Min time", "Admin says");
  }
}

function deleteTimeslot(id) {
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
        url: "/admin/timeslot/delete",
        type: "POST",
        data: {
          id: id,
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            toastr.success(response.message, "Admin says");

            var deliverable_id = $("#deliverable_area_id_for_timeslot").val();

            if ($.fn.DataTable.isDataTable("#timeslot_list")) {
              $("#timeslot_list").DataTable().clear().destroy();
            }

            $("#timeslot_list").DataTable({
              paging: true,
              lengthChange: false,
              ordering: true,
              info: true,
              autoWidth: true,
              responsive: true,
              ajax: {
                url: "/admin/deliverable-area/timeslot",
                type: "POST",
                dataType: "json",
                dataSrc: "data",
                data: function (d) {
                  return {
                    ...d,
                    deliverable_id: deliverable_id,
                  };
                },
              },
            });
          } else {
            toastr.error(response.message, "Admin says");
          }
        },
        error: function () {
          toastr.error("Error While deleting TimeSlot", "Admin says");
        },
      });
    }
  });
}

function changeTimeslotStatus(id) {
  Swal.fire({
    title: "Change Status",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, proceed please!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/timeslot/changeTimeslotStatus",
        type: "POST",
        data: {
          id: id,
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            toastr.success(response.message, "Admin says");

            var deliverable_id = $("#deliverable_area_id_for_timeslot").val();

            if ($.fn.DataTable.isDataTable("#timeslot_list")) {
              $("#timeslot_list").DataTable().clear().destroy();
            }

            $("#timeslot_list").DataTable({
              paging: true,
              lengthChange: false,
              ordering: true,
              info: true,
              autoWidth: true,
              responsive: true,
              ajax: {
                url: "/admin/deliverable-area/timeslot",
                type: "POST",
                dataType: "json",
                dataSrc: "data",
                data: function (d) {
                  return {
                    ...d,
                    deliverable_id: deliverable_id,
                  };
                },
              },
            });
          } else {
            toastr.error(response.message, "Admin says");
          }
        },
        error: function () {
          toastr.error("Error While Changing TimeSlo status", "Admin says");
        },
      });
    }
  });
}
