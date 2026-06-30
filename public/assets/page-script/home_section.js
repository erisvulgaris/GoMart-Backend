function add_section() {
  var title = $("#title").val();
  var category = $("#category").val();
  var sub_category = $("#sub_category").val();
  var city_id = $("#city_id").val();
  var deliverable_area_id = $("#deliverable_area_id").val();
  var sort_by = $("#sort_by").val();
  var product_show_limit = $("#product_show_limit").val();
  var status = $("#status").val();

  if (title == "" || category == "" || sub_category == "" || status == "") {
    toastr.error("All fields are required required", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/home_section/add",
    type: "POST",
    data: {
      title: title,
      category: category,
      sub_category: sub_category,
      status: status,city_id,deliverable_area_id, sort_by, product_show_limit
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success("Home Section Added Successfully", "Admin says");
        $("#view_section").DataTable().ajax.reload();
        $("#title").val("");
        $("#category").val("");
        $("#sub_category").val("");
        $("#status").val("");
      } else {
        toastr.error(
          "Unable to Add Home Section \nAll fields are required",
          "Admin says"
        );
        return false;
      }
    },
  });
}

function update_section() {
  var title = $("#title").val();
  var home_section_id = $("#home_section_id").val();
  var category = $("#category").val();
  var sub_category = $("#sub_category").val();
  var status = $("#status").val();
    var city_id = $("#city_id").val();
  var deliverable_area_id = $("#deliverable_area_id").val();
  var sort_by = $("#sort_by").val();
  var product_show_limit = $("#product_show_limit").val();

  if (title == "" || category == "" || sub_category == "" || status == "") {
    toastr.error("All fields are required required", "Admin says");
    return false;
  }

  $.ajax({
    url: "/admin/home_section/update",
    type: "POST",
    data: {
      title: title,
      category: category,
      sub_category: sub_category,
      status: status,
      home_section_id: home_section_id,
      city_id,deliverable_area_id, sort_by, product_show_limit
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success("Home Section Updated Successfully", "Admin says");
        function route() {
          location = "/admin/home_section";
        }
        setTimeout(route, 2500);
      } else {
        toastr.error("Unable to Update Home Section", "Admin says");
        return false;
      }
    },
  });
}

$("#view_section").dataTable({
  paging: true,
  lengthChange: true,
  searching: true,
  ordering: true,
  info: true,
  autoWidth: true,
  responsive: true,
  ajax: {
    url: "/admin/home_section/list",
    type: "POST",
    dataType: "json",
    dataSrc: "data",
  },
});

function deletesection(id) {
  var r = confirm("Do you really want to delete Home Section?");
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
      url: "/admin/home_section/delete",
      type: "POST",
      data: {
        sec_id: id,
      },
      dataType: "json",
      success: function (response) {
        toastr.success("Section deleted successfully!", "Admin says");

        $("#view_section").DataTable().ajax.reload();
      },

      error: function (e) {
        toastr.error("Error While deleting Section", "Admin says");
      },
    });
  });
}

$("#category").on("change", function () {
  var category = $("#category").val();

  if (category != "") {
    $.ajax({
      url: "/admin/home_section/subcategory",
      data: {
        cat_change: category,
      },
      type: "POST",
      success: function (response) {
        let sub_category = $("#sub_category");
        sub_category.empty(); // Clear existing options
        sub_category.append(`<option value="">Select Subcategory</option>`);

        $.each(response, function (index, subcategory) {
          sub_category.append(
            `<option value="${subcategory.id}">${subcategory.name}</option>`
          );
        });
      },
    });
  } else {
    $("#subcatname").find("option").remove().end();
    $("#subcatname").append('<option value="">Select Subcategory</option>');
  }
});

$("#city_id").on("change", function () {
  var city_id = $(this).val();

  if (city_id != "") {
    $.ajax({
      url: "/admin/deliverable-area/get-by-cityid",
      data: {
        city_id: city_id,
      },
      type: "POST",
      success: function (response) {
        // console.log(response);
        $("#deliverable_area_id").find("option").remove().end();
        $("#deliverable_area_id").append(
          '<option value="">Select Serviceable Area</option>'
        );
        for (var i = 0; i < response.length; i++) {
          $("#deliverable_area_id").append(
            '<option value="' +
              response[i].id +
              '">' +
              response[i].deliverable_area_title +
              "</option>"
          );
        }
      },
    });
  } else {
    $("#deliverable_area_id").find("option").remove().end();
    $("#deliverable_area_id").append(
      '<option value="">Select Serviceable Area</option>'
    );
  }
});
