$(function () {
  $("#city_list").DataTable({
    paging: true,
    lengthChange: false,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: false,
    responsive: true,
    ajax: {
      url: "/admin/manage-city/get_city_list",
      type: "POST",
      dataType: "json",
      dataSrc: "data",
    },
    columns: [
      {
        data: "id",
      },
      {
        data: "name",
      },
      {
        data: null, // Use null if you want to create a custom column
        render: function (data, type, row) {
          // Customize this as per your requirements
          return (
            "<a  data-tooltip='tooltip' title='Edit City' href='/admin/manage-city/edit/" +
            row.id +
            "'  class='btn btn-primary btn-xs'><i class='fi fi-tr-customize-edit'>  </i> </a>  <a type='button' data-tooltip='tooltip' title='Delete City' onclick='deleteCity(" +
            row.id +
            ")' class='btn btn-danger btn-xs'><i class='fi fi-tr-trash-xmark'>  </i> </a>"
          );
        },
      },
    ],
  });
});

function initAutocomplete() {
  const map = new google.maps.Map(document.getElementById("map"), {
    center: {
      lat: -33.8688,
      lng: 151.2195,
    },
    zoom: 13,
    mapTypeId: "roadmap",
  });
  // Create the search box and link it to the UI element.
  var options = {
    types: ["(cities)"],
  };
  const input = document.getElementById("pac-input");
  const searchBox = new google.maps.places.SearchBox(input, options);
  // const searchBox = new google.maps.places.Autocomplete(input, options); // get only city name in searchbox

  // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  // Bias the SearchBox results towards current map's viewport.
  map.addListener("bounds_changed", () => {
    searchBox.setBounds(map.getBounds());
  });

  let markers = [];

  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener("places_changed", () => {
    const places = searchBox.getPlaces();
    const place1 = places[0];

    if (places.length == 0) {
      return;
    }
    $("#city_name").val("");
    $("#latitude").val("");
    $("#longitude").val("");
    const parser = new DOMParser();
    const doc = parser.parseFromString(place1.adr_address, "text/html");
    const locality = doc.querySelector(".locality").textContent;

    if (place1.name != locality) {
      alert("No City Found \ntry another keyword");
      $("#pac-input").val("");
      return;
    }

    // Clear out the old markers.
    markers.forEach((marker) => {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    const bounds = new google.maps.LatLngBounds();

    places.forEach((place) => {
      if (!place.geometry || !place.geometry.location) {
        console.log("Returned place contains no geometry");
        return;
      }

      const icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25),
      };

      // Create a marker for each place.
      markers.push(
        new google.maps.Marker({
          map,
          icon,
          title: place.name,
          position: place.geometry.location,
          anchorPoint: new google.maps.Point(0, -29),
        })
      );

      console.log(bounds.extend(place.geometry.location));
      var lng = place1.geometry.location.lng();
      var lat = place1.geometry.location.lat();

      $("#city_name").val(locality);
      $("#latitude").val(lat);
      $("#longitude").val(lng);

      // const Place_area = console.log(place1)

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
}

window.initAutocomplete = initAutocomplete;

function addCity() {
  var city_name = $("#city_name").val();
  var latitude = $("#latitude").val();
  var longitude = $("#longitude").val();
  if (city_name == "") {
    toastr.error("Select City", "Admin says");
    return false;
  }

  if (latitude == "") {
    toastr.error("Latitude cant be empty", "Admin says");
    return false;
  }

  if (longitude == "") {
    toastr.error("Longitude cant be empty", "Admin says");
    return false;
  }
  $.ajax({
    url: "/admin/manage-city/add",
    type: "POST",
    data: {
      longitude,
      latitude,
      city_name,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#insert_city_form").trigger("reset");
        $("#city_list").DataTable().ajax.reload();
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

function updateCity() {
  var editid = $("#editid").val();
  var city_name = $("#city_name").val();
  var latitude = $("#latitude").val();
  var longitude = $("#longitude").val();

  if (city_name == "") {
    toastr.error("Select City", "Admin says");
    return false;
  }

  if (latitude == "") {
    toastr.error("Latitude cant be empty", "Admin says");
    return false;
  }

  if (longitude == "") {
    toastr.error("Longitude cant be empty", "Admin says");
    return false;
  }
  $.ajax({
    url: "/admin/manage-city/update",
    type: "POST",
    data: {
      editid,
      longitude,
      latitude,
      city_name,
    },
    dataType: "json",
    success: function (response) {
      if (response.success == true) {
        toastr.success(response.message, "Admin says");
        $("#update_city_form").trigger("reset");
        setTimeout(function () {
          location = "/admin/manage-city";
        }, 2500);
      } else {
        toastr.error(response.message, "Admin says");
        return false;
      }
    },
  });
}

function deleteCity(id) {
  var r = confirm("Do you really want to delete city?");
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
      url: "/admin/manage-city/delete",
      type: "POST",
      data: {
        city_id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.success == true) {
          toastr.success(response.message, "Admin says");
          $("#city_list").DataTable().ajax.reload();
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
