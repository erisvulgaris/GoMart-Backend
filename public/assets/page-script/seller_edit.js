

function getAddressFromLatLng(lat, lng) {
    const geocoder = new google.maps.Geocoder();
    const latLng = {
        lat: parseFloat(lat),
        lng: parseFloat(lng)
    };

    geocoder.geocode({
        location: latLng
    }, (results, status) => {
        if (status === "OK") {
            if (results[0]) {
                const fullAddress = results[0].formatted_address;

                $("#map_address").val(fullAddress); // Set full address
            } else {
                console.error("No address results found");
            }
        } else {
            console.error("Geocoder failed due to: " + status);
        }
    });
}


$("#editSellerForm").submit(function(e) {
    e.preventDefault();

    var name = $("#name").val().trim();
    var email = $("#email").val().trim();
    var mobile = $("#mobile").val().trim();
    var deliverable_area_id = $("#deliverable_area_id").val().trim();
    var commission = $("#commission").val().trim();
    var map_address = $("#map_address").val().trim();
    var latitude = $("#latitude").val().trim();
    var longitude = $("#longitude").val().trim();
    // Name validation
    var nameRegex = /^[A-Za-z][A-Za-z\s]*$/;
    if (name === "") {
        toastr.error("Name is required", "Admin says");
        return false;
    }
    if (!nameRegex.test(name)) {
        toastr.error(
            "Name must start with an alphabet and contain only letters and spaces",
            "Admin says"
        );
        return false;
    }

    // Email validation
    var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (email === "") {
        toastr.error("Email is required", "Admin says");
        return false;
    }
    if (!emailRegex.test(email)) {
        toastr.error("Please enter a valid email address", "Admin says");
        return false;
    }


    // Mobile number validation
    if (mobile === "") {
        toastr.error("Mobile number is required", "Admin says");
        return false;
    }

    // Deliverable area ID validation
    if (deliverable_area_id === "") {
        toastr.error("Deliverable area is required", "Admin says");
        return false;
    }

    // Commission validation
    if (commission === "") {
        toastr.error("Commission is required", "Admin says");
        return false;
    }
    if (isNaN(commission) || commission <= 0) {
        toastr.error("Commission must be a valid positive number", "Admin says");
        return false;
    }

    if (map_address === "" && latitude === "" && longitude === "") {
        toastr.error("Select store location on map", "Admin says");
        return false;
      }

    var formData = new FormData(this);

    $.ajax({
        url: "/admin/seller/update", // Specify the PHP processing script
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.success == true) {
                toastr.success(response.msg, "Admin says");

            } else {
                toastr.error(response.msg, "Admin says");

            }
        },
    });
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
  
