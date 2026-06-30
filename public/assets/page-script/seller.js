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

$("#category").select2({
  placeholder: "Select Categories",
  multiple: true,
  allowClear: true,
});

$("#sellerForm").submit(function (e) {
  e.preventDefault();

  var name = $("#name").val().trim();
  var email = $("#email").val().trim();
  var password = $("#password").val().trim();
  var mobile = $("#mobile").val().trim();
  var deliverable_area_id = $("#deliverable_area_id").val().trim();
  var commission = $("#commission").val().trim();
  var latitude = $("#latitude").val().trim();
  var longitude = $("#longitude").val().trim();
  var map_address = $("#map_address").val().trim();

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

  // Password validation
  if (password === "") {
    toastr.error("Password is required", "Admin says");
    return false;
  }
  if (password.length < 6) {
    toastr.error(
      "Password length should be at least 6 characters",
      "Admin says"
    );
    return false;
  }

  // Mobile number validation
  if (mobile === "") {
    toastr.error("Mobile number is required", "Admin says");
    return false;
  }

  if (mobile.length != 10) {
    toastr.error("Mobile number should be 10 digits", "Admin says");
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
    url: "/admin/seller/add", // Specify the PHP processing script
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.msg, "Admin says");
        $("#sellerForm").trigger("reset");
        $("#category").val(null).trigger("change");
      } else {
        toastr.error(response.msg, "Admin says");
      }
    },
  });
});
let map;
let marker;

function initAutocomplete() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: {
      lat: -33.8688,
      lng: 151.2195,
    }, // Default center
    zoom: 13,
    mapTypeId: "roadmap",
  });

  // Marker Initialization
  marker = new google.maps.Marker({
    map: map,
    draggable: true, // Allows user to drag marker
    position: {
      lat: -33.8688,
      lng: 151.2195,
    }, // Default marker position
  });

  // Event listener for marker drag to capture new coordinates
  google.maps.event.addListener(marker, "dragend", function () {
    const position = marker.getPosition();
    $("#latitude").val(position.lat());
    $("#longitude").val(position.lng());
  });

  // Autocomplete for City Search
  const input = document.getElementById("pac-input");
  const searchBox = new google.maps.places.SearchBox(input);

  map.addListener("bounds_changed", () => {
    searchBox.setBounds(map.getBounds());
  });

  searchBox.addListener("places_changed", () => {
    const places = searchBox.getPlaces();

    if (places.length == 0) return;

    const place = places[0];
    if (!place.geometry || !place.geometry.location) return;

    // Move map to searched location
    map.panTo(place.geometry.location);
    map.setZoom(15);

    // Move marker to searched location
    marker.setPosition(place.geometry.location);

    // Extract latitude and longitude
    const lat = place.geometry.location.lat();
    const lng = place.geometry.location.lng();

    $("#latitude").val(lat);
    $("#longitude").val(lng);
    getAddressFromLatLng(lat, lng);
    // Extract city name (if available)
    const parser = new DOMParser();
    const doc = parser.parseFromString(place.adr_address, "text/html");
    const locality = doc.querySelector(".locality")
      ? doc.querySelector(".locality").textContent
      : place.name;

    $("#city_name").val(locality);
  });
}

function getAddressFromLatLng(lat, lng) {
  const geocoder = new google.maps.Geocoder();
  const latLng = {
    lat: parseFloat(lat),
    lng: parseFloat(lng),
  };

  geocoder.geocode(
    {
      location: latLng,
    },
    (results, status) => {
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
    }
  );
}

window.initAutocomplete = initAutocomplete;
